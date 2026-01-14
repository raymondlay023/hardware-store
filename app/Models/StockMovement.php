<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'type',
        'reference_type',
        'reference_id',
        'user_id',
        'notes',
    ];

    protected $appends = ['reason']; // Add reason to JSON output

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }

    // Alias for polymorphic relationship (backward compatibility)
    public function referenceable()
    {
        return $this->morphTo('reference');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor for 'reason' (maps to 'notes')
    public function getReasonAttribute()
    {
        return $this->notes;
    }

    // Mutator for 'reason' (maps to 'notes')
    public function setReasonAttribute($value)
    {
        $this->attributes['notes'] = $value;
    }

    // Accessor for 'referenceable_type' (backward compatibility)
    public function getReferenceableTypeAttribute()
    {
        return $this->reference_type;
    }

    // Accessor for 'referenceable_id' (backward compatibility)
    public function getReferenceableIdAttribute()
    {
        return $this->reference_id;
    }
}
