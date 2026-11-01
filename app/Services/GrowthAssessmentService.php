<?php

namespace App\Services;

use App\Models\Child;
use App\Models\WhoGrowthReference;
use Carbon\Carbon;

class GrowthAssessmentService
{
    /**
     * Assess a child's growth using WHO Child Growth Standards.
     *
     * The system uses:
     *
     * Birth - 23 months:
     * - Weight-for-age
     * - Length-for-age
     * - Weight-for-length
     * - BMI-for-age
     *
     * 24 - 59 months:
     * - Weight-for-age
     * - Height-for-age
     * - Weight-for-height
     * - BMI-for-age
     *
     * MUAC screening:
     * - 6 - 59 completed months
     */
    public function assess(
        Child $child,
        $visitDate,
        $weight = null,
        $height = null,
        $muac = null,
        $measurementType = null
    ): array {
        $visitDate = Carbon::parse($visitDate);
        $dateOfBirth = Carbon::parse($child->date_of_birth);

        /*
         * Exact age in days is used for WHO child-growth
         * standards.
         */
        $ageInDays = $dateOfBirth->diffInDays($visitDate);

        /*
         * Completed months are used for MUAC eligibility
         * and display.
         */
        $ageInMonths = (int) $dateOfBirth->diffInMonths($visitDate);

        $ageAtVisit = $child->ageAt($visitDate);

        /*
         * Normalize measurement type.
         */
        $measurementType = $this->determineMeasurementType(
            $ageInMonths,
            $measurementType
        );

        /*
         * Calculate BMI.
         */
        $bmi = $this->calculateBmi(
            $weight,
            $height
        );

        /*
         * Prepare default results.
         */
        $weightForAgeZscore = null;
        $weightForAgeStatus = null;

        $heightForAgeZscore = null;
        $heightForAgeStatus = null;

        $weightForHeightZscore = null;
        $weightForHeightStatus = null;

        $weightForLengthZscore = null;
        $weightForLengthStatus = null;

        $bmiForAgeZscore = null;
        $bmiForAgeStatus = null;

        /*
         * --------------------------------------------
         * WEIGHT-FOR-AGE
         * --------------------------------------------
         */
        if ($this->validPositiveNumber($weight)) {

            $reference = $this->findAgeReference(
                'weight_for_age',
                $child->sex,
                $ageInDays
            );

            if ($reference) {

                $weightForAgeZscore =
                    $this->calculateZScore(
                        (float) $weight,
                        $reference
                    );

                $weightForAgeStatus =
                    $this->classifyWeightForAge(
                        $weightForAgeZscore
                    );
            }
        }

        /*
         * --------------------------------------------
         * LENGTH / HEIGHT-FOR-AGE
         * --------------------------------------------
         */
        if ($this->validPositiveNumber($height)) {

            $indicator = $ageInMonths < 24
                ? 'length_height_for_age'
                : 'length_height_for_age';

            $reference = $this->findAgeReference(
                $indicator,
                $child->sex,
                $ageInDays
            );

            if ($reference) {

                $heightForAgeZscore =
                    $this->calculateZScore(
                        (float) $height,
                        $reference
                    );

                $heightForAgeStatus =
                    $this->classifyHeightForAge(
                        $heightForAgeZscore
                    );
            }
        }

        /*
         * --------------------------------------------
         * WEIGHT-FOR-LENGTH / WEIGHT-FOR-HEIGHT
         * --------------------------------------------
         */
        if (
            $this->validPositiveNumber($weight) &&
            $this->validPositiveNumber($height)
        ) {

            if ($ageInMonths < 24) {

                $reference = $this->findMeasurementReference(
                    'weight_for_length',
                    $child->sex,
                    $height
                );

                if ($reference) {

                    $weightForLengthZscore =
                        $this->calculateZScore(
                            (float) $weight,
                            $reference
                        );

                    $weightForLengthStatus =
                        $this->classifyWeightForLengthHeight(
                            $weightForLengthZscore
                        );
                }

            } else {

                $reference = $this->findMeasurementReference(
                    'weight_for_height',
                    $child->sex,
                    $height
                );

                if ($reference) {

                    $weightForHeightZscore =
                        $this->calculateZScore(
                            (float) $weight,
                            $reference
                        );

                    $weightForHeightStatus =
                        $this->classifyWeightForLengthHeight(
                            $weightForHeightZscore
                        );
                }
            }
        }

        /*
         * --------------------------------------------
         * BMI-FOR-AGE
         * --------------------------------------------
         */
        if ($bmi !== null) {

            $reference = $this->findAgeReference(
                'bmi_for_age',
                $child->sex,
                $ageInDays
            );

            if ($reference) {

                $bmiForAgeZscore =
                    $this->calculateZScore(
                        $bmi,
                        $reference
                    );

                $bmiForAgeStatus =
                    $this->classifyBmiForAge(
                        $bmiForAgeZscore
                    );
            }
        }

        /*
         * --------------------------------------------
         * MUAC
         * --------------------------------------------
         */
        $muacAssessed =
            $this->isMuacAssessmentEligible(
                $ageInMonths
            );

        $muacStatus =
            $this->assessMuac(
                $ageInMonths,
                $muac
            );

        /*
         * --------------------------------------------
         * OVERALL STATUS
         * --------------------------------------------
         */
        $overallStatus =
            $this->determineOverallStatus(
                $weightForAgeStatus,
                $heightForAgeStatus,
                $weightForLengthStatus,
                $weightForHeightStatus,
                $bmiForAgeStatus,
                $muacStatus
            );

        /*
         * --------------------------------------------
         * ATTENTION FLAG
         * --------------------------------------------
         */
        $requiresAttention =
            $this->requiresAttention(
                $overallStatus,
                $weightForAgeZscore,
                $heightForAgeZscore,
                $weightForLengthZscore,
                $weightForHeightZscore,
                $bmiForAgeZscore,
                $muacStatus
            );

        /*
         * --------------------------------------------
         * NOTES
         * --------------------------------------------
         */
        $assessmentNotes =
            $this->buildAssessmentNotes(
                $overallStatus,
                $requiresAttention,
                $weightForAgeZscore,
                $heightForAgeZscore,
                $weightForLengthZscore,
                $weightForHeightZscore,
                $bmiForAgeZscore,
                $muacStatus
            );

        /*
         * Nutrition status is the overall screening
         * status used by the existing HealthRecord table.
         */
        $nutritionStatus = $overallStatus;

        return [

            'age_in_days' =>
                $ageInDays,

            'age_in_months' =>
                $ageInMonths,

            'age_at_visit' =>
                $ageAtVisit,

            'measurement_type' =>
                $measurementType,

            'bmi' =>
                $bmi,

            /*
             * WHO indicators
             */
            'weight_for_age_zscore' =>
                $this->roundZScore($weightForAgeZscore),

            'weight_for_age_status' =>
                $weightForAgeStatus,

            'height_for_age_zscore' =>
                $this->roundZScore($heightForAgeZscore),

            'height_for_age_status' =>
                $heightForAgeStatus,

            'weight_for_height_zscore' =>
                $this->roundZScore($weightForHeightZscore),

            'weight_for_height_status' =>
                $weightForHeightStatus,

            'weight_for_length_zscore' =>
                $this->roundZScore($weightForLengthZscore),

            'weight_for_length_status' =>
                $weightForLengthStatus,

            'bmi_for_age_zscore' =>
                $this->roundZScore($bmiForAgeZscore),

            'bmi_for_age_status' =>
                $bmiForAgeStatus,

            /*
             * MUAC
             */
            'muac_assessed' =>
                $muacAssessed,

            'muac_status' =>
                $muacStatus,

            /*
             * Overall
             */
            'nutrition_status' =>
                $nutritionStatus,

            'overall_status' =>
                $overallStatus,

            'requires_attention' =>
                $requiresAttention,

            'assessment_notes' =>
                $assessmentNotes,
        ];
    }


