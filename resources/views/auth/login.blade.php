@extends('layouts.guest')

@section('title', 'Login')
@section('body-class', 'portal-auth-page')

@section('guest-content')
<section class="portal-auth-scene">
    @include('auth.partials.panel', [
        'title' => 'Alumni Tracking & Job Portal',
        'description' => 'Access graduate tracing, job opportunities, employer partnerships, and admin tools from one protected portal.',
    ])

    <div class="portal-auth-form-pane">
        <div class="portal-auth-form-wrap">
            <a class="portal-auth-home-link" href="{{ route('landing') }}">
                <i class="ph ph-arrow-left"></i>
                <span>Back to home</span>
            </a>

            <div class="portal-auth-heading">
                <span class="portal-auth-eyebrow">Secure Access</span>
                <h2>Sign In</h2>
                <p>Use your admin, alumni, or employer account to continue.</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="portal-auth-form">
                @csrf

                <div class="kit-field portal-auth-field">
                    <label for="email">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" autocomplete="username" required autofocus>
                    @error('email')
                        <span class="portal-auth-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="kit-field portal-auth-field">
                    <label for="password">Password</label>
                    <div class="portal-auth-input-wrap">
                        <input id="password" type="password" name="password" placeholder="Enter your password" autocomplete="current-password" required>
                        <button class="portal-password-toggle" type="button" data-password-toggle="password" aria-label="Show password" aria-pressed="false">
                            <i class="ph ph-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="portal-auth-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="portal-auth-meta">
                    <label class="portal-inline-check">
                        <input type="checkbox" name="remember" @checked(old('remember'))>
                        <span>Remember me</span>
                    </label>
                    <span class="portal-auth-meta-copy">One secure login for every portal user.</span>
                </div>

                <button class="kit-button primary portal-auth-submit" type="submit">Login</button>

                <p class="portal-auth-switch">
                    Don't have an account yet?
                    <a href="{{ route('register') }}">Create one here</a>
                </p>
            </form>
        </div>
    </div>
</section>
@endsection
