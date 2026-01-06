<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Repositories\ProductRepository;
use App\Repositories\SaleRepository;
use App\Exceptions\InsufficientStockException;
use App\Exceptions\BusinessLogicException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SaleService
{
    protected ProductRepository $productRepository;
    protected SaleRepository $saleRepository;

    public function __construct(
        ProductRepository $productRepository,
        SaleRepository $saleRepository
    ) {
        $this->productRepository = $productRepository;
        $this->saleRepository = $saleRepository;
    }
    /**
     * Create a new sale with items
     *
     * @param array $saleData Sale data (customer_id, customer_name, date, payment_method, etc.)
     * @param array $items Array of items [{product_id, quantity, price}, ...]
     * @return Sale
     * @throws InsufficientStockException
     * @throws BusinessLogicException
     */
    public function createSale(array $saleData, array $items): Sale
    {
        // Validate items exist
        if (empty($items)) {
            throw new BusinessLogicException('Cannot create sale without items');
        }

        return DB::transaction(function () use ($saleData, $items) {
            // Validate stock availability for all items first
            $this->validateStockAvailability($items);

            // Calculate totals
            $subtotal = $this->calculateSubtotal($items);
            $discountAmount = $this->calculateDiscount(
                $subtotal,
                $saleData['discount_type'] ?? 'none',
                $saleData['discount_value'] ?? 0
            );

            // Create the sale
            $sale = Sale::create([
                'customer_id' => $saleData['customer_id'] ?? null,
                'customer_name' => $saleData['customer_name'] ?? null,
                'date' => $saleData['date'],
                'total_amount' => $subtotal,
                'discount_type' => $saleData['discount_type'] ?? 'none',
                'discount_value' => $discountAmount,
                'payment_method' => $saleData['payment_method'],
                'notes' => $saleData['notes'] ?? null,
                'created_by' => Auth::id(),
            ]);

            // Add items and adjust stock
            foreach ($items as $item) {
                $this->addSaleItem($sale, $item);
            }

            // Update customer statistics if customer exists
            if ($sale->customer_id) {
                $this->updateCustomerStats($sale);
            }

            return $sale->load('saleItems.product', 'customer');
        });
    }

    /**
     * Validate that all items have sufficient stock
     *
     * @param array $items
     * @throws InsufficientStockException
     */
    protected function validateStockAvailability(array $items): void
    {
        foreach ($items as $item) {
            $product = $this->productRepository->findOrFail($item['product_id']);

            if ($product->current_stock < $item['quantity']) {
                throw new InsufficientStockException(
                    $product->name,
                    $item['quantity'],
                    $product->current_stock
                );
            }
        }
    }

    /**
     * Add a sale item and adjust product stock
     *
     * @param Sale $sale
     * @param array $item
     * @return SaleItem
     */
    protected function addSaleItem(Sale $sale, array $item): SaleItem
    {
        $product = Product::findOrFail($item['product_id']);

        // Create sale item
        $saleItem = $sale->saleItems()->create([
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'unit_price' => $item['price'],
        ]);

        // Adjust stock with tracking
        $product->adjustStock(
            -$item['quantity'],
            'sale',
            "Sale #{$sale->id}",
            $sale
        );

        return $saleItem;
    }

    /**
     * Calculate subtotal from items
     *
     * @param array $items
     * @return float
     */
    protected function calculateSubtotal(array $items): float
    {
        return collect($items)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    }

    /**
     * Calculate discount amount
     *
     * @param float $subtotal
     * @param string $discountType
     * @param float $discountValue
     * @return float
     */
    protected function calculateDiscount(float $subtotal, string $discountType, float $discountValue): float
    {
        if ($discountType === 'percentage') {
            $discountValue = max(0, min(100, $discountValue));
            return ($subtotal * $discountValue) / 100;
        }

        if ($discountType === 'fixed') {
            return min(max(0, $discountValue), $subtotal);
        }

        return 0;
    }

    /**
     * Update customer purchase statistics
     *
     * @param Sale $sale
     */
    protected function updateCustomerStats(Sale $sale): void
    {
        if ($customer = $sale->customer) {
            $customer->increment('total_orders');
            $customer->increment('total_purchases', $sale->total_amount - $sale->discount_value);
        }
    }

    /**
     * Get sale details with all relationships
     *
     * @param int $saleId
     * @return Sale
     */
    public function getSaleDetails(int $saleId): Sale
    {
        return $this->saleRepository->findWithDetails($saleId);
    }
}
