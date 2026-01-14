<?php

namespace Tests\Unit\Observers;

use App\Models\Product;
use App\Models\ProductPriceHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductObserverTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_price_history_logged_on_price_change(): void
    {
        $product = Product::factory()->create([
            'price' => 10000,
            'cost' => 8000,
        ]);

        // Update the price
        $product->update(['price' => 12000]);

        // Verify price history was created
        $this->assertDatabaseHas('product_price_history', [
            'product_id' => $product->id,
            'old_price' => 10000,
            'new_price' => 12000,
        ]);
    }

    public function test_price_history_not_logged_if_price_unchanged(): void
    {
        $product = Product::factory()->create([
            'price' => 10000,
            'cost' => 8000,
        ]);

        $initialCount = ProductPriceHistory::count();

        // Update something other than price
        $product->update(['name' => 'Updated Name']);

        // Verify no new price history was created
        $this->assertEquals($initialCount, ProductPriceHistory::count());
    }

    public function test_old_and_new_price_saved_correctly(): void
    {
        $product = Product::factory()->create([
            'price' => 15000,
            'cost' => 12000,
        ]);

        // Update both price and cost
        $product->update([
            'price' => 18000,
            'cost' => 14000,
        ]);

        $history = ProductPriceHistory::where('product_id', $product->id)->latest()->first();

        $this->assertNotNull($history);
        $this->assertEquals(15000, $history->old_price);
        $this->assertEquals(18000, $history->new_price);
        $this->assertEquals(12000, $history->old_cost);
        $this->assertEquals(14000, $history->new_cost);
        $this->assertEquals($this->user->id, $history->changed_by);
    }
}
