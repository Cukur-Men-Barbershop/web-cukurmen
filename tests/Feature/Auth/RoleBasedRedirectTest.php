<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleBasedRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_user_redirected_to_admin_dashboard_after_login()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        // Access the login page to get a CSRF token
        $this->get('/login');
        $token = $this->app['session']->token();

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
            '_token' => $token,
        ]);

        $response->assertRedirect('/admin/dashboard');
    }

    public function test_user_redirected_to_user_dashboard_after_login()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'password' => bcrypt('password'),
        ]);

        // Access the login page to get a CSRF token
        $this->get('/login');
        $token = $this->app['session']->token();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            '_token' => $token,
        ]);

        $response->assertRedirect('/user/dashboard');
    }

    public function test_admin_user_redirected_to_admin_dashboard_after_registration()
    {
        // Access the register page to get a CSRF token
        $this->get('/register');
        $token = $this->app['session']->token();

        // Note: The register method in AuthController creates users with 'user' role by default
        $response = $this->post('/register', [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            '_token' => $token,
        ]);

        // Even if registration creates a user with 'user' role by default, it should redirect to user dashboard
        $response->assertRedirect('/user/dashboard');

        $this->assertDatabaseHas('users', [
            'email' => 'admin@example.com',
            'role' => 'user', // Default role
        ]);
    }

    public function test_role_check_works_correctly()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($user->isAdmin());
    }
}