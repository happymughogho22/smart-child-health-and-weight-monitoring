<?php



namespace App\Http\Controllers;



use App\Models\Child;

use App\Models\HealthRecord;

use Carbon\Carbon;

use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Support\Facades\Mail;



class ReportController extends Controller

{

    /*

    |--------------------------------------------------------------------------

    | Reports Index

    |--------------------------------------------------------------------------

    */



    public function index()

    {

        $children = Child::orderBy('first_name')

            ->orderBy('last_name')

            ->get();



        return view(

            'reports.index',

            compact('children')

        );

    }





    /*

    |--------------------------------------------------------------------------

    | Generate System Report

    |--------------------------------------------------------------------------

    */



    public function generate(Request $request)

    {

        $request->validate([

            'period' => [

                'required',

                'in:1_month,2_months,6_months,1_year,2_years,custom'

            ],



            'start_date' => [

                'nullable',

                'date'

            ],



            'end_date' => [

                'nullable',

                'date',

                'after_or_equal:start_date'

            ],

        ]);



        [

            $startDate,

            $endDate,

            $periodLabel

        ] = $this->determinePeriod($request);



        $reportData = $this->buildSystemReportData(

            $startDate,

            $endDate,

            $periodLabel,

            $request->period

        );



        return view(

            'reports.preview',

            $reportData

        );

    }





    /*

    |--------------------------------------------------------------------------

    | Download System Report

    |--------------------------------------------------------------------------

    */



    public function download(Request $request)

    {

        $request->validate([

            'period' => [

                'required',

                'in:1_month,2_months,6_months,1_year,2_years,custom'

            ],



            'start_date' => [

                'nullable',

                'date'

            ],



            'end_date' => [

                'nullable',

                'date',

                'after_or_equal:start_date'

            ],

        ]);



        [

            $startDate,

            $endDate,

            $periodLabel

        ] = $this->determinePeriod($request);



        $reportData = $this->buildSystemReportData(

            $startDate,

            $endDate,

            $periodLabel,

            $request->period

        );



        $pdf = Pdf::loadView(

            'reports.pdf',

            $reportData

        );



        $pdf->setPaper(

            'a4',

            'portrait'

        );



        $fileName =

            'Growth_Monitoring_Report_' .

            str_replace(' ', '_', $periodLabel) .

            '_' .

            $startDate->format('Y-m-d') .

            '_to_' .

            $endDate->format('Y-m-d') .

            '.pdf';



        return $pdf->download($fileName);

    }





    /*

    |--------------------------------------------------------------------------

    | Child Report

    |--------------------------------------------------------------------------

    */



    public function childReport(

        Request $request,

        Child $child

    ) {

        $period = $request->get(

            'period',

            '6_months'

        );



        $reportData = $this->buildChildReportData(

            $child,

            $request,

            $period

        );



        return view(

            'reports.child',

            $reportData

        );

    }





    /*

    |--------------------------------------------------------------------------

    | Download Child Report PDF

    |--------------------------------------------------------------------------

    */



    public function downloadChildReport(

        Child $child,

        Request $request

    ) {

        $request->validate([

            'period' => [

                'nullable',

                'in:1_month,2_months,6_months,1_year,2_years,all,custom'

            ],



            'start_date' => [

                'nullable',

                'date'

            ],



            'end_date' => [

                'nullable',

                'date',

                'after_or_equal:start_date'

            ],

        ]);



        $period = $request->period ?? '6_months';



        $reportData = $this->buildChildReportData(

            $child,

            $request,

            $period

        );



        $pdf = Pdf::loadView(

            'reports.child-pdf',

            $reportData

        );



        $pdf->setPaper(

            'a4',

            'portrait'

        );



        $childName = trim(

            $child->first_name . '_' . $child->last_name

        );



        $childName = preg_replace(

            '/[^A-Za-z0-9_-]/',

            '_',

            $childName

        );



        $fileName =

            'Child_Health_Report_' .

            $childName .

            '_' .

            str_replace(

                ' ',

                '_',

                $reportData['periodLabel']

            ) .

            '.pdf';



        return $pdf->download(

            $fileName

        );

    }





    /*

    |--------------------------------------------------------------------------

    | Nutrition Report

    |--------------------------------------------------------------------------

    */



    public function nutritionReport(

        Request $request

    ) {

        $request->validate([

            'period' => [

                'nullable',

                'in:1_month,2_months,6_months,1_year,2_years,all'

            ],

        ]);



        $period = $request->period ?? '6_months';



        $reportData = $this->buildNutritionReportData(

            $period

        );



        return view(

            'reports.nutrition',

            $reportData

        );

    }





    /*

    |--------------------------------------------------------------------------

    | Download Nutrition Report

    |--------------------------------------------------------------------------

    */



    public function downloadNutritionReport(

        Request $request

    ) {

        $request->validate([

            'period' => [

                'nullable',

                'in:1_month,2_months,6_months,1_year,2_years,all'

            ],

        ]);



        $period = $request->period ?? '6_months';



        $reportData = $this->buildNutritionReportData(

            $period

        );



        $pdf = Pdf::loadView(

            'reports.nutrition-pdf',

            $reportData

        );



        $pdf->setPaper(

            'a4',

            'portrait'

        );



        $fileName =

            'Nutrition_Malnutrition_Report_' .

            str_replace(

                ' ',

                '_',

                $reportData['periodLabel']

            ) .

            '.pdf';



        return $pdf->download($fileName);

    }





