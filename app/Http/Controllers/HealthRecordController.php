<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\HealthRecord;
use App\Services\GrowthAssessmentService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\ChildHealthReportMail;
use Carbon\Carbon;

class HealthRecordController extends Controller
{
    protected GrowthAssessmentService $growthAssessmentService;

    public function __construct(
        GrowthAssessmentService $growthAssessmentService
    ) {
        $this->growthAssessmentService = $growthAssessmentService;
    }

/**
 * Display weight records for all registered children.
 */

/**
 * Display weight records for all registered children.
 * Supports searching by child name, child ID, facility,
 * visit date, and nutrition status.
 */
public function all(Request $request)
{
    $search = trim((string) $request->query('search', ''));

    $query = HealthRecord::with('child');

    if ($search !== '') {
        $query->where(function ($recordQuery) use ($search) {
            // Search by child information.
            $recordQuery->whereHas('child', function ($childQuery) use ($search) {
                $childQuery->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('child_id', 'like', "%{$search}%")
                    ->orWhereRaw(
                        "CONCAT(COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) LIKE ?",
                        ["%{$search}%"]
                    );
            });

            // Search by facility and nutrition status.
            $recordQuery->orWhere('facility_name', 'like', "%{$search}%")
                ->orWhere('nutrition_status', 'like', "%{$search}%");

            // Search by exact date when the search is a valid date.
            try {
                $date = \Carbon\Carbon::createFromFormat('Y-m-d', $search);

                if ($date && $date->format('Y-m-d') === $search) {
                    $recordQuery->orWhereDate('visit_date', $search);
                }
            } catch (\Throwable $exception) {
                // Non-date searches continue to match the other fields.
            }
        });
    }

    $healthRecords = $query
        ->orderByDesc('visit_date')
        ->orderByDesc('id')
        ->paginate(15)
        ->withQueryString();

    return view('weight-records.index', compact(
        'healthRecords',
        'search'
    ));
}

    /*
    |--------------------------------------------------------------------------
    | SHARE CHILD REPORT WITH GUARDIAN
    |--------------------------------------------------------------------------
    */

