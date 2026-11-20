<?php

use App\Mail\RegistrationConfirmationMail;
use App\Mail\RegistrationNotificationMail;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Symfony\Component\Mailer\Exception\TransportException;

uses(RefreshDatabase::class);

function registrationPayload(array $overrides = []): array
{
    return array_replace([
        'full_name' => 'Alex Morgan',
        'email' => 'alex.morgan@example.test',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'date_of_birth' => '2000-04-12',
    ], $overrides);
}

it('shows the registration page with a csrf token', function () {
    $this->get(route('registrations.create'))
        ->assertOk()
        ->assertSee('Student Registration Portal')
        ->assertSee('name="_token"', false)
        ->assertSee('Complete your registration to join the Cloud Computing 2025 program.');
});

it('stores a normalized registration with a hashed password and sends the required emails', function () {
    Mail::fake();
    config(['services.registration_notify_emails' => [
        'elizabeth.nathania@ciputra.ac.id',
        'haning.kumbara@ciputra.ac.id',
    ]]);

    $response = $this->post(route('registrations.store'), registrationPayload([
        'email' => '  ALEX.MORGAN@EXAMPLE.TEST ',
    ]));

    $response->assertRedirect(route('registrations.submitted'))
        ->assertSessionHas('registration_confirmation_delivered', true);

    $registration = Registration::query()->sole();

    expect($registration->email)->toBe('alex.morgan@example.test')
        ->and($registration->status)->toBe(Registration::STATUS_PENDING)
        ->and($registration->email_verified_at)->toBeNull()
        ->and($registration->password)->not->toBe('Password123!')
        ->and(Hash::check('Password123!', $registration->password))->toBeTrue();

    $this->assertDatabaseCount('registrations', 1);
    Mail::assertSent(RegistrationConfirmationMail::class, function (RegistrationConfirmationMail $mail) use ($registration): bool {
        parse_str((string) parse_url($mail->confirmationUrl, PHP_URL_QUERY), $query);

        return $mail->hasTo($registration->email)
            && isset($query['signature'], $query['expires'])
            && abs((int) $query['expires'] - now()->addDays(7)->timestamp) <= 2;
    });
    Mail::assertSent(RegistrationNotificationMail::class, fn (RegistrationNotificationMail $mail): bool => $mail->hasTo('elizabeth.nathania@ciputra.ac.id'));
    Mail::assertSent(RegistrationNotificationMail::class, fn (RegistrationNotificationMail $mail): bool => $mail->hasTo('haning.kumbara@ciputra.ac.id'));
    Mail::assertSent(RegistrationNotificationMail::class, 2);
});

it('returns validation errors without flashing a password', function (array $overrides, string $field) {
    $this->from(route('registrations.create'))
        ->post(route('registrations.store'), registrationPayload($overrides))
        ->assertRedirect(route('registrations.create'))
        ->assertSessionHasErrors($field)
        ->assertSessionMissing('_old_input.password');

    $this->assertDatabaseCount('registrations', 0);
})->with([
    'malformed email' => [['email' => 'not-an-email'], 'email'],
    'mismatched passwords' => [['password_confirmation' => 'Different123!'], 'password'],
    'password shorter than eight characters' => [['password' => 'short', 'password_confirmation' => 'short'], 'password'],
    'future date of birth' => [['date_of_birth' => '2999-01-01'], 'date_of_birth'],
    'malformed date of birth' => [['date_of_birth' => 'not-a-date'], 'date_of_birth'],
    'too-short name' => [['full_name' => 'A'], 'full_name'],
]);

it('escapes a name when it is returned to the registration form after validation fails', function () {
    $this->followingRedirects()
        ->post(route('registrations.store'), registrationPayload([
            'full_name' => "<script>alert('xss')</script>",
            'email' => 'not-an-email',
        ]))
        ->assertOk()
        ->assertSee('&lt;script&gt;', false)
        ->assertDontSee("<script>alert('xss')</script>", false);
});

it('allows the same email to register more than once and notifies both teachers each time', function () {
    Mail::fake();
    config(['services.registration_notify_emails' => [
        'elizabeth.nathania@ciputra.ac.id',
        'haning.kumbara@ciputra.ac.id',
    ]]);

    foreach (['Alex Morgan', 'Alex Morgan Again'] as $fullName) {
        $this->post(route('registrations.store'), registrationPayload([
            'full_name' => $fullName,
            'email' => 'ALEX.MORGAN@EXAMPLE.TEST',
        ]))
            ->assertRedirect(route('registrations.submitted'))
            ->assertSessionHas('registration_confirmation_delivered', true);
    }

    expect(Registration::query()->where('email', 'alex.morgan@example.test')->count())->toBe(2);
    $this->assertDatabaseCount('registrations', 2);

    Mail::assertSent(RegistrationConfirmationMail::class, 2);
    Mail::assertSent(RegistrationNotificationMail::class, 4);
    expect(Mail::sent(
        RegistrationNotificationMail::class,
        fn (RegistrationNotificationMail $mail): bool => $mail->hasTo('elizabeth.nathania@ciputra.ac.id'),
    )->count())->toBe(2);
    expect(Mail::sent(
        RegistrationNotificationMail::class,
        fn (RegistrationNotificationMail $mail): bool => $mail->hasTo('haning.kumbara@ciputra.ac.id'),
    )->count())->toBe(2);
});