    /*

    |--------------------------------------------------------------------------

    | Share Child Report

    |--------------------------------------------------------------------------

    */



    public function shareChildReport(Request $request, Child $child)
    {
        $validated = $request->validate([
            'guardian_email' => ['nullable', 'email', 'max:255'],
        ]);

        // Prefer the email entered on the form; otherwise use the child's saved guardian email.
        $guardianEmail = trim((string) ($validated['guardian_email'] ?? $child->guardian_email ?? ''));

        if ($guardianEmail === '') {
            return back()
                ->withErrors([
                    'guardian_email' => 'No guardian email address is available. Please enter a valid email address.',
                ])
                ->withInput();
        }

        // Prevent placeholder values from being used as recipient addresses.
        $placeholderEmails = [
            'YOUR_GMAIL_ADDRESS',
            'YOUR_EMAIL',
            'YOUR_EMAIL_ADDRESS',
            'example@example.com',
            'your@email.com',
        ];

        if (
            !filter_var($guardianEmail, FILTER_VALIDATE_EMAIL) ||
            in_array(strtolower($guardianEmail), array_map('strtolower', $placeholderEmails), true)
        ) {
            return back()
                ->withErrors([
                    'guardian_email' => 'Please enter a real guardian email address before sending the report.',
                ])
                ->withInput();
        }

        // The sender address comes from config/mail.php, normally configured through .env.
        // Check it before building/sending the email so placeholder configuration fails gracefully.
        $senderEmail = trim((string) config('mail.from.address', ''));
        $senderPlaceholders = [
            '',
            'YOUR_GMAIL_ADDRESS',
            'YOUR_EMAIL',
            'YOUR_EMAIL_ADDRESS',
            'example@example.com',
            'your@email.com',
        ];

        if (
            !filter_var($senderEmail, FILTER_VALIDATE_EMAIL) ||
            in_array(strtolower($senderEmail), array_map('strtolower', $senderPlaceholders), true)
        ) {
            return back()->withErrors([
                'guardian_email' => 'The report cannot be emailed because the system sender email is not configured. Set a valid MAIL_FROM_ADDRESS in your .env file, then run php artisan config:clear.',
            ])->withInput();
        }

        $reportData = $this->buildChildReportData($child, $request, '6_months');

        $pdf = Pdf::loadView('reports.child-pdf', $reportData)
            ->setPaper('a4', 'portrait');

        Mail::raw(
            'Please find attached the child health and growth monitoring report for '
                . $child->first_name . ' ' . $child->last_name . '.',
            function ($message) use ($guardianEmail, $senderEmail, $child, $pdf) {
                $message->from(
                    $senderEmail,
                    config('mail.from.name', 'Smart Child Health Monitoring System')
                )
                    ->to($guardianEmail)
                    ->subject(
                        'Child Health & Growth Monitoring Report - '
                        . $child->first_name . ' ' . $child->last_name
                    )
                    ->attachData(
                        $pdf->output(),
                        'Child_Health_Report_' . $child->child_id . '.pdf',
                        ['mime' => 'application/pdf']
                    );
            }
        );

        return back()->with(
            'success',
            'The child health report has been sent to the guardian.'
        );
    }



    /*

    |--------------------------------------------------------------------------

    | Determine System Report Period

    |--------------------------------------------------------------------------

    */



    private function determinePeriod(

        Request $request

    ) {

        $today = Carbon::today();



        switch ($request->period) {



            case '1_month':

                return [

                    $today->copy()

                        ->subMonth()

                        ->startOfDay(),



                    $today->copy()

                        ->endOfDay(),



                    '1 Month'

                ];



            case '2_months':

                return [

                    $today->copy()

                        ->subMonths(2)

                        ->startOfDay(),



                    $today->copy()

                        ->endOfDay(),



                    '2 Months'

                ];



            case '6_months':

                return [

                    $today->copy()

                        ->subMonths(6)

                        ->startOfDay(),



                    $today->copy()

                        ->endOfDay(),



                    '6 Months'

                ];



            case '1_year':

                return [

                    $today->copy()

                        ->subYear()

                        ->startOfDay(),



                    $today->copy()

                        ->endOfDay(),



                    '1 Year'

                ];



            case '2_years':

                return [

                    $today->copy()

                        ->subYears(2)

                        ->startOfDay(),



                    $today->copy()

                        ->endOfDay(),



                    '2 Years'

                ];



            case 'custom':

                return [

                    Carbon::parse(

                        $request->start_date

                    )->startOfDay(),



                    Carbon::parse(

                        $request->end_date

                    )->endOfDay(),



                    'Custom Period'

                ];



            default:

                return [

                    $today->copy()

                        ->subMonths(6)

                        ->startOfDay(),



                    $today->copy()

                        ->endOfDay(),



                    '6 Months'

                ];

        }

    }





    /*

    |--------------------------------------------------------------------------

    | Determine Child Report Period

    |--------------------------------------------------------------------------

    */



