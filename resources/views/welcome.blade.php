@extends('layouts.guest')

@section('title', 'Welcome')

@section('guest-content')
<section class="kit-hero">
    <div class="kit-container">
        <div class="kit-hero-grid">
            <div class="kit-hero-copy">
                <span class="kit-kicker" style="color: var(--accent);">DSSC Career Outcomes Platform</span>
                <h1>Track alumni success and connect graduates with real job opportunities.</h1>
                <p>The portal supports graduate tracing, employer engagement, job matching, and accreditation-ready reporting in one platform.</p>
                <div class="kit-action-row" style="margin-top: 24px;">
                    <a class="kit-button primary" href="{{ route('login') }}"><i class="ph ph-sign-in"></i>Login</a>
                    <a class="kit-button secondary" href="{{ route('register') }}"><i class="ph ph-user-plus"></i>Create account</a>
                </div>
            </div>

            <div class="kit-hero-card">
                <span class="kit-pill"><i class="ph ph-chart-line-up"></i>Graduate Tracking Made Practical</span>
                <div class="kit-grid cols-2" style="margin-top: 18px;">
                    <div class="kit-card pad-md"><strong>{{ $alumniCount }}</strong><p class="kit-muted">Tracked alumni records</p></div>
                    <div class="kit-card pad-md"><strong>{{ $approvedEmployersCount }}</strong><p class="kit-muted">Verified employers</p></div>
                    <div class="kit-card pad-md"><strong>{{ $jobCategories->count() }}</strong><p class="kit-muted">Job categories</p></div>
                    <div class="kit-card pad-md"><strong>{{ $featuredJobs->count() }}</strong><p class="kit-muted">Featured opportunities</p></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="kit-section">
    <div class="kit-container">
        <div class="kit-section-header">
            <div>
                <span class="kit-kicker" style="color: var(--accent);">Featured Jobs</span>
                <h2>Current opportunities for graduates</h2>
                <p>Approved employers can post opportunities matched against alumni profiles and skills.</p>
            </div>
        </div>

        <div class="portal-card-grid">
            @forelse ($featuredJobs as $job)
                <div class="kit-card pad-md">
                    <h3>{{ $job->title }}</h3>
                    <p class="kit-muted">{{ $job->employer?->company_name }} &middot; {{ $job->location }}</p>
                    <p class="kit-muted">{{ \Illuminate\Support\Str::limit(strip_tags($job->description), 120) }}</p>
                </div>
            @empty
                <div class="kit-empty">
                    <i class="ph ph-briefcase"></i>
                    <h3>No featured jobs yet</h3>
                    <p class="kit-muted">Employers can start posting after approval.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
