<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    public function run(): void
    {
        $industries = [
            ['industry_name' => 'Information Technology', 'industry_code' => 'IT'],
            ['industry_name' => 'Healthcare', 'industry_code' => 'HEALTH'],
            ['industry_name' => 'Finance', 'industry_code' => 'FIN'],
            ['industry_name' => 'Education', 'industry_code' => 'EDU'],
            ['industry_name' => 'Agriculture', 'industry_code' => 'AGR'],
            ['industry_name' => 'Business Services', 'industry_code' => 'BIZ'],
        ];

        foreach ($industries as $industry) {
            Industry::query()->updateOrCreate(['industry_code' => $industry['industry_code']], $industry + ['is_active' => true]);
        }
    }
}
