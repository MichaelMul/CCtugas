<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Registration $registration,
        public string $confirmationUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirm Your Registration — Cloud Computing 2025',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.registration-confirmation',
            with: [
                'registration' => $this->registration,
                'confirmationUrl' => $this->confirmationUrl,
            ],
        );
    }
}
