<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Hair Tonic',
                'price' => 45000,
                'description' => 'Penghilang rasa gatal dan ketombe',
                'image_path' => '/assets/img/hair.jpg',
                'stock_quantity' => 50,
                'status' => 'active'
            ],
            [
                'name' => 'Hair Vitamin',
                'price' => 75000,
                'description' => 'Vitamin rambut untuk pertumbuhan',
                'image_path' => '/assets/img/vit.jpg',
                'stock_quantity' => 30,
                'status' => 'active'
            ],
            [
                'name' => 'Hair Powder',
                'price' => 55000,
                'description' => 'Serbuk rambut untuk tekstur',
                'image_path' => '/assets/img/vow.jpg',
                'stock_quantity' => 40,
                'status' => 'active'
            ],
            [
                'name' => 'Premium Pomade',
                'price' => 65000,
                'description' => 'Pomade premium untuk gaya rambut',
                'image_path' => '/assets/img/pom.jpg',
                'stock_quantity' => 25,
                'status' => 'active'
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