    private function determineChildPeriod(

        Request $request,

        string $period

    ) {

        $today = Carbon::today();



        switch ($period) {



            case '1_month':

                return [

                    $today->copy()

                        ->subMonth()

                        ->startOfDay(),



                    $today->copy()

                        ->endOfDay(),



                    '1 Month'

                ];



            case '2_months':

                return [

                    $today->copy()

                        ->subMonths(2)

                        ->startOfDay(),



                    $today->copy()

                        ->endOfDay(),



                    '2 Months'

                ];



            case '6_months':

                return [

                    $today->copy()

                        ->subMonths(6)

                        ->startOfDay(),



                    $today->copy()

                        ->endOfDay(),



                    '6 Months'

                ];



            case '1_year':

                return [

                    $today->copy()

                        ->subYear()

                        ->startOfDay(),



                    $today->copy()

                        ->endOfDay(),



                    '1 Year'

                ];



            case '2_years':

                return [

                    $today->copy()

                        ->subYears(2)

                        ->startOfDay(),



                    $today->copy()

                        ->endOfDay(),



                    '2 Years'

                ];



            case 'custom':

                return [

                    Carbon::parse(

                        $request->start_date

                    )->startOfDay(),



                    Carbon::parse(

                        $request->end_date

                    )->endOfDay(),



                    'Custom Period'

                ];



            case 'all':

                return [

                    null,

                    null,

                    'All Available Records'

                ];



            default:

                return [

                    $today->copy()

                        ->subMonths(6)

                        ->startOfDay(),



                    $today->copy()

                        ->endOfDay(),



                    '6 Months'

                ];

        }

    }





    /*

    |--------------------------------------------------------------------------

    | Determine Nutrition Period

    |--------------------------------------------------------------------------

    */



    private function determineNutritionPeriod(

        string $period

    ) {

        $today = Carbon::today();



        switch ($period) {



            case '1_month':

                return [

                    $today->copy()

                        ->subMonth()

                        ->startOfDay(),



                    $today->copy()

                        ->endOfDay(),



                    '1 Month'

                ];



            case '2_months':

                return [

                    $today->copy()

                        ->subMonths(2)

                        ->startOfDay(),



                    $today->copy()

                        ->endOfDay(),



                    '2 Months'

                ];



            case '6_months':

                return [

                    $today->copy()

                        ->subMonths(6)

                        ->startOfDay(),



                    $today->copy()

                        ->endOfDay(),



                    '6 Months'

                ];



            case '1_year':

                return [

                    $today->copy()

                        ->subYear()

                        ->startOfDay(),



                    $today->copy()

                        ->endOfDay(),



                    '1 Year'

                ];



            case '2_years':

                return [

                    $today->copy()

                        ->subYears(2)

                        ->startOfDay(),



                    $today->copy()

                        ->endOfDay(),



                    '2 Years'

                ];



            case 'all':

                return [

                    null,

                    null,

                    'All Available Records'

                ];



            default:

                return [

                    $today->copy()

                        ->subMonths(6)

                        ->startOfDay(),



                    $today->copy()

                        ->endOfDay(),



                    '6 Months'

                ];

        }

    }





    /*

    |--------------------------------------------------------------------------

    | Build System Report Data

    |--------------------------------------------------------------------------

    */



    private function buildSystemReportData(

        Carbon $startDate,

        Carbon $endDate,

        string $periodLabel,

        string $period

    ) {

        $healthRecords = HealthRecord::with([

            'child',

            'growthAssessment'

        ])

            ->whereBetween(

                'visit_date',

                [

                    $startDate->toDateString(),

                    $endDate->toDateString()

                ]

            )

            ->orderBy(

                'visit_date',

                'desc'

            )

            ->get();



        $totalAssessments =

            $healthRecords->count();



        $monitoredChildren =

            $healthRecords

                ->pluck('child_id')

                ->filter()

                ->unique()

                ->count();



        $normalAssessments = 0;

        $monitorAssessments = 0;

        $attentionAssessments = 0;



        foreach ($healthRecords as $record) {



            if (!$record->growthAssessment) {

                continue;

            }



            $status = strtolower(

                trim(

                    (string) $record

                        ->growthAssessment

                        ->overall_status

                )

            );



            if (

                str_contains($status, 'danger') ||

                str_contains($status, 'severe') ||

                str_contains($status, 'critical') ||

                str_contains($status, 'attention')

            ) {

                $attentionAssessments++;



            } elseif (

                str_contains($status, 'moderate') ||

                str_contains($status, 'monitor') ||

                str_contains($status, 'warning') ||

                str_contains($status, 'risk')

            ) {

                $monitorAssessments++;



            } else {

                $normalAssessments++;

            }

        }



        $attentionChildIds =

            $healthRecords

                ->filter(function ($record) {

                    return $record->growthAssessment &&

                        $record->growthAssessment

                            ->requires_attention;

                })

                ->pluck('child_id')

                ->filter()

                ->unique();



        $childrenRequiringAttention =

            Child::whereIn(

                'id',

                $attentionChildIds

            )

            ->orderBy('first_name')

            ->get();



        $children =

            Child::whereIn(

                'id',

                $healthRecords

                    ->pluck('child_id')

                    ->filter()

                    ->unique()

            )

            ->orderBy('first_name')

            ->get();



        return [



            'period' =>

                $period,



            'periodLabel' =>

                $periodLabel,



            'startDate' =>

                $startDate,



            'endDate' =>

                $endDate,



            'generatedAt' =>

                Carbon::now(),



            'healthRecords' =>

                $healthRecords,



            'children' =>

                $children,



            'childrenRequiringAttention' =>

                $childrenRequiringAttention,



            'totalChildren' =>

                Child::count(),



            'monitoredChildren' =>

                $monitoredChildren,



            'totalAssessments' =>

                $totalAssessments,



            'normalAssessments' =>

                $normalAssessments,



            'monitorAssessments' =>

                $monitorAssessments,



            'attentionAssessments' =>

                $attentionAssessments,

        ];

    }





