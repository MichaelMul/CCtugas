<x-mail::message>
# Confirm Your Registration

Hello {{ $registration->full_name }},

Your registration for Cloud Computing 2025 has been received. Confirm your email address to complete your registration.

<x-mail::button :url="$confirmationUrl">
Confirm Registration
</x-mail::button>

This confirmation link expires in 7 days. If you did not submit this registration, you can safely ignore this email.

Regards,<br>
Cloud Computing 2025
</x-mail::message>
