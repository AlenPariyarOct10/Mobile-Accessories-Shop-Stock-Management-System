<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairService extends Model
{
    public const PAYMENT_MODES = ['Cash', 'eSewa', 'Bank'];

    protected $fillable = [
        'date',
        'serviced_at',
        'customer_name',
        'customer_phone',
        'customer_address',
        'service_type',
        'description',
        'cost_price',
        'charged_price',
        'profit',
        'payment_mode',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'serviced_at' => 'datetime',
            'cost_price' => 'decimal:2',
            'charged_price' => 'decimal:2',
            'profit' => 'decimal:2',
        ];
    }
}
