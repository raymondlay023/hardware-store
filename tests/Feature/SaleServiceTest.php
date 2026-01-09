<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\SaleService;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use App\Repositories\ProductRepository;
use App\Repositories\SaleRepository;
use App\Exceptions\InsufficientStockException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SaleServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SaleService $saleService;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->saleService = app(SaleService::class);
        
        // Create and authenticate a user for created_by field
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_create_sale_with_valid_data()
    {
        // Arrange
        $product = Product::factory()->create([
            'price' => 100000,
            'current_stock' => 50,
        ]);

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
                'quantity' => 5,
                'price' => 100000,
            ]
        ];

        // Act
        $sale = $this->saleService->createSale($saleData, $items);

        // Assert
        $this->assertInstanceOf(Sale::class, $sale);
        $this->assertEquals(500000, $sale->total_amount);
        $this->assertEquals(1, $sale->saleItems->count());
        
        // Verify stock was decreased
        $this->assertEquals(45, $product->fresh()->current_stock);
    }

    /** @test */
    public function it_throws_exception_when_insufficient_stock()
    {
        // Arrange
        $product = Product::factory()->create([
            'price' => 100000,
            'current_stock' => 3, // Only 3 in stock
        ]);

        $saleData = [
            'customer_name' => 'Test Customer',
            'date' => now()->format('Y-m-d'),
            'payment_method' => 'cash',
        ];

        $items = [
            [
                'product_id' => $product->id,
                'quantity' => 5, // Trying to sell 5
                'price' => 100000,
            ]
        ];

        // Act & Assert
        $this->expectException(InsufficientStockException::class);
        $this->saleService->createSale($saleData, $items);
    }

    /** @test */
    public function it_applies_percentage_discount_correctly()
    {
        // Arrange
        $product = Product::factory()->create([
            'price' => 100000,
            'current_stock' => 50,
        ]);

        $saleData = [
            'customer_name' => 'Test Customer',
            'date' => now()->format('Y-m-d'),
            'payment_method' => 'cash',
            'discount_type' => 'percentage',
            'discount_value' => 10, // 10% discount
        ];

        $items = [
            [
                'product_id' => $product->id,
                'quantity' => 10,
                'price' => 100000,
            ]
        ];

        // Act
        $sale = $this->saleService->createSale($saleData, $items);

        // Assert
        $this->assertEquals(1000000, $sale->total_amount); // Subtotal
        $this->assertEquals(10, $sale->discount_value); // 10% stored as 10
        $this->assertEquals(900000, $sale->getFinalAmount()); // 1,000,000 - 10% = 900,000
    }
}
