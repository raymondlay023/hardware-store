<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Auditable;
use Illuminate\Support\Facades\Auth;
use Exception;

class Product extends Model
{
    use Auditable;

    protected $fillable = [
        'name', 'brand', 'category', 'unit', 'price', 'current_stock', 'supplier_id',
        'barcode', 'low_stock_threshold', 'critical_stock_threshold',
        'auto_reorder_enabled', 'reorder_quantity'
    ];

    public function isLowStock()
    {
        return $this->current_stock < $this->low_stock_threshold;
    }

    public function isCriticalStock()
    {
        return $this->current_stock < $this->critical_stock_threshold;
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function aliases(): HasMany
    {
        return $this->hasMany(ProductAlias::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Adjust stock and log movement.
     *
     * @param int $quantity Change in quantity (positive or negative)
     * @param string $type Type of movement (purchase, sale, adjustment, return)
     * @param string|null $notes
     * @param Model|null $reference Reference model (Sale, Purchase)
     * @return StockMovement
     * @throws Exception
     */
    public function adjustStock(int $quantity, string $type, ?string $notes = null, ?Model $reference = null)
    {
        // Prevent negative stock if critical? For now allow, but maybe warn.
        // Or strictly strictly allow negative?
        
        $this->current_stock += $quantity;
        $this->save();

        return StockMovement::create([
            'product_id' => $this->id,
            'quantity' => $quantity,
            'type' => $type,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id' => $reference ? $reference->getKey() : null,
            'user_id' => Auth::id() ?? 1, // Fallback for seeds/tests
            'notes' => $notes,
        ]);
    }
}
