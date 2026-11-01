@extends('layouts.app')

@section('title', 'Nutrition & Malnutrition Report')

@section('page-title', 'Nutrition & Malnutrition Report')

@section('page-subtitle', 'Nutritional growth monitoring summary')

@section('content')

<style>

    .nutrition-page {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
    }

    .report-header {
        margin-bottom: 22px;
    }

    .report-header h1 {
        margin: 0 0 6px;
        color: #123b6d;
        font-size: 24px;
        font-weight: 800;
    }

    .report-header p {
        margin: 0;
        color: #64748b;
        font-size: 11px;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 20px;
    }

    .summary-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 16px;
    }

    .summary-label {
        color: #64748b;
        font-size: 9px;
        font-weight: 650;
        margin-bottom: 7px;
    }

    .summary-value {
        color: #123b6d;
        font-size: 23px;
        font-weight: 800;
    }

    .summary-card.severe .summary-value {
        color: #dc2626;
    }

    .summary-card.moderate .summary-value {
        color: #d97706;
    }

    .summary-card.attention .summary-value {
        color: #b45309;
    }

    .report-panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
    }

    .panel-header {
        padding: 17px 20px;
        border-bottom: 1px solid #e8edf3;
    }

    .panel-header h3 {
        margin: 0 0 4px;
        color: #1e293b;
        font-size: 13px;
    }

    .panel-header p {
        margin: 0;
        color: #94a3b8;
        font-size: 9px;
    }

    .table-wrap {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: #f8fafc;
        color: #64748b;
        font-size: 9px;
        font-weight: 750;
        text-align: left;
        padding: 11px 13px;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    td {
        padding: 12px 13px;
        color: #475569;
        font-size: 9.5px;
        border-bottom: 1px solid #edf1f5;
        vertical-align: middle;
    }

    .child-name {
        color: #1e293b;
        font-weight: 700;
    }

    .child-id {
        display: block;
        margin-top: 2px;
        color: #94a3b8;
        font-size: 8px;
    }

    .status {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 999px;
        font-size: 8px;
        font-weight: 750;
        background: #f1f5f9;
        color: #475569;
    }

    .status.severe {
        background: #fee2e2;
        color: #991b1b;
    }

    .status.moderate {
        background: #fef3c7;
        color: #92400e;
    }

    .status.risk {
        background: #fff7ed;
        color: #9a3412;
    }

    .status.normal {
        background: #dcfce7;
        color: #166534;
    }

    .attention {
        color: #b45309;
        font-weight: 750;
    }

    .empty-state {
        padding: 45px 20px;
        text-align: center;
        color: #94a3b8;
        font-size: 10px;
    }

    .footer-note {
        margin-top: 16px;
        padding: 12px 14px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        color: #64748b;
        font-size: 9px;
        line-height: 1.6;
    }

    @media (max-width: 900px) {
        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 550px) {
        .summary-grid {
            grid-template-columns: 1fr;
        }
    }
.download-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    background: #2563eb;
    color: #ffffff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    transition: background 0.2s ease;
}

.download-btn:hover {
    background: #1d4ed8;
}
</style>


<div class="nutrition-page">

    <div class="report-header">

        <h1>
            Nutrition &amp; Malnutrition Report
        </h1>

        <p>
            Reporting period:
            <strong>{{ $periodLabel }}</strong>
        </p>
<a
    href="{{ route('reports.nutrition.download', ['period' => $period]) }}"
    class="download-btn"
>
    ↓ Download PDF
</a>
    </div>


    <div class="summary-grid">

        <div class="summary-card">

            <div class="summary-label">
                Children Affected
            </div>

            <div class="summary-value">
                {{ $affectedChildren }}
            </div>

        </div>


        <div class="summary-card severe">

            <div class="summary-label">
                Severe / Critical
            </div>

            <div class="summary-value">
                {{ $severeCount }}
            </div>

        </div>


        <div class="summary-card moderate">

            <div class="summary-label">
                Moderate
            </div>

            <div class="summary-value">
                {{ $moderateCount }}
            </div>

        </div>


        <div class="summary-card attention">

            <div class="summary-label">
                Attention Required
            </div>

            <div class="summary-value">
                {{ $attentionCount }}
            </div>

        </div>

    </div>


    <section class="report-panel">

        <div class="panel-header">

            <h3>
                Children With Nutritional Monitoring Findings
            </h3>

            <p>
                Recorded assessments requiring nutritional or growth-monitoring review.
            </p>

        </div>


        <div class="table-wrap">

            @if($nutritionRecords->count())

                <table>

                    <thead>

                        <tr>

                            <th>Child</th>

                            <th>Visit Date</th>

                            <th>Overall Status</th>

                            <th>WFA</th>

                            <th>HFA</th>

                            <th>MUAC</th>

                            <th>Attention</th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($nutritionRecords as $record)

                            @php

                                /*
                                 * A health record may not have a growth assessment.
                                 * Therefore, $assessment can be null.
                                 */
                                $assessment = $record->growthAssessment;

                                $status = strtolower(
                                    trim(
                                        (string) ($assessment?->overall_status ?? '')
                                    )
                                );

                                if (
                                    str_contains($status, 'severe') ||
                                    str_contains($status, 'danger') ||
                                    str_contains($status, 'critical')
                                ) {

                                    $statusClass = 'severe';

                                } elseif (
                                    str_contains($status, 'moderate')
                                ) {

                                    $statusClass = 'moderate';

                                } elseif (
                                    str_contains($status, 'risk') ||
                                    str_contains($status, 'overweight') ||
                                    str_contains($status, 'obese')
                                ) {

                                    $statusClass = 'risk';

                                } else {

                                    $statusClass = 'normal';

                                }

                            @endphp


                            <tr>

                                <td>

                                    <span class="child-name">

                                        {{ $record->child->first_name }}
                                        {{ $record->child->last_name }}

                                    </span>


                                    <span class="child-id">

                                        {{ $record->child->child_id }}

                                    </span>

                                </td>


                                <td>

                                    {{ \Carbon\Carbon::parse($record->visit_date)->format('d M Y') }}

                                </td>


                                <td>

                                    <span class="status {{ $statusClass }}">

                                        {{ $assessment?->overall_status ?? '—' }}

                                    </span>

                                </td>


                                <td>

                                    {{ $assessment?->weight_for_age_zscore !== null
                                        ? number_format(
                                            (float) $assessment->weight_for_age_zscore,
                                            2
                                        )
                                        : '—'
                                    }}

                                </td>


                                <td>

                                    {{ $assessment?->height_for_age_zscore !== null
                                        ? number_format(
                                            (float) $assessment->height_for_age_zscore,
                                            2
                                        )
                                        : '—'
                                    }}

                                </td>


                                <td>

                                    {{ $record->muac !== null
                                        ? number_format(
                                            (float) $record->muac,
                                            1
                                        ) . ' cm'
                                        : '—'
                                    }}

                                </td>


                                <td>

                                    @if($assessment?->requires_attention)

                                        <span class="attention">
                                            Yes
                                        </span>

                                    @else

                                        No

                                    @endif

                                </td>

                            </tr>


                        @endforeach

                    </tbody>

                </table>


            @else

                <div class="empty-state">

                    No nutritional monitoring findings were recorded
                    during the selected reporting period.

                </div>

            @endif

        </div>

    </section>


    <div class="footer-note">

        <strong>Important:</strong>

        This report is based on recorded growth-monitoring assessments.
        It supports screening and monitoring and should be interpreted
        together with appropriate clinical assessment.

    </div>


</div>

@endsection