@extends('layouts.employer')

@section('page-title', 'Manage Jobs')

@section('dashboard-content')
<div class="kit-action-row" style="margin-bottom: 20px;">
    <a class="kit-button primary" href="{{ route('employer.jobs.create') }}">Post New Job</a>
</div>

<section class="kit-card pad-lg">
    <div class="kit-table-wrap">
        <table class="kit-table">
            <thead><tr><th>Title</th><th>Status</th><th>Category</th><th>Applications</th><th></th></tr></thead>
            <tbody>
            @forelse ($jobs as $job)
                <tr>
                    <td>{{ $job->title }}</td>
                    <td><span class="kit-badge info">{{ ucfirst($job->status) }}</span></td>
                    <td>{{ $job->jobCategory?->category_name }}</td>
                    <td>{{ $job->applications_count }}</td>
                    <td><a class="kit-button secondary" href="{{ route('employer.jobs.show', $job) }}">Open</a></td>
                </tr>
            @empty
                <tr><td colspan="5">No jobs posted yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $jobs->links() }}
</section>
@endsection
