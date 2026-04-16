@extends('layouts.employer')

@section('page-title', 'Employer Dashboard')

@section('dashboard-content')
<section class="kit-dashboard-stats">
    <x-cards.statistic-card label="Jobs Posted" :value="$stats['jobs']" subtitle="All company job postings" icon="ph-briefcase" />
    <x-cards.statistic-card label="Approved Jobs" :value="$stats['approved_jobs']" subtitle="Visible to alumni" icon="ph-check-circle" tag="Published" tag-class="kit-dashboard-tag-green" />
    <x-cards.statistic-card label="Applications" :value="$stats['applications']" subtitle="Received applications" icon="ph-files" tag="Recruitment" tag-class="kit-dashboard-tag-yellow" />
    <x-cards.statistic-card label="Pending Jobs" :value="$stats['pending_jobs']" subtitle="Awaiting admin approval" icon="ph-clock" tag="Review" tag-class="kit-dashboard-tag-rose" />
</section>

<section class="portal-two-col-grid">
    <article class="kit-card pad-lg">
        <div class="kit-card-head"><div><h2>Latest Jobs</h2><p class="kit-card-subtitle">Your most recent postings.</p></div></div>
        <div class="kit-list">
            @forelse ($latestJobs as $job)
                <div class="kit-list-item">
                    <div class="kit-list-meta">
                        <strong>{{ $job->title }}</strong>
                        <span>Status: {{ ucfirst($job->status) }}</span>
                    </div>
                    <a class="kit-button secondary" href="{{ route('employer.jobs.show', $job) }}">View</a>
                </div>
            @empty
                <div class="kit-empty"><i class="ph ph-briefcase"></i><h3>No jobs posted yet</h3></div>
            @endforelse
        </div>
    </article>

    <article class="kit-card pad-lg">
        <div class="kit-card-head"><div><h2>Latest Applications</h2><p class="kit-card-subtitle">Recent alumni interest in your jobs.</p></div></div>
        <div class="kit-list">
            @forelse ($latestApplications as $application)
                <div class="kit-list-item">
                    <div class="kit-list-meta">
                        <strong>{{ $application->alumni?->name }}</strong>
                        <span>{{ $application->job?->title }} • {{ ucfirst($application->status) }}</span>
                    </div>
                    <a class="kit-button secondary" href="{{ route('employer.applications.show', $application) }}">Review</a>
                </div>
            @empty
                <div class="kit-empty"><i class="ph ph-files"></i><h3>No applications yet</h3></div>
            @endforelse
        </div>
    </article>
</section>
@endsection