it('limits repeated registration submissions by ip and email', function () {
    $invalidPayload = ['email' => 'limit@example.test'];

    foreach (range(1, 3) as $attempt) {
        $this->post(route('registrations.store'), $invalidPayload)
            ->assertRedirect(route('registrations.create'));
    }

    $this->post(route('registrations.store'), $invalidPayload)
        ->assertTooManyRequests();
});

it('verifies a registration through its valid temporary signature', function () {
    $registration = Registration::factory()->create();
    $url = URL::temporarySignedRoute(
        'registrations.verify',
        now()->addDays(7),
        ['registration' => $registration],
    );

    $this->get($url)
        ->assertOk()
        ->assertSee('Email Verified Successfully');

    $registration->refresh();

    expect($registration->status)->toBe(Registration::STATUS_VERIFIED)
        ->and($registration->email_verified_at)->not->toBeNull();
});

it('allows a valid confirmation link to be opened repeatedly without duplicating a registration', function () {
    $registration = Registration::factory()->create();
    $url = URL::temporarySignedRoute(
        'registrations.verify',
        now()->addDays(7),
        ['registration' => $registration],
    );

    $this->get($url)->assertOk();
    $verifiedAt = $registration->fresh()->email_verified_at;
    $this->get($url)->assertOk();

    expect($registration->fresh()->email_verified_at->equalTo($verifiedAt))->toBeTrue();
    $this->assertDatabaseCount('registrations', 1);
});

it('shows the invalid confirmation page for an expired link without changing the registration', function () {
    $registration = Registration::factory()->create();
    $url = URL::temporarySignedRoute(
        'registrations.verify',
        now()->subMinute(),
        ['registration' => $registration],
    );

    $this->get($url)
        ->assertForbidden()
        ->assertSee('Confirmation Link Unavailable');

    expect($registration->fresh()->status)->toBe(Registration::STATUS_PENDING)
        ->and($registration->fresh()->email_verified_at)->toBeNull();
});

it('shows the invalid confirmation page when the signed url is modified', function () {
    $registration = Registration::factory()->create();
    $url = URL::temporarySignedRoute(
        'registrations.verify',
        now()->addDays(7),
        ['registration' => $registration],
    );

    $this->get($url.'&tampered=1')
        ->assertForbidden()
        ->assertSee('Confirmation Link Unavailable');

    expect($registration->fresh()->status)->toBe(Registration::STATUS_PENDING);
});

it('limits repeated confirmation requests', function () {
    $registration = Registration::factory()->create();
    $url = URL::temporarySignedRoute(
        'registrations.verify',
        now()->addDays(7),
        ['registration' => $registration],
    ).'&tampered=1';

    foreach (range(1, 20) as $attempt) {
        $this->get($url)->assertForbidden();
    }

    $this->get($url)->assertTooManyRequests();
});

it('keeps the registration when mail transport fails and gives a safe message', function () {
    config(['services.registration_notify_emails' => [
        'elizabeth.nathania@ciputra.ac.id',
        'haning.kumbara@ciputra.ac.id',
    ]]);
    Mail::shouldReceive('to')
        ->times(3)
        ->andThrow(new TransportException('private SMTP diagnostic'));

    $this->post(route('registrations.store'), registrationPayload())
        ->assertRedirect(route('registrations.submitted'))
        ->assertSessionHas('registration_confirmation_delivered', false);

    $this->get(route('registrations.submitted'))
        ->assertOk()
        ->assertSee('Your registration has been saved')
        ->assertSee('we couldn’t deliver the email just now')
        ->assertDontSee('private SMTP diagnostic')
        ->assertDontSee('SMTP');

    $this->assertDatabaseCount('registrations', 1);
});

it('escapes student-provided values in mail and never includes the password', function () {
    $registration = Registration::factory()->create([
        'full_name' => "<script>alert('xss')</script>",
        'password' => 'SecretPassword456!',
    ]);

    $confirmationHtml = (new RegistrationConfirmationMail($registration, 'https://portal.example.test/confirm'))
        ->render();
    $notificationHtml = (new RegistrationNotificationMail($registration))->render();

    expect($confirmationHtml)
        ->toContain('&lt;script&gt;')
        ->not->toContain("<script>alert('xss')</script>")
        ->not->toContain('SecretPassword456!');
    expect($notificationHtml)
        ->toContain('&lt;script&gt;')
        ->not->toContain("<script>alert('xss')</script>")
        ->not->toContain('SecretPassword456!');
});
