@extends('layouts.admin')

@section('page-title', 'Generate Report')

@section('dashboard-content')
<section class="kit-card pad-lg">
    <form method="POST" action="{{ route('admin.reports.generate') }}" class="kit-form-grid">
        @csrf
        <div class="kit-field">
            <label for="report_type">Report Type</label>
            <select id="report_type" name="report_type">
                <option value="employment_rate">Employment Rate</option>
                <option value="job_trends">Job Trends</option>
                <option value="alumni_distribution">Alumni Distribution</option>
                <option value="employer_activity">Employer Activity</option>
            </select>
        </div>
        <div class="kit-field">
            <label for="format">Format</label>
            <select id="format" name="format">
                <option value="json">JSON</option>
                <option value="pdf">PDF</option>
                <option value="excel">Excel</option>
            </select>
        </div>
        <div class="kit-field"><label for="year">Year</label><input id="year" type="number" name="year" value="{{ now()->year }}"></div>
        <div class="kit-action-row"><button class="kit-button primary" type="submit">Generate report</button></div>
    </form>
</section>
@endsection
