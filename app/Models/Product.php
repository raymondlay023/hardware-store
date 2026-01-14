<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Auditable;
use Illuminate\Support\Facades\Auth;
use Exception;

class Product extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'name', 'brand', 'category', 'category_id', 'unit', 'price', 'cost', 'markup_percentage',
        'current_stock', 'supplier_id', 'barcode', 'low_stock_threshold',
        'critical_stock_threshold', 'auto_reorder_enabled', 'reorder_quantity'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'markup_percentage' => 'decimal:2',
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Adjust stock and log movement.
     *
     * @param int $quantity Change in quantity (positive or negative)
     * @param string $type Type of movement (purchase, sale, adjustment_in, adjustment_out, etc.)
     * @param string|null $reason Reason for adjustment (maps to 'notes' in database)
     * @param Model|null $reference Reference model (Sale, Purchase)
     * @param int|null $userId User ID who performed the adjustment
     * @return StockMovement
     * @throws Exception
     */
    public function adjustStock(int $quantity, string $type, ?string $reason = null, ?Model $reference = null, ?int $userId = null)
    {
        // Prevent negative stock
        if ($this->current_stock + $quantity < 0) {
            throw new Exception("Cannot reduce stock below zero. Current stock: {$this->current_stock}, Attempted change: {$quantity}");
        }
        
        $this->current_stock += $quantity;
        $this->save();

        return StockMovement::create([
            'product_id' => $this->id,
            'quantity' => $quantity,
            'type' => $type,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id' => $reference ? $reference->getKey() : null,
            'user_id' => $userId ?? Auth::id() ?? 1, // Use provided userId, fallback to auth, then to 1 for tests
            'notes' => $reason, // Map 'reason' parameter to 'notes' column
        ]);
    }

    /**
     * Calculate profit margin (price - cost)
     */
    public function getProfitMargin(): float
    {
        return $this->price - $this->cost;
    }

    /**
     * Calculate profit margin percentage
     */
    public function getProfitMarginPercentage(): float
    {
        if ($this->cost == 0) {
            return 0;
        }
        return (($this->price - $this->cost) / $this->cost) * 100;
    }

    /**
     * Calculate selling price from cost and markup
     */
    public function calculatePriceFromMarkup(): float
    {
        if ($this->markup_percentage) {
            return $this->cost * (1 + ($this->markup_percentage / 100));
        }
        return $this->cost;
    }
}
