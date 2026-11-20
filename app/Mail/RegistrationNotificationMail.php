<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Registration $registration) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Student Registration — Cloud Computing 2025',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.registration-notification',
            with: [
                'registration' => $this->registration,
            ],
        );
    }
}