    /*

    |--------------------------------------------------------------------------

    | Build Child Report Data

    |--------------------------------------------------------------------------

    */



    private function buildChildReportData(

        Child $child,

        Request $request,

        string $period

    ) {

        [

            $startDate,

            $endDate,

            $periodLabel

        ] = $this->determineChildPeriod(

            $request,

            $period

        );



        /*

        |--------------------------------------------------------------------------

        | Get Health Records

        |--------------------------------------------------------------------------

        */



        $query = HealthRecord::with([

            'growthAssessment'

        ])

            ->where(

                'child_id',

                $child->id

            )

            ->orderBy(

                'visit_date',

                'asc'

            );



        if ($startDate && $endDate) {



            $query->whereBetween(

                'visit_date',

                [

                    $startDate->toDateString(),

                    $endDate->toDateString()

                ]

            );

        }



        $healthRecords =

            $query->get();



        /*

        |--------------------------------------------------------------------------

        | Prepare Measurement Data

        |--------------------------------------------------------------------------

        */



        $measurementData =

            $healthRecords

                ->map(function ($record) {



                    return [



                        'date' =>

                            $record->visit_date

                                ? Carbon::parse(

                                    $record->visit_date

                                )->format('d M Y')

                                : '—',



                        'weight' =>

                            $record->weight !== null

                                ? (float) $record->weight

                                : null,



                        'height' =>

                            $record->height !== null

                                ? (float) $record->height

                                : null,



                        'muac' =>

                            $record->muac !== null

                                ? (float) $record->muac

                                : null,



                        'bmi' =>

                            $record->bmi !== null

                                ? (float) $record->bmi

                                : null,



                        'nutrition_status' =>

                            $record->nutrition_status

                            ?? null,



                        'overall_status' =>

                            $record->overall_status

                            ?? $record->status

                            ?? null,

                    ];

                })

                ->values();



        /*

        |--------------------------------------------------------------------------

        | Latest Record

        |--------------------------------------------------------------------------

        */



        $latestRecord =

            $healthRecords->last();



        $latestAssessment =

            $latestRecord

                ? $latestRecord->growthAssessment

                : null;



        /*

        |--------------------------------------------------------------------------

        | Assessment Counts

        |--------------------------------------------------------------------------

        */



        $normalCount = 0;

        $monitorCount = 0;

        $attentionCount = 0;



        foreach ($healthRecords as $record) {



            if (!$record->growthAssessment) {

                continue;

            }



            $status = strtolower(

                trim(

                    (string) $record

                        ->growthAssessment

                        ->overall_status

                )

            );



            if (

                str_contains($status, 'danger') ||

                str_contains($status, 'severe') ||

                str_contains($status, 'critical') ||

                str_contains($status, 'attention')

            ) {

                $attentionCount++;



            } elseif (

                str_contains($status, 'moderate') ||

                str_contains($status, 'monitor') ||

                str_contains($status, 'warning') ||

                str_contains($status, 'risk')

            ) {

                $monitorCount++;



            } else {

                $normalCount++;

            }

        }



        /*

        |--------------------------------------------------------------------------

        | Recommendations

        |--------------------------------------------------------------------------

        */



        $recommendations =

            $this->buildChildRecommendations(

                $latestRecord,

                $latestAssessment,

                $attentionCount,

                $monitorCount

            );



        /*

        |--------------------------------------------------------------------------

        | Follow-Up Date

        |--------------------------------------------------------------------------

        */



        $followUpDate = null;



        if (

            $latestRecord &&

            !empty(

                $latestRecord->follow_up_date

            )

        ) {

            $followUpDate =

                Carbon::parse(

                    $latestRecord->follow_up_date

                );

        }



        /*

        |--------------------------------------------------------------------------

        | Facility Name

        |--------------------------------------------------------------------------

        */



        $facilityName =

            $healthRecords

                ->pluck('health_facility')

                ->filter()

                ->first();



        /*

        |--------------------------------------------------------------------------

        | Growth Trend Charts

        |--------------------------------------------------------------------------

        */



        $weightChart =

            $this->renderChildTrendChart(

                $measurementData,

                'weight',

                'Weight Trend',

                'Weight (kg)'

            );



        $heightChart =

            $this->renderChildTrendChart(

                $measurementData,

                'height',

                'Height / Length Trend',

                'Height / Length (cm)'

            );



        $bmiChart =

            $this->renderChildTrendChart(

                $measurementData,

                'bmi',

                'BMI Trend',

                'BMI'

            );



        $muacChart =

            $this->renderChildTrendChart(

                $measurementData,

                'muac',

                'MUAC Trend',

                'MUAC (cm)'

            );



        /*

        |--------------------------------------------------------------------------

        | Return Report Data

        |--------------------------------------------------------------------------

        */



        return [



            'child' =>

                $child,



            'period' =>

                $period,



            'periodLabel' =>

                $periodLabel,



            'reportStart' =>

                $startDate,



            'reportEnd' =>

                $endDate,



            'startDate' =>

                $startDate,



            'endDate' =>

                $endDate,



            'generatedAt' =>

                Carbon::now(),



            'healthRecords' =>

                $healthRecords,



            'measurementData' =>

                $measurementData,



            'latestRecord' =>

                $latestRecord,



            'latestAssessment' =>

                $latestAssessment,



            'normalCount' =>

                $normalCount,



            'monitorCount' =>

                $monitorCount,



            'attentionCount' =>

                $attentionCount,



            'recommendations' =>

                $recommendations,



            'followUpDate' =>

                $followUpDate,



            'facilityName' =>

                $facilityName,



            'weightChart' =>

                $weightChart,



            'heightChart' =>

                $heightChart,



            'bmiChart' =>

                $bmiChart,



            'muacChart' =>

                $muacChart,

        ];

    }





