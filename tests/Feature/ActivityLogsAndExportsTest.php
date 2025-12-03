<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogsAndExportsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_activity_log_created_on_department_update(): void
    {
        $dept = Department::factory()->create(['name' => 'Old']);
        $user = User::factory()->create(['role' => 'manager']);
        $this->actingAs($user)->put('/departments/'.$dept->id, ['name' => 'New', 'description' => 'Updated'])
            ->assertRedirect('/departments');

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'updated',
            'model_type' => 'Department',
            'model_id' => $dept->id,
        ]);
    }

    public function test_employees_export_csv_headers(): void
    {
        $user = User::factory()->create(['role' => 'viewer']);
        $this->actingAs($user)
            ->get('/employees/export/csv')
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=utf-8')
            ->assertHeader('content-disposition');
    }

    public function test_departments_export_csv_headers(): void
    {
        $user = User::factory()->create(['role' => 'viewer']);
        $this->actingAs($user)
            ->get('/departments/export/csv')
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=utf-8')
            ->assertHeader('content-disposition');
    }
}
