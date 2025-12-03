<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrudDepartmentsTest extends TestCase
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

    public function test_index_lists_departments(): void
    {
        Department::factory()->count(3)->create();
        $this->actingAs($this->viewer())
            ->get('/departments')
            ->assertStatus(200)
            ->assertSee('Departments');
    }

    public function test_admin_manager_can_view_create_form(): void
    {
        $this->actingAs($this->admin())->get('/departments/create')->assertStatus(200);
        $this->actingAs($this->manager())->get('/departments/create')->assertStatus(200);
    }

    public function test_viewer_cannot_view_create_form(): void
    {
        $this->actingAs($this->viewer())->get('/departments/create')->assertRedirect('/departments')->assertSessionHas('error');
    }

    public function test_store_creates_department(): void
    {
        $this->actingAs($this->admin())
            ->post('/departments', [
                'name' => 'Accounting',
                'description' => 'Desc',
            ])
            ->assertRedirect('/departments')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('departments', ['name' => 'Accounting']);
    }

    public function test_viewer_cannot_store_department(): void
    {
        $this->actingAs($this->viewer())
            ->post('/departments', [
                'name' => 'Blocked',
            ])
            ->assertRedirect('/departments')
            ->assertSessionHas('error');
    }

    public function test_show_department(): void
    {
        $dept = Department::factory()->create();
        $this->actingAs($this->viewer())
            ->get('/departments/'.$dept->id)
            ->assertStatus(200)
            ->assertSee($dept->name);
    }

    public function test_edit_update_department(): void
    {
        $dept = Department::factory()->create(['name' => 'Old']);
        $this->actingAs($this->manager())
            ->get('/departments/'.$dept->id.'/edit')->assertStatus(200);

        $this->actingAs($this->manager())
            ->put('/departments/'.$dept->id, [
                'name' => 'New',
                'description' => 'Updated',
            ])
            ->assertRedirect('/departments')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('departments', ['id' => $dept->id, 'name' => 'New']);
    }

    public function test_destroy_soft_deletes_department(): void
    {
        $dept = Department::factory()->create();
        $this->actingAs($this->manager())
            ->delete('/departments/'.$dept->id)
            ->assertRedirect('/departments')
            ->assertSessionHas('success');
        $this->assertSoftDeleted('departments', ['id' => $dept->id]);
    }

    public function test_trash_restore_force_delete_department(): void
    {
        $dept = Department::factory()->create();
        $this->actingAs($this->manager())->delete('/departments/'.$dept->id);

        // Trash list
        $this->actingAs($this->manager())
            ->get('/trash/departments')
            ->assertStatus(200)
            ->assertSee($dept->name);

        // Restore (admin only)
        $this->actingAs($this->admin())
            ->post('/trash/departments/'.$dept->id.'/restore')
            ->assertRedirect('/trash/departments')
            ->assertSessionHas('success');

        // Soft delete again to test force delete
        $this->actingAs($this->manager())->delete('/departments/'.$dept->id);

        $this->actingAs($this->admin())
            ->delete('/trash/departments/'.$dept->id.'/force-delete')
            ->assertRedirect('/trash/departments')
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('departments', ['id' => $dept->id]);
    }
}
