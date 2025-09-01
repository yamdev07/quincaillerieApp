<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        Supplier::insert([
            ['name' => 'Quincaillerie Centrale', 'contact' => '00229 90000001', 'email' => 'central@fournisseur.com', 'address' => 'Cotonou'],
            ['name' => 'Fournitures Électriques Bénin', 'contact' => '00229 90000002', 'email' => 'elec@fournisseur.com', 'address' => 'Porto-Novo'],
            ['name' => 'BTP Matériaux SARL', 'contact' => '00229 90000003', 'email' => 'btp@fournisseur.com', 'address' => 'Parakou'],
        ]);
    }
}
