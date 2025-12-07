<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Service;
use App\Models\Barber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_access_dashboard()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $response = $this->get('/user/dashboard');

        $response->assertStatus(302); // Redirects to booking flow
    }

    public function test_user_can_view_booking_flow()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $response = $this->get('/user/booking');

        // The booking route might redirect to service selection
        $response->assertStatus(302);
    }

    public function test_user_can_view_service_selection()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $response = $this->get('/user/booking/service');

        $response->assertStatus(200);
    }

    public function test_user_can_view_barber_selection()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $response = $this->get('/user/booking/barber');

        $response->assertStatus(200);
    }

    public function test_user_can_view_schedule_selection()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $response = $this->get('/user/booking/schedule');

        $response->assertStatus(200);
    }

    public function test_user_can_view_confirmation()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $service = Service::factory()->create();
        $barber = Barber::factory()->create();

        $response = $this->get('/user/booking/confirmation', [
            'service_id' => $service->id,
            'barber_id' => $barber->id,
            'date' => '2024-12-20',
            'time' => '10:00'
        ]);

        $response->assertStatus(200);
    }

    public function test_user_can_view_profile()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $response = $this->get('/user/profile');

        $response->assertStatus(200);
    }

    public function test_user_can_get_profile_data()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $response = $this->get('/user/profile/data');

        $response->assertStatus(200);
        $response->assertJson([
            'user' => [
                'name' => $user->name,
                'email' => $user->email
            ]
        ]);
    }

    public function test_user_can_view_products()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $response = $this->get('/user/products');

        $response->assertStatus(200);
    }

    public function test_user_cannot_access_admin_routes()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $response = $this->get('/admin/dashboard');

        $response->assertStatus(302); // Should redirect due to role-based redirection
    }
}