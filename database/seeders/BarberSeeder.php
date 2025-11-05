<?php

namespace Database\Seeders;

use App\Models\Barber;
use Illuminate\Database\Seeder;

class BarberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barbers = [
            [
                'name' => 'Zaki',
                'specialty' => 'Scissor Cut',
                'rating' => 4.8,
                'image_path' => '/assets/img/barber1.jpg',
                'status' => 'active'
            ],
            [
                'name' => 'Safik',
                'specialty' => 'Razor Shave',
                'rating' => 4.9,
                'image_path' => '/assets/img/barber2.jpg',
                'status' => 'active'
            ],
            [
                'name' => 'Budi',
                'specialty' => 'Coloring',
                'rating' => 4.7,
                'image_path' => '/assets/img/barber3.jpg',
                'status' => 'active'
            ]
        ];

        foreach ($barbers as $barber) {
            Barber::create($barber);
        }
    }
}
