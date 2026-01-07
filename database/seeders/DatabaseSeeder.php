<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data first
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('role_user')->truncate();
        User::truncate();
        Supplier::truncate();
        Category::truncate();
        Product::truncate();
        Customer::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Seed roles first
        $this->call(RoleSeeder::class);

        // Get role IDs
        $adminRole = \App\Models\Role::where('name', 'admin')->first();
        $managerRole = \App\Models\Role::where('name', 'manager')->first();
        $cashierRole = \App\Models\Role::where('name', 'cashier')->first();

        // Create Admin User
        $admin = User::create([
            'name' => 'Admin BangunanPro',
            'email' => 'admin@bangunanpro.com',
            'password' => Hash::make('password'),
        ]);
        $admin->roles()->attach($adminRole);

        // Create Manager user
        $manager = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@bangunanpro.com',
            'password' => Hash::make('password'),
        ]);
        $manager->roles()->attach($managerRole);

        // Create Cashier user
        $cashier = User::create([
            'name' => 'Siti Rahayu',
            'email' => 'siti@bangunanpro.com',
            'password' => Hash::make('password'),
        ]);
        $cashier->roles()->attach($cashierRole);

        // Create Suppliers
        $suppliers = [
            ['name' => 'PT Semen Indonesia', 'contact' => '081234567890'],
            ['name' => 'CV Baja Mandiri', 'contact' => '082345678901'],
            ['name' => 'Toko Cat Jaya', 'contact' => '083456789012'],
            ['name' => 'PT Besi Mega', 'contact' => '084567890123'],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }

        // Create Categories
        $categories = [
            ['name' => 'Semen & Pasir', 'slug' => 'semen-pasir'],
            ['name' => 'Besi & Baja', 'slug' => 'besi-baja'],
            ['name' => 'Cat & Finishing', 'slug' => 'cat-finishing'],
            ['name' => 'Pipa & Fitting', 'slug' => 'pipa-fitting'],
            ['name' => 'Listrik & Kabel', 'slug' => 'listrik-kabel'],
            ['name' => 'Alat Tukang', 'slug' => 'alat-tukang'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create Products
        $products = [
            ['name' => 'Semen Gresik 50kg', 'brand' => 'Gresik', 'category' => 'Semen & Pasir', 'category_id' => 1, 'unit' => 'sak', 'price' => 65000, 'cost' => 58000, 'current_stock' => 150, 'supplier_id' => 1],
            ['name' => 'Semen Tiga Roda 50kg', 'brand' => 'Tiga Roda', 'category' => 'Semen & Pasir', 'category_id' => 1, 'unit' => 'sak', 'price' => 63000, 'cost' => 56000, 'current_stock' => 200, 'supplier_id' => 1],
            ['name' => 'Pasir Cor', 'brand' => 'Lokal', 'category' => 'Semen & Pasir', 'category_id' => 1, 'unit' => 'm3', 'price' => 350000, 'cost' => 300000, 'current_stock' => 50, 'supplier_id' => 1],
            
            ['name' => 'Besi Beton 10mm', 'brand' => 'KS', 'category' => 'Besi & Baja', 'category_id' => 2, 'unit' => 'batang', 'price' => 85000, 'cost' => 75000, 'current_stock' => 300, 'supplier_id' => 2],
            ['name' => 'Besi Beton 12mm', 'brand' => 'KS', 'category' => 'Besi & Baja', 'category_id' => 2, 'unit' => 'batang', 'price' => 105000, 'cost' => 95000, 'current_stock' => 250, 'supplier_id' => 2],
            ['name' => 'Wiremesh M8', 'brand' => 'BJA', 'category' => 'Besi & Baja', 'category_id' => 2, 'unit' => 'lembar', 'price' => 185000, 'cost' => 165000, 'current_stock' => 100, 'supplier_id' => 2],
            
            ['name' => 'Cat Tembok Avitex 25kg', 'brand' => 'Avitex', 'category' => 'Cat & Finishing', 'category_id' => 3, 'unit' => 'pail', 'price' => 485000, 'cost' => 425000, 'current_stock' => 75, 'supplier_id' => 3],
            ['name' => 'Cat Kayu Propan 1L', 'brand' => 'Propan', 'category' => 'Cat & Finishing', 'category_id' => 3, 'unit' => 'kaleng', 'price' => 75000, 'cost' => 65000, 'current_stock' => 120, 'supplier_id' => 3],
            
            ['name' => 'Pipa PVC 3 inch', 'brand' => 'Rucika', 'category' => 'Pipa & Fitting', 'category_id' => 4, 'unit' => 'batang', 'price' => 95000, 'cost' => 85000, 'current_stock' => 180, 'supplier_id' => 4],
            ['name' => 'Pipa PVC 4 inch', 'brand' => 'Rucika', 'category' => 'Pipa & Fitting', 'category_id' => 4, 'unit' => 'batang', 'price' => 125000, 'cost' => 110000, 'current_stock' => 150, 'supplier_id' => 4],
            
            ['name' => 'Kabel NYM 2x2.5', 'brand' => 'Supreme', 'category' => 'Listrik & Kabel', 'category_id' => 5, 'unit' => 'meter', 'price' => 12500, 'cost' => 10000, 'current_stock' => 500, 'supplier_id' => 4],
            ['name' => 'Saklar Broco', 'brand' => 'Broco', 'category' => 'Listrik & Kabel', 'category_id' => 5, 'unit' => 'pcs', 'price' => 15000, 'cost' => 12000, 'current_stock' => 300, 'supplier_id' => 4],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // Create Customers
        $customers = [
            ['name' => 'Kontraktor Maju Jaya', 'type' => 'contractor', 'phone' => '081122334455', 'email' => 'kontraktor@majujaya.com'],
            ['name' => 'Toko Bangunan Sejahtera', 'type' => 'retail', 'phone' => '081233445566', 'email' => 'sejahtera@gmail.com'],
            ['name' => 'CV Pembangunan Raya', 'type' => 'wholesale', 'phone' => '081344556677', 'email' => 'pembangun@raya.com'],
            ['name' => 'Bapak Andi', 'type' => 'retail', 'phone' => '081455667788', 'email' => null],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        echo "\n✅ Seeder completed successfully!\n";
        echo "📧 Admin: admin@bangunanpro.com | Password: password\n";
        echo "📧 Manager: budi@bangunanpro.com | Password: password\n";
        echo "📧 Cashier: siti@bangunanpro.com | Password: password\n\n";
    }
}
