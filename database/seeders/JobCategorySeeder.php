<?php

namespace Database\Seeders;

use App\Models\JobCategory;
use Illuminate\Database\Seeder;

class JobCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['category_name' => 'Software Development', 'category_code' => 'SOFTDEV', 'description' => 'Web, mobile, and backend engineering roles'],
            ['category_name' => 'Data Science', 'category_code' => 'DATASCI', 'description' => 'Analytics, AI support, and data engineering roles'],
            ['category_name' => 'Networking', 'category_code' => 'NET', 'description' => 'Infrastructure and systems administration'],
            ['category_name' => 'Education', 'category_code' => 'EDU', 'description' => 'Teaching, curriculum, and academic roles'],
            ['category_name' => 'Finance and Accounting', 'category_code' => 'FINACC', 'description' => 'Accounting, payroll, and finance operations'],
            ['category_name' => 'Agribusiness', 'category_code' => 'AGRIBIZ', 'description' => 'Farm operations, agritech, and agribusiness roles'],
        ];

        foreach ($categories as $category) {
            JobCategory::query()->updateOrCreate(['category_code' => $category['category_code']], $category + ['is_active' => true]);
        }
    }
}
