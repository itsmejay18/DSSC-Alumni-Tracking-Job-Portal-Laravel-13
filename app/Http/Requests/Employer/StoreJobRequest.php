<?php

namespace App\Http\Requests\Employer;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'employer';
    }

    public function rules(): array
    {
        return [
            'job_category_id' => ['nullable', 'exists:job_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => [
                'required',
                'string',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (mb_strlen(trim(strip_tags((string) $value))) < 50) {
                        $fail('The job description must contain at least 50 characters of readable content.');
                    }
                },
            ],
            'requirements' => ['nullable', 'string'],
            'responsibilities' => ['nullable', 'string'],
            'qualifications' => ['nullable', 'string'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0', 'gte:salary_min'],
            'salary_type' => ['required', Rule::in(['monthly', 'yearly', 'hourly', 'project'])],
            'location' => ['nullable', 'string', 'max:255'],
            'is_remote' => ['nullable', 'boolean'],
            'job_type' => ['required', Rule::in(['full-time', 'part-time', 'contract', 'freelance', 'internship'])],
            'experience_level' => ['required', Rule::in(['entry', 'junior', 'senior', 'lead'])],
            'education_requirement' => ['nullable', 'string', 'max:255'],
            'skills_required' => ['nullable', 'array'],
            'skills_required.*' => ['nullable', 'string', 'max:100'],
            'application_deadline' => ['nullable', 'date', 'after_or_equal:today'],
            'max_applicants' => ['nullable', 'integer', 'min:1'],
            'is_featured' => ['nullable', 'boolean'],
        ];
    }
}
