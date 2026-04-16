@extends('layouts.alumni')

@section('page-title', 'My Applications')

@section('dashboard-content')
<section class="kit-card pad-lg">
    <div class="kit-table-wrap">
        <table class="kit-table">
            <thead><tr><th>Job</th><th>Employer</th><th>Status</th><th>Applied</th></tr></thead>
            <tbody>
            @forelse ($applications as $application)
                <tr>
                    <td>{{ $application->job?->title }}</td>
                    <td>{{ $application->job?->employer?->company_name }}</td>
                    <td><span class="kit-badge info">{{ ucfirst($application->status) }}</span></td>
                    <td>{{ optional($application->created_at)->format('M d, Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="4">You have not applied to any jobs yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $applications->links() }}
</section>
@endsection
