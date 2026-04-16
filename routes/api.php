<?php

use App\Http\Controllers\Api\V1\AlumniController;
use App\Http\Controllers\Api\V1\JobController;
use App\Http\Controllers\Api\V1\StatisticsController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

Route::prefix('v1')->name('api.v1.')->group(function (): void {
    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()->where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'token' => $user->createToken('mobile')->plainTextToken,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    })->middleware('throttle:10,1')->name('login');

    Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');
    Route::get('/statistics/employment', [StatisticsController::class, 'employment'])->name('statistics.employment');

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::post('/logout', function (Request $request) {
            $request->user()->currentAccessToken()?->delete();

            return response()->json(['message' => 'Logged out successfully.']);
        })->name('logout');

        Route::post('/jobs/{job}/apply', [JobController::class, 'apply'])->middleware('throttle:10,1')->name('jobs.apply');
        Route::get('/alumni/profile', [AlumniController::class, 'show'])->name('alumni.profile.show');
        Route::put('/alumni/profile', [AlumniController::class, 'update'])->name('alumni.profile.update');
    });
});
