@extends('layouts.employer')

@section('page-title', 'Job Details')

@section('dashboard-content')
<section class="portal-two-col-grid">
    <article class="kit-card pad-lg">
        <div class="kit-card-head">
            <div>
                <h2>{{ $job->title }}</h2>
                <p class="kit-card-subtitle">{{ $job->jobCategory?->category_name }} &middot; {{ ucfirst($job->status) }}</p>
            </div>
        </div>
        <div class="portal-richtext">{!! $job->description !!}</div>
        <div class="kit-action-row" style="margin-top: 20px;">
            <a class="kit-button primary" href="{{ route('employer.jobs.edit', $job) }}">Edit</a>
            <form method="POST" action="{{ route('employer.jobs.close', $job) }}">@csrf<button class="kit-button secondary" type="submit">Close</button></form>
            <form method="POST" action="{{ route('employer.jobs.destroy', $job) }}">@csrf @method('DELETE')<button class="kit-button danger" type="submit">Delete</button></form>
        </div>
    </article>

    <article class="kit-card pad-lg">
        <div class="kit-card-head"><div><h2>Applicants</h2><p class="kit-card-subtitle">People who applied to this job.</p></div></div>
        <div class="kit-list">
            @forelse ($job->applications as $application)
                <div class="kit-list-item">
                    <div class="kit-list-meta">
                        <strong>{{ $application->alumni?->name }}</strong>
                        <span>{{ ucfirst($application->status) }}</span>
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
