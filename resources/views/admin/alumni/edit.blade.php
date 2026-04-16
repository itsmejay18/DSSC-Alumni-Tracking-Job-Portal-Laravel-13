@extends('layouts.admin')

@section('page-title', 'Edit Alumni')

@section('dashboard-content')
<section class="kit-card pad-lg">
    <form method="POST" action="{{ route('admin.alumni.update', $alumni) }}" class="kit-form-grid">
        @csrf
        @method('PUT')
        <div class="kit-field"><label for="name">Name</label><input id="name" type="text" name="name" value="{{ old('name', $alumni->name) }}" required></div>
        <div class="kit-field"><label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email', $alumni->email) }}" required></div>
        <div class="kit-field"><label for="first_name">First name</label><input id="first_name" type="text" name="first_name" value="{{ old('first_name', $alumni->alumniProfile?->first_name) }}" required></div>
        <div class="kit-field"><label for="last_name">Last name</label><input id="last_name" type="text" name="last_name" value="{{ old('last_name', $alumni->alumniProfile?->last_name) }}" required></div>
        <div class="kit-field"><label for="middle_name">Middle name</label><input id="middle_name" type="text" name="middle_name" value="{{ old('middle_name', $alumni->alumniProfile?->middle_name) }}"></div>
        <div class="kit-field">
            <label for="course_id">Course</label>
            <select id="course_id" name="course_id">
                <option value="">Select course</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" @selected(old('course_id', $alumni->alumniProfile?->course_id) == $course->id)>{{ $course->course_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="kit-field"><label for="year_graduated">Year Graduated</label><input id="year_graduated" type="number" name="year_graduated" value="{{ old('year_graduated', $alumni->alumniProfile?->year_graduated) }}"></div>
        <div class="kit-field">
            <label for="employment_status">Employment Status</label>
            <select id="employment_status" name="employment_status">
                @foreach (['employed', 'unemployed', 'self-employed', 'further_study', 'not_looking'] as $status)
                    <option value="{{ $status }}" @selected(old('employment_status', $alumni->alumniProfile?->employment_status) === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="kit-field"><label for="contact_number">Contact Number</label><input id="contact_number" type="text" name="contact_number" value="{{ old('contact_number', $alumni->alumniProfile?->contact_number) }}"></div>
        <div class="kit-action-row"><button class="kit-button primary" type="submit">Save changes</button></div>
    </form>
</section>
@endsection
