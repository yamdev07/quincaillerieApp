<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Client;
use App\Models\User;

class SalesSeeder extends Seeder
{
    public function run(): void
    {
        $caissier = User::first(); // on prend le premier utilisateur (admin ou caissier test)

        $client = Client::first();
        $product = Product::where('name', 'Sac de ciment 50kg')->first();

        if ($client && $product && $caissier) {
            Sale::create([
                'product_id' => $product->id,
                'client_id' => $client->id,
                'quantity' => 10,
                'total_price' => $product->price * 10,
                'user_id' => $caissier->id,
            ]);
        }
    }
}
