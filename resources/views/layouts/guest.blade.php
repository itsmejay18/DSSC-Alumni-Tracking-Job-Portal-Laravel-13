@extends('layouts.app')

@section('content')
@php($showGuestHeader = filter_var(trim($__env->yieldContent('show-guest-header', request()->routeIs('landing') ? 'true' : 'false')), FILTER_VALIDATE_BOOLEAN))
<div class="kit-shell">
    @if ($showGuestHeader)
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

                    <div class="kit-inline-actions">
                        <button class="kit-icon-button" type="button" data-theme-toggle aria-label="Toggle theme">
                            <i class="ph ph-moon"></i>
                        </button>
                    </div>
                </div>
            </div>
        </header>
    @endif

    <main class="{{ $showGuestHeader ? '' : 'portal-guest-main' }}">
        @yield('guest-content')
    </main>
</div>
@endsection
