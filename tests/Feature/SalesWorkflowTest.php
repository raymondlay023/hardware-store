<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Role;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\User;
use App\Services\SaleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalesWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $cashier;
    protected SaleService $saleService;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles and permissions
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        $this->artisan('db:seed', ['--class' => 'PermissionSeeder']);

        // Create test users
        $this->admin = User::factory()->create();
        $this->admin->roles()->attach(Role::where('name', 'admin')->first());

        $this->cashier = User::factory()->create();
        $this->cashier->roles()->attach(Role::where('name', 'cashier')->first());

        $this->saleService = app(SaleService::class);
    }

    public function test_can_create_sale_with_items(): void
    {
        $customer = Customer::factory()->create();
        $product1 = Product::factory()->create(['current_stock' => 100, 'price' => 10000]);
        $product2 = Product::factory()->create(['current_stock' => 50, 'price' => 20000]);

        $saleData = [
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'date' => now()->format('Y-m-d'),
            'payment_method' => 'cash',
            'discount_type' => 'none',
            'discount_value' => 0,
            'notes' => 'Test sale',
        ];

        $items = [
            [
                'product_id' => $product1->id,
                'quantity' => 5,
                'price' => 10000,
            ],
            [
                'product_id' => $product2->id,
                'quantity' => 2,
                'price' => 20000,
            ],
        ];

        $this->actingAs($this->admin);

        $sale = $this->saleService->createSale($saleData, $items);

        // Verify sale created
        $this->assertDatabaseHas('sales', [
            'id' => $sale->id,
            'customer_name' => $customer->name,
            'total_amount' => 90000, // (5*10000) + (2*20000)
        ]);

        // Verify sale items created
        $this->assertEquals(2, $sale->saleItems->count());
    }

    public function test_sale_reduces_product_stock(): void
    {
        $product = Product::factory()->create(['current_stock' => 100, 'price' => 10000]);

        $saleData = [
            'customer_name' => 'Walk-in Customer',
            'date' => now()->format('Y-m-d'),
            'payment_method' => 'cash',
            'discount_type' => 'none',
            'discount_value' => 0,
        ];

        $items = [
            [
                'product_id' => $product->id,
                'quantity' => 25,
                'price' => 10000,
            ],
        ];

        $this->actingAs($this->admin);

        $this->saleService->createSale($saleData, $items);

        // Verify stock reduced
        $product->refresh();
        $this->assertEquals(75, $product->current_stock);
    }

    public function test_sale_creates_stock_movement(): void
    {
        $product = Product::factory()->create(['current_stock' => 100, 'price' => 10000]);

        $saleData = [
            'customer_name' => 'Test Customer',
            'date' => now()->format('Y-m-d'),
            'payment_method' => 'cash',
            'discount_type' => 'none',
            'discount_value' => 0,
        ];

        $items = [
            [
                'product_id' => $product->id,
                'quantity' => 10,
                'price' => 10000,
            ],
        ];

        $this->actingAs($this->admin);

        $sale = $this->saleService->createSale($saleData, $items);

        // Verify stock movement logged (negative for sales/outgoing)
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'quantity' => -10,
            'type' => 'sale',
        ]);
    }

    public function test_percentage_discount_applied_correctly(): void
    {
        $product = Product::factory()->create(['current_stock' => 100, 'price' => 10000]);

        $saleData = [
            'customer_name' => 'Discount Customer',
            'date' => now()->format('Y-m-d'),
            'payment_method' => 'cash',
            'discount_type' => 'percentage',
            'discount_value' => 10, // 10% discount
        ];

        $items = [
            [
                'product_id' => $product->id,
                'quantity' => 10,
                'price' => 10000,
            ],
        ];

        $this->actingAs($this->admin);

        $sale = $this->saleService->createSale($saleData, $items);

        // Subtotal: 100,000 - 10% = 90,000
        $this->assertEquals(100000, $sale->total_amount); // total_amount is subtotal
        $this->assertEquals(90000, $sale->getFinalAmount());
    }

    public function test_fixed_discount_applied_correctly(): void
    {
        $product = Product::factory()->create(['current_stock' => 100, 'price' => 10000]);

        $saleData = [
            'customer_name' => 'Fixed Discount Customer',
            'date' => now()->format('Y-m-d'),
            'payment_method' => 'cash',
            'discount_type' => 'fixed',
            'discount_value' => 15000, // Rp 15,000 discount
        ];

        $items = [
            [
                'product_id' => $product->id,
                'quantity' => 10,
                'price' => 10000,
            ],
        ];

        $this->actingAs($this->admin);

        $sale = $this->saleService->createSale($saleData, $items);

        // Subtotal: 100,000 - 15,000 = 85,000
        $this->assertEquals(100000, $sale->total_amount);
        $this->assertEquals(85000, $sale->getFinalAmount());
    }

    public function test_cannot_sell_more_than_available_stock(): void
    {
        $product = Product::factory()->create(['current_stock' => 5, 'price' => 10000]);

        $saleData = [
            'customer_name' => 'Over Stock Customer',
            'date' => now()->format('Y-m-d'),
            'payment_method' => 'cash',
            'discount_type' => 'none',
            'discount_value' => 0,
        ];

        $items = [
            [
                'product_id' => $product->id,
                'quantity' => 100, // More than available
                'price' => 10000,
            ],
        ];

        $this->actingAs($this->admin);

        $this->expectException(\App\Exceptions\InsufficientStockException::class);

        $this->saleService->createSale($saleData, $items);
    }

    public function test_customer_stats_updated_after_sale(): void
    {
        $customer = Customer::factory()->create([
            'total_purchases' => 0,
            'total_orders' => 0,
        ]);

        $product = Product::factory()->create(['current_stock' => 100, 'price' => 10000]);

        $saleData = [
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'date' => now()->format('Y-m-d'),
            'payment_method' => 'cash',
            'discount_type' => 'none',
            'discount_value' => 0,
        ];

        $items = [
            [
                'product_id' => $product->id,
                'quantity' => 10,
                'price' => 10000,
            ],
        ];

        $this->actingAs($this->admin);

        $this->saleService->createSale($saleData, $items);

        $customer->refresh();
        $this->assertEquals(100000, $customer->total_purchases);
        $this->assertEquals(1, $customer->total_orders);
    }

    public function test_cashier_can_create_sale(): void
    {
        $this->assertTrue($this->cashier->hasPermission('sales.create'));
        $this->assertTrue($this->cashier->can('create', Sale::class));
    }

    public function test_sale_page_loads_for_authenticated_user(): void
    {
        $this->actingAs($this->cashier)
            ->get(route('sales.create'))
            ->assertStatus(200);
    }

    public function test_sale_list_page_loads(): void
    {
        Sale::factory()->count(3)->create();

        $this->actingAs($this->cashier)
            ->get(route('sales.index'))
            ->assertStatus(200);
    }
}
