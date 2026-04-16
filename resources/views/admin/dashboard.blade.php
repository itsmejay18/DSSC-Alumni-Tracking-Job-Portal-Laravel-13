@extends('layouts.admin')

@section('page-title', 'Admin Dashboard')

@section('dashboard-content')
<section class="kit-dashboard-section-head">
    <div>
        <h2>Analytics Overview</h2>
        <p>Institution-wide alumni, employer, and job portal metrics.</p>
    </div>
    <span class="kit-dashboard-tag kit-dashboard-tag-blue">Live Summary</span>
</section>

<section class="kit-dashboard-stats">
    <x-cards.statistic-card label="Alumni" :value="$stats['alumni']" subtitle="Tracked graduate records" icon="ph-users" tag="Graduate Tracking" stat-key="alumni" />
    <x-cards.statistic-card label="Employers" :value="$stats['employers']" subtitle="Registered partner companies" icon="ph-buildings" tag="Verification" tag-class="kit-dashboard-tag-green" stat-key="employers" />
    <x-cards.statistic-card label="Active Jobs" :value="$stats['active_jobs']" subtitle="Approved and visible postings" icon="ph-briefcase" tag="Opportunities" tag-class="kit-dashboard-tag-yellow" stat-key="active_jobs" />
    <x-cards.statistic-card label="Applications" :value="$stats['applications']" subtitle="Submitted job applications" icon="ph-files" tag="Hiring" tag-class="kit-dashboard-tag-rose" stat-key="applications" />
</section>

<section class="kit-dashboard-chart-grid">
    <x-charts.employment-trend id="admin-employment-trend" :series="$employmentTrend" />
    <x-charts.job-distribution id="admin-job-distribution" :series="$jobDistribution" />
</section>

<section class="portal-two-col-grid">
    <article class="kit-card pad-lg">
        <div class="kit-card-head">
            <div>
                <h2>Top Employers</h2>
                <p class="kit-card-subtitle">Most active companies on the portal.</p>
            </div>
        </div>
        <div class="kit-list">
            @foreach ($topEmployers as $employer)
                <div class="kit-list-item">
                    <div class="kit-list-meta">
                        <strong>{{ $employer['label'] }}</strong>
                        <span>{{ $employer['value'] }} job postings</span>
                    </div>
                    <span class="kit-badge info">Active</span>
                </div>
            @endforeach
        </div>
    </article>

    <article class="kit-card pad-lg">
        <div class="kit-card-head">
            <div>
                <h2>Pending Employers</h2>
                <p class="kit-card-subtitle">New registrations awaiting verification.</p>
            </div>
        </div>
        <div class="kit-list">
            @forelse ($pendingEmployers as $employer)
                <div class="kit-list-item">
                    <div class="kit-list-meta">
                        <strong>{{ $employer->company_name }}</strong>
                        <span>{{ $employer->user?->email }}</span>
                    </div>
                    <a class="kit-button secondary" href="{{ route('admin.employers.show', $employer) }}">Review</a>
                </div>
            @empty
                <div class="kit-empty">
                    <i class="ph ph-check-circle"></i>
                    <h3>No pending employers</h3>
                    <p class="kit-muted">All current registrations have been reviewed.</p>
                </div>
            @endforelse
        </div>
    </article>
</section>

@push('scripts')
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endpush
@endsection
