<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class StockEntry extends Model
{
    protected $fillable = [
        'date',
        'item_id',
        'supplier_id',
        'quantity',
        'price_per_item',
        'selling_price',
        'total_purchase_price',
        'image',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'quantity' => 'integer',
            'price_per_item' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'total_purchase_price' => 'decimal:2',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
