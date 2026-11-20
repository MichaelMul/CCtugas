@extends('layouts.app')

@section('title', 'Confirmation Link Unavailable')

@section('content')
    <section class="status-card" aria-labelledby="status-title">
        <span class="status-icon status-icon--warning" aria-hidden="true">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none">
                <path d="M12 8v5m0 3.5h.01M10.3 4.8 2.9 18a1.8 1.8 0 0 0 1.6 2.7h15a1.8 1.8 0 0 0 1.6-2.7L13.7 4.8a2 2 0 0 0-3.4 0Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>
        <h1 class="status-title" id="status-title">Confirmation Link Unavailable</h1>
        <p class="status-copy">This confirmation link is invalid or has expired. Registration confirmation links are valid for 7 days. Please contact the program team for help.</p>
        <a class="status-action" href="{{ route('registrations.create') }}">Return to registration</a>
    </section>
@endsection
