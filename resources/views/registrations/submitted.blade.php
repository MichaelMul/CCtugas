@extends('layouts.app')

@section('title', 'Registration Submitted')

@section('content')
    <section class="status-card" aria-labelledby="status-title">
        <span class="status-icon" aria-hidden="true">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none">
                <path d="m5 12.5 4.5 4.5L19 7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>
        <h1 class="status-title" id="status-title">Registration Submitted Successfully</h1>
        @if (session()->has('registration_confirmation_delivered'))
            @if (session('registration_confirmation_delivered'))
                <p class="status-copy">Your registration has been saved. Check your inbox for the confirmation email and follow its link to verify your address.</p>
            @else
                <p class="status-copy">Your registration has been saved, but we couldn’t deliver the email just now. Please try again later. Your submitted details have not been lost.</p>
            @endif
        @else
            <p class="status-copy">Your registration details have been saved. If you have submitted the form, check your inbox for a confirmation email. If you need help, please contact the program team.</p>
        @endif
        <a class="status-action" href="{{ route('registrations.create') }}">Return to registration</a>
    </section>
@endsection
