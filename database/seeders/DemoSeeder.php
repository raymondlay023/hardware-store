<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoSeeder extends Seeder
{
    /**
     * Run the demo database seeds.
     * Creates realistic 30-day demo data for hardware store
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Get or create demo user
            $demoUser = User::firstOrCreate(
                ['email' => 'demo@bangunanpro.test'],
                [
                    'name' => 'Demo User',
                    'password' => bcrypt('demo123'),
                ]
            );

            // Create categories
            $categories = [
                'Semen & Pasir',
                'Cat & Finishing',
                'Besi & Baja',
                'Kayu & Triplek',
                'Pipa & Fitting',
                'Listrik',
                'Tools & Perkakas',
            ];

            foreach ($categories as $categoryName) {
                Category::firstOrCreate(['name' => $categoryName]);
            }

            // Create 50 demo products
            $allCategories = Category::all();
            $products = [];

            for ($i = 1; $i <= 50; $i++) {
                $category = $allCategories->random();
                $cost = rand(5, 200) * 1000; // Rp 5k - 200k
                $price = $cost * 1.3; // 30% markup

                $products[] = Product::create([
                    'name' => $this->getProductName($category->name, $i),
                    'code' => 'DEMO' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'category_id' => $category->id,
                    'cost' => $cost,
                    'price' => $price,
                    'stock' => rand(10, 100),
                    'min_stock' => rand(5, 20),
                    'created_at' => now()->subDays(rand(5, 30)),
                ]);
            }

            // Create 20 suppliers
            $suppliers = [];
            for ($i = 1; $i <= 20; $i++) {
                $suppliers[] = Supplier::create([
                    'name' => 'PT ' . $this->getSupplierName($i),
                    'phone' => '08' . rand(1000000000, 9999999999),
                    'email' => 'supplier' . $i . '@example.com',
                    'address' => 'Jalan Raya No. ' . rand(1, 500),
                    'created_at' => now()->subDays(rand(10, 60)),
                ]);
            }

            // Create 30 customers
            $customers = [];
            for ($i = 1; $i <= 30; $i++) {
                $customers[] = Customer::create([
                    'name' => $this->getCustomerName($i),
                    'phone' => '08' . rand(1000000000, 9999999999),
                    'email' => 'customer' . $i . '@example.com',
                    'address' => 'Perumahan Blok ' . chr(65 + ($i % 26)) . ' No. ' . rand(1, 50),
                    'created_at' => now()->subDays(rand(5, 45)),
                ]);
            }

            // Create purchases over last 30 days
            for ($day = 30; $day >= 0; $day--) {
                $purchasesPerDay = rand(1, 3);
                
                for ($p = 0; $p < $purchasesPerDay; $p++) {
                    $purchase = Purchase::create([
                        'supplier_id' => $suppliers[array_rand($suppliers)]->id,
                        'date' => now()->subDays($day)->setTime(rand(8, 16), rand(0, 59)),
                        'total_amount' => 0, // Will calculate below
                        'status' => $day == 0 ? 'pending' : 'received',
                        'notes' => $day == 0 ? 'Menunggu barang datang' : 'Sudah diterima',
                    ]);

                    $totalAmount = 0;
                    $itemCount = rand(2, 5);

                    for ($item = 0; $item < $itemCount; $item++) {
                        $product = $products[array_rand($products)];
                        $quantity = rand(5, 20);
                        $itemTotal = $product->cost * $quantity;

                        PurchaseItem::create([
                            'purchase_id' => $purchase->id,
                            'product_id' => $product->id,
                            'quantity' => $quantity,
                            'unit_price' => $product->cost,
                        ]);

                        $totalAmount += $itemTotal;
                    }

                    $purchase->update(['total_amount' => $totalAmount]);
                }
            }

            // Create sales over last 30 days
            for ($day = 30; $day >= 0; $day--) {
                $salesPerDay = rand(5, 15); // More sales than purchases
                
                for ($s = 0; $s < $salesPerDay; $s++) {
                    $customer = rand(0, 100) < 60 ? $customers[array_rand($customers)] : null; // 60% have customer

                    $sale = Sale::create([
                        'customer_id' => $customer?->id,
                        'date' => now()->subDays($day)->setTime(rand(8, 18), rand(0, 59)),
                        'total_amount' => 0, // Will calculate below
                        'payment_method' => ['cash', 'transfer', 'qris'][rand(0, 2)],
                        'notes' => $customer ? null : 'Pelanggan umum',
                    ]);

                    $totalAmount = 0;
                    $itemCount = rand(1, 4);

                    for ($item = 0; $item < $itemCount; $item++) {
                        $product = $products[array_rand($products)];
                        $quantity = rand(1, 10);
                        $itemTotal = $product->price * $quantity;

                        SaleItem::create([
                            'sale_id' => $sale->id,
                            'product_id' => $product->id,
                            'quantity' => $quantity,
                            'unit_price' => $product->price,
                        ]);

                        $totalAmount += $itemTotal;
                    }

                    $sale->update(['total_amount' => $totalAmount]);
                }
            }
        });

        $this->command->info('✅ Demo data created successfully!');
        $this->command->info('📊 Created: 50 products, 20 suppliers, 30 customers');
        $this->command->info('📈 Created 30 days of realistic transactions');
        $this->command->info('👤 Demo login: demo@bangunanpro.test / demo123');
    }

    private function getProductName($category, $index): string
    {
        $names = [
            'Semen & Pasir' => ['Semen Gresik 40kg', 'Semen Tiga Roda 50kg', 'Pasir Beton', 'Pasir Pasang', 'Batu Split'],
            'Cat & Finishing' => ['Cat Tembok Avian', 'Cat Kayu Mowilex', 'Cat Besi Nippon', 'Plamir Dinding', 'Thinner'],
            'Besi & Baja' => ['Besi Beton 10mm', 'Besi Hollow 4x4', 'Wiremesh M8', 'Plat Besi 3mm', 'Besi Siku'],
            'Kayu & Triplek' => ['Triplek 9mm', 'Kayu Kamper 4x6', 'Papan Meranti', 'Plywood 12mm', 'Kayu Balok'],
            'Pipa & Fitting' => ['Pipa PVC 3"', 'Elbow PVC', 'Kran Air', 'Pipa PPR', 'Sock Drat'],
            'Listrik' => ['Kabel NYM 2x1.5', 'Saklar Broco', 'Stop Kontak', 'MCB 2A', 'Lampu LED'],
            'Tools & Perkakas' => ['Palu', 'Tang Potong', 'Obeng Set', 'Meteran 5m', 'Gergaji'],
        ];

        $categoryNames = $names[$category] ?? ['Produk'];
        return $categoryNames[$index % count($categoryNames)] . ' ' . chr(65 + ($index % 3));
    }

    private function getSupplierName($index): string
    {
        $names = ['Mitra Bangunan', 'Global Material', 'Berkah Jaya', 'Sentosa Makmur', 'Cahaya Abadi'];
        return $names[$index % count($names)];
    }

    private function getCustomerName($index): string
    {
        $firstNames = ['Budi', 'Andi', 'Siti', 'Rina', 'Ahmad', 'Dewi', 'Rudi', 'Lina', 'Hendra', 'Maya'];
        $lastNames = ['Santoso', 'Wijaya', 'Kusuma', 'Pratama', 'Setiawan', 'Putri', 'Rahman', 'Handoko'];
        
        return $firstNames[$index % count($firstNames)] . ' ' . $lastNames[($index * 3) % count($lastNames)];
    }
}
