<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Barber>
 */
class BarberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'specialty' => $this->faker->word(),
            'rating' => $this->faker->randomFloat(1, 3, 5),
            'image_path' => $this->faker->imageUrl(),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}