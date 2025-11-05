<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@cukurmen.com',
            'password' => bcrypt('password'),
            'phone' => '081234567890',
            'role' => 'admin'
        ]);

        // Create default regular user
        User::create([
            'name' => 'Regular User',
            'email' => 'user@cukurmen.com',
            'password' => bcrypt('password'),
            'phone' => '081234567891',
            'role' => 'user'
        ]);
        
        // Run other seeders
        $this->call([
            ServiceSeeder::class,
            BarberSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
