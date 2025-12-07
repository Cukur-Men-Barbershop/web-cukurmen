<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Service;
use App\Models\Barber;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'service_id' => Service::factory(),
            'barber_id' => Barber::factory(),
            'booking_date' => $this->faker->date(),
            'booking_time' => $this->faker->time('H:i'),
            'total_price' => $this->faker->numberBetween(30000, 100000),
            'duration' => $this->faker->numberBetween(30, 90),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'in_progress', 'completed', 'cancelled']),
            'payment_method' => $this->faker->randomElement(['Cash', 'Card', 'Transfer']),
            'payment_status' => $this->faker->randomElement(['unpaid', 'paid']),
            'booking_id' => 'BK' . date('Ymd') . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6)),
            'phone' => $this->faker->phoneNumber(),
        ];
    }
}