<?php

namespace App\Http\Requests\Employer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['admin', 'employer'], true);
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(['pending', 'shortlisted', 'interviewed', 'accepted', 'rejected', 'hired'])],
            'status_notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
