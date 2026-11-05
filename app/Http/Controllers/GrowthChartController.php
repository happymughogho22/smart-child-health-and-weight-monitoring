<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Child;
use App\Models\WhoGrowthReference;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class GrowthChartController extends Controller
{

public function listChildren(Request $request)
{
    // Get the search input.
    $search = trim((string) $request->input('search', ''));

    /*
    |--------------------------------------------------------------------------
    | Summary metrics
    |--------------------------------------------------------------------------
    */

    // 1. Total children registered in the system.
    $totalChildren = Child::count();

    // 2. Children who have at least one health record.
    $monitoredChildren = Child::has('healthRecords')->count();

    // 3. Total growth assessments recorded.
    $totalGrowthAssessments = \App\Models\GrowthAssessment::count();

    // 4. Children whose latest growth assessment requires attention.
    $attentionChildren = Child::with([
        'growthAssessments.healthRecord'
    ])
        ->get()
        ->filter(function ($child) {

            $latestAssessment = $child->growthAssessments
                ->sortBy(function ($assessment) {

                    return $assessment->healthRecord?->visit_date
                        ?->timestamp ?? $assessment->id;

                })
                ->last();

            return $latestAssessment
                && $latestAssessment->requires_attention === true;
        })
        ->count();

    /*
    |--------------------------------------------------------------------------
    | Children listing and search
    |--------------------------------------------------------------------------
    */

    $children = Child::query()
        ->when($search !== '', function ($query) use ($search) {

            $query->where(function ($q) use ($search) {

                $q->where('child_id', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");

            });

        })
        ->orderBy('first_name')
        ->orderBy('last_name')
        ->paginate(10)
        ->withQueryString();

    /*
    |--------------------------------------------------------------------------
    | Return the Growth Charts overview
    |--------------------------------------------------------------------------
    */

    return view('children.growth-charts-index', compact(
        'children',
        'search',
        'totalChildren',
        'monitoredChildren',
        'attentionChildren',
        'totalGrowthAssessments'
    ));
}

    /**
     * Display growth charts for a specific child.
     */
    public function show(Child $child)
    {
        /*
        |--------------------------------------------------------------------------
        | Load health records and growth assessments
        |--------------------------------------------------------------------------
        */

        $child->load([
            'healthRecords' => function ($query) {
                $query
                    ->with('growthAssessment')
                    ->orderBy('visit_date', 'asc');
            },
        ]);

        /*
        |--------------------------------------------------------------------------
        | Records
        |--------------------------------------------------------------------------
        |
        | The Blade view uses $records.
        |
        */

        $records = $child->healthRecords;

        /*
        |--------------------------------------------------------------------------
        | Latest record
        |--------------------------------------------------------------------------
        */

        $latestRecord = $records
            ->sortByDesc(function ($record) {
                return $record->visit_date;
            })
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Latest assessment
        |--------------------------------------------------------------------------
        */

        $latestAssessment = $latestRecord
            ? $latestRecord->growthAssessment
            : null;

        /*
        |--------------------------------------------------------------------------
        | Child's measurement data
        |--------------------------------------------------------------------------
        |
        | This is the structure expected by the JavaScript charts.
        |
        */

        $measurementData = $records
            ->map(function ($record) use ($child) {

                $visitDate = $record->visit_date
                    ? Carbon::parse($record->visit_date)
                    : null;

                $dateOfBirth = $child->date_of_birth
                    ? Carbon::parse($child->date_of_birth)
                    : null;

                /*
                |--------------------------------------------------------------
                | Age
                |--------------------------------------------------------------
                |
                | We use completed months for the chart's x-axis.
                |
                */

                $ageMonths = null;

                if ($visitDate && $dateOfBirth) {
                    $ageMonths = $dateOfBirth->diffInMonths(
                        $visitDate
                    );
                }

                /*
                |--------------------------------------------------------------
                | Measurement type
                |--------------------------------------------------------------
                */

                $measurementType = $record->measurement_type ?? null;

                if (
                    $measurementType !== 'length' &&
                    $measurementType !== 'height'
                ) {
                    if (
                        $ageMonths !== null &&
                        $ageMonths < 24
                    ) {
                        $measurementType = 'length';
                    } else {
                        $measurementType = 'height';
                    }
                }

                /*
                |--------------------------------------------------------------
                | BMI
                |--------------------------------------------------------------
                |
                | Prefer the BMI stored in the health record.
                | If it is not stored, calculate it from weight and height.
                |
                */

                $bmi = null;

                if (
                    $record->bmi !== null &&
                    is_numeric($record->bmi)
                ) {
                    $bmi = (float) $record->bmi;
                } elseif (
                    $record->weight !== null &&
                    $record->height !== null &&
                    is_numeric($record->weight) &&
                    is_numeric($record->height) &&
                    (float) $record->weight > 0 &&
                    (float) $record->height > 0
                ) {
                    $heightMetres =
                        (float) $record->height / 100;

                    $bmi = round(
                        (float) $record->weight /
                        ($heightMetres * $heightMetres),
                        2
                    );
                }

                return [
                    'date' => $visitDate
                        ? $visitDate->format('d M Y')
                        : null,

                    'age_months' => $ageMonths,

                    'age' => $ageMonths !== null
                        ? number_format($ageMonths, 1) . ' months'
                        : null,

                    'measurement_type' =>
                        $measurementType,

                    'weight' => $record->weight !== null
                        ? (float) $record->weight
                        : null,

                    'height' => $record->height !== null
                        ? (float) $record->height
                        : null,

                    'bmi' => $bmi,

                    'muac' => $record->muac !== null
                        ? (float) $record->muac
                        : null,
                ];
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | WHO reference curves
        |--------------------------------------------------------------------------
        |
        | The Blade page expects:
        |
        | [
        |     '-3' => [
        |         ['x' => ..., 'y' => ...],
        |         ...
        |     ],
        |     '-2' => [...],
        |     '0'  => [...],
        |     '2'  => [...],
        |     '3'  => [...]
        | ]
        |
        | WHO reference data comes from the existing
        | who_growth_references table/model.
        |
        */

        $zScores = [
            -3,
            -2,
            0,
            2,
            3,
        ];

        /*
        |--------------------------------------------------------------------------
        | Weight-for-Age
        |--------------------------------------------------------------------------
        */

        $weightForAgeReferences =
            $this->buildAgeReferenceCurves(
                'weight_for_age',
                $child->sex,
                $zScores
            );

        /*
        |--------------------------------------------------------------------------
        | Length/Height-for-Age
        |--------------------------------------------------------------------------
        */

        $heightForAgeReferences =
            $this->buildAgeReferenceCurves(
                'length_height_for_age',
                $child->sex,
                $zScores
            );

        /*
        |--------------------------------------------------------------------------
        | BMI-for-Age
        |--------------------------------------------------------------------------
        */

        $bmiForAgeReferences =
            $this->buildAgeReferenceCurves(
                'bmi_for_age',
                $child->sex,
                $zScores
            );

        /*
        |--------------------------------------------------------------------------
        | Weight-for-Length
        |--------------------------------------------------------------------------
        */

        $weightForLengthReferences =
            $this->buildMeasurementReferenceCurves(
                'weight_for_length',
                $child->sex,
                $zScores
            );

        /*
        |--------------------------------------------------------------------------
        | Weight-for-Height
        |--------------------------------------------------------------------------
        */

        $weightForHeightReferences =
            $this->buildMeasurementReferenceCurves(
                'weight_for_height',
                $child->sex,
                $zScores
            );

        /*
        |--------------------------------------------------------------------------
        | Decide which weight-for-size chart should be displayed
        |--------------------------------------------------------------------------
        |
        | Under 24 completed months:
        |     Weight-for-Length
        |
        | 24 months and above:
        |     Weight-for-Height
        |
        */

        $showWeightForLength = $measurementData
            ->contains(function ($record) {

                return
                    $record['age_months'] !== null &&
                    $record['age_months'] < 24 &&
                    $record['weight'] !== null &&
                    $record['height'] !== null;
            });

        $showWeightForHeight = $measurementData
            ->contains(function ($record) {

                return
                    $record['age_months'] !== null &&
                    $record['age_months'] >= 24 &&
                    $record['weight'] !== null &&
                    $record['height'] !== null;
            });

        /*
        |--------------------------------------------------------------------------
        | Return view
        |--------------------------------------------------------------------------
        */

        return view(
            'children.growth-charts',
            [
                'child' =>
                    $child,

                'records' =>
                    $records,

                'measurementData' =>
                    $measurementData,

                'latestRecord' =>
                    $latestRecord,

                'latestAssessment' =>
                    $latestAssessment,

                'weightForAgeReferences' =>
                    $weightForAgeReferences,

                'heightForAgeReferences' =>
                    $heightForAgeReferences,

                'weightForLengthReferences' =>
                    $weightForLengthReferences,

                'weightForHeightReferences' =>
                    $weightForHeightReferences,

                'bmiForAgeReferences' =>
                    $bmiForAgeReferences,

                'showWeightForLength' =>
                    $showWeightForLength,

                'showWeightForHeight' =>
                    $showWeightForHeight,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BUILD AGE-BASED WHO CURVES
    |--------------------------------------------------------------------------
    |
    | Used for:
    |
    | - Weight-for-Age
    | - Length/Height-for-Age
    | - BMI-for-Age
    |
    */

    private function buildAgeReferenceCurves(
        string $indicator,
        string $sex,
        array $zScores
    ): array {

        $result = [];

        /*
        |----------------------------------------------------------------------
        | Normalize sex
        |----------------------------------------------------------------------
        */

        $sex = strtolower(trim($sex));

        /*
        |----------------------------------------------------------------------
        | Get all references for this indicator and sex
        |----------------------------------------------------------------------
        */

        $references = WhoGrowthReference::query()
            ->where('indicator', $indicator)
            ->where('sex', $sex)
            ->whereNotNull('age_days')
            ->whereNotNull('l')
            ->whereNotNull('m')
            ->whereNotNull('s')
            ->orderBy('age_days')
            ->get();

        /*
        |----------------------------------------------------------------------
        | Create empty arrays
        |----------------------------------------------------------------------
        */

        foreach ($zScores as $z) {
            $result[(string) $z] = [];
        }

        /*
        |----------------------------------------------------------------------
        | Build each curve
        |----------------------------------------------------------------------
        */

        foreach ($references as $reference) {

            $ageDays = (float) $reference->age_days;

            /*
            | WHO reference age in months.
            |
            | 30.4375 is the average number of days per month.
            |
            */

            $ageMonths =
                $ageDays / 30.4375;

            $l = (float) $reference->l;
            $m = (float) $reference->m;
            $s = (float) $reference->s;

            if (
                $m <= 0 ||
                $s <= 0
            ) {
                continue;
            }

            foreach ($zScores as $z) {

                $value =
                    $this->measurementFromZScore(
                        (float) $z,
                        $l,
                        $m,
                        $s
                    );

                if ($value === null) {
                    continue;
                }

                $result[(string) $z][] = [
                    'x' =>
                        round($ageMonths, 3),

                    'y' =>
                        round($value, 3),
                ];
            }
        }

        return $result;
    }


    /*
    |--------------------------------------------------------------------------
    | BUILD MEASUREMENT-BASED WHO CURVES
    |--------------------------------------------------------------------------
    |
    | Used for:
    |
    | - Weight-for-Length
    | - Weight-for-Height
    |
    */

    private function buildMeasurementReferenceCurves(
        string $indicator,
        string $sex,
        array $zScores
    ): array {

        $result = [];

        $sex = strtolower(trim($sex));

        /*
        |----------------------------------------------------------------------
        | Get references
        |----------------------------------------------------------------------
        */

        $references = WhoGrowthReference::query()
            ->where('indicator', $indicator)
            ->where('sex', $sex)
            ->whereNotNull('measurement_value')
            ->whereNotNull('l')
            ->whereNotNull('m')
            ->whereNotNull('s')
            ->orderBy('measurement_value')
            ->get();

        /*
        |----------------------------------------------------------------------
        | Empty curve arrays
        |----------------------------------------------------------------------
        */

        foreach ($zScores as $z) {
            $result[(string) $z] = [];
        }

        /*
        |----------------------------------------------------------------------
        | Build curves
        |----------------------------------------------------------------------
        */

        foreach ($references as $reference) {

            $measurement =
                (float) $reference->measurement_value;

            $l = (float) $reference->l;
            $m = (float) $reference->m;
            $s = (float) $reference->s;

            if (
                $measurement <= 0 ||
                $m <= 0 ||
                $s <= 0
            ) {
                continue;
            }

            foreach ($zScores as $z) {

                $weight =
                    $this->measurementFromZScore(
                        (float) $z,
                        $l,
                        $m,
                        $s
                    );

                if ($weight === null) {
                    continue;
                }

                $result[(string) $z][] = [
                    'x' =>
                        round($measurement, 3),

                    'y' =>
                        round($weight, 3),
                ];
            }
        }

        return $result;
    }

/**
 * Display all children so the user can select one
 * to view their growth charts.
 */
public function index(Request $request)
{
    $search = trim($request->input('search', ''));

    $children = Child::query()
        ->when($search !== '', function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('child_id', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        })
        ->orderBy('child_id')
        ->paginate(10)
        ->withQueryString();

    return view('children.growth-charts-index', compact(
        'children',
        'search'
    ));
}

    /*
    |--------------------------------------------------------------------------
    | CONVERT Z-SCORE TO MEASUREMENT USING WHO LMS
    |--------------------------------------------------------------------------
    |
    | WHO uses the LMS method.
    |
    | z = (((X/M)^L) - 1) / (L*S)
    |
    | Therefore:
    |
    | X = M * (1 + L*S*z)^(1/L)
    |
    | If L = 0:
    |
    | X = M * exp(S*z)
    |
    */

    private function measurementFromZScore(
        float $z,
        float $l,
        float $m,
        float $s
    ): ?float {

        if (
            $m <= 0 ||
            $s <= 0
        ) {
            return null;
        }

        /*
        |----------------------------------------------------------------------
        | Special case: L = 0
        |----------------------------------------------------------------------
        */

        if (abs($l) < 0.0000001) {

            return
                $m *
                exp($s * $z);
        }

        /*
        |----------------------------------------------------------------------
        | Standard LMS calculation
        |----------------------------------------------------------------------
        */

        $base =
            1 +
            ($l * $s * $z);

        /*
        |----------------------------------------------------------------------
        | Invalid mathematical result
        |----------------------------------------------------------------------
        */

        if ($base <= 0) {
            return null;
        }

        return
            $m *
            pow(
                $base,
                1 / $l
            );
    }
}