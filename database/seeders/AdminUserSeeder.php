<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@ldsc.com'], // 👈 email par défaut de l’admin
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'), // 👈 mot de passe par défaut
                'role' => 'admin',
            ]
        );
    }
}
