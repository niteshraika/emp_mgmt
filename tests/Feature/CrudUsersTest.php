<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrudUsersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    private function admin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    private function manager(): User
    {
        return User::factory()->create([
            'role' => 'manager',
        ]);
    }

    private function viewer(): User
    {
        return User::factory()->create([
            'role' => 'viewer',
        ]);
    }

    public function test_admin_can_list_users(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)
            ->get('/users')
            ->assertStatus(200)
            ->assertSee('All Users');
    }

    public function test_non_admin_cannot_list_users(): void
    {
        $this->actingAs($this->manager())
            ->get('/users')
            ->assertRedirect('/dashboard')
            ->assertSessionHas('error');

        $this->actingAs($this->viewer())
            ->get('/users')
            ->assertRedirect('/dashboard')
            ->assertSessionHas('error');
    }

    public function test_guest_redirected_for_users_index(): void
    {
        $this->get('/users')->assertRedirect('/login');
    }

    public function test_admin_can_view_create_user_form(): void
    {
        $this->actingAs($this->admin())
            ->get('/users/create')
            ->assertStatus(200)
            ->assertSee('Create User');
    }

    public function test_non_admin_cannot_view_create_user_form(): void
    {
        $this->actingAs($this->manager())
            ->get('/users/create')
            ->assertRedirect('/users')
            ->assertSessionHas('error');
    }

    public function test_admin_can_create_user(): void
    {
        $admin = $this->admin();
        $payload = [
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'Password@123',
            'role' => 'viewer',
        ];
        $this->actingAs($admin)
            ->post('/users', $payload)
            ->assertRedirect('/users')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'new@example.com',
            'role' => 'viewer',
        ]);
        $this->assertNotEquals('Password@123', User::where('email', 'new@example.com')->first()->password);
    }

    public function test_duplicate_email_rejected(): void
    {
        $admin = $this->admin();
        User::factory()->create(['email' => 'dup@example.com']);

        $this->actingAs($admin)
            ->post('/users', [
                'name' => 'Dup',
                'email' => 'dup@example.com',
                'password' => 'Password@123',
                'role' => 'viewer',
            ])
            ->assertSessionHasErrors('email');
    }

    public function test_invalid_role_rejected(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)
            ->post('/users', [
                'name' => 'Bad Role',
                'email' => 'badrole@example.com',
                'password' => 'Password@123',
                'role' => 'invalid',
            ])
            ->assertSessionHasErrors('role');
    }
}
