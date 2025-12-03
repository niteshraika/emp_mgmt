<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrudEmployeesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    private function admin(): User { return User::factory()->create(['role' => 'admin']); }
    private function manager(): User { return User::factory()->create(['role' => 'manager']); }
    private function viewer(): User { return User::factory()->create(['role' => 'viewer']); }

    public function test_index_lists_with_filters(): void
    {
        $deptA = Department::factory()->create(['name' => 'A']);
        $deptB = Department::factory()->create(['name' => 'B']);
        Employee::factory()->create(['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com', 'department_id' => $deptA->id, 'salary' => 1000, 'joining_date' => '2020-01-01']);
        Employee::factory()->create(['first_name' => 'Jane', 'last_name' => 'Roe', 'email' => 'jane@example.com', 'department_id' => $deptB->id, 'salary' => 2000, 'joining_date' => '2021-01-01']);

        $this->actingAs($this->viewer())
            ->get('/employees?department_id='.$deptA->id)
            ->assertStatus(200)
            ->assertSee('John')
            ->assertDontSee('Jane');

        $this->actingAs($this->viewer())
            ->get('/employees?search=jane')
            ->assertStatus(200)
            ->assertSee('Jane');

        $this->actingAs($this->viewer())
            ->get('/employees?salary_min=1500&salary_max=2500')
            ->assertStatus(200)
            ->assertSee('Jane')
            ->assertDontSee('John');
    }

    public function test_create_store_employee(): void
    {
        $dept = Department::factory()->create();
        $payload = [
            'first_name' => 'Sam',
            'last_name' => 'Smith',
            'email' => 'sam@example.com',
            'phone' => '1234567890',
            'department_id' => $dept->id,
            'salary' => 5000,
            'joining_date' => '2020-02-02',
            'address' => 'Street 1',
        ];

        $this->actingAs($this->manager())
            ->from('/employees/create')
            ->post('/employees', $payload)
            ->assertStatus(302)
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('employees', [
            'email' => 'sam@example.com',
            'department_id' => $dept->id,
        ]);
    }

    public function test_update_employee(): void
    {
        $dept = Department::factory()->create();
        $emp = Employee::factory()->create(['department_id' => $dept->id, 'salary' => 3000]);

        $this->actingAs($this->manager())
            ->from('/employees/'.$emp->id.'/edit')
            ->put('/employees/'.$emp->id, [
                'first_name' => $emp->first_name,
                'last_name' => $emp->last_name,
                'email' => $emp->email,
                'phone' => $emp->phone,
                'department_id' => $dept->id,
                'salary' => 3500,
                'joining_date' => $emp->joining_date->format('Y-m-d'),
                'address' => $emp->address,
            ])
            ->assertStatus(302)
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('employees', ['id' => $emp->id, 'salary' => 3500]);
    }

    public function test_destroy_restore_force_delete_employee(): void
    {
        $emp = Employee::factory()->create();
        $this->actingAs($this->manager())
            ->delete('/employees/'.$emp->id)
            ->assertRedirect('/employees')
            ->assertSessionHas('success');
        $this->assertSoftDeleted('employees', ['id' => $emp->id]);

        // restore (admin)
        $this->actingAs($this->admin())
            ->post('/trash/employees/'.$emp->id.'/restore')
            ->assertRedirect('/trash/employees')
            ->assertSessionHas('success');

        // soft delete again to force delete
        $this->actingAs($this->manager())->delete('/employees/'.$emp->id);

        $this->actingAs($this->admin())
            ->delete('/trash/employees/'.$emp->id.'/force-delete')
            ->assertRedirect('/trash/employees')
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('employees', ['id' => $emp->id]);
    }

    public function test_bulk_delete_validation_and_effect(): void
    {
        $emps = Employee::factory()->count(3)->create();
        $ids = $emps->pluck('id')->all();

        $this->actingAs($this->manager())
            ->post('/employees/bulk-delete', ['ids' => $ids])
            ->assertRedirect('/employees')
            ->assertSessionHas('success');

        foreach ($ids as $id) {
            $this->assertSoftDeleted('employees', ['id' => $id]);
        }

        // invalid/empty ids
        $this->actingAs($this->manager())
            ->post('/employees/bulk-delete', ['ids' => []])
            ->assertRedirect('/employees')
            ->assertSessionHas('error');
    }
}
