@extends('layouts.employer')

@section('page-title', 'Applications')

@section('dashboard-content')
<section class="kit-card pad-lg">
    <div class="kit-table-wrap">
        <table class="kit-table">
            <thead><tr><th>Applicant</th><th>Job</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse ($applications as $application)
                <tr>
                    <td>{{ $application->alumni?->name }}</td>
                    <td>{{ $application->job?->title }}</td>
                    <td><span class="kit-badge info">{{ ucfirst($application->status) }}</span></td>
                    <td><a class="kit-button secondary" href="{{ route('employer.applications.show', $application) }}">Review</a></td>
                </tr>
            @empty
                <tr><td colspan="4">No applications submitted yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $applications->links() }}
</section>
@endsection
