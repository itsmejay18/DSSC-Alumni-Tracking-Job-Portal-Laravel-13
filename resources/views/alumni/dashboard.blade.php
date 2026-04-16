@extends('layouts.alumni')

@section('page-title', 'My Dashboard')

@section('dashboard-content')
<section class="kit-dashboard-stats">
    <x-cards.statistic-card label="Applications" :value="$stats['applications']" subtitle="Jobs you applied for" icon="ph-files" />
    <x-cards.statistic-card label="Matches" :value="$stats['matches']" subtitle="Recommended opportunities" icon="ph-magic-wand" tag="Smart Match" tag-class="kit-dashboard-tag-green" />
    <x-cards.statistic-card label="Interviews" :value="$stats['interviews']" subtitle="Applications in interview stage" icon="ph-chats-circle" tag="Career" tag-class="kit-dashboard-tag-yellow" />
    <x-cards.statistic-card label="Alerts" :value="$stats['notifications']" subtitle="Unread notifications" icon="ph-bell" tag="Updates" tag-class="kit-dashboard-tag-rose" />
</section>

<section class="portal-two-col-grid">
    <article class="kit-card pad-lg">
        <div class="kit-card-head"><div><h2>Recent Applications</h2><p class="kit-card-subtitle">Track your active submissions.</p></div></div>
        <div class="kit-list">
            @forelse ($recentApplications as $application)
                <div class="kit-list-item">
                    <div class="kit-list-meta">
                        <strong>{{ $application->job?->title }}</strong>
                        <span>{{ ucfirst($application->status) }} • {{ $application->job?->employer?->company_name }}</span>
                    </div>
                    <span class="kit-badge info">{{ optional($application->created_at)->format('M d') }}</span>
                </div>
            @empty
                <div class="kit-empty"><i class="ph ph-files"></i><h3>No applications yet</h3></div>
            @endforelse
        </div>
    </article>

    <article class="kit-card pad-lg">
        <div class="kit-card-head"><div><h2>Recent Matches</h2><p class="kit-card-subtitle">Jobs aligned with your course and skills.</p></div></div>
        <div class="kit-list">
            @forelse ($recentMatches as $match)
                <div class="kit-list-item">
                    <div class="kit-list-meta">
                        <strong>{{ $match->job?->title }}</strong>
                        <span>{{ $match->job?->employer?->company_name }} • {{ $match->match_score }}%</span>
                    </div>
                    <a class="kit-button secondary" href="{{ route('alumni.jobs.show', $match->job_id) }}">Open</a>
                </div>
            @empty
                <div class="kit-empty"><i class="ph ph-magic-wand"></i><h3>No matches yet</h3></div>
            @endforelse
        </div>
    </article>
</section>
@endsection
