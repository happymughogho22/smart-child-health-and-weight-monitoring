<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeightMeasurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'weight_kg',
        'measured_at',
        'measurement_source',
        'device_id',
        'recorded_by',
        'notes',
    ];

    protected $casts = [
        'weight_kg' => 'decimal:2',
        'measured_at' => 'datetime',
    ];

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}