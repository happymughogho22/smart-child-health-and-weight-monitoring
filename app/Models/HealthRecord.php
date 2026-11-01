<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Child;
use App\Models\GrowthAssessment;

class HealthRecord extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'child_id',
        'visit_date',

        // Growth measurements
        'weight',
        'height',
        'measurement_type',

        // Clinical information
        'symptoms',
        'doctor_remarks',
        'assessment',
        'clinical_observations',

        // Nutrition and monitoring
        'nutritional_status',
        'nutrition_status',
        'monitoring_status',
        'recommendations',

        // Follow-up
        'follow_up_date',
        'additional_notes',

        // Facility information
        'facility_name',
        'health_worker_name',

        // Other measurements
        'temperature',
        'muac',
        'bmi',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'visit_date' => 'date',
        'follow_up_date' => 'date',

        'weight' => 'decimal:2',
        'height' => 'decimal:2',

        'temperature' => 'decimal:1',
        'muac' => 'decimal:1',

        'bmi' => 'decimal:2',
    ];

    /**
     * Get the child belonging to this health record.
     */
    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    /**
     * Get the WHO growth assessment generated
     * from this health record.
     */
    public function growthAssessment()
    {
        return $this->hasOne(
            GrowthAssessment::class,
            'health_record_id'
        );
    }

    /**
     * Get the temperature screening status.
     *
     * This is a screening indicator only.
     * It does not provide a medical diagnosis.
     */
    public function getTemperatureStatusAttribute(): string
    {
        /*
         * No temperature recorded.
         */
        if ($this->temperature === null) {
            return 'Not Recorded';
        }

        $temperature = (float) $this->temperature;

        /*
         * Screening threshold.
         *
         * A temperature of 37.5°C or higher
         * is flagged for clinical review.
         */
        if ($temperature >= 37.5) {
            return 'Fever Screening Flag';
        }

        /*
         * Temperature has been recorded and is
         * below the screening threshold.
         */
        return 'Within Recorded Range';
    }

    /**
     * Determine whether the temperature requires attention.
     */
    public function getTemperatureRequiresAttentionAttribute(): bool
    {
        if ($this->temperature === null) {
            return false;
        }

        return (float) $this->temperature >= 37.5;
    }

    /**
     * Get a CSS-friendly temperature status class.
     *
     * This allows the Blade view to use:
     *
     * {{ $healthRecord->temperature_status_class }}
     */
    public function getTemperatureStatusClassAttribute(): string
    {
        if ($this->temperature === null) {
            return 'status-neutral';
        }

        if ((float) $this->temperature >= 37.5) {
            return 'status-warning';
        }

        return 'status-normal';
    }
} 