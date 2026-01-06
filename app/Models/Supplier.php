<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class Supplier extends Model
{
    use SoftDeletes, Auditable;

    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone',
        'address',
        'tax_id',
        'contact',
        'payment_terms',
        'credit_limit',
        'outstanding_balance',
        'status',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Check if supplier has available credit
     */
    public function hasAvailableCredit(float $amount = 0): bool
    {
        if ($this->credit_limit == 0) {
            return true; // No credit limit means unlimited
        }
        return $this->outstanding_balance + $amount <= $this->credit_limit;
    }

    /**
     * Increment outstanding balance
     */
    public function addToBalance(float $amount)
    {
        $this->increment('outstanding_balance', $amount);
    }

    /**
     * Decrease outstanding balance (payment received)
     */
    public function reduceBalance(float $amount)
    {
        $this->decrement('outstanding_balance', $amount);
    }

    /**
     * Check if supplier is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
