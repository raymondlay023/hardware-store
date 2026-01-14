<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Role;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use App\Services\PurchaseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected User $manager;
    protected User $cashier;
    protected PurchaseService $purchaseService;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles and permissions
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        $this->artisan('db:seed', ['--class' => 'PermissionSeeder']);

        // Create test users
        $this->manager = User::factory()->create();
        $this->manager->roles()->attach(Role::where('name', 'manager')->first());

        $this->cashier = User::factory()->create();
        $this->cashier->roles()->attach(Role::where('name', 'cashier')->first());

        $this->purchaseService = app(PurchaseService::class);
    }

    public function test_can_create_purchase_order(): void
    {
        $supplier = Supplier::factory()->create();
        $product1 = Product::factory()->create(['cost' => 10000]);
        $product2 = Product::factory()->create(['cost' => 20000]);

        $purchaseData = [
            'supplier_id' => $supplier->id,
            'date' => now()->format('Y-m-d'),
            'notes' => 'Test purchase order',
        ];

        $items = [
            [
                'product_id' => $product1->id,
                'quantity' => 100,
                'unit_cost' => 10000,
            ],
            [
                'product_id' => $product2->id,
                'quantity' => 50,
                'unit_cost' => 20000,
            ],
        ];

        $this->actingAs($this->manager);

        $purchase = $this->purchaseService->createPurchase($purchaseData, $items);

        // Verify purchase created
        $this->assertDatabaseHas('purchases', [
            'id' => $purchase->id,
            'supplier_id' => $supplier->id,
            'total_amount' => 2000000, // (100*10000) + (50*20000)
        ]);

        // Verify purchase items created
        $this->assertEquals(2, $purchase->purchaseItems->count());
    }

    public function test_purchase_status_starts_as_pending(): void
    {
        $supplier = Supplier::factory()->create();
        $product = Product::factory()->create(['cost' => 10000]);

        $purchaseData = [
            'supplier_id' => $supplier->id,
            'date' => now()->format('Y-m-d'),
        ];

        $items = [
            [
                'product_id' => $product->id,
                'quantity' => 10,
                'unit_cost' => 10000,
            ],
        ];

        $this->actingAs($this->manager);

        $purchase = $this->purchaseService->createPurchase($purchaseData, $items);

        $this->assertEquals('pending', $purchase->status);
    }

    public function test_can_receive_purchase_and_update_stock(): void
    {
        $product = Product::factory()->create(['current_stock' => 50, 'cost' => 10000]);
        
        $purchase = Purchase::factory()->pending()->create();
        $purchaseItem = PurchaseItem::create([
            'purchase_id' => $purchase->id,
            'product_id' => $product->id,
            'quantity' => 100,
            'unit_cost' => 10000,
        ]);

        $this->actingAs($this->manager);

        // Receive the purchase
        $this->purchaseService->receivePurchase($purchase->id);

        // Verify stock increased
        $product->refresh();
        $this->assertEquals(150, $product->current_stock); // 50 + 100
    }

    public function test_stock_movement_logged_on_purchase_receive(): void
    {
        $product = Product::factory()->create(['current_stock' => 50]);
        
        $purchase = Purchase::factory()->pending()->create();
        PurchaseItem::create([
            'purchase_id' => $purchase->id,
            'product_id' => $product->id,
            'quantity' => 75,
            'unit_cost' => 10000,
        ]);

        $this->actingAs($this->manager);

        $this->purchaseService->receivePurchase($purchase->id);

        // Verify stock movement logged (positive for purchases/incoming)
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'quantity' => 75,
            'type' => 'purchase',
            'reference_type' => Purchase::class, // Changed from referenceable_type to reference_type (actual column)
            'reference_id' => $purchase->id, // Changed from referenceable_id to reference_id (actual column)
        ]);
    }

    public function test_purchase_status_changes_to_received(): void
    {
        $product = Product::factory()->create(['current_stock' => 50]);
        
        $purchase = Purchase::factory()->pending()->create();
        PurchaseItem::create([
            'purchase_id' => $purchase->id,
            'product_id' => $product->id,
            'quantity' => 20,
            'unit_cost' => 10000,
        ]);

        $this->actingAs($this->manager);

        $this->purchaseService->receivePurchase($purchase->id);

        $purchase->refresh();
        $this->assertEquals('received', $purchase->status);
    }

    public function test_cannot_receive_purchase_twice(): void
    {
        $product = Product::factory()->create(['current_stock' => 50]);
        
        $purchase = Purchase::factory()->received()->create(); // Already received
        PurchaseItem::create([
            'purchase_id' => $purchase->id,
            'product_id' => $product->id,
            'quantity' => 20,
            'unit_cost' => 10000,
        ]);

        $this->actingAs($this->manager);

        $this->expectException(\App\Exceptions\BusinessLogicException::class);

        $this->purchaseService->receivePurchase($purchase->id);
    }

    public function test_purchase_calculation_correct(): void
    {
        $supplier = Supplier::factory()->create();
        $product1 = Product::factory()->create(['cost' => 15000]);
        $product2 = Product::factory()->create(['cost' => 25000]);
        $product3 = Product::factory()->create(['cost' => 8000]);

        $purchaseData = [
            'supplier_id' => $supplier->id,
            'date' => now()->format('Y-m-d'),
        ];

        $items = [
            ['product_id' => $product1->id, 'quantity' => 50, 'unit_cost' => 15000],
            ['product_id' => $product2->id, 'quantity' => 30, 'unit_cost' => 25000],
            ['product_id' => $product3->id, 'quantity' => 100, 'unit_cost' => 8000],
        ];

        $this->actingAs($this->manager);

        $purchase = $this->purchaseService->createPurchase($purchaseData, $items);

        // Expected: (50*15000) + (30*25000) + (100*8000) = 750000 + 750000 + 800000 = 2,300,000
        $this->assertEquals(2300000, $purchase->total_amount);
    }

    public function test_manager_can_create_purchase_order(): void
    {
        $this->assertTrue($this->manager->hasPermission('purchases.create'));
        $this->assertTrue($this->manager->can('create', Purchase::class));
    }

    public function test_cashier_cannot_create_purchase_order(): void
    {
        $this->assertFalse($this->cashier->hasPermission('purchases.create'));
        $this->assertFalse($this->cashier->can('create', Purchase::class));
    }
}
