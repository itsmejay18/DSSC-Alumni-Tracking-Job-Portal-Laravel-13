@extends('layouts.guest')

@section('title', 'Reset Password')
@section('body-class', 'portal-auth-page')

@section('guest-content')
<section class="portal-auth-scene">
    @include('auth.partials.panel', [
        'title' => 'Set A New Password',
        'description' => 'Create a fresh password to restore access to your alumni tracking and job portal account.',
    ])

    <div class="portal-auth-form-pane">
        <div class="portal-auth-form-wrap">
            <a class="portal-auth-home-link" href="{{ route('login') }}">
                <i class="ph ph-arrow-left"></i>
                <span>Back to login</span>
            </a>

            <div class="portal-auth-heading">
                <span class="portal-auth-eyebrow">Secure Reset</span>
                <h2>Reset Password</h2>
                <p>Enter your account email and choose a new password.</p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="portal-auth-form">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="kit-field portal-auth-field">
                    <label for="email">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $email) }}" placeholder="name@example.com" autocomplete="username" required autofocus>
                    @error('email')
                        <span class="portal-auth-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="kit-field portal-auth-field">
                    <label for="password">New password</label>
                    <div class="portal-auth-input-wrap">
                        <input id="password" type="password" name="password" placeholder="Create a new password" autocomplete="new-password" required>
                        <button class="portal-password-toggle" type="button" data-password-toggle="password" aria-label="Show password" aria-pressed="false">
                            <i class="ph ph-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="portal-auth-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="kit-field portal-auth-field">
                    <label for="password_confirmation">Confirm new password</label>
                    <div class="portal-auth-input-wrap">
                        <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Repeat your new password" autocomplete="new-password" required>
                        <button class="portal-password-toggle" type="button" data-password-toggle="password_confirmation" aria-label="Show password confirmation" aria-pressed="false">
                            <i class="ph ph-eye"></i>
                        </button>
                    </div>
                </div>

                <button class="kit-button primary portal-auth-submit" type="submit">Reset password</button>
            </form>
        </div>
    </div>
</section>
@endsection