    /**
     * Determine whether the recorded measurement
     * is length or standing height.
     */
    private function determineMeasurementType(
        int $ageInMonths,
        ?string $measurementType
    ): ?string {

        if (
            $measurementType === 'length' ||
            $measurementType === 'height'
        ) {
            return $measurementType;
        }

        if ($ageInMonths < 24) {
            return 'length';
        }

        return 'height';
    }


    /**
     * Calculate BMI.
     *
     * Weight = kilograms
     * Measurement = centimetres
     */
    private function calculateBmi(
        $weight,
        $height
    ): ?float {

        if (
            !$this->validPositiveNumber($weight) ||
            !$this->validPositiveNumber($height)
        ) {
            return null;
        }

        $weight = (float) $weight;
        $height = (float) $height;

        $heightInMetres =
            $height / 100;

        return round(
            $weight /
            ($heightInMetres * $heightInMetres),
            2
        );
    }


    /**
     * Check positive numeric value.
     */
    private function validPositiveNumber($value): bool
    {
        return $value !== null &&
               $value !== '' &&
               is_numeric($value) &&
               (float) $value > 0;
    }


    /**
     * Find age-based WHO reference.
     *
     * Exact age is preferred.
     *
     * If exact age is unavailable, the closest
     * available WHO age row is used.
     */
    private function findAgeReference(
        string $indicator,
        string $sex,
        int $ageInDays
    ): ?WhoGrowthReference {

        $reference =
            WhoGrowthReference::where(
                'indicator',
                $indicator
            )
            ->where(
                'sex',
                strtolower($sex)
            )
            ->where(
                'age_days',
                $ageInDays
            )
            ->first();

        if ($reference) {
            return $reference;
        }

        return WhoGrowthReference::where(
            'indicator',
            $indicator
        )
        ->where(
            'sex',
            strtolower($sex)
        )
        ->whereNotNull('age_days')
        ->orderByRaw(
            'ABS(CAST(age_days AS SIGNED) - ?)',
            [$ageInDays]
        )
        ->first();
    }


