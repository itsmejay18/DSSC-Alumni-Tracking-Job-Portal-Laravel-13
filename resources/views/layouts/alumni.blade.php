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
                <strong>Alumni Access</strong>
            </div>
        </div>
        <nav class="kit-dashboard-nav">
            <a class="kit-dashboard-nav-link {{ request()->routeIs('alumni.dashboard*') ? 'is-active' : '' }}" href="{{ route('alumni.dashboard') }}"><i class="ph ph-gauge"></i><span>Dashboard</span></a>
            <a class="kit-dashboard-nav-link {{ request()->routeIs('alumni.profile*') ? 'is-active' : '' }}" href="{{ route('alumni.profile.show') }}"><i class="ph ph-user-circle"></i><span>Profile</span></a>
            <a class="kit-dashboard-nav-link {{ request()->routeIs('alumni.jobs.index') ? 'is-active' : '' }}" href="{{ route('alumni.jobs.index') }}"><i class="ph ph-briefcase"></i><span>Jobs</span></a>
            <a class="kit-dashboard-nav-link {{ request()->routeIs('alumni.jobs.matched', 'alumni.matches*') ? 'is-active' : '' }}" href="{{ route('alumni.jobs.matched') }}"><i class="ph ph-magic-wand"></i><span>Matches</span></a>
            <a class="kit-dashboard-nav-link {{ request()->routeIs('alumni.applications*') ? 'is-active' : '' }}" href="{{ route('alumni.applications.index') }}"><i class="ph ph-files"></i><span>Applications</span></a>
            <a class="kit-dashboard-nav-link {{ request()->routeIs('alumni.notifications*') ? 'is-active' : '' }}" href="{{ route('alumni.notifications.index') }}"><i class="ph ph-bell"></i><span>Notifications</span></a>
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
                    <div class="portal-user-menu" data-user-menu>
                        <button class="kit-dashboard-user portal-user-trigger" type="button" data-user-menu-trigger aria-expanded="false" aria-haspopup="true">
                            <img src="{{ asset('uikit/assets/images/dssc-logo-circle.png') }}" alt="{{ $user->name }}" class="kit-dashboard-user-avatar">
                            <div class="kit-dashboard-user-copy">
                                <strong>{{ $user->name }}</strong>
                                <span>{{ $user->alumniProfile?->student_id }}</span>
                            </div>
                            <i class="ph ph-caret-down"></i>
                        </button>
                        <div class="portal-user-dropdown" data-user-menu-panel hidden>
                            <div class="portal-user-dropdown-head">
                                <strong>{{ $user->name }}</strong>
                                <span>{{ $user->email }}</span>
                            </div>
                            <a class="portal-user-dropdown-link" href="{{ route('alumni.profile.show') }}">
                                <i class="ph ph-user-circle"></i>
                                <span>View Profile</span>
                            </a>
                            <a class="portal-user-dropdown-link" href="{{ route('alumni.profile.edit') }}">
                                <i class="ph ph-pencil-simple"></i>
                                <span>Edit Profile</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="portal-user-dropdown-link danger" type="submit">
                                    <i class="ph ph-sign-out"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
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
