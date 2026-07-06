<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'address',
        'email',
        'notes',
    ];

    public function stockEntries(): HasMany
    {
        return $this->hasMany(StockEntry::class);
    }
}
