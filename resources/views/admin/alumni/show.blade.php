@extends('layouts.admin')

@section('page-title', 'Alumni Profile')

@section('dashboard-content')
<section class="portal-two-col-grid">
    <article class="kit-card pad-lg">
        <div class="kit-card-head">
            <div>
                <h2>{{ $alumni->name }}</h2>
                <p class="kit-card-subtitle">{{ $alumni->alumniProfile?->student_id }} • {{ $alumni->alumniProfile?->course?->course_name }}</p>
            </div>
        </div>
        <div class="portal-detail-grid">
            <div><strong>Email</strong><p>{{ $alumni->email }}</p></div>
            <div><strong>Employment</strong><p>{{ ucfirst(str_replace('_', ' ', $alumni->alumniProfile?->employment_status ?? 'n/a')) }}</p></div>
            <div><strong>Current Company</strong><p>{{ $alumni->alumniProfile?->current_company ?: 'Not set' }}</p></div>
            <div><strong>Current Role</strong><p>{{ $alumni->alumniProfile?->current_job_title ?: 'Not set' }}</p></div>
        </div>
        <div class="kit-action-row" style="margin-top: 18px;">
            <a class="kit-button primary" href="{{ route('admin.alumni.edit', $alumni) }}">Edit</a>
            <a class="kit-button secondary" href="{{ route('admin.alumni.verify', $alumni) }}">Verification</a>
        </div>
    </article>

    <article class="kit-card pad-lg">
        <div class="kit-card-head">
            <div>
                <h2>Applications & Matches</h2>
                <p class="kit-card-subtitle">Latest engagement in the job portal.</p>
            </div>
        </div>
        <div class="kit-list">
            @forelse ($alumni->jobApplications as $application)
                <div class="kit-list-item">
                    <div class="kit-list-meta">
                        <strong>{{ $application->job?->title }}</strong>
                        <span>Application status: {{ ucfirst($application->status) }}</span>
                    </div>
                    <span class="kit-badge info">{{ optional($application->created_at)->format('M d, Y') }}</span>
                </div>
            @empty
                <div class="kit-empty"><i class="ph ph-files"></i><h3>No applications yet</h3></div>
            @endforelse
        </div>
    </article>
</section>
@endsection
