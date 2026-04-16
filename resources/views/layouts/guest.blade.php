@extends('layouts.app')

@section('content')
<div class="kit-shell">
    <header class="kit-public-header">
        <div class="kit-container">
            <div class="kit-public-nav">
                <div class="kit-brand">
                    <div class="kit-brand-mark">
                        <img src="{{ asset('uikit/assets/images/dssc-logo-circle.png') }}" alt="DSSC logo">
                    </div>
                    <div class="kit-brand-copy">
                        <small>Davao del Sur State College</small>
                        <strong>Alumni Tracking & Job Portal</strong>
                    </div>
                </div>

                <nav class="kit-public-links">
                    <a class="{{ request()->routeIs('landing') ? 'is-active' : '' }}" href="{{ route('landing') }}">Home</a>
                    <a class="{{ request()->routeIs('login') ? 'is-active' : '' }}" href="{{ route('login') }}">Login</a>
                    <a class="{{ request()->routeIs('register') ? 'is-active' : '' }}" href="{{ route('register') }}">Register</a>
                </nav>

                <div class="kit-inline-actions">
                    <button class="kit-icon-button" type="button" data-theme-toggle aria-label="Toggle theme">
                        <i class="ph ph-moon"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main>
        @yield('guest-content')
    </main>
</div>
@endsection
