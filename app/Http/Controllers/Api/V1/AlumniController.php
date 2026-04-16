<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Alumni\UpdateProfileRequest;
use App\Http\Resources\AlumniResource;

class AlumniController extends Controller
{
    public function show(): AlumniResource
    {
        return new AlumniResource(auth()->user()->load('alumniProfile.course'));
    }

    public function update(UpdateProfileRequest $request): AlumniResource
    {
        $user = $request->user();
        $user->update([
            'name' => $request->string('name'),
            'email' => $request->string('email'),
        ]);

        $user->alumniProfile?->update([
            'student_id' => $request->string('student_id'),
            'first_name' => $request->string('first_name'),
            'last_name' => $request->string('last_name'),
            'middle_name' => $request->string('middle_name'),
            'course_id' => $request->input('course_id'),
            'year_graduated' => $request->input('year_graduated'),
            'skills' => collect($request->input('skills', []))
                ->flatMap(fn ($value) => explode(',', (string) $value))
                ->map(fn ($value) => trim($value))
                ->filter()
                ->values()
                ->all(),
            'employment_status' => $request->string('employment_status'),
            'current_job_title' => $request->string('current_job_title'),
            'current_company' => $request->string('current_company'),
            'current_salary' => $request->input('current_salary'),
            'linkedin_url' => $request->string('linkedin_url'),
            'portfolio_url' => $request->string('portfolio_url'),
        ]);

        return new AlumniResource($user->fresh()->load('alumniProfile.course'));
    }
}
