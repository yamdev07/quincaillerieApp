<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Appel du seeder pour créer un compte admin
        $this->call([
            AdminUserSeeder::class,
            SupplierSeeder::class,
            ProductSeeder::class,
            ClientSeeder::class,
            SalesSeeder::class,
        ]);
    }
}
