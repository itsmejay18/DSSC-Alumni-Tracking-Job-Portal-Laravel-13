@extends('layouts.alumni')

@section('page-title', 'My Profile')

@section('dashboard-content')
<section class="portal-two-col-grid">
    <article class="kit-card pad-lg">
        <div class="kit-card-head">
            <div>
                <h2>{{ $user->name }}</h2>
                <p class="kit-card-subtitle">{{ $user->alumniProfile?->course?->course_name }} • {{ $user->alumniProfile?->student_id }}</p>
            </div>
        </div>
        <div class="portal-detail-grid">
            <div><strong>Email</strong><p>{{ $user->email }}</p></div>
            <div><strong>Employment</strong><p>{{ ucfirst(str_replace('_', ' ', $user->alumniProfile?->employment_status ?? 'n/a')) }}</p></div>
            <div><strong>Company</strong><p>{{ $user->alumniProfile?->current_company ?: 'Not set' }}</p></div>
            <div><strong>Role</strong><p>{{ $user->alumniProfile?->current_job_title ?: 'Not set' }}</p></div>
        </div>
        <div class="kit-action-row" style="margin-top: 18px;">
            <a class="kit-button primary" href="{{ route('alumni.profile.edit') }}">Edit profile</a>
            <a class="kit-button secondary" href="{{ route('alumni.profile.export') }}">Export data</a>
        </div>
    </article>

    <article class="kit-card pad-lg">
        <div class="kit-card-head"><div><h2>Skills</h2><p class="kit-card-subtitle">Used for smart job matching.</p></div></div>
        <div class="portal-chip-row">
            @forelse ($user->alumniProfile?->skills ?? [] as $skill)
                <span class="kit-badge info">{{ $skill }}</span>
            @empty
                <p class="kit-muted">No skills added yet.</p>
            @endforelse
        </div>
    </article>
</section>
@endsection
