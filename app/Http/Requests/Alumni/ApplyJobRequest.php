<?php

namespace App\Http\Requests\Alumni;

use Illuminate\Foundation\Http\FormRequest;

class ApplyJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'alumni';
    }

    public function rules(): array
    {
        return [
            'cover_letter' => ['required', 'string', 'min:50'],
            'resume' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'portfolio_link' => ['nullable', 'url', 'max:255'],
            'availability_date' => ['nullable', 'date'],
            'expected_salary' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
