<?php

namespace App\Http\Controllers\Auth;

use App\Events\EmployerRegistered;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\AlumniProfile;
use App\Models\Course;
use App\Models\Employer;
use App\Models\Industry;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    public function __construct(protected NotificationService $notificationService)
    {
    }

    public function create()
    {
        return view('auth.register', [
            'courses' => Course::query()->where('is_active', true)->orderBy('course_name')->get(),
            'industries' => Industry::query()->where('is_active', true)->orderBy('industry_name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'role' => ['required', Rule::in(['alumni', 'employer'])],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'student_id' => ['required_if:role,alumni', 'nullable', 'string', 'max:50', 'unique:alumni_profiles,student_id'],
            'first_name' => ['required_if:role,alumni', 'nullable', 'string', 'max:100'],
            'last_name' => ['required_if:role,alumni', 'nullable', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'course_id' => ['required_if:role,alumni', 'nullable', 'exists:courses,id'],
            'year_graduated' => ['nullable', 'integer', 'min:1990', 'max:2100'],
            'employment_status' => ['nullable', Rule::in(['employed', 'unemployed', 'self-employed', 'further_study', 'not_looking'])],
            'company_name' => ['required_if:role,employer', 'nullable', 'string', 'max:255', 'unique:employers,company_name'],
            'industry_id' => ['required_if:role,employer', 'nullable', 'exists:industries,id'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address_line1' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
        ]);

        $user = DB::transaction(function () use ($request) {
            $user = User::query()->create([
                'name' => $request->string('name'),
                'email' => $request->string('email'),
                'password' => Hash::make($request->string('password')),
                'role' => $request->string('role'),
                'is_active' => true,
                'is_approved' => $request->role === 'alumni',
            ]);

            if ($request->role === 'alumni') {
                AlumniProfile::query()->create([
                    'user_id' => $user->id,
                    'student_id' => $request->string('student_id'),
                    'first_name' => $request->string('first_name'),
                    'last_name' => $request->string('last_name'),
                    'middle_name' => $request->string('middle_name'),
                    'course_id' => $request->integer('course_id'),
                    'year_graduated' => $request->integer('year_graduated') ?: null,
                    'employment_status' => $request->input('employment_status', 'unemployed'),
                    'skills' => [],
                ]);
            }

            if ($request->role === 'employer') {
                $employer = Employer::query()->create([
                    'user_id' => $user->id,
                    'company_name' => $request->string('company_name'),
                    'industry_id' => $request->integer('industry_id'),
                    'phone' => $request->string('phone'),
                    'address_line1' => $request->string('address_line1'),
                    'city' => $request->string('city'),
                    'province' => $request->string('province'),
                    'country' => 'Philippines',
                ]);

                event(new EmployerRegistered($employer));
            }

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        ActivityLog::record($user, 'create', 'user', 'Registered a new account.', [], ['role' => $user->role]);

        if ($user->isAlumni()) {
            $this->notificationService->sendWelcome($user);
            $user->sendEmailVerificationNotification();

            return redirect()->route('verification.notice')->with('success', 'Registration successful. Please verify your email.');
        }

        return redirect()->route('employer.profile.verification')->with('success', 'Registration submitted. Wait for admin approval.');
    }
}
