@extends('layouts.app')

@section('title', 'Student Registration')

@section('content')
    <div class="registration-layout grid grid-cols-1 items-center gap-10 xl:grid-cols-[minmax(0,0.92fr)_minmax(440px,1fr)]">
        <section aria-labelledby="page-title">
            <p class="eyebrow">Registration now open</p>
            <h1 class="hero-title" id="page-title">
                Build what’s
                <span>next in cloud.</span>
            </h1>
            <p class="hero-copy">
                Complete your registration to join the Cloud Computing 2025 program.
                Start your next chapter with a community creating the future.
            </p>
            <div class="program-note">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 3.5 4.5 7v5c0 4.2 3.1 7.3 7.5 8.5 4.4-1.2 7.5-4.3 7.5-8.5V7L12 3.5Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                    <path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>Your details are protected and used only to process this registration.</span>
            </div>
        </section>

        <section class="form-card" aria-labelledby="form-title">
            <h2 class="form-heading" id="form-title">Create your registration</h2>
            <p class="form-subheading">Enter your details below. You’ll receive an email to verify your address.</p>

            <form method="POST" action="{{ route('registrations.store') }}" data-registration-form>
                @csrf
                <div class="form-fields">
                    <div>
                        <label class="field-label" for="full_name">Full name</label>
                        <input
                            class="field-control"
                            id="full_name"
                            name="full_name"
                            type="text"
                            value="{{ old('full_name') }}"
                            autocomplete="name"
                            minlength="2"
                            maxlength="255"
                            required
                            @if ($errors->has('full_name')) aria-invalid="true" @endif
                            placeholder="e.g. Alex Morgan"
                        >
                        @error('full_name')
                            <p class="field-error" id="full-name-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="field-label" for="email">Student email</label>
                        <input
                            class="field-control"
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            maxlength="255"
                            required
                            @if ($errors->has('email')) aria-invalid="true" @endif
                            placeholder="you@university.edu"
                        >
                        @error('email')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field-row">
                        <div>
                            <label class="field-label" for="password">Password</label>
                            <div class="password-field">
                                <input
                                    class="field-control"
                                    id="password"
                                    name="password"
                                    type="password"
                                    autocomplete="new-password"
                                    minlength="8"
                                    required
                                    @if ($errors->has('password')) aria-invalid="true" @endif
                                    placeholder="At least 8 characters"
                                >
                                <button class="password-toggle" type="button" data-password-toggle="password" aria-label="Show password" aria-pressed="false">Show</button>
                            </div>
                            <p class="field-hint">Use at least 8 characters.</p>
                            @error('password')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="field-label" for="password_confirmation">Confirm password</label>
                            <div class="password-field">
                                <input
                                    class="field-control"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    autocomplete="new-password"
                                    minlength="8"
                                    required
                                    placeholder="Re-enter your password"
                                >
                                <button class="password-toggle" type="button" data-password-toggle="password_confirmation" aria-label="Show password" aria-pressed="false">Show</button>
                            </div>
                            <p class="field-hint" data-password-match aria-live="polite" hidden></p>
                        </div>
                    </div>

                    <div>
                        <label class="field-label" for="date_of_birth">Date of birth</label>
                        <input
                            class="field-control"
                            id="date_of_birth"
                            name="date_of_birth"
                            type="date"
                            value="{{ old('date_of_birth') }}"
                            max="{{ now()->toDateString() }}"
                            autocomplete="bday"
                            required
                            @if ($errors->has('date_of_birth')) aria-invalid="true" @endif
                        >
                        @error('date_of_birth')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <button class="submit-button" type="submit" data-submit-button>
                        <span class="loading-indicator" data-loading-indicator aria-hidden="true" hidden></span>
                        <span data-submit-label>Submit Registration</span>
                    </button>
                </div>
                <p class="privacy-note">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M7 10V7a5 5 0 0 1 10 0v3m-11 0h12a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>By submitting, you agree to receive a registration confirmation email. Your password is never shared.</span>
                </p>
            </form>
        </section>
    </div>
@endsection
