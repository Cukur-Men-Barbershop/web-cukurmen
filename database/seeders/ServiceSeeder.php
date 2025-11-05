<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Cukur + Hair Tonic',
                'price' => 20000,
                'duration' => 35,
                'description' => 'Pemotongan rambut dengan hair tonic'
            ],
            [
                'name' => 'Cukur + Kramas + Vitamin + Tonic',
                'price' => 30000,
                'duration' => 45,
                'description' => 'Pemotongan rambut lengkap dengan keramas, vitamin dan hair tonic'
            ],
            [
                'name' => 'Cukur + Keramas + Vitamin + Pijat',
                'price' => 45000,
                'duration' => 60,
                'description' => 'Pemotongan rambut lengkap dengan keramas, vitamin dan pijat'
            ],
            [
                'name' => 'Cukur + Semir',
                'price' => 80000,
                'duration' => 105,
                'description' => 'Pemotongan rambut dengan pengecatan rambut'
            ],
            [
                'name' => 'Hairlight',
                'price' => 175000,
                'duration' => 120,
                'description' => 'Treatment cahaya rambut premium'
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
