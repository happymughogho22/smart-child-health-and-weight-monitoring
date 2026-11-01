<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>
        Growth Monitoring Report
    </title>

    <style>

        @page {
            margin: 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #334155;
            font-size: 9px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #123b6d;
            padding-bottom: 15px;
            margin-bottom: 18px;
        }

        .header h1 {
            color: #123b6d;
            font-size: 20px;
            margin: 0 0 6px;
        }

        .header p {
            margin: 3px 0;
            color: #64748b;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .summary-table td {
            width: 25%;
            border: 1px solid #dce3eb;
            padding: 10px;
            text-align: center;
        }

        .summary-label {
            display: block;
            color: #64748b;
            font-size: 8px;
            margin-bottom: 5px;
        }

        .summary-value {
            display: block;
            color: #123b6d;
            font-size: 17px;
            font-weight: bold;
        }

        .section-title {
            color: #123b6d;
            font-size: 12px;
            font-weight: bold;
            border-bottom: 1px solid #dce3eb;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.data-table th {
            background: #f1f5f9;
            color: #475569;
            font-size: 8px;
            font-weight: bold;
            padding: 7px;
            text-align: left;
            border: 1px solid #dce3eb;
        }

        table.data-table td {
            padding: 7px;
            border: 1px solid #e2e8f0;
            font-size: 8px;
        }

        .status-normal {
            color: #16803c;
            font-weight: bold;
        }

        .status-monitor {
            color: #b45309;
            font-weight: bold;
        }

        .status-attention {
            color: #b91c1c;
            font-weight: bold;
        }

        .empty {
            text-align: center;
            padding: 15px;
            color: #94a3b8;
        }

        .footer {
            margin-top: 25px;
            padding-top: 8px;
            border-top: 1px solid #dce3eb;
            text-align: center;
            color: #94a3b8;
            font-size: 7px;
        }

    </style>

</head>


<body>


    {{-- HEADER --}}

    <div class="header">

        <h1>
            Growth Monitoring Report
        </h1>

        <p>
            Reporting Period:
            <strong>
                {{ $periodLabel }}
            </strong>
        </p>

        <p>
            {{ $startDate->format('d M Y') }}
            –
            {{ $endDate->format('d M Y') }}
        </p>

        <p>
            Generated:
            {{ $generatedAt->format('d M Y H:i') }}
        </p>

    </div>


    {{-- SUMMARY --}}

    <table class="summary-table">

        <tr>

            <td>

                <span class="summary-label">
                    Total Children
                </span>

                <span class="summary-value">
                    {{ $totalChildren }}
                </span>

            </td>


            <td>

                <span class="summary-label">
                    Monitored Children
                </span>

                <span class="summary-value">
                    {{ $monitoredChildren }}
                </span>

            </td>


            <td>

                <span class="summary-label">
                    Assessments
                </span>

                <span class="summary-value">
                    {{ $totalAssessments }}
                </span>

            </td>


            <td>

                <span class="summary-label">
                    Attention
                </span>

                <span class="summary-value">
                    {{ $attentionAssessments }}
                </span>

            </td>

        </tr>

    </table>


    {{-- ASSESSMENT SUMMARY --}}

    <div class="section-title">
        Assessment Summary
    </div>


    <table class="data-table">

        <thead>

            <tr>

                <th>
                    Normal Assessments
                </th>

                <th>
                    Monitor Assessments
                </th>

                <th>
                    Attention Assessments
                </th>

            </tr>

        </thead>


        <tbody>

            <tr>

                <td>
                    {{ $normalAssessments }}
                </td>

                <td>
                    {{ $monitorAssessments }}
                </td>

                <td>
                    {{ $attentionAssessments }}
                </td>

            </tr>

        </tbody>

    </table>


    {{-- GROWTH RECORDS --}}

    <div class="section-title">
        Growth Monitoring Records
    </div>


    @if($healthRecords->count() > 0)

        <table class="data-table">

            <thead>

                <tr>

                    <th>
                        Child
                    </th>

                    <th>
                        Visit Date
                    </th>

                    <th>
                        Weight
                    </th>

                    <th>
                        Height
                    </th>

                    <th>
                        Status
                    </th>

                </tr>

            </thead>


            <tbody>

                @foreach($healthRecords as $record)

                    @php

                        $assessment =
                            $record->growthAssessment;

                        $status =
                            strtolower(
                                trim(
                                    (string) (
                                        $assessment->status
                                        ?? ''
                                    )
                                )
                            );

                        if (
                            str_contains(
                                $status,
                                'danger'
                            ) ||
                            str_contains(
                                $status,
                                'severe'
                            ) ||
                            str_contains(
                                $status,
                                'critical'
                            ) ||
                            str_contains(
                                $status,
                                'attention'
                            )
                        ) {

                            $statusClass =
                                'status-attention';

                            $statusText =
                                'Attention';

                        } elseif (
                            str_contains(
                                $status,
                                'moderate'
                            ) ||
                            str_contains(
                                $status,
                                'monitor'
                            ) ||
                            str_contains(
                                $status,
                                'warning'
                            )
                        ) {

                            $statusClass =
                                'status-monitor';

                            $statusText =
                                'Monitor';

                        } else {

                            $statusClass =
                                'status-normal';

                            $statusText =
                                'Normal';

                        }

                    @endphp


                    <tr>

                        <td>

                            {{ $record->child
                                ? trim(
                                    $record->child->first_name .
                                    ' ' .
                                    $record->child->last_name
                                )
                                : 'Unknown Child'
                            }}

                        </td>


                        <td>

                            {{ $record->visit_date
                                ? \Carbon\Carbon::parse(
                                    $record->visit_date
                                )->format('d M Y')
                                : '—'
                            }}

                        </td>


                        <td>

                            @if($record->weight !== null)

                                {{ number_format(
                                    (float)$record->weight,
                                    1
                                ) }}
                                kg

                            @else

                                —

                            @endif

                        </td>


                        <td>

                            @if($record->height !== null)

                                {{ number_format(
                                    (float)$record->height,
                                    1
                                ) }}
                                cm

                            @else

                                —

                            @endif

                        </td>


                        <td>

                            <span class="{{ $statusClass }}">
                                {{ $statusText }}
                            </span>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <div class="empty">

            No growth monitoring records were found
            for this reporting period.

        </div>

    @endif


    {{-- ATTENTION CHILDREN --}}

    <div class="section-title">
        Children Requiring Attention
    </div>


    @if(
        $childrenRequiringAttention->count() > 0
    )

        <table class="data-table">

            <thead>

                <tr>

                    <th>
                        Child
                    </th>

                    <th>
                        Child ID
                    </th>

                    <th>
                        Sex
                    </th>

                </tr>

            </thead>


            <tbody>

                @foreach(
                    $childrenRequiringAttention
                    as $child
                )

                    <tr>

                        <td>

                            {{ trim(
                                $child->first_name .
                                ' ' .
                                $child->last_name
                            ) }}

                        </td>

                        <td>
                            {{ $child->child_id ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $child->sex ?? '—' }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <div class="empty">

            No children were flagged for attention
            during this reporting period.

        </div>

    @endif


    <div class="footer">

        Smart Child Weight Monitoring System
        &mdash;
        Growth Monitoring Report

    </div>


</body>

</html>