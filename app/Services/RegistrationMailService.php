<?php

namespace App\Services;

use App\Mail\RegistrationConfirmationMail;
use App\Mail\RegistrationNotificationMail;
use App\Models\Registration;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class RegistrationMailService
{
    public function sendRegistrationEmails(Registration $registration, string $confirmationUrl): bool
    {
        $confirmationDelivered = $this->sendConfirmation($registration, $confirmationUrl);

        foreach (config('services.registration_notify_emails', []) as $recipient) {
            try {
                Mail::to($recipient)->send(new RegistrationNotificationMail($registration));
            } catch (TransportExceptionInterface $exception) {
                Log::error('Registration staff notification delivery failed.', [
                    'exception_type' => $exception::class,
                ]);
            }
        }

        return $confirmationDelivered;
    }

    private function sendConfirmation(Registration $registration, string $confirmationUrl): bool
    {
        try {
            Mail::to($registration->email)->send(
                new RegistrationConfirmationMail($registration, $confirmationUrl),
            );
        } catch (TransportExceptionInterface $exception) {
            Log::error('Registration confirmation delivery failed.', [
                'exception_type' => $exception::class,
            ]);

            return false;
        }

        return true;
    }
}
