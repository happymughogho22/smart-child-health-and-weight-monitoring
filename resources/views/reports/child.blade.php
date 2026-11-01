@extends('layouts.app')

@section('title', 'Child Health Report | Child Weight Monitoring System')
@section('page-title', 'Child Health Report')
@section('page-subtitle', 'Growth monitoring history, assessment and recommendations')

@section('content')

<style>
.child-report{
    width:100%;
    box-sizing:border-box
}

.child-report *{
    box-sizing:border-box
}

.report-header,
.section,
.info-card,
.chart-box{
    background:#fff;
    border:1px solid #e2e8f0;
    border-radius:9px
}

.report-header{
    padding:22px;
    margin-bottom:20px;
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:20px
}

.report-header h1{
    margin:0 0 5px;
    color:#123b6d;
    font-size:24px
}

.report-header p{
    margin:0;
    color:#64748b;
    font-size:11px
}

.report-actions{
    display:flex;
    gap:8px;
    flex-wrap:wrap
}

.report-button{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:9px 14px;
    border-radius:6px;
    text-decoration:none;
    border:0;
    cursor:pointer;
    font-size:11px;
    font-weight:700
}

.report-button-primary{
    background:#123b6d;
    color:#fff
}

.report-button-secondary{
    background:#f1f5f9;
    color:#334155;
    border:1px solid #e2e8f0
}

.report-button-success{
    background:#166534;
    color:#fff
}

.section{
    margin-bottom:20px;
    overflow:hidden
}

.section-header{
    background:#f8fafc;
    border-bottom:1px solid #e2e8f0;
    padding:14px 18px
}

.section-header h3{
    margin:0;
    color:#123b6d;
    font-size:13px
}

.section-body{
    padding:18px
}

.info-grid{
    display:grid;
    grid-template-columns:repeat(4,minmax(0,1fr));
    gap:14px;
    margin-bottom:20px
}

.info-card{
    padding:16px
}

.info-label{
    color:#64748b;
    font-size:9px;
    font-weight:700;
    text-transform:uppercase;
    margin-bottom:7px
}

.info-value{
    color:#123b6d;
    font-size:17px;
    font-weight:700
}

.child-info-grid{
    display:grid;
    grid-template-columns:repeat(2,minmax(0,1fr));
    gap:0 25px
}

.detail{
    padding:10px 0;
    border-bottom:1px solid #f1f5f9
}

.detail-label{
    color:#64748b;
    font-size:9px;
    font-weight:700;
    margin-bottom:4px
}

.detail-value{
    color:#334155;
    font-size:11px;
    font-weight:600
}

.chart-grid{
    display:grid;
    grid-template-columns:repeat(2,minmax(0,1fr));
    gap:20px
}

.chart-box{
    padding:15px;
    overflow:hidden
}

.chart-title{
    color:#123b6d;
    font-size:12px;
    font-weight:700;
    margin-bottom:10px
}

.chart-container{
    position:relative;
    width:100%;
    height:300px
}

.chart-container canvas{
    display:block;
    width:100% !important;
    height:100% !important
}

.status-grid{
    display:grid;
    grid-template-columns:repeat(3,minmax(0,1fr));
    gap:14px
}

.status-card{
    border-radius:8px;
    padding:15px;
    text-align:center
}

.status-normal{
    background:#f0fdf4;
    border:1px solid #bbf7d0
}

.status-monitor{
    background:#fffbeb;
    border:1px solid #fde68a
}

.status-attention{
    background:#fef2f2;
    border:1px solid #fecaca
}

.status-number{
    font-size:25px;
    font-weight:700
}

.status-label{
    font-size:9px;
    font-weight:700;
    margin-top:4px
}

.recommendation{
    display:flex;
    gap:10px;
    padding:12px;
    background:#f8fafc;
    border:1px solid #e2e8f0;
    border-radius:7px;
    margin-bottom:9px
}

.recommendation-number{
    width:23px;
    height:23px;
    flex-shrink:0;
    border-radius:50%;
    background:#123b6d;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:10px;
    font-weight:700
}

.recommendation-text{
    color:#334155;
    font-size:11px;
    line-height:1.6
}

.history-table{
    width:100%;
    border-collapse:collapse;
    font-size:10px
}

.history-table th{
    background:#f8fafc;
    color:#64748b;
    text-align:left;
    padding:10px;
    border-bottom:1px solid #e2e8f0;
    font-size:9px
}

