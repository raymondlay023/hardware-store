<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPriceHistory extends Model
{
    protected $table = 'product_price_history';

    protected $fillable = [
        'product_id',
        'old_price',
        'new_price',
        'old_cost',
        'new_cost',
        'changed_by',
        'reason',
    ];

    protected $casts = [
        'old_price' => 'decimal:2',
        'new_price' => 'decimal:2',
        'old_cost' => 'decimal:2',
        'new_cost' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /**
     * Get price change percentage
     */
    public function getPriceChangePercentage(): float
    {
        if ($this->old_price == 0) {
            return 0;
        }

        return (($this->new_price - $this->old_price) / $this->old_price) * 100;
    }
}
