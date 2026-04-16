@extends('layouts.admin')

@section('page-title', 'Alumni Directory')

@section('dashboard-content')
<section class="kit-card pad-lg">
    <div class="kit-card-head">
        <div>
            <h2>Alumni Records</h2>
            <p class="kit-card-subtitle">Search and verify graduate profiles by course, student ID, or employment status.</p>
        </div>
    </div>
    <form method="GET" class="kit-form-grid">
        <div class="kit-field"><label for="name">Name</label><input id="name" type="text" name="name" value="{{ request('name') }}"></div>
        <div class="kit-field"><label for="student_id">Student ID</label><input id="student_id" type="text" name="student_id" value="{{ request('student_id') }}"></div>
        <div class="kit-field"><label for="year_graduated">Year Graduated</label><input id="year_graduated" type="number" name="year_graduated" value="{{ request('year_graduated') }}"></div>
        <div class="kit-action-row"><button class="kit-button primary" type="submit">Search</button></div>
    </form>
</section>

<section class="kit-card pad-lg">
    <div class="kit-table-wrap">
        <table class="kit-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Student ID</th>
                <th>Course</th>
                <th>Status</th>
                <th>Verified</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse ($alumni as $record)
                <tr>
                    <td>{{ $record->name }}</td>
                    <td>{{ $record->alumniProfile?->student_id }}</td>
                    <td>{{ $record->alumniProfile?->course?->course_code }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $record->alumniProfile?->employment_status ?? 'n/a')) }}</td>
                    <td><span class="kit-badge {{ $record->alumniProfile?->is_verified ? 'success' : 'warning' }}">{{ $record->alumniProfile?->is_verified ? 'Verified' : 'Pending' }}</span></td>
                    <td><a class="kit-button secondary" href="{{ route('admin.alumni.show', $record) }}">View</a></td>
                </tr>
            @empty
                <tr><td colspan="6">No alumni records found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $alumni->links() }}
</section>
@endsection
