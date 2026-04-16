<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AlumniController as AdminAlumniController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EmployerController as AdminEmployerController;
use App\Http\Controllers\Admin\JobController as AdminJobController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Alumni\ApplicationController as AlumniApplicationController;
use App\Http\Controllers\Alumni\DashboardController as AlumniDashboardController;
use App\Http\Controllers\Alumni\JobController as AlumniJobController;
use App\Http\Controllers\Alumni\MatchController as AlumniMatchController;
use App\Http\Controllers\Alumni\ProfileController as AlumniProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Employer\ApplicationController as EmployerApplicationController;
use App\Http\Controllers\Employer\DashboardController as EmployerDashboardController;
use App\Http\Controllers\Employer\JobController as EmployerJobController;
use App\Http\Controllers\Employer\ProfileController as EmployerProfileController;
use App\Models\Employer;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'featuredJobs' => Job::query()
            ->with(['employer', 'jobCategory'])
            ->where('status', 'approved')
            ->where('is_featured', true)
            ->latest()
            ->take(6)
            ->get(),
        'jobCategories' => JobCategory::query()->where('is_active', true)->orderBy('category_name')->get(),
        'approvedEmployersCount' => Employer::query()->where('is_verified', true)->count(),
        'alumniCount' => User::query()->where('role', 'alumni')->count(),
    ]);
})->name('landing');

Route::get('/health', function () {
    try {
        DB::connection()->getPdo();
        $database = 'ok';
    } catch (\Throwable $exception) {
        $database = 'error';
    }

    return response()->json([
        'name' => config('app.name'),
        'status' => $database === 'ok' ? 'ok' : 'degraded',
        'timestamp' => now()->toIso8601String(),
        'checks' => [
            'database' => $database,
            'cache_store' => get_class(Cache::getStore()),
            'queue_driver' => config('queue.default'),
            'storage_link' => is_link(public_path('storage')),
        ],
    ], $database === 'ok' ? 200 : 503);
})->name('health');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:10,1');

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('throttle:10,1');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        event(new Verified($request->user()));

        return redirect()->intended(route($request->user()->role.'.dashboard'));
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent successfully.');
    })->middleware('throttle:6,1')->name('verification.send');

    Route::post('/notifications/{notification}/read', function (Notification $notification) {
        abort_unless($notification->user_id === auth()->id(), 403);

        $notification->update(['is_read' => true]);

        return back()->with('success', 'Notification marked as read.');
    })->name('notifications.read');
});

Route::middleware(['auth', 'admin', 'check.status'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [AdminDashboardController::class, 'data'])->name('dashboard.data');

    Route::get('/alumni', [AdminAlumniController::class, 'index'])->name('alumni.index');
    Route::get('/alumni/{alumni}', [AdminAlumniController::class, 'show'])->name('alumni.show');
    Route::get('/alumni/{alumni}/edit', [AdminAlumniController::class, 'edit'])->name('alumni.edit');
    Route::put('/alumni/{alumni}', [AdminAlumniController::class, 'update'])->name('alumni.update');
    Route::get('/alumni/{alumni}/verify', [AdminAlumniController::class, 'verify'])->name('alumni.verify');
    Route::post('/alumni/{alumni}/verify', [AdminAlumniController::class, 'storeVerification'])->name('alumni.verify.store');

    Route::get('/employers', [AdminEmployerController::class, 'index'])->name('employers.index');
    Route::get('/employers/{employer}', [AdminEmployerController::class, 'show'])->name('employers.show');
    Route::post('/employers/{employer}/approve', [AdminEmployerController::class, 'approve'])->name('employers.approve');
    Route::post('/employers/{employer}/reject', [AdminEmployerController::class, 'reject'])->name('employers.reject');

    Route::get('/jobs', [AdminJobController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/{job}', [AdminJobController::class, 'show'])->name('jobs.show');
    Route::post('/jobs/{job}/approve', [AdminJobController::class, 'approve'])->name('jobs.approve');
    Route::post('/jobs/{job}/reject', [AdminJobController::class, 'reject'])->name('jobs.reject');
    Route::delete('/jobs/{job}', [AdminJobController::class, 'destroy'])->name('jobs.destroy');

    Route::get('/reports/export', [AdminReportController::class, 'exportForm'])->name('reports.export-form');
    Route::post('/reports/generate', [AdminReportController::class, 'generate'])->name('reports.generate');
    Route::get('/reports/employment', [AdminReportController::class, 'employment'])->name('reports.employment');
    Route::get('/reports/trends', [AdminReportController::class, 'trends'])->name('reports.trends');
    Route::get('/reports/{report}/download', [AdminReportController::class, 'download'])->name('reports.download');

    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');

    Route::get('/logs/activity', [ActivityLogController::class, 'index'])->name('logs.activity');
});

Route::middleware(['auth', 'alumni', 'verified', 'check.status'])->prefix('alumni')->name('alumni.')->group(function (): void {
    Route::get('/dashboard', [AlumniDashboardController::class, 'index'])->name('dashboard');
    Route::get('/notifications', [AlumniDashboardController::class, 'notifications'])->name('notifications.index');

    Route::get('/profile', [AlumniProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [AlumniProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [AlumniProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/employment-history', [AlumniProfileController::class, 'employmentHistory'])->name('profile.employment-history');
    Route::get('/profile/export', [AlumniProfileController::class, 'exportData'])->name('profile.export');
    Route::delete('/profile', [AlumniProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/jobs', [AlumniJobController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/matched', [AlumniJobController::class, 'matched'])->name('jobs.matched');
    Route::get('/jobs/{job}', [AlumniJobController::class, 'show'])->name('jobs.show');

    Route::get('/applications', [AlumniApplicationController::class, 'index'])->name('applications.index');
    Route::post('/jobs/{job}/apply', [AlumniApplicationController::class, 'store'])->middleware('throttle:10,1')->name('jobs.apply');

    Route::get('/matches', [AlumniMatchController::class, 'index'])->name('matches.index');
    Route::post('/matches/{match}/viewed', [AlumniMatchController::class, 'markViewed'])->name('matches.viewed');
});

Route::middleware(['auth', 'employer', 'check.status'])->prefix('employer')->name('employer.')->group(function (): void {
    Route::get('/dashboard', [EmployerDashboardController::class, 'index'])->name('dashboard');

    Route::get('/jobs', [EmployerJobController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/create', [EmployerJobController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [EmployerJobController::class, 'store'])->name('jobs.store');
    Route::get('/jobs/{job}', [EmployerJobController::class, 'show'])->name('jobs.show');
    Route::get('/jobs/{job}/edit', [EmployerJobController::class, 'edit'])->name('jobs.edit');
    Route::put('/jobs/{job}', [EmployerJobController::class, 'update'])->name('jobs.update');
    Route::delete('/jobs/{job}', [EmployerJobController::class, 'destroy'])->name('jobs.destroy');
    Route::post('/jobs/{job}/close', [EmployerJobController::class, 'close'])->name('jobs.close');

    Route::get('/applications', [EmployerApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [EmployerApplicationController::class, 'show'])->name('applications.show');
    Route::put('/applications/{application}', [EmployerApplicationController::class, 'update'])->name('applications.update');

    Route::get('/profile/edit', [EmployerProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [EmployerProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/verification', [EmployerProfileController::class, 'verification'])->name('profile.verification');
});
