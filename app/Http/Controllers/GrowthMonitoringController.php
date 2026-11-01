<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\GrowthAssessment;
use Illuminate\Http\Request;

class GrowthMonitoringController extends Controller
{
    /**
     * Display the growth monitoring dashboard.
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        $search = $request->input('search');


        /*
        |--------------------------------------------------------------------------
        | CHILDREN FOR MAIN TABLE
        |--------------------------------------------------------------------------
        */

        $children = Child::query()

            ->when($search, function ($query, $search) {

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'first_name',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'last_name',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'child_id',
                        'like',
                        "%{$search}%"
                    );

                });

            })

            ->with([
                'healthRecords' => function ($query) {

                    $query
                        ->with('growthAssessment')
                        ->orderByDesc('visit_date');

                }
            ])

            ->latest()

            ->paginate(10)

            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | ALL CHILDREN WITH RECORDS
        |--------------------------------------------------------------------------
        |
        | This is a COLLECTION.
        | Do NOT use this variable for arithmetic.
        |--------------------------------------------------------------------------
        */

        $childrenWithRecords = Child::query()

            ->with([
                'healthRecords' => function ($query) {

                    $query
                        ->with('growthAssessment')
                        ->orderByDesc('visit_date');

                }
            ])

            ->get();


        /*
        |--------------------------------------------------------------------------
        | TOTAL CHILDREN
        |--------------------------------------------------------------------------
        */

        $totalChildren = Child::count();


        /*
        |--------------------------------------------------------------------------
        | MONITORED CHILDREN
        |--------------------------------------------------------------------------
        |
        | This is an INTEGER.
        | It is safe to use in calculations.
        |--------------------------------------------------------------------------
        */

        $monitoredChildren = Child::whereHas(
            'healthRecords'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | CHILDREN REQUIRING ATTENTION
        |--------------------------------------------------------------------------
        |
        | This must be a COLLECTION because the Blade page
        | displays the actual children in a list.
        |--------------------------------------------------------------------------
        */

        $childrenRequiringAttention = collect();


        foreach ($childrenWithRecords as $child) {

            /*
            |--------------------------------------------------------------------------
            | GET LATEST HEALTH RECORD
            |--------------------------------------------------------------------------
            */

            $latestRecord = $child->healthRecords
                ->sortByDesc(function ($record) {
                    return $record->visit_date;
                })
                ->first();


            /*
            |--------------------------------------------------------------------------
            | SKIP IF NO HEALTH RECORD
            |--------------------------------------------------------------------------
            */

            if (!$latestRecord) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | GET GROWTH ASSESSMENT
            |--------------------------------------------------------------------------
            */

            $assessment = $latestRecord->growthAssessment;


            /*
            |--------------------------------------------------------------------------
            | SKIP IF NO ASSESSMENT
            |--------------------------------------------------------------------------
            */

            if (!$assessment) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | DEFAULT
            |--------------------------------------------------------------------------
            */

            $needsAttention = false;


            /*
            |--------------------------------------------------------------------------
            | CHECK STATUS
            |--------------------------------------------------------------------------
            */

            if (
                isset($assessment->status)
                &&
                in_array(
                    strtolower((string) $assessment->status),
                    [
                        'attention',
                        'at risk',
                        'at_risk',
                        'danger',
                        'severe',
                        'malnutrition',
                        'moderate',
                        'critical',
                    ],
                    true
                )
            ) {

                $needsAttention = true;

            }


            /*
            |--------------------------------------------------------------------------
            | CHECK OVERALL STATUS
            |--------------------------------------------------------------------------
            */

            if (
                isset($assessment->overall_status)
                &&
                in_array(
                    strtolower((string) $assessment->overall_status),
                    [
                        'attention',
                        'at risk',
                        'at_risk',
                        'danger',
                        'severe',
                        'malnutrition',
                        'moderate',
                        'critical',
                    ],
                    true
                )
            ) {

                $needsAttention = true;

            }


            /*
            |--------------------------------------------------------------------------
            | CHECK RISK LEVEL
            |--------------------------------------------------------------------------
            */

            if (
                isset($assessment->risk_level)
                &&
                in_array(
                    strtolower((string) $assessment->risk_level),
                    [
                        'attention',
                        'at risk',
                        'at_risk',
                        'high',
                        'severe',
                        'danger',
                        'critical',
                    ],
                    true
                )
            ) {

                $needsAttention = true;

            }


            /*
            |--------------------------------------------------------------------------
            | ADD CHILD TO ATTENTION COLLECTION
            |--------------------------------------------------------------------------
            */

            if ($needsAttention) {

                $childrenRequiringAttention->push($child);

            }

        }


        /*
        |--------------------------------------------------------------------------
        | ATTENTION COUNT
        |--------------------------------------------------------------------------
        */

        $attentionChildren =
            $childrenRequiringAttention->count();


        /*
        |--------------------------------------------------------------------------
        | TOTAL GROWTH ASSESSMENTS
        |--------------------------------------------------------------------------
        */

        $totalGrowthAssessments =
            GrowthAssessment::count();


        /*
        |--------------------------------------------------------------------------
        | ALIAS
        |--------------------------------------------------------------------------
        */

        $totalAssessments =
            $totalGrowthAssessments;


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'growth-monitoring.index',
            compact(
                'children',
                'childrenWithRecords',
                'search',
                'totalChildren',
                'monitoredChildren',
                'attentionChildren',
                'childrenRequiringAttention',
                'totalGrowthAssessments',
                'totalAssessments'
            )
        );
    }
}