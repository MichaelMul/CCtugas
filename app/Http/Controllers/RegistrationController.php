<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegistrationRequest;
use App\Models\Registration;
use App\Services\RegistrationMailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function create(): View
    {
        return view('registrations.create');
    }

    public function store(
        StoreRegistrationRequest $request,
        RegistrationMailService $mailService,
    ): RedirectResponse {
        $validated = $request->validated();
        $registration = Registration::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'date_of_birth' => $validated['date_of_birth'],
        ]);

        $confirmationUrl = URL::temporarySignedRoute(
            'registrations.verify',
            now()->addDays(7),
            ['registration' => $registration],
        );
        $emailsDelivered = $mailService->sendRegistrationEmails($registration, $confirmationUrl);

        return redirect()
            ->route('registrations.submitted')
            ->with('registration_confirmation_delivered', $emailsDelivered);
    }

    public function submitted(): View
    {
        return view('registrations.submitted');
    }

    public function verify(Request $request, Registration $registration): View|Response
    {
        if (! $request->hasValidSignature()) {
            return response()->view('registrations.invalid', status: 403);
        }

        if ($registration->status !== Registration::STATUS_VERIFIED) {
            $registration->forceFill([
                'status' => Registration::STATUS_VERIFIED,
                'email_verified_at' => now(),
            ])->save();
        }

        return view('registrations.verified');
    }
}