    /**
     * Find measurement-based WHO reference.
     *
     * Used for weight-for-length and
     * weight-for-height.
     */
    private function findMeasurementReference(
        string $indicator,
        string $sex,
        $measurement
    ): ?WhoGrowthReference {

        if (!$this->validPositiveNumber($measurement)) {
            return null;
        }

        return WhoGrowthReference::where(
            'indicator',
            $indicator
        )
        ->where(
            'sex',
            strtolower($sex)
        )
        ->whereNotNull('measurement_value')
        ->orderByRaw(
            'ABS(measurement_value - ?)',
            [(float) $measurement]
        )
        ->first();
    }


    /**
     * WHO LMS z-score calculation.
     */
    private function calculateZScore(
        float $measurement,
        WhoGrowthReference $reference
    ): ?float {

        $l = (float) $reference->l;
        $m = (float) $reference->m;
        $s = (float) $reference->s;

        if (
            $measurement <= 0 ||
            $m <= 0 ||
            $s <= 0
        ) {
            return null;
        }

        if ($l == 0.0) {

            return log(
                $measurement / $m
            ) / $s;
        }

        return (
            pow(
                $measurement / $m,
                $l
            ) - 1
        ) / (
            $l * $s
        );
    }


    /**
     * Round z-score for storage/display.
     */
    private function roundZScore(
        $zscore
    ): ?float {

        if ($zscore === null) {
            return null;
        }

        return round(
            (float) $zscore,
            3
        );
    }


    /**
     * Weight-for-age classification.
     *
     * Weight-for-age is primarily used to identify
     * underweight. It is not the indicator used to
     * classify overweight/obesity.
     */
    private function classifyWeightForAge(
        ?float $zscore
    ): ?string {

        if ($zscore === null) {
            return null;
        }

        if ($zscore < -3) {
            return 'Severe Underweight';
        }

        if ($zscore < -2) {
            return 'Moderate Underweight';
        }

        return 'Normal';
    }


    /**
     * Length/height-for-age classification.
     */
    private function classifyHeightForAge(
        ?float $zscore
    ): ?string {

        if ($zscore === null) {
            return null;
        }

        if ($zscore < -3) {
            return 'Severe Stunting';
        }

        if ($zscore < -2) {
            return 'Moderate Stunting';
        }

        return 'Normal';
    }


    /**
     * Weight-for-length/height classification
     * for children under five.
     */
    private function classifyWeightForLengthHeight(
        ?float $zscore
    ): ?string {

        if ($zscore === null) {
            return null;
        }

        if ($zscore < -3) {
            return 'Severe Wasting';
        }

        if ($zscore < -2) {
            return 'Wasting';
        }

        if ($zscore > 3) {
            return 'Obese';
        }

        if ($zscore > 2) {
            return 'Overweight';
        }

        return 'Normal';
    }


    /**
     * BMI-for-age classification for children
     * under five.
     */
    private function classifyBmiForAge(
        ?float $zscore
    ): ?string {

        if ($zscore === null) {
            return null;
        }

        if ($zscore < -3) {
            return 'Severe Thinness';
        }

        if ($zscore < -2) {
            return 'Thinness';
        }

        if ($zscore > 3) {
            return 'Obese';
        }

        if ($zscore > 2) {
            return 'Overweight';
        }

        if ($zscore > 1) {
            return 'At Risk of Overweight';
        }

        return 'Normal';
    }


