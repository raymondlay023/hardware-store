<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        Supplier::create([
            'name' => 'BuildMart Suppliers',
            'contact' => '555-0101',
            'payment_terms' => '30 days',
        ]);

        Supplier::create([
            'name' => 'Steel City Inc',
            'contact' => '555-0102',
            'payment_terms' => '15 days',
        ]);

        Supplier::create([
            'name' => 'Brick & Mortar Co',
            'contact' => '555-0103',
            'payment_terms' => 'COD',
        ]);
    }
}
