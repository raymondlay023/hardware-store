<?php

namespace App\Services;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\Supplier;
use App\Exceptions\BusinessLogicException;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    /**
     * Create a new purchase order
     *
     * @param array $purchaseData Purchase data (supplier_id, date, status)
     * @param array $items Array of items [{product_id, quantity, unit_price}, ...]
     * @return Purchase
     * @throws BusinessLogicException
     */
    public function createPurchase(array $purchaseData, array $items): Purchase
    {
        if (empty($items)) {
            throw new BusinessLogicException('Cannot create purchase without items');
        }

        return DB::transaction(function () use ($purchaseData, $items) {
            // Calculate total
            $totalAmount = $this->calculateTotal($items);

            // Create the purchase
            $purchase = Purchase::create([
                'supplier_id' => $purchaseData['supplier_id'],
                'date' => $purchaseData['date'],
                'total_amount' => $totalAmount,
                'status' => $purchaseData['status'] ?? 'pending',
            ]);

            // Add items
            foreach ($items as $item) {
                $this->addPurchaseItem($purchase, $item);
            }

            return $purchase->load('purchaseItems.product', 'supplier');
        });
    }

    /**
     * Mark purchase as received and update stock
     *
     * @param int $purchaseId
     * @return Purchase
     */
    public function receivePurchase(int $purchaseId): Purchase
    {
        return DB::transaction(function () use ($purchaseId) {
            $purchase = Purchase::with('purchaseItems.product')->findOrFail($purchaseId);

            if ($purchase->status === 'received') {
                throw new BusinessLogicException('Purchase has already been received');
            }

            // Update stock for each item
            foreach ($purchase->purchaseItems as $item) {
                $item->product->adjustStock(
                    $item->quantity,
                    'purchase',
                    "Purchase #{$purchase->id} received",
                    $purchase,
                    auth()->id() // Added userId parameter
                );
            }

            // Update purchase status
            $purchase->update(['status' => 'received']);

            return $purchase;
        });
    }

    /**
     * Add a purchase item
     *
     * @param Purchase $purchase
     * @param array $item
     * @return PurchaseItem
     */
    protected function addPurchaseItem(Purchase $purchase, array $item): PurchaseItem
    {
        return $purchase->purchaseItems()->create([
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'unit_cost' => $item['unit_cost'], // Changed from unit_price to unit_cost
        ]);
    }

    /**
     * Calculate total from items
     *
     * @param array $items
     * @return float
     */
    protected function calculateTotal(array $items): float
    {
        return collect($items)->sum(function ($item) {
            return $item['unit_cost'] * $item['quantity']; // Changed from unit_price to unit_cost
        });
    }

    /**
     * Get purchase details with relationships
     *
     * @param int $purchaseId
     * @return Purchase
     */
    public function getPurchaseDetails(int $purchaseId): Purchase
    {
        return Purchase::with(['purchaseItems.product', 'supplier'])
            ->findOrFail($purchaseId);
    }
}
