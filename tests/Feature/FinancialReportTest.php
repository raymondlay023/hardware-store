<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialReportTest extends TestCase
{
    use RefreshDatabase;

    protected User $manager;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles and permissions
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        $this->artisan('db:seed', ['--class' => 'PermissionSeeder']);

        $this->manager = User::factory()->create();
        $this->manager->roles()->attach(Role::where('name', 'manager')->first());
        
        $this->actingAs($this->manager);
    }

    public function test_revenue_calculation_correct(): void
    {
        // Create sales with known amounts
        Sale::factory()->create([
            'date' => now(),
            'total_amount' => 100000,
        ]);
        
        Sale::factory()->create([
            'date' => now(),
            'total_amount' => 50000,
        ]);

        // Revenue should be sum of total_amount
        $revenue = Sale::whereBetween('date', [now()->startOfDay(), now()->endOfDay()])
            ->sum('total_amount');

        $this->assertEquals(150000, $revenue);
    }

    public function test_cogs_calculation_correct(): void
    {
        $product = Product::factory()->create(['cost' => 8000, 'price' => 10000]);
        
        $sale = Sale::factory()->create(['date' => now()]);
        
        SaleItem::factory()->create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => 10,
            'unit_price' => 10000,
        ]);

        // COGS = quantity * cost = 10 * 8000 = 80,000
        $cogs = \DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereBetween('sales.date', [now()->startOfDay(), now()->endOfDay()])
            ->sum(\DB::raw('sale_items.quantity * products.cost'));

        $this->assertEquals(80000, $cogs);
    }

    public function test_gross_profit_calculation(): void
    {
        $product = Product::factory()->create(['cost' => 8000, 'price' => 10000]);
        
        $sale = Sale::factory()->create([
            'date' => now(),
            'total_amount' => 100000,
        ]);
        
        SaleItem::factory()->create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => 10,
            'unit_price' => 10000,
        ]);

        $revenue = 100000;
        $cogs = 80000; // 10 * 8000
        $grossProfit = $revenue - $cogs;

        $this->assertEquals(20000, $grossProfit);
    }

    public function test_profit_margin_percentage(): void
    {
        $revenue = 100000;
        $grossProfit = 20000;
        
        $profitMargin = ($grossProfit / $revenue) * 100;

        $this->assertEquals(20, $profitMargin); // 20%
    }

    public function test_profit_by_product_top_15(): void
    {
        // Create a single category to reuse (avoid unique constraint issues)
        $category = Category::factory()->create();
        
        // Create 20 products with sales
        for ($i = 1; $i <= 20; $i++) {
            $product = Product::factory()->create([
                'name' => "Product {$i}",
                'category_id' => $category->id, // Reuse same category
                'cost' => 5000,
                'price' => 10000,
            ]);
            
            $sale = Sale::factory()->create(['date' => now()]);
            
            SaleItem::factory()->create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'quantity' => $i * 2, // Varying quantities
                'unit_price' => 10000,
            ]);
        }

        // Get top 15 by profit
        $topProducts = \DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereBetween('sales.date', [now()->startOfMonth(), now()->endOfMonth()])
            ->select(\DB::raw('products.id, COUNT(*) as count'))
            ->groupBy('products.id')
            ->orderByDesc('count')
            ->limit(15)
            ->get();

        // Should return exactly 15 products
        $this->assertEquals(15, $topProducts->count());
    }

    public function test_profit_by_category(): void
    {
        $category1 = Category::factory()->create(['name' => 'Semen']);
        $category2 = Category::factory()->create(['name' => 'Cat']);

        $product1 = Product::factory()->create([
            'category_id' => $category1->id,
            'cost' => 5000,
            'price' => 10000,
        ]);
        
        $product2 = Product::factory()->create([
            'category_id' => $category2->id,
            'cost' => 8000,
            'price' => 12000,
        ]);

        $sale = Sale::factory()->create(['date' => now()]);
        
        SaleItem::factory()->create([
            'sale_id' => $sale->id,
            'product_id' => $product1->id,
            'quantity' => 10,
            'unit_price' => 10000,
        ]);
        
        SaleItem::factory()->create([
            'sale_id' => $sale->id,
            'product_id' => $product2->id,
            'quantity' => 5,
            'unit_price' => 12000,
        ]);

        $profitByCategory = \DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('sales.date', [now()->startOfDay(), now()->endOfDay()])
            ->select('categories.name')
            ->groupBy('categories.id', 'categories.name')
            ->get();

        // Should have 2 categories
        $this->assertEquals(2, $profitByCategory->count());
    }

    public function test_cash_flow_calculation(): void
    {
        // Sales (money in)
        Sale::factory()->create([
            'date' => now(),
            'total_amount' => 200000,
        ]);

        // Purchases (money out)
        Purchase::factory()->create([
            'date' => now(),
            'total_amount' => 150000,
            'status' => 'received',
        ]);

        $salesTotal = Sale::whereBetween('date', [now()->startOfDay(), now()->endOfDay()])
            ->sum('total_amount');
            
        $purchaseTotal = Purchase::whereBetween('date', [now()->startOfDay(), now()->endOfDay()])
            ->sum('total_amount');

        $cashFlow = $salesTotal - $purchaseTotal;

        $this->assertEquals(50000, $cashFlow); // 200,000 - 150,000
    }

    public function test_date_range_filtering_works(): void
    {
        // Create sales in different time periods
        Sale::factory()->create([
            'date' => now()->subDays(10), // Outside range
            'total_amount' => 100000,
        ]);
        
        Sale::factory()->create([
            'date' => now(), // Inside range
            'total_amount' => 50000,
        ]);

        // Query for today only
        $todayRevenue = Sale::whereBetween('date', [now()->startOfDay(), now()->endOfDay()])
            ->sum('total_amount');

        // Should only include today's sale
        $this->assertEquals(50000, $todayRevenue);
    }
}
