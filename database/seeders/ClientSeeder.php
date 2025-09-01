<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        Client::insert([
            ['name' => 'Jean Dupont', 'phone' => '00229 97000001', 'email' => 'jean.dupont@mail.com'],
            ['name' => 'Entreprise BTP Services', 'phone' => '00229 97000002', 'email' => 'contact@btp-services.com'],
            ['name' => 'Marie Houngbédji', 'phone' => '00229 97000003', 'email' => 'marie.houng@mail.com'],
        ]);
    }
}
