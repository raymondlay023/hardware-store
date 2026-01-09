<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use App\Notifications\AutoReorderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutoReorder extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'inventory:auto-reorder 
                            {--dry-run : Show what would be ordered without creating purchases}';

    /**
     * The console command description.
     */
    protected $description = 'Check low stock products and create draft purchase orders for auto-reorder enabled items';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔍 Checking for products needing reorder...');

        $products = Product::where('auto_reorder_enabled', true)
            ->whereNotNull('supplier_id')
            ->whereColumn('current_stock', '<=', 'low_stock_threshold')
            ->with('supplier')
            ->get();

        if ($products->isEmpty()) {
            $this->info('✅ All products have adequate stock. No reorders needed.');
            return Command::SUCCESS;
        }

        $this->warn("⚠️  Found {$products->count()} product(s) needing reorder.");

        // Group by supplier
        $bySupplier = $products->groupBy('supplier_id');

        $purchasesCreated = [];

        foreach ($bySupplier as $supplierId => $supplierProducts) {
            $supplier = $supplierProducts->first()->supplier;
            
            $this->line("📦 Supplier: {$supplier->name}");

            if ($this->option('dry-run')) {
                foreach ($supplierProducts as $product) {
                    $reorderQty = $this->calculateReorderQuantity($product);
                    $this->line("   - {$product->name}: Current {$product->current_stock}, Reorder {$reorderQty}");
                }
                continue;
            }

            // Create draft purchase order
            $purchase = $this->createDraftPurchase($supplier, $supplierProducts);
            $purchasesCreated[] = $purchase;

            $this->info("   ✅ Created draft PO #{$purchase->id} with {$supplierProducts->count()} item(s)");
        }

        if (!$this->option('dry-run') && count($purchasesCreated) > 0) {
            // Notify admins/managers about new draft purchase orders
            $this->notifyAdmins($purchasesCreated);
            $this->info("📧 Notifications sent to admin/manager users.");
        }

        $this->newLine();
        $this->info('🎉 Auto-reorder check complete!');

        return Command::SUCCESS;
    }

    /**
     * Calculate the reorder quantity for a product
     */
    protected function calculateReorderQuantity(Product $product): int
    {
        // Use configured reorder quantity or calculate based on threshold
        if ($product->reorder_quantity > 0) {
            return $product->reorder_quantity;
        }

        // Default: order enough to reach 2x the low stock threshold
        $targetStock = $product->low_stock_threshold * 2;
        return max($targetStock - $product->current_stock, $product->low_stock_threshold);
    }

    /**
     * Create a draft purchase order for a supplier
     */
    protected function createDraftPurchase($supplier, $products): Purchase
    {
        return DB::transaction(function () use ($supplier, $products) {
            $items = [];
            $totalAmount = 0;

            foreach ($products as $product) {
                $quantity = $this->calculateReorderQuantity($product);
                $unitPrice = $product->cost ?: 0;
                
                $items[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                ];
                
                $totalAmount += $quantity * $unitPrice;
            }

            $purchase = Purchase::create([
                'supplier_id' => $supplier->id,
                'date' => now()->format('Y-m-d'),
                'total_amount' => $totalAmount,
                'status' => 'pending', // Draft status
            ]);

            foreach ($items as $item) {
                $purchase->purchaseItems()->create($item);
            }

            return $purchase;
        });
    }

    /**
     * Notify admin/manager users about draft purchase orders
     */
    protected function notifyAdmins(array $purchases): void
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['admin', 'manager']);
        })->get();

        foreach ($admins as $admin) {
            $admin->notify(new AutoReorderNotification($purchases));
        }
    }
}
