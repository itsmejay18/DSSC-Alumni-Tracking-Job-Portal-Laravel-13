<?php

namespace App\Http\Requests\Alumni;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'alumni';
    }

    public function rules(): array
    {
        $profileId = $this->user()?->alumniProfile?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user()?->id)],
            'student_id' => ['required', 'string', 'max:50', Rule::unique('alumni_profiles', 'student_id')->ignore($profileId)],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:20'],
            'contact_number' => ['nullable', 'string', 'max:30'],
            'course_id' => ['nullable', 'exists:courses,id'],
            'year_graduated' => ['nullable', 'integer', 'min:1990', 'max:2100'],
            'graduation_date' => ['nullable', 'date'],
            'skills' => ['nullable', 'array'],
            'skills.*' => ['nullable', 'string', 'max:100'],
            'employment_status' => ['required', Rule::in(['employed', 'unemployed', 'self-employed', 'further_study', 'not_looking'])],
            'current_job_title' => ['nullable', 'string', 'max:255'],
            'current_company' => ['nullable', 'string', 'max:255'],
            'current_salary' => ['nullable', 'numeric', 'min:0'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
