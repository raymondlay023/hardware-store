<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'category', 'unit', 'price', 'current_stock', 'supplier_id',
        'barcode', 'low_stock_threshold', 'critical_stock_threshold',
        'auto_reorder_enabled', 'reorder_quantity',
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
}
