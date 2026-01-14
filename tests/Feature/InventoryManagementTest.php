<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Role;
use App\Models\StockMovement;
use App\Models\User;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $manager;
    protected User $cashier;
    protected StockService $stockService;

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

        $this->stockService = app(StockService::class);
    }

    public function test_can_add_stock_via_adjustment(): void
    {
        $product = Product::factory()->create(['current_stock' => 100]);

        $this->actingAs($this->manager);

        // Add 50 units
        $product->adjustStock(50, 'adjustment_in', 'Stock addition test', null, $this->manager->id);

        $product->refresh();
        $this->assertEquals(150, $product->current_stock);
    }

    public function test_can_remove_stock_via_adjustment(): void
    {
        $product = Product::factory()->create(['current_stock' => 100]);

        $this->actingAs($this->manager);

        // Remove 30 units
        $product->adjustStock(-30, 'adjustment_out', 'Stock reduction test', null, $this->manager->id);

        $product->refresh();
        $this->assertEquals(70, $product->current_stock);
    }

    public function test_stock_movement_logged_on_adjustment(): void
    {
        $product = Product::factory()->create(['current_stock' => 100]);

        $this->actingAs($this->manager);

        $product->adjustStock(25, 'adjustment_in', 'Found extra stock', null, $this->manager->id);

        // Verify stock movement created
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'quantity' => 25,
            'type' => 'adjustment_in',
            'notes' => 'Found extra stock', // Changed from 'reason' to 'notes' (actual column name)
            'user_id' => $this->manager->id,
        ]);
    }

    public function test_cannot_adjust_stock_below_zero(): void
    {
        $product = Product::factory()->create(['current_stock' => 10]);

        $this->actingAs($this->manager);

        // Try to remove more than available
        $this->expectException(\Exception::class);
        
        $product->adjustStock(-50, 'adjustment_out', 'Over reduction', null, $this->manager->id);
    }

    public function test_adjustment_requires_reason(): void
    {
        $product = Product::factory()->create(['current_stock' => 100]);

        $this->actingAs($this->manager);

        // Adjustment with reason should work
        $product->adjustStock(10, 'adjustment_in', 'Found in warehouse', null, $this->manager->id);

        $movement = StockMovement::where('product_id', $product->id)->latest()->first();
        
        $this->assertNotNull($movement->reason);
        $this->assertEquals('Found in warehouse', $movement->reason);
    }

    public function test_adjustment_tracks_user(): void
    {
        $product = Product::factory()->create(['current_stock' => 100]);

        $this->actingAs($this->manager);

        $product->adjustStock(5, 'adjustment_in', 'Test adjustment', null, $this->manager->id);

        $movement = StockMovement::where('product_id', $product->id)->latest()->first();
        
        $this->assertEquals($this->manager->id, $movement->user_id);
    }

    public function test_movement_history_filtering_by_product(): void
    {
        $product1 = Product::factory()->create(['current_stock' => 100]);
        $product2 = Product::factory()->create(['current_stock' => 50]);

        $this->actingAs($this->manager);

        $product1->adjustStock(10, 'adjustment_in', 'Test', null, $this->manager->id);
        $product2->adjustStock(5, 'adjustment_in', 'Test', null, $this->manager->id);

        $product1Movements = StockMovement::where('product_id', $product1->id)->get();
        
        $this->assertEquals(1, $product1Movements->count());
        $this->assertEquals($product1->id, $product1Movements->first()->product_id);
    }

    public function test_movement_history_filtering_by_type(): void
    {
        $product = Product::factory()->create(['current_stock' => 100]);

        $this->actingAs($this->manager);

        $product->adjustStock(10, 'adjustment_in', 'Add stock', null, $this->manager->id);
        $product->adjustStock(-5, 'adjustment_out', 'Remove stock', null, $this->manager->id);

        $inMovements = StockMovement::where('product_id', $product->id)
            ->where('type', 'adjustment_in')
            ->get();

        $outMovements = StockMovement::where('product_id', $product->id)
            ->where('type', 'adjustment_out')
            ->get();

        $this->assertEquals(1, $inMovements->count());
        $this->assertEquals(1, $outMovements->count());
        $this->assertEquals(10, $inMovements->first()->quantity);
        $this->assertEquals(-5, $outMovements->first()->quantity);
    }
}
