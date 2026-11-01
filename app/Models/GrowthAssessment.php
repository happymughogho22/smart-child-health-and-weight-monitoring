<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrowthAssessment extends Model
{
    use HasFactory;

    /**
     * Fields that can be mass assigned.
     */
    protected $fillable = [
        'child_id',
        'health_record_id',
        'age_in_months',

        // Weight-for-Age
        'weight_for_age_zscore',
        'weight_for_age_status',

        // Height/Length-for-Age
        'height_for_age_zscore',
        'height_for_age_status',

        // Weight-for-Height
        'weight_for_height_zscore',
        'weight_for_height_status',

        // Weight-for-Length
        'weight_for_length_zscore',
        'weight_for_length_status',

        // BMI-for-Age
        'bmi_for_age_zscore',
        'bmi_for_age_status',

        // MUAC
        'muac_status',

        // Overall assessment
        'overall_status',
        'requires_attention',
        'assessment_notes',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'age_in_months' => 'integer',

        'weight_for_age_zscore' => 'decimal:3',
        'height_for_age_zscore' => 'decimal:3',
        'weight_for_height_zscore' => 'decimal:3',
        'weight_for_length_zscore' => 'decimal:3',
        'bmi_for_age_zscore' => 'decimal:3',

        'requires_attention' => 'boolean',
    ];

    /**
     * The child who was assessed.
     */
    public function child(): BelongsTo
    {
        return $this->belongsTo(
            Child::class
        );
    }

    /**
     * The health record that produced this assessment.
     */
    public function healthRecord(): BelongsTo
    {
        return $this->belongsTo(
            HealthRecord::class
        );
    }
}