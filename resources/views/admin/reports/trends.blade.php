@extends('layouts.admin')

@section('page-title', 'Trend Reports')

@section('dashboard-content')
<section class="kit-card pad-lg">
    <div class="kit-table-wrap">
        <table class="kit-table">
            <thead><tr><th>Generated</th><th>Format</th><th>Created By</th><th></th></tr></thead>
            <tbody>
            @forelse ($reports as $report)
                <tr>
                    <td>{{ optional($report->generated_at)->format('M d, Y h:i A') }}</td>
                    <td>{{ strtoupper($report->format) }}</td>
                    <td>{{ $report->generatedBy?->name }}</td>
                    <td><a class="kit-button secondary" href="{{ route('admin.reports.download', $report) }}">Download</a></td>
                </tr>
            @empty
                <tr><td colspan="4">No trend reports available.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $reports->links() }}
</section>
@endsection
