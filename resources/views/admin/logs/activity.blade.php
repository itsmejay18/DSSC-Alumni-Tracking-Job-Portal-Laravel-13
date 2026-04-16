@extends('layouts.admin')

@section('page-title', 'Activity Logs')

@section('dashboard-content')
<section class="kit-card pad-lg">
    <div class="kit-table-wrap">
        <table class="kit-table">
            <thead><tr><th>Date</th><th>User</th><th>Action</th><th>Module</th><th>Description</th></tr></thead>
            <tbody>
            @forelse ($logs as $log)
                <tr>
                    <td>{{ optional($log->created_at)->format('M d, Y h:i A') }}</td>
                    <td>{{ $log->user?->name ?: 'System' }}</td>
                    <td>{{ ucfirst($log->action) }}</td>
                    <td>{{ ucfirst($log->module) }}</td>
                    <td>{{ $log->description }}</td>
                </tr>
            @empty
                <tr><td colspan="5">No activity logs available.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $logs->links() }}
</section>
@endsection
