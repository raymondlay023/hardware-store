<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use App\Exceptions\BusinessLogicException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class StockService
{
    /**
     * Adjust product stock
     *
     * @param int $productId
     * @param int $quantity Positive for increase, negative for decrease
     * @param string $type Type of movement (purchase, sale, adjustment, return, initial)
     * @param string|null $notes
     * @param mixed|null $reference Related model (Sale, Purchase, etc.)
     * @return StockMovement
     * @throws BusinessLogicException
     */
    public function adjustStock(
        int $productId,
        int $quantity,
        string $type,
        ?string $notes = null,
        $reference = null
    ): StockMovement {
        $product = Product::findOrFail($productId);

        // Validate that we're not going negative (except for adjustments)
        if ($product->current_stock + $quantity < 0 && $type !== 'adjustment') {
            throw new BusinessLogicException(
                "Cannot reduce stock below zero. Current: {$product->current_stock}, Requested: {$quantity}"
            );
        }

        return $product->adjustStock($quantity, $type, $notes, $reference);
    }

    /**
     * Perform bulk stock adjustment (e.g., physical inventory count)
     *
     * @param array $adjustments Array of [{product_id, new_quantity, notes}, ...]
     * @return Collection
     */
    public function bulkAdjustStock(array $adjustments): Collection
    {
        $movements = collect();

        foreach ($adjustments as $adjustment) {
            $product = Product::findOrFail($adjustment['product_id']);
            $difference = $adjustment['new_quantity'] - $product->current_stock;

            if ($difference !== 0) {
                $movement = $this->adjustStock(
                    $product->id,
                    $difference,
                    'adjustment',
                    $adjustment['notes'] ?? 'Bulk inventory adjustment'
                );

                $movements->push($movement);
            }
        }

        return $movements;
    }

    /**
     * Get stock movement history for a product
     *
     * @param int $productId
     * @param int $limit
     * @return Collection
     */
    public function getStockHistory(int $productId, int $limit = 50): Collection
    {
        return StockMovement::where('product_id', $productId)
            ->with(['user', 'reference'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get stock movements for a date range
     *
     * @param string $startDate
     * @param string $endDate
     * @param string|null $type
     * @return Collection
     */
    public function getMovementsByDateRange(
        string $startDate,
        string $endDate,
        ?string $type = null
    ): Collection {
        $query = StockMovement::whereBetween('created_at', [$startDate, $endDate])
            ->with(['product', 'user', 'reference']);

        if ($type) {
            $query->where('type', $type);
        }

        return $query->latest()->get();
    }

    /**
     * Calculate stock value for inventory
     *
     * @return array
     */
    public function calculateInventoryValue(): array
    {
        $products = Product::all();

        $totalCostValue = $products->sum(function ($product) {
            return $product->current_stock * $product->cost;
        });

        $totalRetailValue = $products->sum(function ($product) {
            return $product->current_stock * $product->price;
        });

        $potentialProfit = $totalRetailValue - $totalCostValue;

        return [
            'total_cost_value' => $totalCostValue,
            'total_retail_value' => $totalRetailValue,
            'potential_profit' => $potentialProfit,
            'profit_margin_percentage' => $totalRetailValue > 0 
                ? ($potentialProfit / $totalRetailValue) * 100 
                : 0,
        ];
    }
}
