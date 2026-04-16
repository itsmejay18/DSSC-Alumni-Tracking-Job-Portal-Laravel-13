<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GenerateReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'report_type' => ['required', Rule::in(['employment_rate', 'job_trends', 'alumni_distribution', 'employer_activity'])],
            'format' => ['required', Rule::in(['pdf', 'excel', 'json'])],
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'course_id' => ['nullable', 'exists:courses,id'],
            'job_category_id' => ['nullable', 'exists:job_categories,id'],
        ];
    }
}
