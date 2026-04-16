<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'requirements' => $this->requirements,
            'qualifications' => $this->qualifications,
            'salary_range' => $this->salary_range,
            'location' => $this->location,
            'is_remote' => $this->is_remote,
            'job_type' => $this->job_type,
            'experience_level' => $this->experience_level,
            'application_deadline' => optional($this->application_deadline)->toDateString(),
            'skills_required' => $this->skills_required ?? [],
            'employer' => [
                'company_name' => $this->employer?->company_name,
                'website' => $this->employer?->website,
            ],
            'category' => $this->jobCategory?->category_name,
        ];
    }
}
