<?php

namespace App\Http\Controllers;
use App\Models\GrowthAssessment;
use App\Models\Child;
use Illuminate\Http\Request;

class ChildController extends Controller
{
    /**
     * Display all children.
     */
    
public function index(Request $request)
{
    $search = trim($request->input('search', ''));

    $children = \App\Models\Child::query()
        ->when($search !== '', function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('child_id', 'like', "%{$search}%");
            });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('children.index', compact('children', 'search'));
}

    /**
     * Show the form for registering a new child.
     */
    public function create()
    {
        return view('children.create');
    }

    /**
     * Store a newly registered child.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'child_id' => 'required|string|max:50|unique:children,child_id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'sex' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date|before_or_equal:today',
            'place_of_birth' => 'nullable|string|max:150',
            'guardian_name' => 'required|string|max:150',
            'guardian_phone' => 'nullable|string|max:30',
            'guardian_email' => ['nullable', 'email', 'max:255'],
            'address' => 'nullable|string|max:255',
        ]);

        Child::create($validated);

        return redirect()
            ->route('children.index')
            ->with('success', 'Child registered successfully.');
    }

    /**
     * Display a specific child's profile and growth history.
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
            }
        ]);

        /*
        |--------------------------------------------------------------------------
        | Growth records
        |--------------------------------------------------------------------------
        */

        $growthRecords = $child->healthRecords;

        /*
        |--------------------------------------------------------------------------
        | Child age
        |--------------------------------------------------------------------------
        */

        $ageInMonths = null;
        $ageLabel = 'Age not available';

        if ($child->date_of_birth) {
            $ageInMonths = (int) $child->date_of_birth->diffInMonths(now());

            $years = intdiv($ageInMonths, 12);
            $months = $ageInMonths % 12;

            if ($years > 0 && $months > 0) {
                $ageLabel = $years . ' year' . ($years !== 1 ? 's' : '')
                    . ' ' . $months . ' month' . ($months !== 1 ? 's' : '');
            } elseif ($years > 0) {
                $ageLabel = $years . ' year' . ($years !== 1 ? 's' : '');
            } else {
                $ageLabel = $months . ' month' . ($months !== 1 ? 's' : '');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Chart labels
        |--------------------------------------------------------------------------
        */

        $chartLabels = $growthRecords
            ->map(function ($record) {
                return $record->visit_date
                    ? $record->visit_date->format('d M Y')
                    : 'Unknown Date';
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Weight data
        |--------------------------------------------------------------------------
        */

        $weightData = $growthRecords
            ->map(function ($record) {
                return $record->weight !== null
                    ? (float) $record->weight
                    : null;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Height / Length data
        |--------------------------------------------------------------------------
        */

        $heightData = $growthRecords
            ->map(function ($record) {
                return $record->height !== null
                    ? (float) $record->height
                    : null;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | BMI data
        |--------------------------------------------------------------------------
        */

        $bmiData = $growthRecords
            ->map(function ($record) {
                return $record->bmi !== null
                    ? (float) $record->bmi
                    : null;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | MUAC data
        |--------------------------------------------------------------------------
        */

        $muacData = $growthRecords
            ->map(function ($record) {
                return $record->muac !== null
                    ? (float) $record->muac
                    : null;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Latest health record
        |--------------------------------------------------------------------------
        */

        $latestRecord = $growthRecords
            ->sortByDesc(function ($record) {
                return $record->visit_date;
            })
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Latest growth assessment
        |--------------------------------------------------------------------------
        */

        $latestGrowthAssessment = $latestRecord
            ? $latestRecord->growthAssessment
            : null;

        /*
        |--------------------------------------------------------------------------
        | Summary statistics
        |--------------------------------------------------------------------------
        */

        $totalVisits = $growthRecords->count();

        $totalAssessments = $growthRecords
            ->filter(function ($record) {
                return $record->growthAssessment !== null;
            })
            ->count();

        $attentionCount = $growthRecords
            ->filter(function ($record) {
                return $record->growthAssessment
                    && $record->growthAssessment->requires_attention;
            })
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Latest measurements
        |--------------------------------------------------------------------------
        */

        $latestWeight = $latestRecord?->weight !== null
            ? (float) $latestRecord->weight
            : null;

        $latestHeight = $latestRecord?->height !== null
            ? (float) $latestRecord->height
            : null;

        $latestBmi = $latestRecord?->bmi !== null
            ? (float) $latestRecord->bmi
            : null;

        $latestMuac = $latestRecord?->muac !== null
            ? (float) $latestRecord->muac
            : null;

        /*
        |--------------------------------------------------------------------------
        | Latest statuses
        |--------------------------------------------------------------------------
        */

        $latestNutritionStatus = $latestGrowthAssessment?->overall_status
            ?? 'Awaiting Assessment';

        $latestMonitoringStatus = $latestRecord?->monitoring_status
            ?? 'Awaiting Measurement';

        /*
        |--------------------------------------------------------------------------
        | Complete growth history
        |--------------------------------------------------------------------------
        |
        | This collection will be used by the growth history dashboard.
        | Each item contains the visit, measurements and WHO assessment.
        |
        */

        $growthHistory = $growthRecords
            ->map(function ($record) {
                $assessment = $record->growthAssessment;

                return [
                    'id' => $record->id,

                    'visit_date' => $record->visit_date
                        ? $record->visit_date->format('d M Y')
                        : 'Unknown Date',

                    'visit_date_raw' => $record->visit_date,

                    'weight' => $record->weight !== null
                        ? (float) $record->weight
                        : null,

                    'height' => $record->height !== null
                        ? (float) $record->height
                        : null,

                    'bmi' => $record->bmi !== null
                        ? (float) $record->bmi
                        : null,

                    'muac' => $record->muac !== null
                        ? (float) $record->muac
                        : null,

                    'temperature' => $record->temperature !== null
                        ? (float) $record->temperature
                        : null,

                    'measurement_type' => $record->measurement_type,

                    'monitoring_status' => $record->monitoring_status
                        ?? 'Awaiting Measurement',

                    'nutrition_status' => $record->nutrition_status
                        ?? $assessment?->overall_status
                        ?? 'Not Assessed',

                    'overall_status' => $assessment?->overall_status
                        ?? 'Not Assessed',

                    'requires_attention' => (bool) (
                        $assessment?->requires_attention ?? false
                    ),

                    'assessment_notes' => $assessment?->assessment_notes,

                    'weight_for_age_zscore' => $assessment?->weight_for_age_zscore !== null
                        ? (float) $assessment->weight_for_age_zscore
                        : null,

                    'height_for_age_zscore' => $assessment?->height_for_age_zscore !== null
                        ? (float) $assessment->height_for_age_zscore
                        : null,

                    'weight_for_length_zscore' => $assessment?->weight_for_length_zscore !== null
                        ? (float) $assessment->weight_for_length_zscore
                        : null,

                    'weight_for_height_zscore' => $assessment?->weight_for_height_zscore !== null
                        ? (float) $assessment->weight_for_height_zscore
                        : null,

                    'bmi_for_age_zscore' => $assessment?->bmi_for_age_zscore !== null
                        ? (float) $assessment->bmi_for_age_zscore
                        : null,

                    'muac_status' => $assessment?->muac_status,

                    'follow_up_date' => $record->follow_up_date
                        ? $record->follow_up_date->format('d M Y')
                        : null,
                ];
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Return child profile
        |--------------------------------------------------------------------------
        */

        return view(
            'children.show',
            compact(
                'child',

                // Existing profile/chart data
                'chartLabels',
                'weightData',
                'heightData',
                'bmiData',
                'muacData',

                // Existing latest-record data
                'latestRecord',
                'latestGrowthAssessment',

                // New growth-history dashboard data
                'ageInMonths',
                'ageLabel',
                'totalVisits',
                'totalAssessments',
                'attentionCount',
                'latestWeight',
                'latestHeight',
                'latestBmi',
                'latestMuac',
                'latestNutritionStatus',
                'latestMonitoringStatus',
                'growthHistory'
            )
        );
    }

    /**
     * Show the edit form.
     */
    public function edit(Child $child)
    {
        return view(
            'children.edit',
            compact('child')
        );
    }

    /**
     * Update a child's information.
     */
    public function update(Request $request, Child $child)
    {
        $validated = $request->validate([
            'child_id' => 'required|string|max:50|unique:children,child_id,' . $child->id,
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'sex' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date|before_or_equal:today',
            'place_of_birth' => 'nullable|string|max:150',
            'guardian_name' => 'required|string|max:150',
            'guardian_phone' => 'nullable|string|max:30',
            'guardian_email' => ['nullable', 'email', 'max:255'],
            'address' => 'nullable|string|max:255',
        ]);

        $child->update($validated);

        return redirect()
            ->route('children.show', $child)
            ->with('success', 'Child information updated successfully.');
    }

    /**
     * Delete a child.
     */
    public function destroy(Child $child)
    {
        $child->delete();

        return redirect()
            ->route('children.index')
            ->with('success', 'Child record deleted successfully.');
    }
}