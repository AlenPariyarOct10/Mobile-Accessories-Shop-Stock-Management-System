<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    public const PAYMENT_MODES = ['Cash', 'eSewa', 'Bank'];

    protected $fillable = [
        'date',
        'sold_at',
        'item_id',
        'quantity',
        'price_per_item',
        'total_sales_price',
        'estimated_purchase_cost',
        'profit',
        'payment_mode',
        'customer_name',
        'customer_phone',
        'customer_address',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'sold_at' => 'datetime',
            'quantity' => 'integer',
            'price_per_item' => 'decimal:2',
            'total_sales_price' => 'decimal:2',
            'estimated_purchase_cost' => 'decimal:2',
            'profit' => 'decimal:2',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
