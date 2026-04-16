@extends('layouts.guest')

@section('title', 'Verify Email')

@section('guest-content')
<section class="kit-section">
    <div class="kit-container portal-auth-wrap">
        <div class="kit-card pad-lg portal-auth-card">
            <div class="kit-card-head">
                <div>
                    <span class="kit-kicker" style="color: var(--accent);">Email Verification</span>
                    <h2>Confirm your email address</h2>
                    <p class="kit-card-subtitle">Alumni accounts require email verification before accessing job applications.</p>
                </div>
            </div>
            <div class="kit-action-row">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button class="kit-button primary" type="submit">Resend verification email</button>
                </form>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="kit-button secondary" type="submit">Logout</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
