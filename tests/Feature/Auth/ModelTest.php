<?php

namespace Tests\Feature\Auth;

use App\Models\Booking;
use App\Models\User;
use App\Models\Service;
use App\Models\Barber;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_model_creation()
    {
        $user = User::factory()->create();
        $service = Service::factory()->create();
        $barber = Barber::factory()->create();

        $booking = Booking::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'barber_id' => $barber->id,
            'booking_date' => '2024-12-20',
            'booking_time' => '10:00',
            'total_price' => 50000,
            'duration' => 45, // Add required duration field
            'status' => 'pending',
            'booking_id' => 'BK20241220TEST'
        ]);

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'service_id' => $service->id,
            'barber_id' => $barber->id,
            'booking_date' => '2024-12-20',
            'booking_time' => '10:00',
            'total_price' => 50000,
            'status' => 'pending',
            'booking_id' => 'BK20241220TEST'
        ]);
    }

    public function test_booking_belongs_to_user()
    {
        $user = User::factory()->create();
        $service = Service::factory()->create();
        $barber = Barber::factory()->create();

        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'barber_id' => $barber->id
        ]);

        $this->assertEquals($user->id, $booking->user->id);
    }

    public function test_booking_belongs_to_service()
    {
        $user = User::factory()->create();
        $service = Service::factory()->create();
        $barber = Barber::factory()->create();

        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'barber_id' => $barber->id
        ]);

        $this->assertEquals($service->id, $booking->service->id);
    }

    public function test_booking_belongs_to_barber()
    {
        $user = User::factory()->create();
        $service = Service::factory()->create();
        $barber = Barber::factory()->create();

        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'barber_id' => $barber->id
        ]);

        $this->assertEquals($barber->id, $booking->barber->id);
    }

    public function test_service_model_creation()
    {
        $service = Service::create([
            'name' => 'Haircut',
            'price' => 50000,
            'duration' => 45,
            'description' => 'Basic haircut service'
        ]);

        $this->assertDatabaseHas('services', [
            'name' => 'Haircut',
            'price' => 50000,
            'duration' => 45,
            'description' => 'Basic haircut service'
        ]);
    }

    public function test_barber_model_creation()
    {
        $barber = Barber::create([
            'name' => 'John Doe',
            'specialty' => 'Haircut',
            'rating' => 4.5,
            'status' => 'active'
        ]);

        $this->assertDatabaseHas('barbers', [
            'name' => 'John Doe',
            'specialty' => 'Haircut',
            'rating' => 4.5,
            'status' => 'active'
        ]);
    }

    public function test_product_model_creation()
    {
        $product = Product::create([
            'name' => 'Shampoo',
            'price' => 25000,
            'description' => 'Hair care product'
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Shampoo',
            'price' => 25000,
            'description' => 'Hair care product'
        ]);
    }

    public function test_user_has_bookings_relationship()
    {
        $user = User::factory()->create();
        $service = Service::factory()->create();
        $barber = Barber::factory()->create();

        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'barber_id' => $barber->id
        ]);

        $this->assertCount(1, $user->bookings);
        $this->assertEquals($booking->id, $user->bookings->first()->id);
    }
}