<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\HealthRecord;

class Child extends Model
{
    protected $fillable = [
        'child_id',
        'first_name',
        'last_name',
        'sex',
        'date_of_birth',
        'place_of_birth',
        'guardian_name',
        'guardian_phone',
        'guardian_email',
        'address',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

public function growthAssessments()
{
    return $this->hasMany(
        GrowthAssessment::class
    );
}

    /**
     * Get all health records belonging to this child.
     */
    public function healthRecords()
    {
        return $this->hasMany(
            HealthRecord::class
        );
    }

    /**
     * Get the child's current age in completed years.
     */
    public function getAgeInYearsAttribute()
    {
        if (!$this->date_of_birth) {
            return null;
        }

        return Carbon::parse($this->date_of_birth)
            ->diffInYears(Carbon::today());
    }

    /**
     * Get the child's current age in completed months.
     */
    public function getAgeInMonthsAttribute()
    {
        if (!$this->date_of_birth) {
            return null;
        }

        return Carbon::parse($this->date_of_birth)
            ->diffInMonths(Carbon::today());
    }

    /**
     * Get the child's completed age in months
     * at a specific health-record visit.
     */
    public function ageInMonthsAt($visitDate)
    {
        if (!$this->date_of_birth || !$visitDate) {
            return null;
        }

        try {
            $dateOfBirth = Carbon::parse(
                $this->date_of_birth
            )->startOfDay();

            $dateOfVisit = Carbon::parse(
                $visitDate
            )->startOfDay();

            if ($dateOfVisit->lt($dateOfBirth)) {
                return null;
            }

            return (int) $dateOfBirth->diffInMonths(
                $dateOfVisit
            );

        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get a readable age at a specific health-record visit.
     *
     * Examples:
     * 1 year 8 months
     * 8 months
     * 2 years
     */
    public function ageAt($visitDate)
    {
        if (!$this->date_of_birth || !$visitDate) {
            return 'Unknown';
        }

        try {
            $dateOfBirth = Carbon::parse(
                $this->date_of_birth
            )->startOfDay();

            $dateOfVisit = Carbon::parse(
                $visitDate
            )->startOfDay();

            if ($dateOfVisit->lt($dateOfBirth)) {
                return 'Unknown';
            }

            /*
             * Calculate completed years as an integer.
             */
            $years = (int) $dateOfBirth->diffInYears(
                $dateOfVisit
            );

            /*
             * Move the birth date forward by the
             * completed number of years.
             */
            $afterYears = $dateOfBirth->copy()
                ->addYears($years);

            /*
             * Calculate the remaining completed months.
             */
            $months = (int) $afterYears->diffInMonths(
                $dateOfVisit
            );

            /*
             * If the child has not reached one full year,
             * display only months.
             */
            if ($years === 0) {
                return $months . ' month' .
                    ($months === 1 ? '' : 's');
            }

            /*
             * If the child has completed years,
             * display years and remaining months.
             */
            $result = $years . ' year' .
                ($years === 1 ? '' : 's');

            if ($months > 0) {
                $result .= ' ' . $months . ' month' .
                    ($months === 1 ? '' : 's');
            }

            return $result;

        } catch (\Exception $e) {
            return 'Unknown';
        }
    }
}