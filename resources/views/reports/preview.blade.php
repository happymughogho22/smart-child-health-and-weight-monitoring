@extends('layouts.app')

@section('title', 'Report Preview')

@section('page-title', 'Report Preview')

@section('page-subtitle', 'Review the generated growth monitoring report')

@section('content')

<style>

    .preview-page {
        width: 100%;
    }

    .preview-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        gap: 12px;
    }

    .back-button,
    .download-button {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 14px;
        border-radius: 7px;
        text-decoration: none;
        font-size: 10px;
        font-weight: 700;
        border: none;
        cursor: pointer;
    }

    .back-button {
        background: white;
        border: 1px solid #dce3eb;
        color: #475569;
    }

    .download-button {
        background: #123b6d;
        color: white;
    }

    .download-button:hover {
        background: #0c2d52;
    }

    .report-document {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
    }

    .report-title-area {
        padding: 28px;
        text-align: center;
        border-bottom: 1px solid #e8edf3;
    }

    .report-title-area h1 {
        color: #123b6d;
        font-size: 22px;
        margin: 0 0 6px;
    }

    .report-title-area p {
        color: #64748b;
        font-size: 10px;
        margin: 3px 0;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        padding: 20px;
    }

    .summary-card {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 15px;
    }

    .summary-card span {
        display: block;
        color: #64748b;
        font-size: 9px;
        margin-bottom: 7px;
    }

    .summary-card strong {
        display: block;
        color: #1e293b;
        font-size: 20px;
    }

    .section {
        padding: 0 20px 20px;
    }

    .section-title {
        color: #123b6d;
        font-size: 13px;
        font-weight: 750;
        padding: 12px 0;
        border-bottom: 1px solid #e8edf3;
        margin-bottom: 12px;
    }

    .report-table-wrapper {
        overflow-x: auto;
    }

    .report-table {
        width: 100%;
        border-collapse: collapse;
    }

    .report-table th {
        background: #f8fafc;
        color: #64748b;
        font-size: 8px;
        text-transform: uppercase;
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .report-table td {
        color: #334155;
        font-size: 9px;
        padding: 10px;
        border-bottom: 1px solid #edf1f5;
    }

    .status {
        display: inline-block;
        padding: 4px 7px;
        border-radius: 12px;
        font-size: 8px;
        font-weight: 700;
    }

    .normal {
        color: #16803c;
        background: #ecfdf3;
    }

    .monitor {
        color: #b45309;
        background: #fff7ed;
    }

    .attention {
        color: #b91c1c;
        background: #fef2f2;
    }

    .empty-report {
        text-align: center;
        padding: 30px;
        color: #94a3b8;
        font-size: 10px;
    }

    @media (max-width: 800px) {

        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }

    }

    @media (max-width: 500px) {

        .summary-grid {
            grid-template-columns: 1fr;
        }

        .preview-actions {
            flex-direction: column;
            align-items: stretch;
        }

    }

</style>


<div class="preview-page">


    <div class="preview-actions">

        <a
            href="{{ route('reports.index') }}"
            class="back-button"
        >
            ← Back to Reports
        </a>


        <form
            method="POST"
            action="{{ route('reports.download') }}"
        >

            @csrf

            <input
                type="hidden"
                name="period"
                value="{{ $period }}"
            >

            @if($period === 'custom')

                <input
                    type="hidden"
                    name="start_date"
                    value="{{ $startDate->format('Y-m-d') }}"
                >

                <input
                    type="hidden"
                    name="end_date"
                    value="{{ $endDate->format('Y-m-d') }}"
                >

            @endif


            <button
                type="submit"
                class="download-button"
            >
                ↓ Download PDF
            </button>

        </form>

    </div>


    <div class="report-document">


        {{-- REPORT HEADER --}}

        <div class="report-title-area">

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

        <div class="summary-grid">


            <div class="summary-card">

                <span>
                    Total Children
                </span>

                <strong>
                    {{ $totalChildren }}
                </strong>

            </div>


            <div class="summary-card">

                <span>
                    Monitored Children
                </span>

                <strong>
                    {{ $monitoredChildren }}
                </strong>

            </div>


            <div class="summary-card">

                <span>
                    Assessments
                </span>

                <strong>
                    {{ $totalAssessments }}
                </strong>

            </div>


            <div class="summary-card">

                <span>
                    Attention
                </span>

                <strong>
                    {{ $attentionAssessments }}
                </strong>

            </div>

        </div>


        {{-- ASSESSMENT SUMMARY --}}

        <div class="section">

            <div class="section-title">
                Assessment Summary
            </div>


            <div class="summary-grid" style="padding:0;">

                <div class="summary-card">

                    <span>
                        Normal
                    </span>

                    <strong>
                        {{ $normalAssessments }}
                    </strong>

                </div>


                <div class="summary-card">

                    <span>
                        Monitor
                    </span>

                    <strong>
                        {{ $monitorAssessments }}
                    </strong>

                </div>


                <div class="summary-card">

                    <span>
                        Attention
                    </span>

                    <strong>
                        {{ $attentionAssessments }}
                    </strong>

                </div>

            </div>

        </div>


        {{-- HEALTH RECORDS --}}

        <div class="section">

            <div class="section-title">
                Growth Monitoring Records
            </div>


            @if($healthRecords->count() > 0)

                <div class="report-table-wrapper">

                    <table class="report-table">

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

                                        $statusClass = 'attention';

                                        $statusText = 'Attention';

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

                                        $statusClass = 'monitor';

                                        $statusText = 'Monitor';

                                    } else {

                                        $statusClass = 'normal';

                                        $statusText = 'Normal';

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

                                        <span
                                            class="status {{ $statusClass }}"
                                        >
                                            {{ $statusText }}
                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="empty-report">

                    No growth monitoring records were found
                    for the selected period.

                </div>

            @endif

        </div>


        {{-- ATTENTION CHILDREN --}}

        <div class="section">

            <div class="section-title">
                Children Requiring Attention
            </div>


            @if(
                $childrenRequiringAttention->count() > 0
            )

                <div class="report-table-wrapper">

                    <table class="report-table">

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

                </div>

            @else

                <div class="empty-report">

                    No children were flagged for attention
                    during this reporting period.

                </div>

            @endif

        </div>


    </div>

</div>

@endsection