    /**
     * MUAC screening eligibility.
     */
    private function isMuacAssessmentEligible(
        $ageInMonths
    ): bool {

        if ($ageInMonths === null) {
            return false;
        }

        return $ageInMonths >= 6 &&
               $ageInMonths <= 59;
    }


    /**
     * MUAC classification.
     *
     * MUAC is recorded in centimetres.
     */
    private function assessMuac(
        $ageInMonths,
        $muac
    ): string {

        if (
            !$this->isMuacAssessmentEligible(
                $ageInMonths
            )
        ) {
            return 'Not Assessed';
        }

        if (
            $muac === null ||
            $muac === '' ||
            !is_numeric($muac)
        ) {
            return 'Not Assessed';
        }

        $muac = (float) $muac;

        // Basic measurement-quality guard. MUAC values outside this
        // plausible recording range should not be classified as normal.
        if ($muac < 5 || $muac > 30) {
            return 'Invalid Measurement';
        }

        if ($muac < 11.5) {
            return 'Severe Malnutrition';
        }

        if ($muac < 12.5) {
            return 'Moderate Malnutrition';
        }

        return 'Normal';
    }


    /**
     * Determine the overall screening status.
     *
     * The most serious relevant finding takes priority.
     */
    private function determineOverallStatus(
        ?string $weightForAgeStatus,
        ?string $heightForAgeStatus,
        ?string $weightForLengthStatus,
        ?string $weightForHeightStatus,
        ?string $bmiForAgeStatus,
        ?string $muacStatus
    ): string {

        $statuses = [
            $weightForAgeStatus,
            $heightForAgeStatus,
            $weightForLengthStatus,
            $weightForHeightStatus,
            $bmiForAgeStatus,
            $muacStatus,
        ];

        /*
         * Severe wasting / severe MUAC /
         * severe stunting / severe underweight.
         */
        foreach ($statuses as $status) {

            if (in_array(
                $status,
                [
                    'Severe Wasting',
                    'Severe Malnutrition',
                    'Severe Stunting',
                    'Severe Underweight',
                    'Severe Thinness',
                ],
                true
            )) {
                return 'Severe Malnutrition';
            }
        }

        /*
         * Moderate wasting / MUAC / stunting /
         * underweight.
         */
        foreach ($statuses as $status) {

            if (in_array(
                $status,
                [
                    'Wasting',
                    'Moderate Malnutrition',
                    'Moderate Stunting',
                    'Moderate Underweight',
                    'Thinness',
                ],
                true
            )) {
                return 'Moderate Malnutrition';
            }
        }

        /*
         * Excess weight is flagged as a nutritional
         * risk rather than being labelled malnutrition.
         */
        foreach ($statuses as $status) {

            if (in_array(
                $status,
                [
                    'Obese',
                    'Overweight',
                    'At Risk of Overweight',
                ],
                true
            )) {
                return 'At Risk';
            }
        }

        return 'Normal';
    }


    /**
     * Determine whether clinical attention is required.
     */
    private function requiresAttention(
        string $overallStatus,
        ?float $weightForAgeZscore,
        ?float $heightForAgeZscore,
        ?float $weightForLengthZscore,
        ?float $weightForHeightZscore,
        ?float $bmiForAgeZscore,
        string $muacStatus
    ): bool {

        if (
            in_array(
                $overallStatus,
                [
                    'At Risk',
                    'Moderate Malnutrition',
                    'Severe Malnutrition',
                ],
                true
            )
        ) {
            return true;
        }

        /*
         * WHO extreme-value flags.
         *
         * These indicate that the measurement should
         * be reviewed rather than silently trusted.
         */
        if (
            $heightForAgeZscore !== null &&
            (
                $heightForAgeZscore < -6 ||
                $heightForAgeZscore > 6
            )
        ) {
            return true;
        }

        if (
            $weightForLengthZscore !== null &&
            (
                $weightForLengthZscore < -5 ||
                $weightForLengthZscore > 5
            )
        ) {
            return true;
        }

        if (
            $weightForHeightZscore !== null &&
            (
                $weightForHeightZscore < -5 ||
                $weightForHeightZscore > 5
            )
        ) {
            return true;
        }

        if (
            $weightForAgeZscore !== null &&
            (
                $weightForAgeZscore < -6 ||
                $weightForAgeZscore > 5
            )
        ) {
            return true;
        }

        if (
            $bmiForAgeZscore !== null &&
            (
                $bmiForAgeZscore < -5 ||
                $bmiForAgeZscore > 5
            )
        ) {
            return true;
        }

        if (
            in_array(
                $muacStatus,
                [
                    'Moderate Malnutrition',
                    'Severe Malnutrition',
                    'Invalid Measurement',
                ],
                true
            )
        ) {
            return true;
        }

        return false;
    }