    /*

    |--------------------------------------------------------------------------

    | Render Child Trend Chart

    |--------------------------------------------------------------------------

    */



    private function renderChildTrendChart(

        $measurementData,

        string $field,

        string $title,

        string $yLabel

    ): string {



        $points = [];



        foreach (

            $measurementData as $index => $record

        ) {



            $value =

                $record[$field] ?? null;



            if (

                $value === null ||

                $value === ''

            ) {

                continue;

            }



            $number =

                (float) $value;



            if (!is_finite($number)) {

                continue;

            }



            $points[] = [



                'index' =>

                    $index,



                'date' =>

                    (string) (

                        $record['date'] ?? '—'

                    ),



                'value' =>

                    $number,

            ];

        }



        /*

        |--------------------------------------------------------------------------

        | SVG Dimensions

        |--------------------------------------------------------------------------

        */



        $width = 760;

        $height = 300;



        $left = 58;

        $right = 18;



        $top = 32;

        $bottom = 58;



        $plotWidth =

            $width - $left - $right;



        $plotHeight =

            $height - $top - $bottom;



        $esc = static function ($value) {



            return htmlspecialchars(

                (string) $value,

                ENT_QUOTES,

                'UTF-8'

            );

        };



        /*

        |--------------------------------------------------------------------------

        | No Data

        |--------------------------------------------------------------------------

        */



        if (count($points) === 0) {



            return

                '<svg '

                . 'xmlns="http\://www\.w3.org/2000/svg" '

                . 'viewBox="0 0 '

                . $width

                . ' '

                . $height

                . '" '

                . 'width="100%" '

                . 'height="300">'

                .

                '<rect '

                . 'x="0" '

                . 'y="0" '

                . 'width="'

                . $width

                . '" '

                . 'height="'

                . $height

                . '" '

                . 'fill="#ffffff"/>'

                .

                '<text '

                . 'x="'

                . ($width / 2)

                . '" '

                . 'y="145" '

                . 'text-anchor="middle" '

                . 'fill="#64748b" '

                . 'font-size="13" '

                . 'font-family="Arial, sans-serif">'

                . 'No data available'

                . '</text>'

                .

                '</svg>';

        }



        /*

        |--------------------------------------------------------------------------

        | Calculate Axis Range

        |--------------------------------------------------------------------------

        */



        $values =

            array_column(

                $points,

                'value'

            );



        $min =

            min($values);



        $max =

            max($values);



        $range =

            $max - $min;



        if ($range <= 0) {



            $range =

                max(

                    abs($max) * 0.2,

                    1

                );

        }



        $axisMin =

            $min - (

                $range * 0.12

            );



        $axisMax =

            $max + (

                $range * 0.12

            );



        if ($axisMin === $axisMax) {



            $axisMin -= 1;

            $axisMax += 1;

        }



        /*

        |--------------------------------------------------------------------------

        | X Coordinate

        |--------------------------------------------------------------------------

        */



        $xFor = function (

            int $i

        ) use (

            $points,

            $left,

            $plotWidth

        ) {



            if (

                count($points) === 1

            ) {



                return

                    $left +

                    ($plotWidth / 2);

            }



            return

                $left +

                (

                    $i /

                    (

                        count($points) - 1

                    )

                ) *

                $plotWidth;

        };



        /*

        |--------------------------------------------------------------------------

        | Y Coordinate

        |--------------------------------------------------------------------------

        */



        $yFor = function (

            float $value

        ) use (

            $top,

            $plotHeight,

            $axisMin,

            $axisMax

        ) {



            return

                $top +

                (

                    (

                        $axisMax -

                        $value

                    ) /

                    (

                        $axisMax -

                        $axisMin

                    )

                ) *

                $plotHeight;

        };



        /*

        |--------------------------------------------------------------------------

        | Start SVG

        |--------------------------------------------------------------------------

        */



        $svg =

            '<svg '

            . 'xmlns="http\://www\.w3.org/2000/svg" '

            . 'viewBox="0 0 '

            . $width

            . ' '

            . $height

            . '" '

            . 'width="100%" '

            . 'height="300">';



        $svg .=

            '<rect '

            . 'x="0" '

            . 'y="0" '

            . 'width="'

            . $width

            . '" '

            . 'height="'

            . $height

            . '" '

            . 'fill="#ffffff"/>';



        $svg .=

            '<text '

            . 'x="'

            . $left

            . '" '

            . 'y="18" '

            . 'fill="#123b6d" '

            . 'font-size="12" '

            . 'font-weight="700" '

            . 'font-family="Arial, sans-serif">'

            . $esc($title)

            . '</text>';



        /*

        |--------------------------------------------------------------------------

        | Horizontal Grid

        |--------------------------------------------------------------------------

        */



        $tickCount = 5;



        for (

            $t = 0;

            $t < $tickCount;

            $t++

        ) {



            $fraction =

                $t /

                ($tickCount - 1);



            $value =

                $axisMax -

                (

                    (

                        $axisMax -

                        $axisMin

                    ) *

                    $fraction

                );



            $y =

                $top +

                (

                    $plotHeight *

                    $fraction

                );



            $svg .=

                '<line '

                . 'x1="'

                . $left

                . '" '

                . 'y1="'

                . $y

                . '" '

                . 'x2="'

                . (

                    $left +

                    $plotWidth

                )

                . '" '

                . 'y2="'

                . $y

                . '" '

                . 'stroke="#e2e8f0" '

                . 'stroke-width="1"/>';



            $svg .=

                '<text '

                . 'x="'

                . ($left - 8)

                . '" '

                . 'y="'

                . ($y + 4)

                . '" '

                . 'text-anchor="end" '

                . 'fill="#64748b" '

                . 'font-size="9" '

                . 'font-family="Arial, sans-serif">'

                . number_format(

                    $value,

                    2

                )

                . '</text>';

        }



        /*

        |--------------------------------------------------------------------------

        | X Axis

        |--------------------------------------------------------------------------

        */



        $axisY =

            $top +

            $plotHeight;



        $svg .=

            '<line '

            . 'x1="'

            . $left

            . '" '

            . 'y1="'

            . $axisY

            . '" '

            . 'x2="'

            . (

                $left +

                $plotWidth

            )

            . '" '

            . 'y2="'

            . $axisY

            . '" '

            . 'stroke="#cbd5e1" '

            . 'stroke-width="1"/>';



        /*

        |--------------------------------------------------------------------------

        | Build Line

        |--------------------------------------------------------------------------

        */



        if (count($points) === 1) {



            $x =

                $xFor(0);



            $y =

                $yFor(

                    $points[0]['value']

                );



            $path =

                'M '

                . $x

                . ' '

                . $y;



        } else {



            $coords = [];



            foreach (

                $points as $i => $point

            ) {



                $coords[] = [



                    $xFor($i),



                    $yFor(

                        $point['value']

                    ),

                ];

            }



            $path =

                'M '

                . $coords[0][0]

                . ' '

                . $coords[0][1];



            for (

                $i = 0;

                $i < count($coords) - 1;

                $i++

            ) {



                $p0 =

                    $coords[

                        max(

                            0,

                            $i - 1

                        )

                    ];



                $p1 =

                    $coords[$i];



                $p2 =

                    $coords[

                        $i + 1

                    ];



                $p3 =

                    $coords[

                        min(

                            count($coords) - 1,

                            $i + 2

                        )

                    ];



                $c1x =

                    $p1[0] +

                    (

                        (

                            $p2[0] -

                            $p0[0]

                        ) /

                        6

                    ) *

                    0.3;



                $c1y =

                    $p1[1] +

                    (

                        (

                            $p2[1] -

                            $p0[1]

                        ) /

                        6

                    ) *

                    0.3;



                $c2x =

                    $p2[0] -

                    (

                        (

                            $p3[0] -

                            $p1[0]

                        ) /

                        6

                    ) *

                    0.3;



                $c2y =

                    $p2[1] -

                    (

                        (

                            $p3[1] -

                            $p1[1]

                        ) /

                        6

                    ) *

                    0.3;



                $path .=

                    ' C '

                    . $c1x

                    . ' '

                    . $c1y

                    . ', '

                    . $c2x

                    . ' '

                    . $c2y

                    . ', '

                    . $p2[0]

                    . ' '

                    . $p2[1];

            }

        }



        /*

        |--------------------------------------------------------------------------

        | Draw Line

        |--------------------------------------------------------------------------

        */



        $svg .=

            '<path '

            . 'd="'

            . $path

            . '" '

            . 'fill="none" '

            . 'stroke="#123b6d" '

            . 'stroke-width="2.5" '

            . 'stroke-linecap="round" '

            . 'stroke-linejoin="round"/>';



        /*

        |--------------------------------------------------------------------------

        | Draw Points

        |--------------------------------------------------------------------------

        */



        foreach (

            $points as $i => $point

        ) {



            $x =

                $xFor($i);



            $y =

                $yFor(

                    $point['value']

                );



            $svg .=

                '<circle '

                . 'cx="'

                . $x

                . '" '

                . 'cy="'

                . $y

                . '" '

                . 'r="4" '

                . 'fill="#ffffff" '

                . 'stroke="#123b6d" '

                . 'stroke-width="2"/>';



            $label =

                $point['date'];



            if (

                count($points) > 6 &&

                $i % 2 !== 0 &&

                $i !== count($points) - 1

            ) {



                continue;

            }



            $svg .=

                '<text '

                . 'x="'

                . $x

                . '" '

                . 'y="'

                . ($axisY + 17)

                . '" '

                . 'text-anchor="middle" '

                . 'fill="#64748b" '

                . 'font-size="8" '

                . 'font-family="Arial, sans-serif">'

                . $esc($label)

                . '</text>';

        }



        /*

        |--------------------------------------------------------------------------

        | Axis Labels

        |--------------------------------------------------------------------------

        */



        $svg .=

            '<text '

            . 'x="'

            . (

                $left +

                $plotWidth / 2

            )

            . '" '

            . 'y="'

            . ($height - 8)

            . '" '

            . 'text-anchor="middle" '

            . 'fill="#475569" '

            . 'font-size="9" '

            . 'font-family="Arial, sans-serif">'

            . 'Visit Date'

            . '</text>';



        $svg .=

            '<text '

            . 'x="14" '

            . 'y="'

            . (

                $top +

                $plotHeight / 2

            )

            . '" '

            . 'text-anchor="middle" '

            . 'fill="#475569" '

            . 'font-size="9" '

            . 'font-family="Arial, sans-serif" '

            . 'transform="rotate(-90 14 '

            . (

                $top +

                $plotHeight / 2

            )

            . ')">'

            . $esc($yLabel)

            . '</text>';



        $svg .=

            '</svg>';



        return $svg;

    }





