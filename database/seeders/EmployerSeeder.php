<?php

namespace Database\Seeders;

use App\Models\Employer;
use App\Models\Industry;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployerSeeder extends Seeder
{
    public function run(): void
    {
        $industries = Industry::query()->orderBy('id')->get();
        $adminId = User::query()->admins()->value('id');

        if ($industries->isEmpty() || ! $adminId) {
            $this->command?->warn('EmployerSeeder skipped because industries or an administrator are missing.');

            return;
        }

        $faker = fake('en_PH');
        $faker->seed(20260417);
        $password = Hash::make('password');
        $companyNames = [
            'Davao Digital Solutions',
            'Mindanao Agritech Cooperative',
            'Southline Accounting Services',
            'Digos Learning Center',
            'PrimeCare Community Services',
            'GreenFields Food Processing',
            'Pacific Data Systems',
            'DSSC Business Solutions',
            'Mount Apo Geospatial Services',
            'Southern Network Hub',
            'Kapatagan Farm Technologies',
            'Davao Creative Studio',
            'Matanao Logistics Services',
            'Padada Retail Group',
            'Bansalan Engineering Works',
            'Sulop Customer Care Center',
            'Magsaysay Training Institute',
            'Hagonoy Business Process Services',
            'Santa Cruz Renewable Resources',
            'Malalag Community Enterprise',
        ];

        foreach ($companyNames as $offset => $companyName) {
            $index = $offset + 1;
            $isVerified = $index % 5 !== 0;
            $createdAt = now()->subMonths(($index - 1) % 12)->subDays($index);

            $user = User::withTrashed()->updateOrCreate(
                ['email' => "employer{$index}@example.com"],
                [
                    'name' => "{$companyName} Hiring Team",
                    'role' => 'employer',
                    'is_active' => true,
                    'is_approved' => $isVerified,
                    'email_verified_at' => $createdAt->copy()->addDay(),
                    'last_login_at' => now()->subDays($index % 20),
                    'last_login_ip' => '192.168.20.'.(($index % 200) + 1),
                    'password' => $password,
                ]
            );

            if ($user->trashed()) {
                $user->restore();
            }

            $user->forceFill([
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addDays($index % 8),
            ])->saveQuietly();

            $employer = Employer::withTrashed()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'company_name' => $companyName,
                    'company_registration_number' => 'DTI-DEMO-'.str_pad((string) $index, 5, '0', STR_PAD_LEFT),
                    'company_logo_path' => null,
                    'industry_id' => $industries[$offset % $industries->count()]->id,
                    'company_size' => ['1-10', '11-50', '51-200', '201-500', '500+'][$offset % 5],
                    'website' => "https://partner{$index}.example.com",
                    'phone' => '09'.str_pad((string) (180000000 + $index), 9, '0', STR_PAD_LEFT),
                    'address_line1' => $faker->streetAddress(),
                    'address_line2' => $index % 3 === 0 ? 'Second Floor' : null,
                    'city' => ['Digos City', 'Bansalan', 'Matanao', 'Santa Cruz', 'Sulop'][$offset % 5],
                    'province' => 'Davao del Sur',
                    'country' => 'Philippines',
                    'postal_code' => (string) (8000 + ($index % 10)),
                    'verification_documents' => ["permits/demo-employer-{$index}.pdf"],
                    'is_verified' => $isVerified,
                    'verification_date' => $isVerified ? $createdAt->copy()->addDays(4) : null,
                    'verified_by' => $isVerified ? $adminId : null,
                ]
            );

            if ($employer->trashed()) {
                $employer->restore();
            }

            $employer->forceFill([
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addDays($index % 8),
            ])->saveQuietly();
        }
    }
}
