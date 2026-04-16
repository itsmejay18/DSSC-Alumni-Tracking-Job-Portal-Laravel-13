<?php

namespace Database\Seeders;

use App\Models\College;
use Illuminate\Database\Seeder;

class CollegeSeeder extends Seeder
{
    public function run(): void
    {
        $colleges = [
            ['college_name' => 'College of Computer Studies', 'college_code' => 'CCS', 'dean_name' => 'Dr. Ana Reyes', 'dean_email' => 'ccs@dssc.edu.ph'],
            ['college_name' => 'College of Business and Management', 'college_code' => 'CBM', 'dean_name' => 'Dr. Mario Santos', 'dean_email' => 'cbm@dssc.edu.ph'],
            ['college_name' => 'College of Education', 'college_code' => 'COE', 'dean_name' => 'Dr. Liza Ramos', 'dean_email' => 'coe@dssc.edu.ph'],
            ['college_name' => 'College of Agriculture', 'college_code' => 'COA', 'dean_name' => 'Dr. Ben Tupaz', 'dean_email' => 'coa@dssc.edu.ph'],
        ];

        foreach ($colleges as $college) {
            College::query()->updateOrCreate(['college_code' => $college['college_code']], $college);
        }
    }
}
