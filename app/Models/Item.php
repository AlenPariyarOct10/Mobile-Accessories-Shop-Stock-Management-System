<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'image',
        'default_purchase_price',
        'default_selling_price',
        'current_stock',
        'low_stock_alert_quantity',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'default_purchase_price' => 'decimal:2',
            'default_selling_price' => 'decimal:2',
            'current_stock' => 'integer',
            'low_stock_alert_quantity' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function stockEntries(): HasMany
    {
        return $this->hasMany(StockEntry::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function latestStockEntry()
    {
        return $this->hasOne(StockEntry::class)->latestOfMany();
    }
}
