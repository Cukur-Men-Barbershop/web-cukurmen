<?php

namespace Tests\Unit\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertEquals('user', $user->role);
    }

    public function test_user_password_is_hashed()
    {
        $user = User::factory()->create([
            'password' => 'plaintext_password',
        ]);

        $this->assertNotEquals('plaintext_password', $user->password);
        $this->assertTrue(Hash::check('plaintext_password', $user->password));
    }

    public function test_is_admin_returns_true_for_admin_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->assertTrue($admin->isAdmin());
    }

    public function test_is_admin_returns_false_for_non_admin_user()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->assertFalse($user->isAdmin());
    }

    public function test_user_has_bookings_relationship()
    {
        $user = User::factory()->create();
        
        $this->assertEquals(0, $user->bookings->count());
    }

    public function test_fillable_attributes()
    {
        $user = new User();
        $fillable = $user->getFillable();
        
        $this->assertContains('name', $fillable);
        $this->assertContains('email', $fillable);
        $this->assertContains('password', $fillable);
        $this->assertContains('phone', $fillable);
        $this->assertContains('role', $fillable);
    }

    public function test_hidden_attributes()
    {
        $user = new User();
        $hidden = $user->getHidden();
        
        $this->assertContains('password', $hidden);
        $this->assertContains('remember_token', $hidden);
    }
}