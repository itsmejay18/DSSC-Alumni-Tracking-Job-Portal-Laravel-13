@extends('layouts.admin')

@section('page-title', 'Job Review')

@section('dashboard-content')
<section class="portal-two-col-grid">
    <article class="kit-card pad-lg">
        <div class="kit-card-head">
            <div>
                <h2>{{ $job->title }}</h2>
                <p class="kit-card-subtitle">{{ $job->employer?->company_name }} &middot; {{ $job->jobCategory?->category_name }}</p>
            </div>
            <span class="kit-badge info">{{ ucfirst($job->status) }}</span>
        </div>
        <div class="portal-richtext">{!! $job->description !!}</div>
        <div class="portal-detail-grid">
            <div><strong>Salary</strong><p>{{ $job->salary_range }}</p></div>
            <div><strong>Deadline</strong><p>{{ optional($job->application_deadline)->format('M d, Y') ?: 'Open until filled' }}</p></div>
            <div><strong>Type</strong><p>{{ ucfirst($job->job_type) }}</p></div>
            <div><strong>Experience</strong><p>{{ ucfirst($job->experience_level) }}</p></div>
        </div>
        <div class="kit-action-row" style="margin-top: 20px;">
            <form method="POST" action="{{ route('admin.jobs.approve', $job) }}">@csrf<button class="kit-button primary" type="submit">Approve</button></form>
            <form method="POST" action="{{ route('admin.jobs.reject', $job) }}">@csrf<button class="kit-button danger" type="submit">Reject</button></form>
        </div>
    </article>

    <article class="kit-card pad-lg">
        <div class="kit-card-head">
            <div>
                <h2>Applicants</h2>
                <p class="kit-card-subtitle">Submitted alumni applications for this position.</p>
            </div>
        </div>
        <div class="kit-list">
            @forelse ($job->applications as $application)
                <div class="kit-list-item">
                    <div class="kit-list-meta">
                        <strong>{{ $application->alumni?->name }}</strong>
                        <span>Status: {{ ucfirst($application->status) }}</span>
                    </div>
                    <button class="kit-button secondary" type="button" data-modal-open="#application-modal-{{ $application->id }}">View</button>
                </div>
                <x-modals.view-application :application="$application" />
            @empty
                <div class="kit-empty"><i class="ph ph-files"></i><h3>No applications yet</h3></div>
            @endforelse
        </div>
    </article>
</section>
@endsection