.history-table td{
    padding:10px;
    border-bottom:1px solid #f1f5f9;
    color:#334155
}

.notice{
    background:#eaf2fb;
    border:1px solid #d4e4f7;
    border-radius:8px;
    padding:14px;
    color:#365574;
    font-size:10px;
    line-height:1.6
}

.success-message{
    background:#f0fdf4;
    border:1px solid #bbf7d0;
    color:#166534;
    border-radius:7px;
    padding:12px;
    margin-bottom:18px;
    font-size:11px;
    font-weight:600
}

.empty{
    padding:25px;
    background:#f8fafc;
    border:1px dashed #cbd5e1;
    border-radius:7px;
    color:#64748b;
    font-size:11px;
    text-align:center
}

@media(max-width:900px){

    .info-grid{
        grid-template-columns:repeat(2,minmax(0,1fr))
    }

    .chart-grid{
        grid-template-columns:1fr
    }

    .status-grid{
        grid-template-columns:1fr
    }

    .report-header{
        flex-direction:column
    }
}

@media(max-width:600px){

    .info-grid,
    .child-info-grid{
        grid-template-columns:1fr
    }

    .report-actions{
        width:100%;
        flex-direction:column
    }

    .report-button{
        width:100%
    }
}
</style>


@php

    /*
    |--------------------------------------------------------------------------
    | Normalize Measurement Data
    |--------------------------------------------------------------------------
    */

    $measurementDataCollection = collect(
        $measurementData ?? []
    );

@endphp


