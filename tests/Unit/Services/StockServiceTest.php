<?php

namespace Tests\Unit\Services;

use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Models\User;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockServiceTest extends TestCase
{
    use RefreshDatabase;

    protected StockService $stockService;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->stockService = app(StockService::class);
        $this->user = User::factory()->create();
    }

    public function test_adjust_stock_with_positive_quantity(): void
    {
        $product = Product::factory()->create(['current_stock' => 100]);

        $this->actingAs($this->user);

        $product->adjustStock(50, 'adjustment_in', 'Stock addition', null, $this->user->id);

        $product->refresh();
        
        $this->assertEquals(150, $product->current_stock);
    }

    public function test_adjust_stock_with_negative_quantity(): void
    {
        $product = Product::factory()->create(['current_stock' => 100]);

        $this->actingAs($this->user);

        $product->adjustStock(-25, 'adjustment_out', 'Stock removal', null, $this->user->id);

        $product->refresh();
        
        $this->assertEquals(75, $product->current_stock);
    }

    public function test_stock_movement_is_created(): void
    {
        $product = Product::factory()->create(['current_stock' => 100]);

        $this->actingAs($this->user);

        $product->adjustStock(30, 'adjustment_in', 'Test movement', null, $this->user->id);

        $movement = StockMovement::where('product_id', $product->id)->latest()->first();

        $this->assertNotNull($movement);
        $this->assertEquals(30, $movement->quantity);
        $this->assertEquals('adjustment_in', $movement->type);
        $this->assertEquals('Test movement', $movement->reason);
        $this->assertEquals($this->user->id, $movement->user_id);
    }

    public function test_prevents_negative_stock(): void
    {
        $product = Product::factory()->create(['current_stock' => 10]);

        $this->actingAs($this->user);

        // Attempting to remove more than available should throw exception
        $this->expectException(\Exception::class);

        $product->adjustStock(-50, 'adjustment_out', 'Over withdrawal', null, $this->user->id);
    }

    public function test_polymorphic_reference_saved_correctly(): void
    {
        $product = Product::factory()->create(['current_stock' => 100]);
        $sale = Sale::factory()->create();

        $this->actingAs($this->user);

        // Adjust stock with sale reference
        $product->adjustStock(-10, 'sale', 'Sale transaction', $sale, $this->user->id);

        $movement = StockMovement::where('product_id', $product->id)
            ->where('type', 'sale')
            ->latest()
            ->first();

        $this->assertNotNull($movement);
        $this->assertEquals(Sale::class, $movement->referenceable_type);
        $this->assertEquals($sale->id, $movement->referenceable_id);
        
        // Test polymorphic relationship
        $this->assertInstanceOf(Sale::class, $movement->referenceable);
        $this->assertEquals($sale->id, $movement->referenceable->id);
    }
}
