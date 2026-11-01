<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Nutrition & Malnutrition Report</title>

    <style>
        @page {
            margin: 28px 32px 38px 32px;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #334155;
            font-size: 9px;
            line-height: 1.45;
        }

        .header {
            border-bottom: 2px solid #123b6d;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .brand {
            font-size: 10px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
        }

        .title {
            font-size: 20px;
            color: #123b6d;
            font-weight: 700;
            margin: 4px 0;
        }

        .subtitle {
            font-size: 9px;
            color: #64748b;
        }

        .section {
            border: 1px solid #dbe3ec;
            border-radius: 6px;
            margin-bottom: 13px;
            page-break-inside: avoid;
        }

        .section-title {
            background: #f8fafc;
            border-bottom: 1px solid #dbe3ec;
            color: #123b6d;
            font-weight: 700;
            font-size: 10px;
            padding: 8px 10px;
        }

        .section-body {
            padding: 10px;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
        }

        .summary td {
            width: 25%;
            padding: 9px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }

        .summary .big {
            font-size: 17px;
            color: #123b6d;
            font-weight: 700;
        }

        .summary .small {
            font-size: 7px;
            color: #64748b;
            text-transform: uppercase;
        }

        .grid {
            width: 100%;
            border-collapse: collapse;
        }

        .grid th {
            background: #f8fafc;
            color: #64748b;
            text-align: left;
            padding: 7px;
            border-bottom: 1px solid #dbe3ec;
            font-size: 7px;
            text-transform: uppercase;
        }

        .grid td {
            padding: 7px;
            border-bottom: 1px solid #eef2f7;
            vertical-align: top;
        }

        .grid tr {
            page-break-inside: avoid;
        }

        .status-normal {
            color: #166534;
            font-weight: 700;
        }

        .status-risk {
            color: #92400e;
            font-weight: 700;
        }

        .status-moderate {
            color: #b45309;
            font-weight: 700;
        }

        .status-severe {
            color: #991b1b;
            font-weight: 700;
        }

        .status-none {
            color: #64748b;
        }

        .notice {
            background: #eaf2fb;
            border: 1px solid #d4e4f7;
            padding: 9px;
            color: #365574;
            font-size: 8px;
            margin-top: 12px;
        }

        .footer {
            position: fixed;
            bottom: -22px;
            left: 0;
            right: 0;
            text-align: center;
            color: #94a3b8;
            font-size: 7px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

@php
    $healthRecords = collect($healthRecords ?? []);
    $childrenRequiringAttention = collect($childrenRequiringAttention ?? []);
    $childrenMonitored = collect($childrenMonitored ?? []);

    $totalAssessments = $totalAssessments ?? $healthRecords->count();

    $normalCount = $normalCount ?? 0;
    $atRiskCount = $atRiskCount ?? 0;
    $moderateCount = $moderateCount ?? 0;
    $severeCount = $severeCount ?? 0;
    $notAssessedCount = $notAssessedCount ?? 0;

    $attentionCount = $attentionCount
        ?? ($affectedChildren
            ?? $childrenRequiringAttention->count());
@endphp


{{-- ============================================================
     HEADER
============================================================ --}}

<div class="header">

    <div class="brand">
        Child Weight Monitoring System
    </div>

    <div class="title">
        Nutrition &amp; Malnutrition Report
    </div>

    <div class="subtitle">
        Reporting Period:
        {{ $periodLabel ?? 'All Available Records' }}
    </div>

</div>


{{-- ============================================================
     REPORT INFORMATION
============================================================ --}}

<div class="section">

    <div class="section-title">
        Report Information
    </div>

    <div class="section-body">

        <table class="summary">

            <tr>

                <td>
                    <div class="big">
                        {{ $totalAssessments }}
                    </div>

                    <div class="small">
                        Total Assessments
                    </div>
                </td>

                <td>
                    <div class="big">
                        {{ $childrenMonitored->count() }}
                    </div>

                    <div class="small">
                        Children Monitored
                    </div>
                </td>

                <td>
                    <div class="big">
                        {{ $attentionCount }}
                    </div>

                    <div class="small">
                        Attention Required
                    </div>
                </td>

                <td>
                    <div class="big">
                        {{ ($moderateCount + $severeCount) }}
                    </div>

                    <div class="small">
                        Moderate / Severe
                    </div>
                </td>

            </tr>

        </table>

    </div>

</div>


{{-- ============================================================
     NUTRITION SUMMARY
============================================================ --}}

<div class="section">

    <div class="section-title">
        Nutrition Status Summary
    </div>

    <div class="section-body">

        <table class="summary">

            <tr>

                <td>
                    <div class="big">
                        {{ $normalCount }}
                    </div>

                    <div class="small">
                        Normal
                    </div>
                </td>

                <td>
                    <div class="big">
                        {{ $atRiskCount }}
                    </div>

                    <div class="small">
                        At Risk
                    </div>
                </td>

                <td>
                    <div class="big">
                        {{ $moderateCount }}
                    </div>

                    <div class="small">
                        Moderate
                    </div>
                </td>

                <td>
                    <div class="big">
                        {{ $severeCount }}
                    </div>

                    <div class="small">
                        Severe
                    </div>
                </td>

            </tr>

        </table>

    </div>

</div>


{{-- ============================================================
     CHILDREN REQUIRING ATTENTION
============================================================ --}}

<div class="section">

    <div class="section-title">
        Children Requiring Attention
    </div>

    <div class="section-body">

        @if($childrenRequiringAttention->count())

            <table class="grid">

                <thead>

                    <tr>
                        <th>Child</th>
                        <th>Child ID</th>
                        <th>Visit Date</th>
                        <th>Nutrition Status</th>
                        <th>Assessment</th>
                    </tr>

                </thead>

                <tbody>

                @foreach($childrenRequiringAttention as $item)

                    @php
                        $child = $item->child ?? null;
                        $assessment = $item->growthAssessment ?? null;

                        $nutritionStatus =
                            $item->nutrition_status
                            ?? $item->overall_status
                            ?? 'Attention Required';
                    @endphp

                    <tr>

                        <td>
                            {{ $child
                                ? trim($child->first_name . ' ' . $child->last_name)
                                : 'Unknown Child' }}
                        </td>

                        <td>
                            {{ $child->child_id ?? '—' }}
                        </td>

                        <td>
                            {{ $item->visit_date
                                ? \Carbon\Carbon::parse($item->visit_date)->format('d M Y')
                                : '—' }}
                        </td>

                        <td>

                            @php
                                $statusLower = strtolower($nutritionStatus);
                            @endphp

                            @if(str_contains($statusLower, 'severe'))

                                <span class="status-severe">
                                    {{ $nutritionStatus }}
                                </span>

                            @elseif(str_contains($statusLower, 'moderate'))

                                <span class="status-moderate">
                                    {{ $nutritionStatus }}
                                </span>

                            @elseif(str_contains($statusLower, 'risk'))

                                <span class="status-risk">
                                    {{ $nutritionStatus }}
                                </span>

                            @else

                                <span>
                                    {{ $nutritionStatus }}
                                </span>

                            @endif

                        </td>

                        <td>
                            {{ $assessment
                                ? ($assessment->overall_status ?? 'Assessed')
                                : 'Not assessed' }}
                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        @else

            <div class="notice">
                No children requiring immediate attention were recorded
                during this reporting period.
            </div>

        @endif

    </div>

</div>


{{-- ============================================================
     ALL HEALTH / NUTRITION RECORDS
============================================================ --}}

<div class="section">

    <div class="section-title">
        Nutrition Assessment Records
    </div>

    <div class="section-body">

        @if($healthRecords->count())

            <table class="grid">

                <thead>

                    <tr>
                        <th>Child</th>
                        <th>Visit Date</th>
                        <th>Weight</th>
                        <th>Height</th>
                        <th>MUAC</th>
                        <th>Weight-for-Age</th>
                        <th>Height-for-Age</th>
                        <th>Nutrition Status</th>
                    </tr>

                </thead>

                <tbody>

                @foreach($healthRecords as $record)

                    @php
                        $child = $record->child ?? null;
                        $assessment = $record->growthAssessment ?? null;

                        $nutritionStatus =
                            $record->nutrition_status
                            ?? $record->overall_status
                            ?? 'Not assessed';
                    @endphp

                    <tr>

                        <td>
                            {{ $child
                                ? trim($child->first_name . ' ' . $child->last_name)
                                : 'Unknown Child' }}
                        </td>

                        <td>
                            {{ $record->visit_date
                                ? \Carbon\Carbon::parse($record->visit_date)->format('d M Y')
                                : '—' }}
                        </td>

                        <td>
                            {{ $record->weight !== null
                                ? number_format((float)$record->weight, 2) . ' kg'
                                : '—' }}
                        </td>

                        <td>
                            {{ $record->height !== null
                                ? number_format((float)$record->height, 2) . ' cm'
                                : '—' }}
                        </td>

                        <td>
                            {{ $record->muac !== null
                                ? number_format((float)$record->muac, 1) . ' cm'
                                : '—' }}
                        </td>

                        <td>

                            @if($assessment)

                                {{ $assessment->weight_for_age_zscore !== null
                                    ? number_format((float)$assessment->weight_for_age_zscore, 2)
                                    : '—' }}

                            @else

                                —

                            @endif

                        </td>

                        <td>

                            @if($assessment)

                                {{ $assessment->height_for_age_zscore !== null
                                    ? number_format((float)$assessment->height_for_age_zscore, 2)
                                    : '—' }}

                            @else

                                —

                            @endif

                        </td>

                        <td>

                            @php
                                $statusLower = strtolower($nutritionStatus);
                            @endphp

                            @if(str_contains($statusLower, 'severe'))

                                <span class="status-severe">
                                    {{ $nutritionStatus }}
                                </span>

                            @elseif(str_contains($statusLower, 'moderate'))

                                <span class="status-moderate">
                                    {{ $nutritionStatus }}
                                </span>

                            @elseif(str_contains($statusLower, 'risk'))

                                <span class="status-risk">
                                    {{ $nutritionStatus }}
                                </span>

                            @elseif(str_contains($statusLower, 'normal'))

                                <span class="status-normal">
                                    {{ $nutritionStatus }}
                                </span>

                            @else

                                <span class="status-none">
                                    {{ $nutritionStatus }}
                                </span>

                            @endif

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        @else

            <div class="notice">
                No nutrition assessment records were found for this
                reporting period.
            </div>

        @endif

    </div>

</div>


{{-- ============================================================
     MONITORING NOTES
============================================================ --}}

<div class="notice">

    <strong>Important monitoring notice:</strong>

    This report summarizes recorded child growth and nutrition
    monitoring information. Growth indicators are intended for
    screening and monitoring and should not be treated as a medical
    diagnosis. Any concerning result should be reviewed by an
    appropriately qualified health worker.

</div>


{{-- ============================================================
     FOOTER
============================================================ --}}

<div class="footer">

    Child Weight Monitoring System
    |
    {{ $facilityName ?? 'Health Facility' }}
    |
    Generated
    {{ ($generatedAt ?? now())->format('d M Y H:i') }}

</div>

</body>
</html>