<div class="child-report">


    {{-- Success Message --}}

    @if(session('success'))

        <div class="success-message">
            {{ session('success') }}
        </div>

    @endif


    {{-- ================================================================
         REPORT HEADER
         ================================================================ --}}

    <section class="report-header">

        <div>

            <h1>
                {{ $child->first_name }}
                {{ $child->last_name }}
            </h1>

            <p>
                Child Health &amp; Growth Monitoring Report
            </p>

            <p style="margin-top:7px">

                Child ID:
                <strong>
                    {{ $child->child_id }}
                </strong>

                @if($reportStart && $reportEnd)

                    &nbsp; • &nbsp;

                    Reporting period:

                    {{ $reportStart->format('d M Y') }}

                    –

                    {{ $reportEnd->format('d M Y') }}

                @endif

            </p>

        </div>


        <div class="report-actions">

            <a
                href="{{ route('reports.child.download', ['child' => $child->id]) }}"
                class="report-button report-button-primary"
            >
                Download PDF
            </a>


            <button
                type="button"
                class="report-button report-button-success"
                onclick="document.getElementById('shareGuardian').style.display='block'"
            >
                Share with Guardian
            </button>


            <a
                href="{{ route('children.show', $child) }}"
                class="report-button report-button-secondary"
            >
                Back to Child
            </a>

        </div>

    </section>


    {{-- ================================================================
         SHARE WITH GUARDIAN
         ================================================================ --}}

    <section
        id="shareGuardian"
        class="section"
        style="display:none"
    >

        <div class="section-header">

            <h3>
                Share Report with Guardian
            </h3>

        </div>


        <div class="section-body">

            <form
                action="{{ route('children.report.share', $child) }}"
                method="POST"
            >

                @csrf


                <div
                    style="
                        display:grid;
                        grid-template-columns:minmax(0,1fr) auto;
                        gap:10px;
                        align-items:end
                    "
                >

                    <div>

                        <label
                            style="
                                display:block;
                                color:#64748b;
                                font-size:10px;
                                font-weight:700;
                                margin-bottom:6px
                            "
                        >
                            Guardian Email
                        </label>


                        <input
                            type="email"
                            name="guardian_email"
                            value="{{ old('guardian_email', $child->guardian_email ?? '') }}"
                            placeholder="guardian@example.com"
                            required
                            style="
                                width:100%;
                                padding:10px;
                                border:1px solid #cbd5e1;
                                border-radius:6px;
                                font-size:11px
                            "
                        >

                    </div>


                    <button
                        type="submit"
                        class="report-button report-button-success"
                    >
                        Send Report
                    </button>

                </div>


                @error('guardian_email')

                    <div
                        style="
                            color:#be123c;
                            font-size:10px;
                            margin-top:6px
                        "
                    >
                        {{ $message }}
                    </div>

                @enderror


                <div
                    style="
                        color:#64748b;
                        font-size:9px;
                        margin-top:8px
                    "
                >
                    The report will be attached as a PDF.
                </div>

            </form>

        </div>

    </section>


    {{-- ================================================================
         CHILD INFORMATION
         ================================================================ --}}

    <section class="section">

        <div class="section-header">

            <h3>
                Child Information
            </h3>

        </div>


        <div class="section-body">

            <div class="child-info-grid">


                <div class="detail">

                    <div class="detail-label">
                        Child Name
                    </div>

                    <div class="detail-value">

                        {{ $child->first_name }}
                        {{ $child->last_name }}

                    </div>

                </div>


                <div class="detail">

                    <div class="detail-label">
                        Child ID
                    </div>

                    <div class="detail-value">
                        {{ $child->child_id }}
                    </div>

                </div>


                <div class="detail">

                    <div class="detail-label">
                        Sex
                    </div>

                    <div class="detail-value">
                        {{ $child->sex ?? '—' }}
                    </div>

                </div>


                <div class="detail">

                    <div class="detail-label">
                        Date of Birth
                    </div>

                    <div class="detail-value">

                        {{
                            $child->date_of_birth
                                ? \Carbon\Carbon::parse(
                                    $child->date_of_birth
                                )->format('d F Y')
                                : '—'
                        }}

                    </div>

                </div>


                <div class="detail">

                    <div class="detail-label">
                        Guardian
                    </div>

                    <div class="detail-value">
                        {{ $child->guardian_name ?? '—' }}
                    </div>

                </div>


                <div class="detail">

                    <div class="detail-label">
                        Guardian Email
                    </div>

                    <div class="detail-value">
                        {{ $child->guardian_email ?? '—' }}
                    </div>

                </div>


                <div class="detail">

                    <div class="detail-label">
                        Guardian Phone
                    </div>

                    <div class="detail-value">
                        {{ $child->guardian_phone ?? '—' }}
                    </div>

                </div>


                <div class="detail">

                    <div class="detail-label">
                        Health Facility
                    </div>

                    <div class="detail-value">
                        {{ $facilityName ?? '—' }}
                    </div>

                </div>


            </div>

        </div>

    </section>


    {{-- ================================================================
         MONITORING SUMMARY
         ================================================================ --}}

    <section class="section">

        <div class="section-header">

            <h3>
                Monitoring Summary
            </h3>

        </div>


        <div class="section-body">


            <div class="info-grid">


                <div class="info-card">

                    <div class="info-label">
                        Latest Weight
                    </div>

                    <div class="info-value">

                        {{
                            $latestRecord &&
                            $latestRecord->weight !== null

                                ? number_format(
                                    (float)$latestRecord->weight,
                                    2
                                ) . ' kg'

                                : '—'
                        }}

                    </div>

                </div>


                <div class="info-card">

                    <div class="info-label">
                        Latest Height
                    </div>

                    <div class="info-value">

                        {{
                            $latestRecord &&
                            $latestRecord->height !== null

                                ? number_format(
                                    (float)$latestRecord->height,
                                    2
                                ) . ' cm'

                                : '—'
                        }}

                    </div>

                </div>


                <div class="info-card">

                    <div class="info-label">
                        Latest MUAC
                    </div>

                    <div class="info-value">

                        {{
                            $latestRecord &&
                            $latestRecord->muac !== null

                                ? number_format(
                                    (float)$latestRecord->muac,
                                    1
                                ) . ' cm'

                                : '—'
                        }}

                    </div>

                </div>


                <div class="info-card">

                    <div class="info-label">
                        Latest BMI
                    </div>

                    <div class="info-value">

                        {{
                            $latestRecord &&
                            $latestRecord->bmi !== null

                                ? number_format(
                                    (float)$latestRecord->bmi,
                                    2
                                )

                                : '—'
                        }}

                    </div>

                </div>


            </div>


            <div class="status-grid">


                <div class="status-card status-normal">

                    <div class="status-number">
                        {{ $normalCount }}
                    </div>

                    <div class="status-label">
                        Normal Assessments
                    </div>

                </div>


                <div class="status-card status-monitor">

                    <div class="status-number">
                        {{ $monitorCount }}
                    </div>

                    <div class="status-label">
                        Monitor / Risk
                    </div>

                </div>


                <div class="status-card status-attention">

                    <div class="status-number">
                        {{ $attentionCount }}
                    </div>

                    <div class="status-label">
                        Attention
                    </div>

                </div>


            </div>

        </div>

    </section>


    {{-- ================================================================
         GROWTH TRENDS
         ================================================================ --}}

    <section class="section">

        <div class="section-header">

            <h3>
                Growth Trends
            </h3>

        </div>


        <div class="section-body">

            @if($measurementDataCollection->count() > 0)


                <div class="chart-grid">


                    {{-- WEIGHT CHART --}}

                    <div class="chart-box">

                        <div class="chart-title">
                            Weight Trend
                        </div>

                        <div class="chart-container">

                            <canvas
                                id="weightChart"
                            ></canvas>

                        </div>

                    </div>


                    {{-- HEIGHT CHART --}}

                    <div class="chart-box">

                        <div class="chart-title">
                            Height / Length Trend
                        </div>

                        <div class="chart-container">

                            <canvas
                                id="heightChart"
                            ></canvas>

                        </div>

                    </div>


                    {{-- BMI CHART --}}

                    <div class="chart-box">

                        <div class="chart-title">
                            BMI Trend
                        </div>

                        <div class="chart-container">

                            <canvas
                                id="bmiChart"
                            ></canvas>

                        </div>

                    </div>


                    {{-- MUAC CHART --}}

                    <div class="chart-box">

                        <div class="chart-title">
                            MUAC Trend
                        </div>

                        <div class="chart-container">

                            <canvas
                                id="muacChart"
                            ></canvas>

                        </div>

                    </div>


                </div>


            @else

                <div class="empty">
                    No growth measurements are available yet.
                </div>

            @endif

        </div>

    </section>


    {{-- ================================================================
         LATEST GROWTH ASSESSMENT
         ================================================================ --}}

    <section class="section">

        <div class="section-header">

            <h3>
                Latest Growth Assessment
            </h3>

        </div>


        <div class="section-body">


            @if($latestAssessment)


                <div class="child-info-grid">


                    <div class="detail">

                        <div class="detail-label">
                            Overall Status
                        </div>

                        <div class="detail-value">

                            {{
                                $latestAssessment->overall_status
                                ?? $latestAssessment->status
                                ?? 'Not assessed'
                            }}

                        </div>

                    </div>


                    <div class="detail">

                        <div class="detail-label">
                            Attention Required
                        </div>

                        <div class="detail-value">

                            {{
                                $latestAssessment->requires_attention
                                    ? 'Yes'
                                    : 'No'
                            }}

                        </div>

                    </div>


                    <div class="detail">

                        <div class="detail-label">
                            Weight-for-Age
                        </div>

                        <div class="detail-value">

                            {{
                                $latestAssessment->weight_for_age_status
                                ?? 'Not assessed'
                            }}

                            @if(
                                $latestAssessment->weight_for_age_zscore !== null
                            )

                                —
                                {{
                                    number_format(
                                        (float)
                                        $latestAssessment
                                            ->weight_for_age_zscore,
                                        3
                                    )
                                }}

                            @endif

                        </div>

                    </div>


                    <div class="detail">

                        <div class="detail-label">
                            Height-for-Age
                        </div>

                        <div class="detail-value">

                            {{
                                $latestAssessment->height_for_age_status
                                ?? 'Not assessed'
                            }}

                            @if(
                                $latestAssessment->height_for_age_zscore !== null
                            )

                                —
                                {{
                                    number_format(
                                        (float)
                                        $latestAssessment
                                            ->height_for_age_zscore,
                                        3
                                    )
                                }}

                            @endif

                        </div>

                    </div>


                    <div class="detail">

                        <div class="detail-label">
                            Weight-for-Length / Height
                        </div>

                        <div class="detail-value">

                            {{
                                $latestAssessment->weight_for_length_status
                                ?? $latestAssessment->weight_for_height_status
                                ?? 'Not assessed'
                            }}

                        </div>

                    </div>


                    <div class="detail">

                        <div class="detail-label">
                            BMI-for-Age
                        </div>

                        <div class="detail-value">

                            {{
                                $latestAssessment->bmi_for_age_status
                                ?? 'Not assessed'
                            }}

                        </div>

                    </div>


                    <div class="detail">

                        <div class="detail-label">
                            MUAC
                        </div>

                        <div class="detail-value">

                            {{
                                $latestAssessment->muac_status
                                ?? 'Not assessed'
                            }}

                        </div>

                    </div>


                    <div class="detail">

                        <div class="detail-label">
                            Age at Assessment
                        </div>

                        <div class="detail-value">

                            {{
                                $latestAssessment->age_in_months !== null

                                    ? $latestAssessment
                                        ->age_in_months
                                        . ' months'

                                    : '—'
                            }}

                        </div>

                    </div>


                </div>


            @else

                <div class="empty">

                    No growth assessment has been generated
                    for the latest health record.

                </div>

            @endif

        </div>

    </section>


    {{-- ================================================================
         RECOMMENDATIONS
         ================================================================ --}}

    <section class="section">

        <div class="section-header">

            <h3>
                Monitoring Recommendations
            </h3>

        </div>


        <div class="section-body">


            @forelse(
                $recommendations as $index => $recommendation
            )


                <div class="recommendation">


                    <div class="recommendation-number">

                        {{ $index + 1 }}

                    </div>


                    <div class="recommendation-text">

                        {{ $recommendation }}

                    </div>


                </div>


            @empty

                <div class="empty">

                    No monitoring recommendations are available.

                </div>

            @endforelse

        </div>

    </section>


    {{-- ================================================================
         FOLLOW-UP
         ================================================================ --}}

    <section class="section">

        <div class="section-header">

            <h3>
                Follow-Up
            </h3>

        </div>


        <div class="section-body">


            @if($followUpDate)


                <div class="info-card">

                    <div class="info-label">
                        Scheduled Follow-Up
                    </div>

                    <div class="info-value">

                        {{ $followUpDate->format('d F Y') }}

                    </div>

                </div>


            @else

                <div class="empty">

                    No follow-up date has been recorded.

                </div>

            @endif

        </div>

    </section>


    {{-- ================================================================
         COMPLETE GROWTH HISTORY
         ================================================================ --}}

    <section class="section">

        <div class="section-header">

            <h3>
                Complete Growth History
            </h3>

        </div>


        <div class="section-body">


            @if($measurementDataCollection->count() > 0)


                <div style="overflow-x:auto">

                    <table class="history-table">

                        <thead>

                            <tr>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Weight
                                </th>

                                <th>
                                    Height / Length
                                </th>

                                <th>
                                    MUAC
                                </th>

                                <th>
                                    BMI
                                </th>

                                <th>
                                    Nutrition
                                </th>

                                <th>
                                    Assessment
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            @foreach(
                                $measurementDataCollection
                                as $record
                            )


                                <tr>

                                    <td>
                                        {{ $record['date'] }}
                                    </td>


                                    <td>

                                        {{
                                            $record['weight'] !== null
                                                ? $record['weight'].' kg'
                                                : '—'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $record['height'] !== null
                                                ? $record['height'].' cm'
                                                : '—'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $record['muac'] !== null
                                                ? $record['muac'].' cm'
                                                : '—'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $record['bmi'] !== null
                                                ? $record['bmi']
                                                : '—'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $record['nutrition_status']
                                            ?? '—'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $record['overall_status']
                                            ?? 'Not assessed'
                                        }}

                                    </td>

                                </tr>


                            @endforeach


                        </tbody>

                    </table>

                </div>


            @else

                <div class="empty">

                    No health records available.

                </div>

            @endif

        </div>

    </section>


    {{-- ================================================================
         LATEST CLINICAL VISIT
         ================================================================ --}}

    @if($latestRecord)


        <section class="section">

            <div class="section-header">

                <h3>
                    Latest Clinical Visit
                </h3>

            </div>


            <div class="section-body">


                <div class="child-info-grid">


                    <div class="detail">

                        <div class="detail-label">
                            Visit Date
                        </div>

                        <div class="detail-value">

                            {{
                                $latestRecord->visit_date
                                    ? \Carbon\Carbon::parse(
                                        $latestRecord->visit_date
                                    )->format('d F Y')
                                    : '—'
                            }}

                        </div>

                    </div>


                    <div class="detail">

                        <div class="detail-label">
                            Health Facility
                        </div>

                        <div class="detail-value">

                            {{
                                $latestRecord->health_facility
                                ?? '—'
                            }}

                        </div>

                    </div>


                    <div class="detail">

                        <div class="detail-label">
                            Health Worker
                        </div>

                        <div class="detail-value">

                            {{
                                $latestRecord->health_worker
                                ?? '—'
                            }}

                        </div>

                    </div>


                    <div class="detail">

                        <div class="detail-label">
                            Temperature
                        </div>

                        <div class="detail-value">

                            {{
                                $latestRecord->temperature !== null
                                    ? $latestRecord->temperature.' °C'
                                    : '—'
                            }}

                        </div>

                    </div>


                    <div class="detail">

                        <div class="detail-label">
                            Clinical Observations
                        </div>

                        <div class="detail-value">

                            {{
                                $latestRecord->clinical_observations
                                ?? $latestRecord->clinical_remarks
                                ?? '—'
                            }}

                        </div>

                    </div>


                    <div class="detail">

                        <div class="detail-label">
                            Symptoms
                        </div>

                        <div class="detail-value">

                            {{
                                $latestRecord->symptoms
                                ?? '—'
                            }}

                        </div>

                    </div>


                    <div class="detail">

                        <div class="detail-label">
                            Doctor's Remarks
                        </div>

                        <div class="detail-value">

                            {{
                                $latestRecord->doctor_remarks
                                ?? $latestRecord->clinical_remarks
                                ?? '—'
                            }}

                        </div>

                    </div>


                    <div class="detail">

                        <div class="detail-label">
                            Recorded Recommendation
                        </div>

                        <div class="detail-value">

                            {{
                                $latestRecord->recommendations
                                ?? '—'
                            }}

                        </div>

                    </div>


                </div>

            </div>

        </section>

    @endif


    {{-- ================================================================
         NOTICE
         ================================================================ --}}

    <div class="notice">

        <strong>
            Important monitoring notice:
        </strong>

        This report summarizes recorded child health and growth
        monitoring information. Growth indicators are intended
        to support screening and monitoring. They should not be
        treated as a medical diagnosis. Any concerning result
        should be reviewed by an appropriately qualified
        health worker.

    </div>

