@extends('layouts.guest')

@section('title', 'Register')
@section('body-class', 'portal-auth-page')

@section('guest-content')
@php
    $profileFields = [
        'student_id',
        'first_name',
        'last_name',
        'middle_name',
        'course_id',
        'year_graduated',
        'company_name',
        'industry_id',
        'phone',
        'address_line1',
        'city',
        'province',
    ];

    $startRegisterStep = $errors->hasAny($profileFields)
        || collect($profileFields)->contains(fn (string $field): bool => filled(old($field)))
        ? 2
        : 1;
@endphp
<section class="portal-auth-scene portal-auth-scene-register">
    @include('auth.partials.panel', [
        'title' => 'Build Your DSSC Network',
        'description' => 'Create an alumni or employer account to join graduate tracing, post opportunities, and strengthen school-industry connections.',
    ])

    <div class="portal-auth-form-pane">
        <div class="portal-auth-form-wrap portal-auth-form-wrap-register">
            <a class="portal-auth-home-link" href="{{ route('landing') }}">
                <i class="ph ph-arrow-left"></i>
                <span>Back to home</span>
            </a>

            <div class="portal-auth-heading">
                <span class="portal-auth-eyebrow">Portal Registration</span>
                <h2>Create Account</h2>
                <p>Choose your account type and complete the required details to access the alumni tracking and job portal.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="portal-auth-form portal-register-form" data-register-stepper data-register-start-step="{{ $startRegisterStep }}">
                @csrf

                <div class="portal-register-steps" aria-label="Registration progress">
                    <div class="portal-register-step" data-register-step-indicator="1">
                        <span>1</span>
                        <strong>Account Setup</strong>
                    </div>
                    <div class="portal-register-step" data-register-step-indicator="2">
                        <span>2</span>
                        <strong>Profile Details</strong>
                    </div>
                </div>

                <section class="portal-register-stage" data-register-step-panel="1" @if ($startRegisterStep !== 1) hidden @endif>
                    <div class="portal-auth-field-grid portal-auth-field-grid-2">
                        <div class="kit-field portal-auth-field">
                            <label for="role">Register as</label>
                            <select id="role" name="role" data-register-role>
                                <option value="alumni" @selected(old('role', 'alumni') === 'alumni')>Alumni</option>
                                <option value="employer" @selected(old('role', 'alumni') === 'employer')>Employer</option>
                            </select>
                            @error('role')
                                <span class="portal-auth-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="kit-field portal-auth-field">
                            <label for="name">Account name</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Enter the account name" autocomplete="name" required>
                            @error('name')
                                <span class="portal-auth-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="kit-field portal-auth-field">
                            <label for="email">Email address</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" autocomplete="username" required>
                            @error('email')
                                <span class="portal-auth-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="kit-field portal-auth-field">
                            <label for="password">Password</label>
                            <div class="portal-auth-input-wrap">
                                <input id="password" type="password" name="password" placeholder="Create a password" autocomplete="new-password" required>
                                <button class="portal-password-toggle" type="button" data-password-toggle="password" aria-label="Show password" aria-pressed="false">
                                    <i class="ph ph-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="portal-auth-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="kit-field portal-auth-field portal-auth-field-full">
                            <label for="password_confirmation">Confirm password</label>
                            <div class="portal-auth-input-wrap">
                                <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Repeat your password" autocomplete="new-password" required>
                                <button class="portal-password-toggle" type="button" data-password-toggle="password_confirmation" aria-label="Show password confirmation" aria-pressed="false">
                                    <i class="ph ph-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <p class="portal-auth-role-copy" data-role-copy></p>

                    <div class="portal-register-actions">
                        <button class="kit-button primary portal-auth-submit" type="button" data-register-next>Continue</button>
                        <p class="portal-auth-switch">
                            Already have an account?
                            <a href="{{ route('login') }}">Sign in here</a>
                        </p>
                    </div>
                </section>

                <section class="portal-register-stage" data-register-step-panel="2" @if ($startRegisterStep !== 2) hidden @endif>
                    <section class="portal-auth-section" data-role-section="alumni">
                        <div class="portal-auth-section-head">
                            <span class="portal-auth-section-kicker">Alumni Profile</span>
                            <h3>Academic details</h3>
                            <p>We use this information for graduate tracing, profile creation, and smarter job matching.</p>
                        </div>

                        <div class="portal-auth-field-grid portal-auth-field-grid-2">
                            <div class="kit-field portal-auth-field">
                                <label for="student_id">Student ID</label>
                                <input id="student_id" type="text" name="student_id" value="{{ old('student_id') }}" placeholder="Enter your student ID" data-required-for-role="alumni">
                                @error('student_id')
                                    <span class="portal-auth-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="kit-field portal-auth-field">
                                <label for="course_id">Course</label>
                                <select id="course_id" name="course_id" data-required-for-role="alumni">
                                    <option value="">Select course</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>{{ $course->course_name }}</option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <span class="portal-auth-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="kit-field portal-auth-field">
                                <label for="first_name">First name</label>
                                <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Enter your first name" data-required-for-role="alumni">
                                @error('first_name')
                                    <span class="portal-auth-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="kit-field portal-auth-field">
                                <label for="last_name">Last name</label>
                                <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Enter your last name" data-required-for-role="alumni">
                                @error('last_name')
                                    <span class="portal-auth-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="kit-field portal-auth-field">
                                <label for="middle_name">Middle name</label>
                                <input id="middle_name" type="text" name="middle_name" value="{{ old('middle_name') }}" placeholder="Optional">
                                @error('middle_name')
                                    <span class="portal-auth-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="kit-field portal-auth-field">
                                <label for="year_graduated">Year graduated</label>
                                <input id="year_graduated" type="number" name="year_graduated" value="{{ old('year_graduated') }}" placeholder="YYYY" min="1990" max="2100">
                                @error('year_graduated')
                                    <span class="portal-auth-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </section>

                    <section class="portal-auth-section" data-role-section="employer">
                        <div class="portal-auth-section-head">
                            <span class="portal-auth-section-kicker">Employer Profile</span>
                            <h3>Company details</h3>
                            <p>Tell us about your organization so you can post opportunities and connect with DSSC graduates.</p>
                        </div>

                        <div class="portal-auth-field-grid portal-auth-field-grid-2">
                            <div class="kit-field portal-auth-field">
                                <label for="company_name">Company name</label>
                                <input id="company_name" type="text" name="company_name" value="{{ old('company_name') }}" placeholder="Enter your company name" data-required-for-role="employer">
                                @error('company_name')
                                    <span class="portal-auth-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="kit-field portal-auth-field">
                                <label for="industry_id">Industry</label>
                                <select id="industry_id" name="industry_id" data-required-for-role="employer">
                                    <option value="">Select industry</option>
                                    @foreach ($industries as $industry)
                                        <option value="{{ $industry->id }}" @selected(old('industry_id') == $industry->id)>{{ $industry->industry_name }}</option>
                                    @endforeach
                                </select>
                                @error('industry_id')
                                    <span class="portal-auth-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="kit-field portal-auth-field">
                                <label for="phone">Phone</label>
                                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="Enter your contact number">
                                @error('phone')
                                    <span class="portal-auth-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="kit-field portal-auth-field">
                                <label for="address_line1">Address</label>
                                <input id="address_line1" type="text" name="address_line1" value="{{ old('address_line1') }}" placeholder="Street address">
                                @error('address_line1')
                                    <span class="portal-auth-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="kit-field portal-auth-field">
                                <label for="city">City</label>
                                <input id="city" type="text" name="city" value="{{ old('city') }}" placeholder="City or municipality">
                                @error('city')
                                    <span class="portal-auth-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="kit-field portal-auth-field">
                                <label for="province">Province</label>
                                <input id="province" type="text" name="province" value="{{ old('province') }}" placeholder="Province">
                                @error('province')
                                    <span class="portal-auth-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </section>

                    <div class="portal-register-actions">
                        <div class="portal-register-nav">
                            <button class="kit-button secondary portal-register-back" type="button" data-register-prev>Back</button>
                            <button class="kit-button primary portal-auth-submit" type="submit">Create account</button>
                        </div>
                        <p class="portal-auth-switch">
                            Already have an account?
                            <a href="{{ route('login') }}">Sign in here</a>
                        </p>
                    </div>
                </section>
            </form>
        </div>
    </div>
</section>
@endsection
