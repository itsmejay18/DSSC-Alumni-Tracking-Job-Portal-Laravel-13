<div style="font-family: Public Sans, Arial, sans-serif; color: #10233d;">
    <h2>Weekly portal report</h2>
    <p>The latest {{ str_replace('_', ' ', $report->report_type) }} report has been generated.</p>
    <p><strong>Format:</strong> {{ strtoupper($report->format) }}</p>
    <p><a href="{{ route('admin.reports.download', $report) }}">Download report</a></p>
</div>