</div>


{{-- ================================================================
     CHART.JS
     ================================================================ --}}

@if($measurementDataCollection->count() > 0)

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>

        /*
        |--------------------------------------------------------------------------
        | Report Measurement Data
        |--------------------------------------------------------------------------
        */

        const reportData = @json(
            $measurementDataCollection->values()
        );


        /*
        |--------------------------------------------------------------------------
        | Common Chart Configuration
        |--------------------------------------------------------------------------
        |
        | These settings are deliberately kept consistent across
        | all four growth trend charts.
        |
        */

        const chartOptions = {

            responsive: true,

            maintainAspectRatio: false,

            interaction: {
                mode: 'index',
                intersect: false
            },

            plugins: {

                legend: {
                    display: false
                }

            },

            scales: {

                x: {

                    grid: {
                        display: false
                    },

                    ticks: {
                        maxRotation: 45,
                        minRotation: 0,
                        autoSkip: true,
                        maxTicksLimit: 8
                    }

                },

                y: {

                    beginAtZero: false,

                    ticks: {
                        precision: 2
                    }

                }

            }

        };


        /*
        |--------------------------------------------------------------------------
        | Create Line Chart
        |--------------------------------------------------------------------------
        */

        function createLineChart(
            canvasId,
            label,
            field
        ) {

            const canvas =
                document.getElementById(canvasId);


            if (!canvas) {
                return;
            }


            const labels =
                reportData.map(
                    record => record.date
                );


            const values =
                reportData.map(
                    record => {

                        const value =
                            record[field];


                        if (
                            value === null ||
                            value === undefined ||
                            value === ''
                        ) {

                            return null;
                        }


                        const number =
                            Number(value);


                        return Number.isFinite(number)
                            ? number
                            : null;

                    }
                );


            new Chart(
                canvas,
                {

                    type: 'line',

                    data: {

                        labels: labels,

                        datasets: [

                            {

                                label: label,

                                data: values,

                                borderWidth: 2,

                                tension: 0.3,

                                spanGaps: true,

                                pointRadius: 4,

                                pointHoverRadius: 6,

                                fill: false,

                                borderColor: 'rgb(54, 162, 235)',

                                pointBackgroundColor:
                                    'rgb(54, 162, 235)',

                                pointBorderColor:
                                    'rgb(54, 162, 235)'

                            }

                        ]

                    },

                    options: chartOptions

                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Render All Four Growth Trend Charts
        |--------------------------------------------------------------------------
        */

        createLineChart(
            'weightChart',
            'Weight',
            'weight'
        );


        createLineChart(
            'heightChart',
            'Height / Length',
            'height'
        );


        createLineChart(
            'bmiChart',
            'BMI',
            'bmi'
        );


        createLineChart(
            'muacChart',
            'MUAC',
            'muac'
        );

    </script>

@endif

@endsection