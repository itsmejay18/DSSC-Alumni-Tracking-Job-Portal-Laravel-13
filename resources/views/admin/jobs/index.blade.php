@extends('layouts.admin')

@section('page-title', 'Job Approvals')

@section('dashboard-content')
<section class="kit-card pad-lg">
    <form method="GET" class="kit-form-grid">
        <div class="kit-field">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="">All</option>
                @foreach (['pending', 'approved', 'rejected', 'closed', 'expired'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <div class="kit-action-row"><button class="kit-button primary" type="submit">Filter</button></div>
    </form>
</section>

<section class="kit-card pad-lg">
    <div class="kit-table-wrap">
        <table class="kit-table">
            <thead>
            <tr>
                <th>Title</th>
                <th>Employer</th>
                <th>Category</th>
                <th>Status</th>
                <th>Deadline</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse ($jobs as $job)
                <tr>
                    <td>{{ $job->title }}</td>
                    <td>{{ $job->employer?->company_name }}</td>
                    <td>{{ $job->jobCategory?->category_name }}</td>
                    <td><span class="kit-badge info">{{ ucfirst($job->status) }}</span></td>
                    <td>{{ optional($job->application_deadline)->format('M d, Y') ?: 'Open' }}</td>
                    <td><a class="kit-button secondary" href="{{ route('admin.jobs.show', $job) }}">Review</a></td>
                </tr>
            @empty
                <tr><td colspan="6">No jobs found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $jobs->links() }}
</section>
@endsection
