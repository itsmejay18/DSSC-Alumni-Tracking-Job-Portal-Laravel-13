@extends('layouts.guest')

@section('title', 'Register')

@section('guest-content')
<section class="kit-section">
    <div class="kit-container portal-auth-wrap">
        <div class="kit-card pad-lg portal-auth-card">
            <div class="kit-card-head">
                <div>
                    <span class="kit-kicker" style="color: var(--accent);">Portal Registration</span>
                    <h2>Create your account</h2>
                    <p class="kit-card-subtitle">Choose alumni or employer registration and complete the required details.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('register') }}" class="kit-form-grid">
                @csrf
                <div class="kit-field">
                    <label for="role">Register as</label>
                    <select id="role" name="role">
                        <option value="alumni" @selected(old('role') === 'alumni')>Alumni</option>
                        <option value="employer" @selected(old('role') === 'employer')>Employer</option>
                    </select>
                </div>
                <div class="kit-field">
                    <label for="name">Full name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="kit-field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div class="kit-field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                </div>
                <div class="kit-field">
                    <label for="password_confirmation">Confirm password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>

                <div class="portal-section-box">
                    <h3>Alumni details</h3>
                    <div class="kit-form-grid">
                        <div class="kit-field"><label for="student_id">Student ID</label><input id="student_id" type="text" name="student_id" value="{{ old('student_id') }}"></div>
                        <div class="kit-field"><label for="first_name">First name</label><input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}"></div>
                        <div class="kit-field"><label for="last_name">Last name</label><input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}"></div>
                        <div class="kit-field"><label for="middle_name">Middle name</label><input id="middle_name" type="text" name="middle_name" value="{{ old('middle_name') }}"></div>
                        <div class="kit-field">
                            <label for="course_id">Course</label>
                            <select id="course_id" name="course_id">
                                <option value="">Select course</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>{{ $course->course_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="kit-field"><label for="year_graduated">Year graduated</label><input id="year_graduated" type="number" name="year_graduated" value="{{ old('year_graduated') }}"></div>
                    </div>
                </div>

                <div class="portal-section-box">
                    <h3>Employer details</h3>
                    <div class="kit-form-grid">
                        <div class="kit-field"><label for="company_name">Company name</label><input id="company_name" type="text" name="company_name" value="{{ old('company_name') }}"></div>
                        <div class="kit-field">
                            <label for="industry_id">Industry</label>
                            <select id="industry_id" name="industry_id">
                                <option value="">Select industry</option>
                                @foreach ($industries as $industry)
                                    <option value="{{ $industry->id }}" @selected(old('industry_id') == $industry->id)>{{ $industry->industry_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="kit-field"><label for="phone">Phone</label><input id="phone" type="text" name="phone" value="{{ old('phone') }}"></div>
                        <div class="kit-field"><label for="address_line1">Address</label><input id="address_line1" type="text" name="address_line1" value="{{ old('address_line1') }}"></div>
                        <div class="kit-field"><label for="city">City</label><input id="city" type="text" name="city" value="{{ old('city') }}"></div>
                        <div class="kit-field"><label for="province">Province</label><input id="province" type="text" name="province" value="{{ old('province') }}"></div>
                    </div>
                </div>

                <div class="kit-action-row">
                    <button class="kit-button primary" type="submit">Register</button>
                    <a class="kit-button secondary" href="{{ route('login') }}">Already have an account?</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
