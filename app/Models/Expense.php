<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    public const CATEGORIES = ['Food', 'Rent', 'WiFi', 'Utilities', 'Transport', 'Salary', 'Maintenance', 'Misc'];

    public const PAYMENT_MODES = ['Cash', 'eSewa', 'Bank'];

    protected $fillable = [
        'date',
        'title',
        'category',
        'amount',
        'payment_mode',
        'vendor',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'amount' => 'decimal:2',
        ];
    }
}
