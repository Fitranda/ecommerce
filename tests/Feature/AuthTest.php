<?php

namespace Tests\Feature;

use App\Models\Tenant\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TenantTestCase;

class AuthTest extends TenantTestCase
{
    public function test_login_page_loads(): void
    {
        $this->get('/login')
            ->assertStatus(200);
    }

    public function test_register_page_loads(): void
    {
        $this->get('/register')
            ->assertStatus(200);
    }

    public function test_user_can_register(): void
    {
        $this->post('/register', [
            'name' => 'New User',
            'email' => 'newuser@test.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect('/');

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@test.test',
            'role' => 'customer',
        ]);

        $this->assertAuthenticated();
    }

    public function test_user_can_login(): void
    {
        User::create([
            'name' => 'Test',
            'email' => 'login@test.test',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        $this->post('/login', [
            'email' => 'login@test.test',
            'password' => 'password',
        ])->assertRedirect('/');

        $this->assertAuthenticated();
    }

    public function test_invalid_login_fails(): void
    {
        $this->post('/login', [
            'email' => 'no@exist.test',
            'password' => 'wrong',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::create([
            'name' => 'Test',
            'email' => 'logout@test.test',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect('/');

        $this->assertGuest();
    }

    public function test_admin_redirected_to_dashboard_on_login(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@test.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->post('/login', [
            'email' => 'admin@test.test',
            'password' => 'password',
        ])->assertRedirect('/admin/dashboard');
    }
}