    /**
     * Build human-readable assessment notes.
     */
    private function buildAssessmentNotes(
        string $overallStatus,
        bool $requiresAttention,
        ?float $weightForAgeZscore,
        ?float $heightForAgeZscore,
        ?float $weightForLengthZscore,
        ?float $weightForHeightZscore,
        ?float $bmiForAgeZscore,
        string $muacStatus
    ): string {

        $notes = [];

        if ($heightForAgeZscore !== null) {

            if ($heightForAgeZscore < -3) {
                $notes[] =
                    'Height-for-age is in the severe stunting range.';
            } elseif ($heightForAgeZscore < -2) {
                $notes[] =
                    'Height-for-age is in the stunting range.';
            }
        }

        $weightForSize =
            $weightForLengthZscore ??
            $weightForHeightZscore;

        if ($weightForSize !== null) {

            if ($weightForSize < -3) {
                $notes[] =
                    'Weight-for-length/height is in the severe wasting range.';
            } elseif ($weightForSize < -2) {
                $notes[] =
                    'Weight-for-length/height is in the wasting range.';
            } elseif ($weightForSize > 3) {
                $notes[] =
                    'Weight-for-length/height is above the obesity threshold.';
            } elseif ($weightForSize > 2) {
                $notes[] =
                    'Weight-for-length/height is above the overweight threshold.';
            }
        }

        if ($bmiForAgeZscore !== null) {

            if ($bmiForAgeZscore > 3) {
                $notes[] =
                    'BMI-for-age is above the obesity threshold.';
            } elseif ($bmiForAgeZscore > 2) {
                $notes[] =
                    'BMI-for-age is above the overweight threshold.';
            } elseif ($bmiForAgeZscore > 1) {
                $notes[] =
                    'BMI-for-age indicates risk of overweight.';
            }
        }

        if (
            $muacStatus ===
            'Severe Malnutrition'
        ) {
            $notes[] =
                'MUAC is below the severe screening threshold.';
        } elseif (
            $muacStatus ===
            'Moderate Malnutrition'
        ) {
            $notes[] =
                'MUAC is in the moderate screening range.';
        } elseif (
            $muacStatus ===
            'Invalid Measurement'
        ) {
            $notes[] =
                'The recorded MUAC measurement is outside the configured plausible range and should be reviewed.';
        }

        /*
         * Extreme-value quality flag.
         */
        if (
            $heightForAgeZscore !== null &&
            (
                $heightForAgeZscore < -6 ||
                $heightForAgeZscore > 6
            )
        ) {
            $notes[] =
                'The height-for-age z-score is outside the WHO recommended '
                . 'flag range and the recorded measurement should be reviewed.';
        }

        if (
            $weightForAgeZscore !== null &&
            (
                $weightForAgeZscore < -6 ||
                $weightForAgeZscore > 5
            )
        ) {
            $notes[] =
                'The weight-for-age z-score is outside the WHO recommended '
                . 'flag range and the recorded measurement should be reviewed.';
        }

        if (
            $weightForSize !== null &&
            (
                $weightForSize < -5 ||
                $weightForSize > 5
            )
        ) {
            $notes[] =
                'The weight-for-length/height z-score is outside the WHO '
                . 'recommended flag range and the measurement should be reviewed.';
        }

        if (
            $bmiForAgeZscore !== null &&
            (
                $bmiForAgeZscore < -5 ||
                $bmiForAgeZscore > 5
            )
        ) {
            $notes[] =
                'The BMI-for-age z-score is outside the WHO recommended '
                . 'flag range and the measurement should be reviewed.';
        }

        if (empty($notes)) {

            if ($overallStatus === 'Normal') {

                return
                    'The available growth indicators are within the '
                    . 'configured screening ranges.';
            }

            return
                'One or more growth indicators require further '
                . 'assessment based on the current screening results.';
        }

        return implode(' ', $notes);
    }
}