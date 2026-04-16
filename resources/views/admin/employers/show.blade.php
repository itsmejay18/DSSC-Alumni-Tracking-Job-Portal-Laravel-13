@extends('layouts.admin')

@section('page-title', 'Employer Review')

@section('dashboard-content')
<section class="portal-two-col-grid">
    <article class="kit-card pad-lg">
        <div class="kit-card-head">
            <div>
                <h2>{{ $employer->company_name }}</h2>
                <p class="kit-card-subtitle">{{ $employer->industry?->industry_name }} • {{ $employer->user?->email }}</p>
            </div>
            <span class="kit-badge {{ $employer->is_verified ? 'success' : 'warning' }}">{{ $employer->is_verified ? 'Verified' : 'Pending' }}</span>
        </div>
        <div class="portal-detail-grid">
            <div><strong>Registration No.</strong><p>{{ $employer->company_registration_number ?: 'Not provided' }}</p></div>
            <div><strong>Phone</strong><p>{{ $employer->phone ?: 'Not provided' }}</p></div>
            <div><strong>Website</strong><p>{{ $employer->website ?: 'Not provided' }}</p></div>
            <div><strong>Address</strong><p>{{ $employer->full_address ?: 'Not provided' }}</p></div>
        </div>
        <div class="kit-action-row" style="margin-top: 20px;">
            <form method="POST" action="{{ route('admin.employers.approve', $employer) }}">
                @csrf
                <input type="hidden" name="decision" value="approve">
                <button class="kit-button primary" type="submit">Approve</button>
            </form>
            <form method="POST" action="{{ route('admin.employers.reject', $employer) }}">
                @csrf
                <input type="hidden" name="decision" value="reject">
                <button class="kit-button danger" type="submit">Reject</button>
            </form>
        </div>
    </article>

    <article class="kit-card pad-lg">
        <div class="kit-card-head">
            <div>
                <h2>Recent Jobs</h2>
                <p class="kit-card-subtitle">Jobs associated with this employer profile.</p>
            </div>
        </div>
        <div class="kit-list">
            @forelse ($employer->jobs as $job)
                <div class="kit-list-item">
                    <div class="kit-list-meta">
                        <strong>{{ $job->title }}</strong>
                        <span>Status: {{ ucfirst($job->status) }}</span>
                    </div>
                    <a class="kit-button secondary" href="{{ route('admin.jobs.show', $job) }}">View</a>
                </div>
            @empty
                <div class="kit-empty"><i class="ph ph-briefcase"></i><h3>No jobs yet</h3></div>
            @endforelse
        </div>
    </article>
</section>
@endsection
