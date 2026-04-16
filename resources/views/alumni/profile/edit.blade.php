@extends('layouts.alumni')

@section('page-title', 'Edit Profile')

@section('dashboard-content')
<section class="kit-card pad-lg">
    <form method="POST" action="{{ route('alumni.profile.update') }}" enctype="multipart/form-data" class="kit-form-grid">
        @csrf
        @method('PUT')
        <div class="kit-field"><label for="name">Name</label><input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required></div>
        <div class="kit-field"><label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required></div>
        <div class="kit-field"><label for="student_id">Student ID</label><input id="student_id" type="text" name="student_id" value="{{ old('student_id', $user->alumniProfile?->student_id) }}" required></div>
        <div class="kit-field"><label for="first_name">First Name</label><input id="first_name" type="text" name="first_name" value="{{ old('first_name', $user->alumniProfile?->first_name) }}" required></div>
        <div class="kit-field"><label for="last_name">Last Name</label><input id="last_name" type="text" name="last_name" value="{{ old('last_name', $user->alumniProfile?->last_name) }}" required></div>
        <div class="kit-field"><label for="middle_name">Middle Name</label><input id="middle_name" type="text" name="middle_name" value="{{ old('middle_name', $user->alumniProfile?->middle_name) }}"></div>
        <div class="kit-field">
            <label for="course_id">Course</label>
            <select id="course_id" name="course_id">
                <option value="">Select course</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" @selected(old('course_id', $user->alumniProfile?->course_id) == $course->id)>{{ $course->course_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="kit-field"><label for="year_graduated">Year Graduated</label><input id="year_graduated" type="number" name="year_graduated" value="{{ old('year_graduated', $user->alumniProfile?->year_graduated) }}"></div>
        <div class="kit-field"><label for="skills">Skills (comma separated)</label><input id="skills" type="text" name="skills[]" value="{{ implode(', ', $user->alumniProfile?->skills ?? []) }}"></div>
        <div class="kit-field">
            <label for="employment_status">Employment Status</label>
            <select id="employment_status" name="employment_status">
                @foreach (['employed', 'unemployed', 'self-employed', 'further_study', 'not_looking'] as $status)
                    <option value="{{ $status }}" @selected(old('employment_status', $user->alumniProfile?->employment_status) === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="kit-field"><label for="current_job_title">Current Job Title</label><input id="current_job_title" type="text" name="current_job_title" value="{{ old('current_job_title', $user->alumniProfile?->current_job_title) }}"></div>
        <div class="kit-field"><label for="current_company">Current Company</label><input id="current_company" type="text" name="current_company" value="{{ old('current_company', $user->alumniProfile?->current_company) }}"></div>
        <div class="kit-field"><label for="profile_photo">Profile Photo</label><input id="profile_photo" type="file" name="profile_photo" accept="image/*"></div>
        <div class="kit-action-row"><button class="kit-button primary" type="submit">Update profile</button></div>
    </form>
</section>
@endsection
