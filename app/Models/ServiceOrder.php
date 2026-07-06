<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceOrder extends Model
{
    public const STATUSES = ['Pending', 'Completed'];

    protected $fillable = [
        'date',
        'expected_at',
        'completed_at',
        'repair_service_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'service_type',
        'description',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'expected_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function repairService(): BelongsTo
    {
        return $this->belongsTo(RepairService::class);
    }
}
