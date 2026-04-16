@props(['job'])

<article class="kit-card pad-lg portal-job-card">
    <div class="kit-card-head">
        <div>
            <h3>{{ $job->title }}</h3>
            <p class="kit-card-subtitle">{{ $job->employer?->company_name }} • {{ $job->location ?: 'Location flexible' }}</p>
        </div>
        <span class="kit-badge info">{{ ucfirst($job->job_type) }}</span>
    </div>
    <p class="kit-muted">{{ \Illuminate\Support\Str::limit(strip_tags($job->description), 140) }}</p>
    <div class="portal-chip-row">
        <span class="kit-badge success">{{ ucfirst($job->experience_level) }}</span>
        <span class="kit-badge warning">{{ $job->salary_range }}</span>
    </div>
    <div class="kit-action-row" style="margin-top: 18px;">
        <a class="kit-button primary" href="{{ route(auth()->user()?->isEmployer() ? 'employer.jobs.show' : 'alumni.jobs.show', $job) }}">View details</a>
    </div>
</article>
