<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhoGrowthReference extends Model
{
    protected $fillable = [
        'indicator',
        'sex',
        'age_days',
        'measurement_value',

        'l',
        'm',
        's',

        'sd4neg',
        'sd3neg',
        'sd2neg',
        'sd1neg',

        'sd0',

        'sd1',
        'sd2',
        'sd3',
        'sd4',
    ];

    protected $casts = [
        'age_days' => 'integer',

        'measurement_value' => 'decimal:2',

        'l' => 'decimal:8',
        'm' => 'decimal:8',
        's' => 'decimal:8',

        'sd4neg' => 'decimal:4',
        'sd3neg' => 'decimal:4',
        'sd2neg' => 'decimal:4',
        'sd1neg' => 'decimal:4',

        'sd0' => 'decimal:4',

        'sd1' => 'decimal:4',
        'sd2' => 'decimal:4',
        'sd3' => 'decimal:4',
        'sd4' => 'decimal:4',
    ];
}