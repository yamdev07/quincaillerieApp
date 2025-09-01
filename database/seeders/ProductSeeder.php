<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::insert([
            ['name' => 'Clous 100mm', 'description' => 'Clous en acier pour menuiserie', 'price' => 1500, 'quantity' => 500, 'category' => 'Menuiserie', 'supplier_id' => 1],
            ['name' => 'Serrure de porte', 'description' => 'Serrure de sécurité en acier', 'price' => 5000, 'quantity' => 100, 'category' => 'Menuiserie', 'supplier_id' => 1],
            ['name' => 'Ampoule LED 15W', 'description' => 'Ampoule économique', 'price' => 1200, 'quantity' => 300, 'category' => 'Électricité', 'supplier_id' => 2],
            ['name' => 'Fil électrique 2.5mm', 'description' => 'Bobine de 100m', 'price' => 25000, 'quantity' => 50, 'category' => 'Électricité', 'supplier_id' => 2],
            ['name' => 'Tuyau PVC 32mm', 'description' => 'Tuyau pour plomberie', 'price' => 3500, 'quantity' => 200, 'category' => 'Plomberie', 'supplier_id' => 3],
            ['name' => 'Robinet simple', 'description' => 'Robinet en laiton', 'price' => 2500, 'quantity' => 80, 'category' => 'Plomberie', 'supplier_id' => 3],
            ['name' => 'Marteau de menuisier', 'description' => 'Marteau en acier', 'price' => 4000, 'quantity' => 60, 'category' => 'Outils', 'supplier_id' => 1],
            ['name' => 'Sac de ciment 50kg', 'description' => 'Ciment de qualité supérieure', 'price' => 6000, 'quantity' => 150, 'category' => 'Ciment et Fer', 'supplier_id' => 3],
        ]);
    }
}
