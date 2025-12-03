<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndRbacTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_login_page_loads(): void
    {
        $this->get('/login')->assertStatus(200)->assertSee('Login');
    }

    public function test_authentication_success_and_redirect(): void
    {
        $user = User::factory()->create(['password' => 'Password@123']);
        $this->post('/login', ['email' => $user->email, 'password' => 'Password@123'])
            ->assertRedirect('/dashboard');
    }

    public function test_authentication_failure_shows_error(): void
    {
        $user = User::factory()->create(['password' => 'Password@123']);
        $this->from('/login')->post('/login', ['email' => $user->email, 'password' => 'wrong'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors('email');
    }

    public function test_users_menu_visible_only_for_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $viewer = User::factory()->create(['role' => 'viewer']);

        $this->actingAs($admin)->get('/dashboard')->assertSee('Users');
        $this->actingAs($viewer)->get('/dashboard')->assertDontSee('Users');
    }

    public function test_profile_page_accessible_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/profile')->assertStatus(200)->assertSee('Update Profile');
    }
}
