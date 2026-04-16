@extends('layouts.guest')

@section('title', 'Login')

@section('guest-content')
<section class="kit-section">
    <div class="kit-container portal-auth-wrap">
        <div class="kit-card pad-lg portal-auth-card">
            <div class="kit-card-head">
                <div>
                    <span class="kit-kicker" style="color: var(--accent);">Secure Access</span>
                    <h2>Sign in to your portal account</h2>
                    <p class="kit-card-subtitle">Admins, alumni, and employers all use one secure login.</p>
                </div>
            </div>
            <form method="POST" action="{{ route('login') }}" class="kit-form-grid">
                @csrf
                <div class="kit-field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div class="kit-field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                </div>
                <label class="portal-inline-check">
                    <input type="checkbox" name="remember">
                    <span>Keep me signed in</span>
                </label>
                <div class="kit-action-row">
                    <button class="kit-button primary" type="submit">Login</button>
                    <a class="kit-button secondary" href="{{ route('register') }}">Create account</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