    /**
     * Share a child's health report by email.
     */
    public function shareReport(
        Request $request,
        Child $child,
        HealthRecord $healthRecord
    ) {
        /*
        |--------------------------------------------------------------------------
        | SECURITY CHECK
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $healthRecord->child_id === $child->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDATE EMAIL
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | LOAD REQUIRED DATA
        |--------------------------------------------------------------------------
        */

        $healthRecord->load([
            'child',
            'growthAssessment',
        ]);


        /*
        |--------------------------------------------------------------------------
        | SEND REPORT EMAIL
        |--------------------------------------------------------------------------
        */

        Mail::to($validated['email'])
            ->send(
                new ChildHealthReportMail(
                    $child,
                    $healthRecord
                )
            );


        /*
        |--------------------------------------------------------------------------
        | RETURN
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'success',
            'The child health report has been shared successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    /**
     * Display all health records for a child.
     */
    public function index(Child $child)
    {
        $healthRecords = $child->healthRecords()
            ->orderBy('visit_date', 'asc')
            ->get();

        return view(
            'health-records.index',
            compact(
                'child',
                'healthRecords'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    /**
     * Show the form for creating a new health record.
     */
    public function create(Child $child)
    {
        return view(
            'health-records.create',
            compact('child')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    /**
     * Store a newly created health record.
     */
    public function store(
        Request $request,
        Child $child
    ) {
        /*
        |--------------------------------------------------------------------------
        | VALIDATE INPUT
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'visit_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            'facility_name' => [
                'required',
                'string',
                'max:200',
            ],

            'health_worker_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'weight' => [
                'nullable',
                'numeric',
                'min:0',
                'max:200',
            ],

            'height' => [
                'nullable',
                'numeric',
                'min:0',
                'max:250',
            ],

            'measurement_type' => [
                'nullable',
                'required_with:height',
                Rule::in([
                    'length',
                    'height',
                ]),
            ],

            'temperature' => [
                'nullable',
                'numeric',
                'min:25',
                'max:45',
            ],

            'muac' => [
                'nullable',
                'numeric',
                'min:0',
                'max:50',
            ],

            'clinical_observations' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'doctor_remarks' => [
                'required',
                'string',
                'max:5000',
            ],

            'recommendations' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'follow_up_date' => [
                'nullable',
                'date',
                'after_or_equal:visit_date',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | RUN GROWTH ASSESSMENT
        |--------------------------------------------------------------------------
        */

        $assessment = $this->growthAssessmentService->assess(
            $child,
            $validated['visit_date'],
            $validated['weight'] ?? null,
            $validated['height'] ?? null,
            $validated['muac'] ?? null,
            $validated['measurement_type'] ?? null
        );


        /*
        |--------------------------------------------------------------------------
        | STORE CALCULATED VALUES
        |--------------------------------------------------------------------------
        */

        $validated['bmi'] =
            $assessment['bmi'];

        $validated['measurement_type'] =
            $assessment['measurement_type'];

        $validated['nutrition_status'] =
            $assessment['nutrition_status'];


        /*
        |--------------------------------------------------------------------------
        | MONITORING STATUS
        |--------------------------------------------------------------------------
        */

        if (
            isset($validated['weight']) &&
            $validated['weight'] > 0
        ) {
            $validated['monitoring_status'] =
                'Monitoring';
        } else {
            $validated['monitoring_status'] =
                'Awaiting Measurement';
        }


        /*
        |--------------------------------------------------------------------------
        | CHILD ID
        |--------------------------------------------------------------------------
        */

        $validated['child_id'] =
            $child->id;


        /*
        |--------------------------------------------------------------------------
        | CREATE HEALTH RECORD
        |--------------------------------------------------------------------------
        */

        $healthRecord =
            HealthRecord::create(
                $validated
            );


        /*
        |--------------------------------------------------------------------------
        | CREATE GROWTH ASSESSMENT
        |--------------------------------------------------------------------------
        */

        $this->createGrowthAssessment(
            $child,
            $healthRecord,
            $assessment
        );


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'health-records.index',
                $child
            )
            ->with(
                'success',
                'Health record successfully added.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    /**
     * Display a specific health record.
     */
    public function show(
        Child $child,
        HealthRecord $healthRecord
    ) {
        /*
        |--------------------------------------------------------------------------
        | SECURITY / DATA INTEGRITY
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $healthRecord->child_id === $child->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | LOAD CURRENT RECORD
        |--------------------------------------------------------------------------
        */

        $healthRecord->load([
            'child',
            'growthAssessment',
        ]);


        /*
        |--------------------------------------------------------------------------
        | LOAD COMPLETE CHILD HEALTH HISTORY
        |--------------------------------------------------------------------------
        */

        $childHealthRecords = HealthRecord::with([
            'growthAssessment',
        ])
            ->where(
                'child_id',
                $child->id
            )
            ->orderBy(
                'visit_date',
                'asc'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | PREPARE CHART DATA
        |--------------------------------------------------------------------------
        */

        $growthChartData = $childHealthRecords
            ->map(function ($record) {

                $assessment =
                    $record->growthAssessment;

                return [
                    'date' =>
                        Carbon::parse(
                            $record->visit_date
                        )->format('d M Y'),

                    'weight' =>
                        $record->weight !== null
                            ? (float) $record->weight
                            : null,

                    'height' =>
                        $record->height !== null
                            ? (float) $record->height
                            : null,

                    'bmi' =>
                        $record->bmi !== null
                            ? (float) $record->bmi
                            : null,

                    'muac' =>
                        $record->muac !== null
                            ? (float) $record->muac
                            : null,

                    'weight_for_age_zscore' =>
                        $assessment &&
                        $assessment->weight_for_age_zscore !== null
                            ? (float) $assessment->weight_for_age_zscore
                            : null,

                    'height_for_age_zscore' =>
                        $assessment &&
                        $assessment->height_for_age_zscore !== null
                            ? (float) $assessment->height_for_age_zscore
                            : null,

                    'bmi_for_age_zscore' =>
                        $assessment &&
                        $assessment->bmi_for_age_zscore !== null
                            ? (float) $assessment->bmi_for_age_zscore
                            : null,

                    'overall_status' =>
                        $assessment
                            ? $assessment->overall_status
                            : $record->nutrition_status,
                ];
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | GENERATE MONITORING RECOMMENDATIONS
        |--------------------------------------------------------------------------
        */

        $recommendations = [];

        $assessment =
            $healthRecord->growthAssessment;


        if ($assessment) {

            /*
            |--------------------------------------------------------------------------
            | OVERALL STATUS
            |--------------------------------------------------------------------------
            */

            $overallStatus = strtolower(
                trim(
                    (string) (
                        $assessment->overall_status ?? ''
                    )
                )
            );


            if (
                str_contains($overallStatus, 'severe') ||
                str_contains($overallStatus, 'critical') ||
                str_contains($overallStatus, 'danger')
            ) {

                $recommendations[] =
                    'The current growth screening contains a significant attention flag. '
                    . 'Arrange review by an appropriately qualified health professional '
                    . 'and follow the facility referral or follow-up procedure.';

            } elseif (
                str_contains($overallStatus, 'moderate') ||
                str_contains($overallStatus, 'risk') ||
                str_contains($overallStatus, 'warning')
            ) {

                $recommendations[] =
                    'The current growth screening indicates that closer monitoring may '
                    . 'be appropriate. Review the child according to the facility '
                    . 'follow-up schedule and consider professional assessment where necessary.';

            } elseif (
                str_contains($overallStatus, 'normal')
            ) {

                $recommendations[] =
                    'The current growth screening does not contain an immediate '
                    . 'attention flag. Continue routine growth monitoring and scheduled visits.';
            }


            /*
            |--------------------------------------------------------------------------
            | WEIGHT-FOR-AGE
            |--------------------------------------------------------------------------
            */

            $weightAgeStatus = strtolower(
                trim(
                    (string) (
                        $assessment->weight_for_age_status ?? ''
                    )
                )
            );


            if (
                str_contains($weightAgeStatus, 'severe') ||
                str_contains($weightAgeStatus, 'moderate') ||
                str_contains($weightAgeStatus, 'low') ||
                str_contains($weightAgeStatus, 'risk')
            ) {

                $recommendations[] =
                    "Review the weight-for-age finding together with the child's "
                    . 'growth history and other clinical information.';
            }


            /*
            |--------------------------------------------------------------------------
            | HEIGHT-FOR-AGE
            |--------------------------------------------------------------------------
            */

            $heightAgeStatus = strtolower(
                trim(
                    (string) (
                        $assessment->height_for_age_status ?? ''
                    )
                )
            );


            if (
                str_contains($heightAgeStatus, 'severe') ||
                str_contains($heightAgeStatus, 'moderate') ||
                str_contains($heightAgeStatus, 'low') ||
                str_contains($heightAgeStatus, 'stunt')
            ) {

                $recommendations[] =
                    "Review the height/length-for-age finding and monitor the "
                    . "child's growth pattern over subsequent visits.";
            }


            /*
            |--------------------------------------------------------------------------
            | WEIGHT-FOR-HEIGHT
            |--------------------------------------------------------------------------
            */

            $weightHeightStatus = strtolower(
                trim(
                    (string) (
                        $assessment->weight_for_height_status ?? ''
                    )
                )
            );


            if (
                str_contains($weightHeightStatus, 'severe') ||
                str_contains($weightHeightStatus, 'moderate') ||
                str_contains($weightHeightStatus, 'low') ||
                str_contains($weightHeightStatus, 'risk') ||
                str_contains($weightHeightStatus, 'wast')
            ) {

                $recommendations[] =
                    "Review the weight-for-height finding together with the child's "
                    . 'overall growth and nutritional assessment.';
            }


            /*
            |--------------------------------------------------------------------------
            | WEIGHT-FOR-LENGTH
            |--------------------------------------------------------------------------
            */

            $weightLengthStatus = strtolower(
                trim(
                    (string) (
                        $assessment->weight_for_length_status ?? ''
                    )
                )
            );


            if (
                str_contains($weightLengthStatus, 'severe') ||
                str_contains($weightLengthStatus, 'moderate') ||
                str_contains($weightLengthStatus, 'low') ||
                str_contains($weightLengthStatus, 'risk') ||
                str_contains($weightLengthStatus, 'wast')
            ) {

                $recommendations[] =
                    "Review the weight-for-length finding together with the child's "
                    . 'overall growth and nutritional assessment.';
            }


            /*
            |--------------------------------------------------------------------------
            | MUAC
            |--------------------------------------------------------------------------
            */

            $muacStatus = strtolower(
                trim(
                    (string) (
                        $assessment->muac_status ?? ''
                    )
                )
            );


            if (
                str_contains($muacStatus, 'severe') ||
                str_contains($muacStatus, 'moderate') ||
                str_contains($muacStatus, 'risk') ||
                str_contains($muacStatus, 'danger')
            ) {

                $recommendations[] =
                    "Review the MUAC screening result with the child's overall "
                    . 'clinical and nutritional assessment and follow the appropriate '
                    . 'facility procedure.';
            }


            /*
            |--------------------------------------------------------------------------
            | BMI-FOR-AGE
            |--------------------------------------------------------------------------
            */

            $bmiAgeStatus = strtolower(
                trim(
                    (string) (
                        $assessment->bmi_for_age_status ?? ''
                    )
                )
            );


            if (
                str_contains($bmiAgeStatus, 'risk') ||
                str_contains($bmiAgeStatus, 'overweight') ||
                str_contains($bmiAgeStatus, 'obese') ||
                str_contains($bmiAgeStatus, 'severe')
            ) {

                $recommendations[] =
                    "Review the BMI-for-age screening result together with the "
                    . "child's growth trend and other clinical information.";
            }
        }


        /*
        |--------------------------------------------------------------------------
        | TEMPERATURE
        |--------------------------------------------------------------------------
        */

        $temperatureStatus = strtolower(
            trim(
                (string) (
                    $healthRecord->temperature_status ?? ''
                )
            )
        );


        if (
            str_contains($temperatureStatus, 'fever') ||
            str_contains($temperatureStatus, 'flag')
        ) {

            $recommendations[] =
                "The temperature screening contains a flag. Review the temperature "
                . "together with the child's symptoms and clinical assessment.";
        }


        /*
        |--------------------------------------------------------------------------
        | PREVIOUS GROWTH HISTORY
        |--------------------------------------------------------------------------
        */

        $previousAttention = $childHealthRecords
            ->filter(function ($record) use ($healthRecord) {

                if ($record->id === $healthRecord->id) {
                    return false;
                }

                if (!$record->growthAssessment) {
                    return false;
                }

                $status = strtolower(
                    trim(
                        (string) (
                            $record
                                ->growthAssessment
                                ->overall_status ?? ''
                        )
                    )
                );

                return (
                    str_contains($status, 'severe') ||
                    str_contains($status, 'critical') ||
                    str_contains($status, 'danger') ||
                    str_contains($status, 'attention')
                );
            })
            ->isNotEmpty();


        if ($previousAttention) {

            $recommendations[] =
                "Previous records in this child's monitoring history included an "
                . 'attention flag. Continue close growth monitoring and review '
                . "the child's trend.";
        }


        /*
        |--------------------------------------------------------------------------
        | FOLLOW-UP
        |--------------------------------------------------------------------------
        */

        if ($healthRecord->follow_up_date) {

            $recommendations[] =
                'A follow-up visit has been scheduled for '
                . Carbon::parse(
                    $healthRecord->follow_up_date
                )->format('d F Y')
                . '.';

        } else {

            $recommendations[] =
                "Continue routine monitoring and schedule follow-up according to "
                . "the child's clinical and growth-monitoring needs.";
        }


        /*
        |--------------------------------------------------------------------------
        | RECORDED RECOMMENDATIONS
        |--------------------------------------------------------------------------
        */

        if ($healthRecord->recommendations) {

            $recommendations[] =
                'Recorded recommendation: '
                . $healthRecord->recommendations;
        }


        /*
        |--------------------------------------------------------------------------
        | REMOVE DUPLICATES
        |--------------------------------------------------------------------------
        */

        $recommendations = collect(
            $recommendations
        )
            ->filter()
            ->unique()
            ->values()
            ->all();


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'health-records.show',
            compact(
                'child',
                'healthRecord',
                'childHealthRecords',
                'growthChartData',
                'recommendations'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    /**
     * Show the form for editing a health record.
     */
    public function edit(
        Child $child,
        HealthRecord $healthRecord
    ) {
        abort_unless(
            $healthRecord->child_id === $child->id,
            404
        );

        return view(
            'health-records.edit',
            compact(
                'child',
                'healthRecord'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    /**
     * Update a health record.
     */
    public function update(
        Request $request,
        Child $child,
        HealthRecord $healthRecord
    ) {
        /*
        |--------------------------------------------------------------------------
        | SECURITY CHECK
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $healthRecord->child_id === $child->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDATE INPUT
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'visit_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            'facility_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'health_worker_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'weight' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'height' => [
                'nullable',
                'numeric',
                'min:0',
                'max:250',
            ],

            'measurement_type' => [
                'nullable',
                'required_with:height',
                Rule::in([
                    'length',
                    'height',
                ]),
            ],

            'temperature' => [
                'nullable',
                'numeric',
                'min:25',
                'max:45',
            ],

            'muac' => [
                'nullable',
                'numeric',
                'min:0',
                'max:50',
            ],

            'clinical_observations' => [
                'nullable',
                'string',
            ],

            'doctor_remarks' => [
                'nullable',
                'string',
            ],

            'recommendations' => [
                'nullable',
                'string',
            ],

            'follow_up_date' => [
                'nullable',
                'date',
                'after_or_equal:visit_date',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | RECALCULATE GROWTH ASSESSMENT
        |--------------------------------------------------------------------------
        */

        $assessment =
            $this->growthAssessmentService->assess(
                $child,
                $validated['visit_date'],
                $validated['weight'] ?? null,
                $validated['height'] ?? null,
                $validated['muac'] ?? null,
                $validated['measurement_type'] ?? null
            );


        /*
        |--------------------------------------------------------------------------
        | UPDATE CALCULATED VALUES
        |--------------------------------------------------------------------------
        */

        $validated['bmi'] =
            $assessment['bmi'];

        $validated['measurement_type'] =
            $assessment['measurement_type'];

        $validated['nutrition_status'] =
            $assessment['nutrition_status'];


        /*
        |--------------------------------------------------------------------------
        | MONITORING STATUS
        |--------------------------------------------------------------------------
        */

        if (
            isset($validated['weight']) &&
            $validated['weight'] > 0
        ) {

            $validated['monitoring_status'] =
                'Monitoring';

        } else {

            $validated['monitoring_status'] =
                'Awaiting Measurement';
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE RECORD
        |--------------------------------------------------------------------------
        */

        $healthRecord->update(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | UPDATE GROWTH ASSESSMENT
        |--------------------------------------------------------------------------
        */

        $this->createGrowthAssessment(
            $child,
            $healthRecord,
            $assessment
        );


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'health-records.show',
                [
                    $child,
                    $healthRecord,
                ]
            )
            ->with(
                'success',
                'Health record successfully updated.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE / UPDATE GROWTH ASSESSMENT
    |--------------------------------------------------------------------------
    */

    /**
     * Create or update the WHO growth assessment.
     */
    private function createGrowthAssessment(
        Child $child,
        HealthRecord $healthRecord,
        array $assessment
    ): void {

        $healthRecord
            ->growthAssessment()
            ->updateOrCreate(
                [
                    'health_record_id' =>
                        $healthRecord->id,
                ],
                [
                    'child_id' =>
                        $child->id,

                    'age_in_months' =>
                        $assessment['age_in_months'],

                    'weight_for_age_zscore' =>
                        $assessment['weight_for_age_zscore'],

                    'weight_for_age_status' =>
                        $assessment['weight_for_age_status'],

                    'height_for_age_zscore' =>
                        $assessment['height_for_age_zscore'],

                    'height_for_age_status' =>
                        $assessment['height_for_age_status'],

                    'weight_for_height_zscore' =>
                        $assessment['weight_for_height_zscore'],

                    'weight_for_height_status' =>
                        $assessment['weight_for_height_status'],

                    'weight_for_length_zscore' =>
                        $assessment['weight_for_length_zscore'],

                    'weight_for_length_status' =>
                        $assessment['weight_for_length_status'],

                    'bmi_for_age_zscore' =>
                        $assessment['bmi_for_age_zscore'],

                    'bmi_for_age_status' =>
                        $assessment['bmi_for_age_status'],

                    'muac_status' =>
                        $assessment['muac_status'],

                    'overall_status' =>
                        $assessment['overall_status'],

                    'requires_attention' =>
                        $assessment['requires_attention'],

                    'assessment_notes' =>
                        $assessment['assessment_notes'],
                ]
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    /**
     * Delete a health record.
     */
    public function destroy(
        Child $child,
        HealthRecord $healthRecord
    ) {
        abort_unless(
            $healthRecord->child_id === $child->id,
            404
        );


        $healthRecord->delete();


        return redirect()
            ->route(
                'health-records.index',
                $child
            )
            ->with(
                'success',
                'Health record deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SEND FOLLOW-UP REMINDER
    |--------------------------------------------------------------------------
    */

    /**
     * Send a reminder to the child's guardian.
     *
     * Actual reminder email/SMS integration can be connected separately.
     */
    public function sendReminder(
        $child,
        $healthRecord
    ) {

        /*
        |--------------------------------------------------------------------------
        | FIND CHILD
        |--------------------------------------------------------------------------
        */

        $child =
            Child::findOrFail(
                $child
            );


        /*
        |--------------------------------------------------------------------------
        | FIND RECORD
        |--------------------------------------------------------------------------
        */

        $healthRecord =
            HealthRecord::where(
                'id',
                $healthRecord
            )
            ->where(
                'child_id',
                $child->id
            )
            ->firstOrFail();


            
    /*
    |--------------------------------------------------------------------------
    | ALL WEIGHT RECORDS
    |--------------------------------------------------------------------------
    */

    /**
     * Display weight records for all registered children.
     */


        /*
        |--------------------------------------------------------------------------
        | CHECK GUARDIAN EMAIL
        |--------------------------------------------------------------------------
        */

        if (!$child->guardian_email) {

            return back()
                ->with(
                    'error',
                    'No guardian email address has been registered for this child.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CURRENT RESPONSE
        |--------------------------------------------------------------------------
        |
        | The actual reminder delivery can be connected later.
        |
        */

        return back()
            ->with(
                'success',
                'Reminder request successfully received for the guardian.'
            );
    }
}