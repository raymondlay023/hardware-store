<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\ProductPriceHistory;
use App\Events\ProductStockLow;
use Illuminate\Support\Facades\Auth;

class ProductObserver
{
    /**
     * Handle the Product "updating" event.
     */
    public function updating(Product $product): void
    {
        // Track price changes
        if ($product->isDirty('price') || $product->isDirty('cost')) {
            ProductPriceHistory::create([
                'product_id' => $product->id,
                'old_price' => $product->getOriginal('price'),
                'new_price' => $product->price,
                'old_cost' => $product->getOriginal('cost'),
                'new_cost' => $product->cost,
                'changed_by' => Auth::id(),
                'reason' => 'Manual update',
            ]);
        }
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        // Check if stock is low after update
        if ($product->current_stock < $product->low_stock_threshold) {
            ProductStockLow::dispatch(
                $product,
                $product->current_stock,
                $product->low_stock_threshold
            );
        }
    }
}
