<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlumniResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'profile' => [
                'student_id' => $this->alumniProfile?->student_id,
                'first_name' => $this->alumniProfile?->first_name,
                'last_name' => $this->alumniProfile?->last_name,
                'course' => $this->alumniProfile?->course?->course_name,
                'year_graduated' => $this->alumniProfile?->year_graduated,
                'employment_status' => $this->alumniProfile?->employment_status,
                'skills' => $this->alumniProfile?->skills ?? [],
            ],
        ];
    }
}
