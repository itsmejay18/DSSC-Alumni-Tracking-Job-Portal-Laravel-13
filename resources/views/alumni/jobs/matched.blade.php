@extends('layouts.alumni')

@section('page-title', 'Recommended Jobs')

@section('dashboard-content')
<section class="kit-card pad-lg">
    <div class="kit-list">
        @forelse ($matches as $match)
            <div class="kit-list-item">
                <div class="kit-list-meta">
                    <strong>{{ $match->job?->title }}</strong>
                    <span>{{ $match->job?->employer?->company_name }} • {{ $match->match_score }}% match</span>
                </div>
                <a class="kit-button secondary" href="{{ route('alumni.jobs.show', $match->job_id) }}">Open</a>
            </div>
        @empty
            <div class="kit-empty"><i class="ph ph-magic-wand"></i><h3>No matches yet</h3><p class="kit-muted">Complete your profile to improve matching.</p></div>
        @endforelse
    </div>
    {{ $matches->links() }}
</section>
@endsection
