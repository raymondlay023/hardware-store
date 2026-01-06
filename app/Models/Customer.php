<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class Customer extends Model
{
    use SoftDeletes, Auditable;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'type',
        'credit_limit',
        'total_purchases',
        'total_orders',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'total_purchases' => 'decimal:2',
        'total_orders' => 'integer',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get recent sales for this customer
     */
    public function recentSales(int $limit = 10)
    {
        return $this->sales()->latest('date')->limit($limit)->get();
    }

    /**
     * Get average order value
     */
    public function getAverageOrderValue(): float
    {
        if ($this->total_orders == 0) {
            return 0;
        }
        return $this->total_purchases / $this->total_orders;
    }

    /**
     * Check if customer has available credit
     */
    public function hasAvailableCredit(float $amount = 0): bool
    {
        if ($this->credit_limit == 0) {
            return false; // No credit facility
        }
        // For now, just check against limit (in future, track outstanding balance)
        return $this->total_purchases + $amount <= $this->credit_limit;
    }

    /**
     * Increment purchase stats
     */
    public function recordPurchase(float $amount)
    {
        $this->increment('total_purchases', $amount);
        $this->increment('total_orders');
    }
}
