@extends('layouts.app')

@section('body-class', 'kit-dashboard-page')

@section('content')
@php($user = auth()->user())
<div class="kit-overlay" data-sidebar-close></div>
<div class="kit-admin-shell">
    <aside class="kit-sidebar kit-dashboard-sidebar">
        <button class="kit-sidebar-close" type="button" data-sidebar-close aria-label="Close sidebar"><i class="ph ph-x"></i></button>
        <div class="kit-dashboard-brand">
            <img src="{{ asset('uikit/assets/images/dssc-logo-circle.png') }}" alt="DSSC logo" class="kit-dashboard-brand-logo">
            <div class="kit-dashboard-brand-copy">
                <p>DSSC</p>
                <strong>Employer Access</strong>
            </div>
        </div>
        <nav class="kit-dashboard-nav">
            <a class="kit-dashboard-nav-link {{ request()->routeIs('employer.dashboard*') ? 'is-active' : '' }}" href="{{ route('employer.dashboard') }}"><i class="ph ph-gauge"></i><span>Dashboard</span></a>
            <a class="kit-dashboard-nav-link {{ request()->routeIs('employer.jobs*') ? 'is-active' : '' }}" href="{{ route('employer.jobs.index') }}"><i class="ph ph-briefcase"></i><span>Jobs</span></a>
            <a class="kit-dashboard-nav-link {{ request()->routeIs('employer.applications*') ? 'is-active' : '' }}" href="{{ route('employer.applications.index') }}"><i class="ph ph-files"></i><span>Applications</span></a>
            <a class="kit-dashboard-nav-link {{ request()->routeIs('employer.profile*') ? 'is-active' : '' }}" href="{{ route('employer.profile.edit') }}"><i class="ph ph-buildings"></i><span>Company Profile</span></a>
        </nav>
    </aside>

    <main class="kit-main kit-dashboard-main">
        <div class="kit-frame kit-dashboard-frame">
            <header class="kit-dashboard-topbar">
                <div class="kit-dashboard-topbar-left">
                    <button class="kit-icon-button kit-mobile-trigger" type="button" data-sidebar-open aria-label="Open sidebar"><i class="ph ph-list"></i></button>
                    <h1>@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="kit-dashboard-topbar-right">
                    <button class="kit-dashboard-circle-button" type="button" data-theme-toggle aria-label="Toggle theme"><i class="ph ph-sun"></i></button>
                    <div class="kit-dashboard-user">
                        <img src="{{ asset('uikit/assets/images/dssc-logo-circle.png') }}" alt="{{ $user->name }}" class="kit-dashboard-user-avatar">
                        <div class="kit-dashboard-user-copy">
                            <strong>{{ $user->employerProfile?->company_name }}</strong>
                            <span>{{ $user->email }}</span>
                        </div>
                    </div>
                </div>
            </header>
            <div class="kit-dashboard-content">
                @yield('dashboard-content')
            </div>
        </div>
    </main>
</div>
@endsection
