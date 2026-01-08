<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use LogsActivity;
    protected $fillable = [
        'customer_id',
        'customer_name',
        'date',
        'total_amount',
        'discount_type',
        'discount_value',
        'payment_method',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'total_amount' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get customer name (from relationship or fallback to customer_name field)
     */
    public function getCustomerNameAttribute($value)
    {
        return $this->customer ? $this->customer->name : $value;
    }

    /**
     * Calculate final amount after discount
     */
    public function getFinalAmount(): float
    {
        $amount = $this->total_amount;

        if ($this->discount_type === 'percentage') {
            return $amount - ($amount * $this->discount_value / 100);
        } elseif ($this->discount_type === 'fixed') {
            return $amount - $this->discount_value;
        }

        return $amount;
    }

    /**
     * Get formatted final amount
     */
    public function getFormattedFinalAmount(): string
    {
        return 'Rp ' . number_format($this->getFinalAmount(), 0, ',', '.');
    }

    /**
     * Generate secure URL for digital receipt
     */
    public function getDigitalReceiptUrlAttribute()
    {
        $token = hash_hmac('sha256', $this->id . $this->created_at, config('app.key'));
        
        return route('receipt.digital', [
            'sale' => $this->id,
            'token' => $token
        ]);
    }

    /**
     * Verify receipt token
     */
    public function verifyReceiptToken($token)
    {
        $expectedToken = hash_hmac('sha256', $this->id . $this->created_at, config('app.key'));
        return hash_equals($expectedToken, $token);
    }
}
