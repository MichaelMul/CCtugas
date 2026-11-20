@extends('layouts.app')

@section('title', 'Email Verified')

@section('content')
    <section class="status-card" aria-labelledby="status-title">
        <span class="status-icon" aria-hidden="true">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none">
                <path d="m5 12.5 4.5 4.5L19 7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>
        <h1 class="status-title" id="status-title">Email Verified Successfully</h1>
        <p class="status-copy">Your email address is confirmed and your Cloud Computing 2025 registration is now verified.</p>
        <a class="status-action" href="{{ route('registrations.create') }}">Back to the portal</a>
    </section>
@endsection