    /*

    |--------------------------------------------------------------------------

    | Build Nutrition Report Data

    |--------------------------------------------------------------------------

    */



    private function buildNutritionReportData(

        string $period

    ) {

        [

            $startDate,

            $endDate,

            $periodLabel

        ] = $this->determineNutritionPeriod(

            $period

        );



        /*

        |--------------------------------------------------------------------------

        | Get Health Records

        |--------------------------------------------------------------------------

        */



        $query = HealthRecord::with([

            'child',

            'growthAssessment'

        ])

            ->orderBy(

                'visit_date',

                'desc'

            );



        if ($startDate && $endDate) {



            $query->whereBetween(

                'visit_date',

                [

                    $startDate->toDateString(),

                    $endDate->toDateString()

                ]

            );

        }



        $healthRecords =

            $query->get();





        /*

        |--------------------------------------------------------------------------

        | Nutrition Records

        |--------------------------------------------------------------------------

        */



        $nutritionRecords =

            $healthRecords;





        /*

        |--------------------------------------------------------------------------

        | Nutrition Counts

        |--------------------------------------------------------------------------

        */



        $normalCount = 0;

        $atRiskCount = 0;

        $moderateCount = 0;

        $severeCount = 0;

        $notAssessedCount = 0;

        $attentionCount = 0;



        foreach (

            $nutritionRecords as $record

        ) {



            $nutritionStatus =

                strtolower(

                    trim(

                        (string)

                        $record->nutrition_status

                    )

                );



            /*

            |--------------------------------------------------------------------------

            | Nutrition Status Count

            |--------------------------------------------------------------------------

            */



            if (

                $nutritionStatus === 'normal'

            ) {



                $normalCount++;



            } elseif (

                str_contains(

                    $nutritionStatus,

                    'severe'

                )

            ) {



                $severeCount++;



            } elseif (

                str_contains(

                    $nutritionStatus,

                    'moderate'

                )

            ) {



                $moderateCount++;



            } elseif (

                str_contains(

                    $nutritionStatus,

                    'risk'

                )

            ) {



                $atRiskCount++;



            } else {



                $notAssessedCount++;

            }





            /*

            |--------------------------------------------------------------------------

            | Attention Count

            |--------------------------------------------------------------------------

            */



            if (

                $record->growthAssessment &&

                $record->growthAssessment

                    ->requires_attention

            ) {



                $attentionCount++;

            }

        }





        /*

        |--------------------------------------------------------------------------

        | Records Requiring Attention

        |--------------------------------------------------------------------------

        */



        $attentionRecords =

            $nutritionRecords

                ->filter(function ($record) {



                    return

                        $record->growthAssessment &&

                        $record->growthAssessment

                            ->requires_attention;



                })

                ->values();





        /*

        |--------------------------------------------------------------------------

        | Children Requiring Attention

        |--------------------------------------------------------------------------

        */



        $childrenRequiringAttention =

            $attentionRecords

                ->pluck('child')

                ->filter()

                ->unique('id')

                ->sortBy('first_name')

                ->values();





        /*

        |--------------------------------------------------------------------------

        | Affected Children

        |--------------------------------------------------------------------------

        |

        | A child is considered affected when at least one

        | nutritional or growth-assessment finding exists.

        |

        */



        $affectedChildren =

            $nutritionRecords

                ->filter(function ($record) {



                    $nutritionStatus =

                        strtolower(

                            trim(

                                (string)

                                $record->nutrition_status

                            )

                        );



                    $assessment =

                        $record->growthAssessment;



                    $assessmentStatus =

                        $assessment

                            ? strtolower(

                                trim(

                                    (string)

                                    $assessment->overall_status

                                )

                            )

                            : '';



                    /*

                    |--------------------------------------------------------------------------

                    | Nutrition Finding

                    |--------------------------------------------------------------------------

                    */



                    $hasNutritionFinding =

                        $nutritionStatus !== '' &&

                        $nutritionStatus !== 'normal';





                    /*

                    |--------------------------------------------------------------------------

                    | Growth Assessment Finding

                    |--------------------------------------------------------------------------

                    */



                    $hasAssessmentFinding =

                        $assessment &&

                        (

                            str_contains(

                                $assessmentStatus,

                                'severe'

                            ) ||



                            str_contains(

                                $assessmentStatus,

                                'critical'

                            ) ||



                            str_contains(

                                $assessmentStatus,

                                'danger'

                            ) ||



                            str_contains(

                                $assessmentStatus,

                                'moderate'

                            ) ||



                            str_contains(

                                $assessmentStatus,

                                'risk'

                            ) ||



                            (bool)

                            $assessment->requires_attention

                        );





                    return

                        $hasNutritionFinding ||

                        $hasAssessmentFinding;

                })

                ->pluck('child')

                ->filter()

                ->unique('id')

                ->sortBy('first_name')

                ->values();





        /*

        |--------------------------------------------------------------------------

        | Children Monitored

        |--------------------------------------------------------------------------

        */



        $childrenMonitored =

            $nutritionRecords

                ->pluck('child')

                ->filter()

                ->unique('id')

                ->sortBy('first_name')

                ->values();





        /*

        |--------------------------------------------------------------------------

        | Return Nutrition Report Data

        |--------------------------------------------------------------------------

        */



        return [



            'period' =>

                $period,



            'periodLabel' =>

                $periodLabel,



            'startDate' =>

                $startDate,



            'endDate' =>

                $endDate,



            'generatedAt' =>

                Carbon::now(),



            /*

            |--------------------------------------------------------------------------

            | Records

            |--------------------------------------------------------------------------

            */



            'healthRecords' =>

                $healthRecords,



            'nutritionRecords' =>

                $nutritionRecords,





            /*

            |--------------------------------------------------------------------------

            | Children

            |--------------------------------------------------------------------------

            */



            'childrenMonitored' =>

                $childrenMonitored,



            'childrenRequiringAttention' =>

                $childrenRequiringAttention,



            /*

            | IMPORTANT:

            | Blade expects this to be a NUMBER.

            */



            'affectedChildren' =>

                $affectedChildren->count(),





            /*

            |--------------------------------------------------------------------------

            | Attention Records

            |--------------------------------------------------------------------------

            */



            'attentionRecords' =>

                $attentionRecords,





            /*

            |--------------------------------------------------------------------------

            | Counts

            |--------------------------------------------------------------------------

            */



            'totalAssessments' =>

                $nutritionRecords->count(),



            'normalCount' =>

                $normalCount,



            'atRiskCount' =>

                $atRiskCount,



            'moderateCount' =>

                $moderateCount,



            'severeCount' =>

                $severeCount,



            'notAssessedCount' =>

                $notAssessedCount,



            'attentionCount' =>

                $attentionCount,

        ];

    }





