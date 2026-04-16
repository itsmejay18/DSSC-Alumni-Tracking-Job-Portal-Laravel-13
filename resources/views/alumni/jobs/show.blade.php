@extends('layouts.alumni')

@section('page-title', 'Job Details')

@section('dashboard-content')
<section class="portal-two-col-grid">
    <article class="kit-card pad-lg">
        <div class="kit-card-head">
            <div>
                <h2>{{ $job->title }}</h2>
                <p class="kit-card-subtitle">{{ $job->employer?->company_name }} &middot; {{ $job->location }}</p>
            </div>
            @if ($match)
                <span class="kit-badge success">{{ $match->match_score }}% Match</span>
            @endif
        </div>
        <div class="portal-richtext">{!! $job->description !!}</div>
        <div class="portal-chip-row">
            @foreach ($job->skills_required ?? [] as $skill)
                <span class="kit-badge info">{{ $skill }}</span>
            @endforeach
        </div>
    </article>

    <article class="kit-card pad-lg">
        <div class="kit-card-head"><div><h2>Apply</h2><p class="kit-card-subtitle">Submit your updated resume for this role.</p></div></div>
        @if ($existingApplication)
            <div class="kit-empty"><i class="ph ph-check-circle"></i><h3>You already applied</h3><p class="kit-muted">Current status: {{ ucfirst($existingApplication->status) }}</p></div>
        @else
            <form method="POST" action="{{ route('alumni.jobs.apply', $job) }}" enctype="multipart/form-data" class="kit-form-grid">
                @csrf
                <div class="kit-field" style="flex-basis: 100%;"><label for="cover_letter">Cover Letter</label><textarea id="cover_letter" name="cover_letter" rows="6" required></textarea></div>
                <div class="kit-field"><label for="resume">Resume</label><input id="resume" type="file" name="resume" required></div>
                <div class="kit-field"><label for="portfolio_link">Portfolio</label><input id="portfolio_link" type="url" name="portfolio_link"></div>
                <div class="kit-action-row"><button class="kit-button primary" type="submit">Submit Application</button></div>
            </form>
        @endif
    </article>
</section>
@endsection
