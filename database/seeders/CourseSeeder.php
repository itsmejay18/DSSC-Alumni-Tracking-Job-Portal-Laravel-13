<?php

namespace Database\Seeders;

use App\Models\College;
use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            ['college' => 'CCS', 'course_code' => 'BSIT', 'course_name' => 'Bachelor of Science in Information Technology'],
            ['college' => 'CCS', 'course_code' => 'BSCS', 'course_name' => 'Bachelor of Science in Computer Science'],
            ['college' => 'CCS', 'course_code' => 'BSIS', 'course_name' => 'Bachelor of Science in Information Systems'],
            ['college' => 'CBM', 'course_code' => 'BSBA', 'course_name' => 'Bachelor of Science in Business Administration'],
            ['college' => 'CBM', 'course_code' => 'BSA', 'course_name' => 'Bachelor of Science in Accountancy'],
            ['college' => 'COE', 'course_code' => 'BSED', 'course_name' => 'Bachelor of Secondary Education'],
            ['college' => 'COE', 'course_code' => 'BEED', 'course_name' => 'Bachelor of Elementary Education'],
            ['college' => 'COA', 'course_code' => 'BSAg', 'course_name' => 'Bachelor of Science in Agriculture'],
        ];

        foreach ($courses as $course) {
            $college = College::query()->where('college_code', $course['college'])->first();

            Course::query()->updateOrCreate(
                ['course_code' => $course['course_code']],
                [
                    'college_id' => $college?->id,
                    'course_name' => $course['course_name'],
                    'duration' => 4,
                    'is_active' => true,
                ]
            );
        }
    }
}
