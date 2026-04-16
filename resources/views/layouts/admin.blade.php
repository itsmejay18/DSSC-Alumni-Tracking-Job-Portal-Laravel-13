@extends('layouts.app')

@section('body-class', 'kit-dashboard-page')

@section('content')
@php($user = auth()->user())
<div class="kit-overlay" data-sidebar-close></div>
<div class="kit-admin-shell">
    <aside class="kit-sidebar kit-dashboard-sidebar">
        <button class="kit-sidebar-close" type="button" data-sidebar-close aria-label="Close sidebar">
            <i class="ph ph-x"></i>
        </button>

        <div class="kit-dashboard-brand">
            <img src="{{ asset('uikit/assets/images/dssc-logo-circle.png') }}" alt="DSSC logo" class="kit-dashboard-brand-logo">
            <div class="kit-dashboard-brand-copy">
                <p>DSSC</p>
                <strong>Alumni Portal</strong>
            </div>
        </div>

        <nav class="kit-dashboard-nav">
            <a class="kit-dashboard-nav-link {{ request()->routeIs('admin.dashboard*') ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="ph ph-gauge"></i><span>Dashboard</span></a>
            <a class="kit-dashboard-nav-link {{ request()->routeIs('admin.alumni*') ? 'is-active' : '' }}" href="{{ route('admin.alumni.index') }}"><i class="ph ph-users-three"></i><span>Alumni</span></a>
            <a class="kit-dashboard-nav-link {{ request()->routeIs('admin.employers*') ? 'is-active' : '' }}" href="{{ route('admin.employers.index') }}"><i class="ph ph-buildings"></i><span>Employers</span></a>
            <a class="kit-dashboard-nav-link {{ request()->routeIs('admin.jobs*') ? 'is-active' : '' }}" href="{{ route('admin.jobs.index') }}"><i class="ph ph-briefcase"></i><span>Jobs</span></a>
            <a class="kit-dashboard-nav-link {{ request()->routeIs('admin.reports*') ? 'is-active' : '' }}" href="{{ route('admin.reports.export-form') }}"><i class="ph ph-chart-bar"></i><span>Reports</span></a>
            <a class="kit-dashboard-nav-link {{ request()->routeIs('admin.settings*') ? 'is-active' : '' }}" href="{{ route('admin.settings.index') }}"><i class="ph ph-gear"></i><span>Settings</span></a>
            <a class="kit-dashboard-nav-link {{ request()->routeIs('admin.logs*') ? 'is-active' : '' }}" href="{{ route('admin.logs.activity') }}"><i class="ph ph-clipboard-text"></i><span>Activity Logs</span></a>
        </nav>

        <div class="kit-sidebar-footer">
            <p>CHED-ready alumni reporting, employer verification, and scheduled automation are enabled from this panel.</p>
        </div>
    </aside>

    <main class="kit-main kit-dashboard-main">
        <div class="kit-frame kit-dashboard-frame">
            <header class="kit-dashboard-topbar">
                <div class="kit-dashboard-topbar-left">
                    <button class="kit-icon-button kit-mobile-trigger" type="button" data-sidebar-open aria-label="Open sidebar">
                        <i class="ph ph-list"></i>
                    </button>
                    <h1>@yield('page-title', 'Dashboard')</h1>
                </div>

                <div class="kit-dashboard-topbar-right">
                    <button class="kit-dashboard-circle-button" type="button" data-modal-open="#notification-modal" aria-label="Open notifications">
                        <i class="ph ph-bell"></i>
                    </button>
                    <button class="kit-dashboard-circle-button" type="button" data-theme-toggle aria-label="Toggle theme">
                        <i class="ph ph-sun"></i>
                    </button>
                    <div class="portal-user-menu" data-user-menu>
                        <button class="kit-dashboard-user portal-user-trigger" type="button" data-user-menu-trigger aria-expanded="false" aria-haspopup="true">
                            <img src="{{ asset('uikit/assets/images/dssc-logo-circle.png') }}" alt="{{ $user->name }}" class="kit-dashboard-user-avatar">
                            <div class="kit-dashboard-user-copy">
                                <strong>{{ $user->name }}</strong>
                                <span>{{ $user->email }}</span>
                            </div>
                            <i class="ph ph-caret-down"></i>
                        </button>
                        <div class="portal-user-dropdown" data-user-menu-panel hidden>
                            <div class="portal-user-dropdown-head">
                                <strong>{{ $user->name }}</strong>
                                <span>{{ $user->email }}</span>
                            </div>
                            <a class="portal-user-dropdown-link" href="{{ route('admin.settings.index') }}">
                                <i class="ph ph-gear"></i>
                                <span>Account Settings</span>
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

<div class="kit-modal" id="notification-modal">
    <div class="kit-modal-panel">
        <div class="kit-modal-head">
            <div>
                <strong>Recent notifications</strong>
                <p class="kit-muted" style="margin: 6px 0 0;">Portal alerts relevant to your account.</p>
            </div>
            <button class="kit-close" type="button" data-modal-close aria-label="Close">
                <i class="ph ph-x"></i>
            </button>
        </div>
        <div class="kit-modal-body">
            <div class="kit-list">
                @forelse ($user->portalNotifications()->take(5)->get() as $notification)
                    <div class="kit-list-item">
                        <div class="kit-list-meta">
                            <strong>{{ $notification->title }}</strong>
                            <span>{{ $notification->message }}</span>
                        </div>
                        <span class="kit-badge {{ $notification->is_read ? 'info' : 'warning' }}">{{ $notification->is_read ? 'Read' : 'Unread' }}</span>
                    </div>
                @empty
                    <div class="kit-empty">
                        <i class="ph ph-bell-slash"></i>
                        <h3>No notifications</h3>
                        <p class="kit-muted">You are all caught up.</p>
                    </div>
                @endforelse
            </div>
        </div>
        <div class="kit-modal-foot">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="kit-button secondary" type="submit">Logout</button>
            </form>
            <button class="kit-button primary" type="button" data-modal-close>Close</button>
        </div>
    </div>
</div>
@endsection
