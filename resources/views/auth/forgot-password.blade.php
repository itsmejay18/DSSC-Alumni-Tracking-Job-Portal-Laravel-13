@extends('layouts.guest')

@section('title', 'Forgot Password')
@section('body-class', 'portal-auth-page')

@section('guest-content')
<section class="portal-auth-scene">
    @include('auth.partials.panel', [
        'title' => 'Recover Your Portal Access',
        'description' => 'Request a secure password reset link using the email address connected to your DSSC portal account.',
    ])

    <div class="portal-auth-form-pane">
        <div class="portal-auth-form-wrap">
            <a class="portal-auth-home-link" href="{{ route('login') }}">
                <i class="ph ph-arrow-left"></i>
                <span>Back to login</span>
            </a>

            <div class="portal-auth-heading">
                <span class="portal-auth-eyebrow">Password Reset</span>
                <h2>Forgot Password</h2>
                <p>Enter your email address and we will send a reset link through the portal mailer.</p>
            </div>

            <form method="POST" action="{{ route('password.email') }}" class="portal-auth-form">
                @csrf

                <div class="kit-field portal-auth-field">
                    <label for="email">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" autocomplete="username" required autofocus>
                    @error('email')
                        <span class="portal-auth-error">{{ $message }}</span>
                    @enderror
                </div>

                <button class="kit-button primary portal-auth-submit" type="submit">Email reset link</button>

                <p class="portal-auth-switch">
                    Remembered your password?
                    <a href="{{ route('login') }}">Sign in here</a>
                </p>
            </form>
        </div>
    </div>
</section>
@endsection
