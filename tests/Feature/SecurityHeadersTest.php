<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_security_headers_present_on_authenticated_pages(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get('/dashboard')
            ->assertHeader('x-frame-options')
            ->assertHeader('x-content-type-options')
            ->assertHeader('referrer-policy');
    }
}
