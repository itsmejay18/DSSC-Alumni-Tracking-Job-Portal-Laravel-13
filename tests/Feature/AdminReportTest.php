<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_generate_a_json_report(): void
    {
        $admin = User::factory()->admin()->create(['email_verified_at' => now()]);

        $response = $this->actingAs($admin)->post(route('admin.reports.generate'), [
            'report_type' => 'employment_rate',
            'format' => 'json',
            'year' => now()->year,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reports', [
            'report_type' => 'employment_rate',
            'generated_by' => $admin->id,
            'format' => 'json',
        ]);
    }
}
