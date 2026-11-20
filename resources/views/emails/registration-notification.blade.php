<x-mail::message>
# New Student Registration

A student registration has been received.

- **Name:** {{ $registration->full_name }}
- **Email:** {{ $registration->email }}
- **Registered:** {{ $registration->created_at->format('Y-m-d H:i:s T') }}
- **Status:** {{ $registration->status }}

Regards,<br>
Cloud Computing 2025
</x-mail::message>