    /*

    |--------------------------------------------------------------------------

    | Child Recommendations

    |--------------------------------------------------------------------------

    */



    private function buildChildRecommendations(

        $latestRecord,

        $latestAssessment,

        int $attentionCount,

        int $monitorCount

    ) {

        $recommendations = [];



        if (!$latestRecord) {



            $recommendations[] =

                'No health-monitoring record was found for the selected reporting period.';



            $recommendations[] =

                'Schedule a growth-monitoring assessment so that the child can be followed over time.';



            return $recommendations;

        }



        if ($latestAssessment) {



            if (

                $latestAssessment

                    ->requires_attention

            ) {



                $recommendations[] =

                    'The latest growth assessment contains an attention flag. Arrange appropriate follow-up assessment by a qualified health professional.';



            } elseif (

                $attentionCount > 0

            ) {



                $recommendations[] =

                    'Previous records in this reporting period included an attention flag. Continue close growth monitoring and review the child trend.';



            } elseif (

                $monitorCount > 0

            ) {



                $recommendations[] =

                    'Some measurements indicate that continued monitoring may be appropriate. Maintain regular growth assessments.';



            } else {



                $recommendations[] =

                    'Continue routine growth monitoring and maintain regular health assessments.';

            }

        }



        if (

            $latestRecord->weight === null

        ) {



            $recommendations[] =

                "Record the child's weight during the next monitoring visit.";

        }



        if (

            $latestRecord->height === null

        ) {



            $recommendations[] =

                "Record height or length during the next monitoring visit where appropriate for the child's age.";

        }



        if (

            $latestRecord->muac === null

        ) {



            $recommendations[] =

                'Consider recording MUAC where appropriate as part of nutritional screening.';

        }



        if (

            !$latestRecord->follow_up_date

        ) {



            $recommendations[] =

                'No follow-up date is currently recorded for the latest visit.';

        }



        if (

            !empty(

                $latestRecord->recommendations

            )

        ) {



            $recommendations[] =

                'Recorded recommendation: ' .

                $latestRecord->recommendations;

        }



        return $recommendations;

    }

}
