@extends('layouts.app')

@section('title', 'Health Record | Child Weight Monitoring System')

@section('page-title', 'Health Record')

@section(
    'page-subtitle',
    'Clinical visit and growth monitoring details'
)


@section('content')


@php

    /*
    |--------------------------------------------------------------------------
    | CURRENT GROWTH ASSESSMENT
    |--------------------------------------------------------------------------
    */

    $growthAssessment =
        $healthRecord->growthAssessment;


    /*
    |--------------------------------------------------------------------------
    | STATUS CLASS HELPER
    |--------------------------------------------------------------------------
    */

    $statusClass = function ($status) {

        if (!$status) {
            return 'status-not-assessed';
        }

        $status = strtolower(trim($status));


        if ($status === 'normal') {
            return 'status-normal';
        }


        if (
            str_contains($status, 'at risk') ||
            str_contains($status, 'risk') ||
            str_contains($status, 'overweight') ||
            str_contains($status, 'stunting')
        ) {

            return 'status-risk';
        }


        if (
            str_contains($status, 'moderate') ||
            str_contains($status, 'monitor') ||
            str_contains($status, 'warning')
        ) {

            return 'status-moderate';
        }


        if (
            str_contains($status, 'severe') ||
            str_contains($status, 'obese') ||
            str_contains($status, 'danger') ||
            str_contains($status, 'critical')
        ) {

            return 'status-severe';
        }


        return 'status-not-assessed';

    };


    /*
    |--------------------------------------------------------------------------
    | CHART DATA
    |--------------------------------------------------------------------------
    */

    $chartLabels =
        $growthChartData
            ->pluck('date')
            ->values();


    $weightData =
        $growthChartData
            ->pluck('weight')
            ->values();


    $heightData =
        $growthChartData
            ->pluck('height')
            ->values();


    $bmiData =
        $growthChartData
            ->pluck('bmi')
            ->values();


    $muacData =
        $growthChartData
            ->pluck('muac')
            ->values();


@endphp



<style>

/* =========================================================
   PAGE
========================================================= */

.health-record-page {

    width:100%;

    max-width:100%;

    box-sizing:border-box;
}


.health-record-page * {

    box-sizing:border-box;
}


/* =========================================================
   BREADCRUMB
========================================================= */

.record-breadcrumb {

    display:flex;

    align-items:center;

    gap:8px;

    flex-wrap:wrap;

    margin-bottom:20px;

    font-size:11px;

    color:#94a3b8;
}


.record-breadcrumb a {

    color:#123b6d;

    text-decoration:none;

    font-weight:600;
}


.record-breadcrumb a:hover {

    text-decoration:underline;
}


/* =========================================================
   HEADER
========================================================= */

.record-header {

    background:#ffffff;

    border:1px solid #e2e8f0;

    border-radius:10px;

    padding:22px;

    margin-bottom:20px;

    display:flex;

    justify-content:space-between;

    align-items:center;

    gap:20px;
}


.record-header-left {

    min-width:0;
}


.record-title {

    color:#123b6d;

    font-size:23px;

    font-weight:700;

    margin:0 0 5px;
}


.record-subtitle {

    color:#64748b;

    font-size:11px;

    margin:0 0 12px;
}


.record-meta {

    display:flex;

    flex-wrap:wrap;

    gap:8px;
}


.meta-badge {

    display:inline-flex;

    align-items:center;

    background:#eaf2fb;

    color:#123b6d;

    padding:6px 10px;

    border-radius:5px;

    font-size:10px;

    font-weight:700;
}


/* =========================================================
   ACTIONS
========================================================= */

.record-actions {

    display:flex;

    gap:8px;

    align-items:center;

    flex-wrap:wrap;

    flex-shrink:0;
}


.record-action {

    display:inline-flex;

    align-items:center;

    justify-content:center;

    min-height:36px;

    padding:8px 13px;

    border-radius:6px;

    text-decoration:none;

    border:none;

    cursor:pointer;

    font-family:inherit;

    font-size:11px;

    font-weight:700;

    white-space:nowrap;
}


.record-action-secondary {

    background:#f1f5f9;

    color:#334155;

    border:1px solid #e2e8f0;
}


.record-action-primary {

    background:#123b6d;

    color:#ffffff;
}


.record-action-success {

    background:#166534;

    color:#ffffff;
}


.record-action-share {

    background:#0f766e;

    color:#ffffff;
}


.record-action-danger {

    background:#fff1f2;

    color:#be123c;

    border:1px solid #fecdd3;
}


.record-action:hover {

    opacity:.9;
}


/* =========================================================
   MEASUREMENTS
========================================================= */

.measurement-grid {

    display:grid;

    grid-template-columns:
        repeat(4,minmax(0,1fr));

    gap:14px;

    margin-bottom:20px;
}


.measurement-card {

    background:#ffffff;

    border:1px solid #e2e8f0;

    border-radius:9px;

    padding:17px;
}


.measurement-label {

    color:#64748b;

    font-size:9px;

    font-weight:700;

    margin-bottom:8px;

    letter-spacing:.3px;
}


.measurement-value {

    color:#123b6d;

    font-size:21px;

    font-weight:700;

    line-height:1.2;
}


.measurement-unit {

    color:#64748b;

    font-size:10px;

    font-weight:500;
}


/* =========================================================
   GENERAL CARDS
========================================================= */

.record-grid {

    display:grid;

    grid-template-columns:
        repeat(2,minmax(0,1fr));

    gap:20px;

    margin-bottom:20px;
}


.record-card {

    background:#ffffff;

    border:1px solid #e2e8f0;

    border-radius:9px;

    overflow:hidden;
}


.record-card-header {

    padding:15px 18px;

    border-bottom:1px solid #e2e8f0;

    background:#f8fafc;

    display:flex;

    justify-content:space-between;

    align-items:center;

    gap:12px;
}


.record-card-header h3 {

    margin:0;

    color:#123b6d;

    font-size:13px;

    font-weight:700;
}


.record-card-body {

    padding:18px;
}


/* =========================================================
   DETAIL ROW
========================================================= */

.detail-row {

    display:grid;

    grid-template-columns:
        145px
        minmax(0,1fr);

    gap:15px;

    padding:10px 0;

    border-bottom:1px solid #f1f5f9;
}


.detail-row:first-child {

    padding-top:0;
}


.detail-row:last-child {

    border-bottom:none;

    padding-bottom:0;
}


.detail-label {

    color:#64748b;

    font-size:10px;

    font-weight:600;
}


.detail-value {

    color:#334155;

    font-size:11px;

    font-weight:600;

    overflow-wrap:anywhere;
}


/* =========================================================
   TEXT
========================================================= */

.text-section {

    margin-bottom:18px;
}


.text-section:last-child {

    margin-bottom:0;
}


.text-section-title {

    color:#64748b;

    font-size:9px;

    font-weight:700;

    text-transform:uppercase;

    letter-spacing:.3px;

    margin-bottom:7px;
}


.text-section-content {

    background:#f8fafc;

    border:1px solid #eef2f7;

    border-radius:6px;

    padding:11px;

    color:#334155;

    font-size:11px;

    line-height:1.6;

    overflow-wrap:anywhere;
}


/* =========================================================
   STATUS
========================================================= */

.status-badge {

    display:inline-flex;

    align-items:center;

    justify-content:center;

    padding:6px 10px;

    border-radius:999px;

    font-size:10px;

    font-weight:700;

    white-space:nowrap;
}


.status-normal {

    background:#dcfce7;

    color:#166534;
}


.status-risk {

    background:#fef9c3;

    color:#854d0e;
}


.status-moderate {

    background:#ffedd5;

    color:#9a3412;
}


.status-severe {

    background:#fee2e2;

    color:#991b1b;
}


.status-monitoring {

    background:#eaf2fb;

    color:#123b6d;
}


.status-not-assessed {

    background:#e2e8f0;

    color:#475569;
}


/* =========================================================
   GROWTH ASSESSMENT
========================================================= */

.growth-assessment {

    background:#ffffff;

    border:1px solid #e2e8f0;

    border-radius:9px;

    overflow:hidden;

    margin-bottom:20px;
}


.growth-assessment-header {

    padding:15px 18px;

    border-bottom:1px solid #e2e8f0;

    background:#f8fafc;

    display:flex;

    justify-content:space-between;

    align-items:center;

    gap:15px;

    flex-wrap:wrap;
}


.growth-assessment-header h3 {

    margin:0;

    color:#123b6d;

    font-size:13px;

    font-weight:700;
}


.growth-assessment-body {

    padding:18px;
}


.growth-summary {

    display:grid;

    grid-template-columns:
        repeat(3,minmax(0,1fr));

    gap:14px;

    margin-bottom:18px;
}


.growth-summary-card {

    background:#f8fafc;

    border:1px solid #eef2f7;

    border-radius:7px;

    padding:13px;
}


.growth-summary-label {

    color:#64748b;

    font-size:9px;

    font-weight:700;

    text-transform:uppercase;

    margin-bottom:7px;
}


.growth-summary-value {

    color:#123b6d;

    font-size:15px;

    font-weight:700;
}


.growth-indicators {

    display:grid;

    grid-template-columns:
        repeat(2,minmax(0,1fr));

    gap:12px;
}


.growth-indicator {

    border:1px solid #e2e8f0;

    border-radius:7px;

    padding:14px;

    background:#ffffff;
}


.growth-indicator-title {

    color:#123b6d;

    font-size:11px;

    font-weight:700;

    margin-bottom:10px;
}


.growth-indicator-row {

    display:flex;

    justify-content:space-between;

    align-items:center;

    gap:12px;

    padding:7px 0;

    border-bottom:1px solid #f1f5f9;
}


.growth-indicator-row:last-child {

    border-bottom:none;

    padding-bottom:0;
}


.growth-indicator-label {

    color:#64748b;

    font-size:10px;

    font-weight:600;
}


.growth-indicator-value {

    color:#334155;

    font-size:10px;

    font-weight:700;

    text-align:right;
}


/* =========================================================
   CHARTS
========================================================= */

.growth-history {

    background:#ffffff;

    border:1px solid #e2e8f0;

    border-radius:9px;

    overflow:hidden;

    margin-bottom:20px;
}


.growth-history-header {

    padding:15px 18px;

    border-bottom:1px solid #e2e8f0;

    background:#f8fafc;
}


.growth-history-header h3 {

    margin:0 0 4px;

    color:#123b6d;

    font-size:13px;
}


.growth-history-header p {

    margin:0;

    color:#64748b;

    font-size:10px;
}


.chart-grid {

    display:grid;

    grid-template-columns:
        repeat(2,minmax(0,1fr));

    gap:20px;

    padding:18px;
}


.chart-card {

    border:1px solid #e2e8f0;

    border-radius:8px;

    padding:15px;

    background:#ffffff;
}


.chart-card-title {

    color:#123b6d;

    font-size:11px;

    font-weight:700;

    margin-bottom:12px;
}


.chart-container {

    position:relative;

    height:260px;
}


.no-chart-data {

    background:#f8fafc;

    border:1px dashed #cbd5e1;

    border-radius:7px;

    padding:30px 15px;

    text-align:center;

    color:#64748b;

    font-size:10px;
}


/* =========================================================
   RECOMMENDATIONS
========================================================= */

.recommendations-card {

    background:#ffffff;

    border:1px solid #e2e8f0;

    border-radius:9px;

    overflow:hidden;

    margin-bottom:20px;
}


.recommendation-list {

    padding:18px;

    display:flex;

    flex-direction:column;

    gap:10px;
}


.recommendation-item {

    display:flex;

    gap:10px;

    padding:12px;

    background:#f8fafc;

    border:1px solid #eef2f7;

    border-radius:7px;

    color:#334155;

    font-size:10px;

    line-height:1.6;
}


.recommendation-number {

    width:22px;

    height:22px;

    min-width:22px;

    border-radius:50%;

    background:#123b6d;

    color:#ffffff;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:9px;

    font-weight:700;
}


/* =========================================================
   SHARE
========================================================= */

.share-box {

    background:#f0fdfa;

    border:1px solid #99f6e4;

    border-radius:8px;

    padding:18px;

    margin-bottom:20px;
}


.share-box h3 {

    margin:0 0 5px;

    color:#115e59;

    font-size:13px;
}


.share-box p {

    margin:0 0 15px;

    color:#475569;

    font-size:10px;

    line-height:1.5;
}


.share-form {

    display:flex;

    gap:8px;

    align-items:center;
}


.share-input {

    flex:1;

    min-width:0;

    height:38px;

    padding:8px 11px;

    border:1px solid #cbd5e1;

    border-radius:6px;

    font-family:inherit;

    font-size:11px;

    background:#ffffff;
}


.share-button {

    height:38px;

    padding:8px 15px;

    border:none;

    border-radius:6px;

    background:#0f766e;

    color:#ffffff;

    font-size:11px;

    font-weight:700;

    cursor:pointer;
}


.share-button:hover {

    opacity:.9;
}


/* =========================================================
   FOLLOW UP
========================================================= */

.follow-up-box {

    background:#eaf2fb;

    border:1px solid #d4e4f7;

    border-radius:7px;

    padding:14px;
}


.follow-up-date {

    color:#123b6d;

    font-size:16px;

    font-weight:700;

    margin-bottom:5px;
}


.follow-up-text {

    color:#365574;

    font-size:10px;

    line-height:1.5;
}


/* =========================================================
   NOTICE
========================================================= */

.record-notice {

    background:#eaf2fb;

    border:1px solid #d4e4f7;

    border-radius:8px;

    padding:15px;

    margin-top:20px;

    color:#365574;

    font-size:10px;

    line-height:1.6;
}


.record-notice strong {

    display:block;

    color:#123b6d;

    margin-bottom:5px;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width:1000px) {

    .measurement-grid {

        grid-template-columns:
            repeat(2,minmax(0,1fr));
    }


    .record-grid {

        grid-template-columns:1fr;
    }


    .growth-summary {

        grid-template-columns:1fr;
    }


    .growth-indicators {

        grid-template-columns:1fr;
    }


    .chart-grid {

        grid-template-columns:1fr;
    }


    .record-header {

        align-items:flex-start;

        flex-direction:column;
    }


    .record-actions {

        width:100%;
    }

}


@media(max-width:650px) {

    .measurement-grid {

        grid-template-columns:1fr;
    }


    .detail-row {

        grid-template-columns:1fr;

        gap:4px;
    }


    .growth-indicator-row {

        flex-direction:column;

        align-items:flex-start;
    }


    .growth-indicator-value {

        text-align:left;
    }


    .record-actions {

        flex-direction:column;

        align-items:stretch;
    }


    .record-action {

        width:100%;
    }


    .share-form {

        flex-direction:column;

        align-items:stretch;
    }


    .share-button {

        width:100%;
    }

}

</style>



<div class="health-record-page">


{{-- =========================================================
     BREADCRUMB
========================================================= --}}

<div class="record-breadcrumb">

    <a href="{{ route('dashboard') }}">
        Home
    </a>

    <span>/</span>

    <a href="{{ route('children.index') }}">
        Children
    </a>

    <span>/</span>

    <a href="{{ route('children.show', $child) }}">
        {{ $child->child_id }}
    </a>

    <span>/</span>

    <a href="{{ route('health-records.index', $child) }}">
        Health Records
    </a>

    <span>/</span>

    <strong>
        Record #{{ $healthRecord->id }}
    </strong>

</div>



{{-- =========================================================
     SUCCESS MESSAGE
========================================================= --}}

@if(session('success'))

<div
    style="
        background:#dcfce7;
        border:1px solid #bbf7d0;
        color:#166534;
        padding:12px 15px;
        border-radius:7px;
        margin-bottom:20px;
        font-size:11px;
        font-weight:600;
    "
>

    {{ session('success') }}

</div>

@endif



{{-- =========================================================
     VALIDATION ERRORS
========================================================= --}}

@if($errors->any())

<div
    style="
        background:#fef2f2;
        border:1px solid #fecaca;
        color:#991b1b;
        padding:12px 15px;
        border-radius:7px;
        margin-bottom:20px;
        font-size:11px;
    "
>

    @foreach($errors->all() as $error)

        <div>
            {{ $error }}
        </div>

    @endforeach

</div>

@endif



{{-- =========================================================
     HEADER
========================================================= --}}

<section class="record-header">

<div class="record-header-left">

<h1 class="record-title">
    Health Record
</h1>


<p class="record-subtitle">
    Clinical visit and growth monitoring information
</p>


<div class="record-meta">

<span class="meta-badge">
    Child ID: {{ $child->child_id }}
</span>


<span class="meta-badge">

    Visit:
    {{ \Carbon\Carbon::parse(
        $healthRecord->visit_date
    )->format('d M Y') }}

</span>


@if($healthRecord->monitoring_status)

<span class="status-badge status-monitoring">

    {{ $healthRecord->monitoring_status }}

</span>

@endif

</div>

</div>



<div class="record-actions">


<a
    href="{{ route(
        'health-records.index',
        $child
    ) }}"
    class="record-action record-action-secondary"
>
    ← Back to Records
</a>

<a
    href="{{ route('children.report', $child) }}"
    class="record-action record-action-primary"
>
    View Child Report
</a>

<a
    href="{{ route('children.report.download', $child) }}"
    class="record-action record-action-secondary"
>
    Download Report
</a>
<a
    href="{{ route(
        'health-records.edit',
        [$child,$healthRecord]
    ) }}"
    class="record-action record-action-primary"
>
    Edit Record
</a>


{{-- DOWNLOAD CHILD REPORT --}}

<a
    href="{{ route(
        'health-records.report',
        $child
    ) }}"
    class="record-action record-action-success"
>
    Download Report
</a>


{{-- SEND REMINDER --}}

@if($healthRecord->follow_up_date)

<form
    action="{{ route(
        'health-records.send-reminder',
        [$child,$healthRecord]
    ) }}"
    method="POST"
    style="margin:0;"
    onsubmit="
        return confirm(
            'Send a follow-up reminder to the guardian?'
        );
    "
>

@csrf

<button
    type="submit"
    class="record-action record-action-secondary"
>
    Send Reminder
</button>

</form>

@endif


{{-- DELETE --}}

<form
    action="{{ route(
        'health-records.destroy',
        [$child,$healthRecord]
    ) }}"
    method="POST"
    style="margin:0;"
    onsubmit="
        return confirm(
            'Are you sure you want to delete this health record?'
        );
    "
>

@csrf

@method('DELETE')

<button
    type="submit"
    class="record-action record-action-danger"
>
    Delete Record
</button>

</form>

</div>

</section>



{{-- =========================================================
     MEASUREMENTS
========================================================= --}}

<div class="measurement-grid">


<div class="measurement-card">

<div class="measurement-label">
    WEIGHT
</div>

<div class="measurement-value">

@if($healthRecord->weight !== null)

{{ $healthRecord->weight }}

<span class="measurement-unit">
    kg
</span>

@else

—

@endif

</div>

</div>



<div class="measurement-card">

<div class="measurement-label">

@if($healthRecord->measurement_type === 'length')

LENGTH

@else

HEIGHT

@endif

</div>


<div class="measurement-value">

@if($healthRecord->height !== null)

{{ $healthRecord->height }}

<span class="measurement-unit">
    cm
</span>

@else

—

@endif

</div>

</div>



<div class="measurement-card">

<div class="measurement-label">
    MUAC
</div>


<div class="measurement-value">

@if($healthRecord->muac !== null)

{{ $healthRecord->muac }}

<span class="measurement-unit">
    cm
</span>

@else

—

@endif

</div>

</div>



<div class="measurement-card">

<div class="measurement-label">
    BMI
</div>


<div class="measurement-value">

@if($healthRecord->bmi !== null)

{{ $healthRecord->bmi }}

@else

—

@endif

</div>

</div>

</div>



{{-- =========================================================
     SHARE REPORT
========================================================= --}}

<div class="share-box">

<h3>
    Share Child Health Report
</h3>


<p>

Send this child's current health and growth monitoring
information to the guardian by email.

</p>


<form
    action="{{ route(
        'health-records.share-report',
        [$child,$healthRecord]
    ) }}"
    method="POST"
    class="share-form"
>

@csrf


<input
    type="email"
    name="email"
    class="share-input"
    value="{{ old(
        'email',
        $child->guardian_email ?? ''
    ) }}"
    placeholder="Guardian email address"
    required
>


<button
    type="submit"
    class="share-button"
    onclick="
        return confirm(
            'Send this health report to the guardian?'
        );
    "
>
    Share by Email
</button>


</form>

</div>



{{-- =========================================================
     WHO GROWTH ASSESSMENT
========================================================= --}}

<section class="growth-assessment">


<div class="growth-assessment-header">

<h3>
    WHO Growth Assessment
</h3>


@if($growthAssessment)

<span
    class="status-badge
    {{ $statusClass(
        $growthAssessment->overall_status
    ) }}"
>

{{ $growthAssessment->overall_status
    ?? 'Not Assessed' }}

</span>

@else

<span class="status-badge status-not-assessed">
    Not Assessed
</span>

@endif

</div>



<div class="growth-assessment-body">


@if($growthAssessment)


<div class="growth-summary">


<div class="growth-summary-card">

<div class="growth-summary-label">
    Age at Visit
</div>

<div class="growth-summary-value">

@if($growthAssessment->age_in_months !== null)

{{ $growthAssessment->age_in_months }}
months

@else

Not recorded

@endif

</div>

</div>



<div class="growth-summary-card">

<div class="growth-summary-label">
    Overall Status
</div>

<div class="growth-summary-value">

{{ $growthAssessment->overall_status
    ?? 'Not Assessed' }}

</div>

</div>



<div class="growth-summary-card">

<div class="growth-summary-label">
    Attention Required
</div>

<div class="growth-summary-value">

@if($growthAssessment->requires_attention)

<span class="status-badge status-severe">
    Yes
</span>

@else

<span class="status-badge status-normal">
    No
</span>

@endif

</div>

</div>

</div>



{{-- INDICATORS --}}

<div class="growth-indicators">


<div class="growth-indicator">

<div class="growth-indicator-title">
    Weight-for-Age
</div>


<div class="growth-indicator-row">

<span class="growth-indicator-label">
    Z-score
</span>


<span class="growth-indicator-value">

@if(
    $growthAssessment->weight_for_age_zscore !== null
)

{{ number_format(
    (float)
    $growthAssessment->weight_for_age_zscore,
    3
) }}

@else

Not assessed

@endif

</span>

</div>


<div class="growth-indicator-row">

<span class="growth-indicator-label">
    Interpretation
</span>

<span class="growth-indicator-value">

{{ $growthAssessment->weight_for_age_status
    ?? 'Not assessed' }}

</span>

</div>

</div>



<div class="growth-indicator">

<div class="growth-indicator-title">

@if(
    $healthRecord->measurement_type === 'length'
)

Length-for-Age

@else

Height-for-Age

@endif

</div>


<div class="growth-indicator-row">

<span class="growth-indicator-label">
    Z-score
</span>

<span class="growth-indicator-value">

@if(
    $growthAssessment->height_for_age_zscore !== null
)

{{ number_format(
    (float)
    $growthAssessment->height_for_age_zscore,
    3
) }}

@else

Not assessed

@endif

</span>

</div>


<div class="growth-indicator-row">

<span class="growth-indicator-label">
    Interpretation
</span>

<span class="growth-indicator-value">

{{ $growthAssessment->height_for_age_status
    ?? 'Not assessed' }}

</span>

</div>

</div>



@if(
    $growthAssessment->weight_for_length_zscore !== null
)

<div class="growth-indicator">

<div class="growth-indicator-title">
    Weight-for-Length
</div>


<div class="growth-indicator-row">

<span class="growth-indicator-label">
    Z-score
</span>

<span class="growth-indicator-value">

{{ number_format(
    (float)
    $growthAssessment->weight_for_length_zscore,
    3
) }}

</span>

</div>


<div class="growth-indicator-row">

<span class="growth-indicator-label">
    Interpretation
</span>

<span class="growth-indicator-value">

{{ $growthAssessment->weight_for_length_status
    ?? 'Not assessed' }}

</span>

</div>

</div>

@endif



@if(
    $growthAssessment->weight_for_height_zscore !== null
)

<div class="growth-indicator">

<div class="growth-indicator-title">
    Weight-for-Height
</div>


<div class="growth-indicator-row">

<span class="growth-indicator-label">
    Z-score
</span>

<span class="growth-indicator-value">

{{ number_format(
    (float)
    $growthAssessment->weight_for_height_zscore,
    3
) }}

</span>

</div>


<div class="growth-indicator-row">

<span class="growth-indicator-label">
    Interpretation
</span>

<span class="growth-indicator-value">

{{ $growthAssessment->weight_for_height_status
    ?? 'Not assessed' }}

</span>

</div>

</div>

@endif



<div class="growth-indicator">

<div class="growth-indicator-title">
    BMI-for-Age
</div>


<div class="growth-indicator-row">

<span class="growth-indicator-label">
    Z-score
</span>

<span class="growth-indicator-value">

@if(
    $growthAssessment->bmi_for_age_zscore !== null
)

{{ number_format(
    (float)
    $growthAssessment->bmi_for_age_zscore,
    3
) }}

@else

Not assessed

@endif

</span>

</div>


<div class="growth-indicator-row">

<span class="growth-indicator-label">
    Interpretation
</span>

<span class="growth-indicator-value">

{{ $growthAssessment->bmi_for_age_status
    ?? 'Not assessed' }}

</span>

</div>

</div>



<div class="growth-indicator">

<div class="growth-indicator-title">
    MUAC Screening
</div>


<div class="growth-indicator-row">

<span class="growth-indicator-label">
    Measurement
</span>

<span class="growth-indicator-value">

@if($healthRecord->muac !== null)

{{ $healthRecord->muac }} cm

@else

Not recorded

@endif

</span>

</div>


<div class="growth-indicator-row">

<span class="growth-indicator-label">
    Interpretation
</span>

<span class="growth-indicator-value">

{{ $growthAssessment->muac_status
    ?? 'Not assessed' }}

</span>

</div>

</div>


</div>



@if($growthAssessment->requires_attention)

<div class="growth-attention">

<strong>
    Growth Assessment Requires Attention
</strong>

One or more growth indicators have been flagged
by the configured screening assessment.

</div>

@else

<div class="growth-no-attention">

<strong>
    No Immediate Growth Attention Flag
</strong>

The current growth assessment does not contain
an attention flag.

</div>

@endif



@if($growthAssessment->assessment_notes)

<div class="growth-notice">

<strong>
    Assessment Notes
</strong>

{{ $growthAssessment->assessment_notes }}

</div>

@endif



<div class="growth-notice">

<strong>
    Screening Notice
</strong>

These growth indicators are intended for screening
and growth monitoring. They should not be treated
as a medical diagnosis.

</div>


@else


<div class="text-section-content">

<strong>
    Growth assessment has not been generated for this
    health record.
</strong>

<br><br>

No corresponding growth-assessment record is currently
linked to this visit.

</div>

@endif


</div>

</section>



{{-- =========================================================
     GROWTH HISTORY CHARTS
========================================================= --}}

<section class="growth-history">


<div class="growth-history-header">

<h3>
    Child Growth History
</h3>

<p>
    Recorded measurements across the child's health visits.
</p>

</div>



<div class="chart-grid">


{{-- WEIGHT --}}

<div class="chart-card">

<div class="chart-card-title">
    Weight Trend
</div>

@if(
    $growthChartData->contains(
        fn($item) => $item['weight'] !== null
    )
)

<div class="chart-container">

<canvas id="weightChart"></canvas>

</div>

@else

<div class="no-chart-data">
    No weight history is available.
</div>

@endif

</div>



{{-- HEIGHT --}}

<div class="chart-card">

<div class="chart-card-title">
    Height / Length Trend
</div>

@if(
    $growthChartData->contains(
        fn($item) => $item['height'] !== null
    )
)

<div class="chart-container">

<canvas id="heightChart"></canvas>

</div>

@else

<div class="no-chart-data">
    No height/length history is available.
</div>

@endif

</div>



{{-- BMI --}}

<div class="chart-card">

<div class="chart-card-title">
    BMI Trend
</div>

@if(
    $growthChartData->contains(
        fn($item) => $item['bmi'] !== null
    )
)

<div class="chart-container">

<canvas id="bmiChart"></canvas>

</div>

@else

<div class="no-chart-data">
    No BMI history is available.
</div>

@endif

</div>



{{-- MUAC --}}

<div class="chart-card">

<div class="chart-card-title">
    MUAC Trend
</div>

@if(
    $growthChartData->contains(
        fn($item) => $item['muac'] !== null
    )
)

<div class="chart-container">

<canvas id="muacChart"></canvas>

</div>

@else

<div class="no-chart-data">
    No MUAC history is available.
</div>

@endif

</div>


</div>

</section>



{{-- =========================================================
     RECOMMENDATIONS
========================================================= --}}

<section class="recommendations-card">


<div class="record-card-header">

<h3>
    Monitoring Recommendations
</h3>

</div>


<div class="recommendation-list">


@if(!empty($recommendations))

@foreach($recommendations as $recommendation)

<div class="recommendation-item">

<div class="recommendation-number">
    {{ $loop->iteration }}
</div>

<div>
    {{ $recommendation }}
</div>

</div>

@endforeach

@else

<div class="recommendation-item">

<div class="recommendation-number">
    ✓
</div>

<div>

Continue routine growth monitoring and maintain
regular health visits according to the child's
care plan.

</div>

</div>

@endif


</div>

</section>



{{-- =========================================================
     VISIT + CHILD INFORMATION
========================================================= --}}

<div class="record-grid">


<section class="record-card">

<div class="record-card-header">

<h3>
    Visit Information
</h3>

</div>


<div class="record-card-body">


<div class="detail-row">

<div class="detail-label">
    Visit Date
</div>

<div class="detail-value">

{{ \Carbon\Carbon::parse(
    $healthRecord->visit_date
)->format('d F Y') }}

</div>

</div>


<div class="detail-row">

<div class="detail-label">
    Health Facility
</div>

<div class="detail-value">

{{ $healthRecord->facility_name ?? '—' }}

</div>

</div>


<div class="detail-row">

<div class="detail-label">
    Health Worker
</div>

<div class="detail-value">

{{ $healthRecord->health_worker_name ?? '—' }}

</div>

</div>


<div class="detail-row">

<div class="detail-label">
    Temperature
</div>

<div class="detail-value">

@if($healthRecord->temperature !== null)

{{ $healthRecord->temperature }} °C

@else

—

@endif

</div>

</div>


<div class="detail-row">

<div class="detail-label">
    Temperature Screening
</div>

<div class="detail-value">

@if($healthRecord->temperature_status)

<span
    class="status-badge
    {{
        $healthRecord->temperature_status ===
        'Fever Screening Flag'
            ? 'status-risk'
            : (
                $healthRecord->temperature_status ===
                'Within Recorded Range'
                    ? 'status-normal'
                    : 'status-not-assessed'
            )
    }}"
>

{{ $healthRecord->temperature_status }}

</span>

@else

<span class="status-badge status-not-assessed">
    Not Assessed
</span>

@endif

</div>

</div>


<div class="detail-row">

<div class="detail-label">
    Monitoring Status
</div>

<div class="detail-value">

@if($healthRecord->monitoring_status)

<span class="status-badge status-monitoring">

{{ $healthRecord->monitoring_status }}

</span>

@else

<span class="status-badge status-not-assessed">
    Not Set
</span>

@endif

</div>

</div>


<div class="detail-row">

<div class="detail-label">
    Nutrition Status
</div>

<div class="detail-value">

@if($healthRecord->nutrition_status)

<span
    class="status-badge
    {{ $statusClass(
        $healthRecord->nutrition_status
    ) }}"
>

{{ $healthRecord->nutrition_status }}

</span>

@else

<span class="status-badge status-not-assessed">
    Not Assessed
</span>

@endif

</div>

</div>

</div>

</section>



<section class="record-card">

<div class="record-card-header">

<h3>
    Child Information
</h3>

</div>


<div class="record-card-body">


<div class="detail-row">

<div class="detail-label">
    Child Name
</div>

<div class="detail-value">

{{ $child->first_name }}
{{ $child->last_name }}

</div>

</div>


<div class="detail-row">

<div class="detail-label">
    Child ID
</div>

<div class="detail-value">

{{ $child->child_id }}

</div>

</div>


<div class="detail-row">

<div class="detail-label">
    Sex
</div>

<div class="detail-value">

{{ $child->sex ?? '—' }}

</div>

</div>


<div class="detail-row">

<div class="detail-label">
    Date of Birth
</div>

<div class="detail-value">

@if($child->date_of_birth)

{{ \Carbon\Carbon::parse(
    $child->date_of_birth
)->format('d F Y') }}

@else

—

@endif

</div>

</div>


<div class="detail-row">

<div class="detail-label">
    Guardian
</div>

<div class="detail-value">

{{ $child->guardian_name ?? '—' }}

</div>

</div>


@if($child->guardian_phone)

<div class="detail-row">

<div class="detail-label">
    Guardian Phone
</div>

<div class="detail-value">

{{ $child->guardian_phone }}

</div>

</div>

@endif


@if($child->guardian_email)

<div class="detail-row">

<div class="detail-label">
    Guardian Email
</div>

<div class="detail-value">

{{ $child->guardian_email }}

</div>

</div>

@endif


</div>

</section>

</div>



{{-- =========================================================
     CLINICAL INFORMATION
========================================================= --}}

<div class="record-grid">


<section class="record-card">

<div class="record-card-header">

<h3>
    Clinical Assessment
</h3>

</div>


<div class="record-card-body">


@if($healthRecord->clinical_observations)

<div class="text-section">

<div class="text-section-title">
    Clinical Observations
</div>

<div class="text-section-content">

{{ $healthRecord->clinical_observations }}

</div>

</div>

@endif


@if($healthRecord->symptoms)

<div class="text-section">

<div class="text-section-title">
    Symptoms
</div>

<div class="text-section-content">

{{ $healthRecord->symptoms }}

</div>

</div>

@endif


@if($healthRecord->assessment)

<div class="text-section">

<div class="text-section-title">
    Clinical Assessment
</div>

<div class="text-section-content">

{{ $healthRecord->assessment }}

</div>

</div>

@endif


@if($healthRecord->doctor_remarks)

<div class="text-section">

<div class="text-section-title">
    Doctor's Remarks
</div>

<div class="text-section-content">

{{ $healthRecord->doctor_remarks }}

</div>

</div>

@endif


@if(
    !$healthRecord->clinical_observations &&
    !$healthRecord->symptoms &&
    !$healthRecord->assessment &&
    !$healthRecord->doctor_remarks
)

<div class="text-section-content">

No clinical assessment information was recorded
for this visit.

</div>

@endif


</div>

</section>



<section class="record-card">

<div class="record-card-header">

<h3>
    Nutrition & Monitoring
</h3>

</div>


<div class="record-card-body">


<div class="text-section">

<div class="text-section-title">
    Nutrition Status
</div>


<div class="text-section-content">

@if($healthRecord->nutrition_status)

<span
    class="status-badge
    {{ $statusClass(
        $healthRecord->nutrition_status
    ) }}"
>

{{ $healthRecord->nutrition_status }}

</span>

@else

<span class="status-badge status-not-assessed">

Not Assessed

</span>

@endif

</div>

</div>


@if($healthRecord->nutritional_status)

<div class="text-section">

<div class="text-section-title">
    Recorded Nutritional Status
</div>

<div class="text-section-content">

{{ $healthRecord->nutritional_status }}

</div>

</div>

@endif


@if($healthRecord->additional_notes)

<div class="text-section">

<div class="text-section-title">
    Additional Notes
</div>

<div class="text-section-content">

{{ $healthRecord->additional_notes }}

</div>

</div>

@endif


</div>

</section>

</div>



{{-- =========================================================
     RECOMMENDATIONS + FOLLOW-UP
========================================================= --}}

<div class="record-grid">


<section class="record-card">

<div class="record-card-header">

<h3>
    Recorded Recommendations
</h3>

</div>


<div class="record-card-body">

<div class="text-section-content">

@if($healthRecord->recommendations)

{{ $healthRecord->recommendations }}

@else

No recommendations were recorded for this visit.

@endif

</div>

</div>

</section>



<section class="record-card">

<div class="record-card-header">

<h3>
    Follow-Up
</h3>

</div>


<div class="record-card-body">


@if($healthRecord->follow_up_date)

<div class="follow-up-box">

<div class="follow-up-date">

{{ \Carbon\Carbon::parse(
    $healthRecord->follow_up_date
)->format('d F Y') }}

</div>


<div class="follow-up-text">

Scheduled follow-up date for continued monitoring
and assessment.

</div>

</div>

@else

<div class="text-section-content">

No follow-up date has been scheduled.

</div>

@endif

</div>

</section>

</div>



{{-- =========================================================
     INFORMATION NOTICE
========================================================= --}}

<div class="record-notice">

<strong>
    Health Monitoring
</strong>

This record forms part of the child's clinical and
growth-monitoring history. Recorded measurements,
observations, recommendations and follow-up information
can be used for continued monitoring and future reporting.


<br><br>


<strong>
    Growth Screening
</strong>

Growth indicators are screening and monitoring information
generated from recorded measurements and configured
growth-reference data. They should not be treated as a
medical diagnosis.


<br><br>


<strong>
    Report Sharing
</strong>

Only share child health information with an appropriate
guardian or authorised recipient. Verify the email address
before sending the report.

</div>


</div>



{{-- =========================================================
     CHART.JS
========================================================= --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        /*
        |--------------------------------------------------------------------------
        | DATA FROM LARAVEL
        |--------------------------------------------------------------------------
        */

        const labels =
            @json($chartLabels);


        const weightData =
            @json($weightData);


        const heightData =
            @json($heightData);


        const bmiData =
            @json($bmiData);


        const muacData =
            @json($muacData);


        /*
        |--------------------------------------------------------------------------
        | COMMON CHART OPTIONS
        |--------------------------------------------------------------------------
        */

        const commonOptions = {

            responsive:true,

            maintainAspectRatio:false,

            interaction:{

                intersect:false,

                mode:'index'

            },

            plugins:{

                legend:{

                    display:true,

                    position:'top'

                }

            },

            scales:{

                y:{

                    beginAtZero:false,

                    grid:{

                        color:'#e2e8f0'

                    }

                },

                x:{

                    grid:{

                        display:false

                    }

                }

            }

        };


        /*
        |--------------------------------------------------------------------------
        | CREATE CHART HELPER
        |--------------------------------------------------------------------------
        */

        function createChart(
            elementId,
            label,
            data,
            unit
        ) {

            const canvas =
                document.getElementById(elementId);


            if (!canvas) {

                return;

            }


            new Chart(
                canvas,
                {

                    type:'line',

                    data:{

                        labels:labels,

                        datasets:[{

                            label:label,

                            data:data,

                            tension:0.3,

                            fill:false,

                            borderWidth:2,

                            pointRadius:4,

                            pointHoverRadius:6

                        }]

                    },

                    options:{

                        ...commonOptions,

                        plugins:{

                            ...commonOptions.plugins,

                            tooltip:{

                                callbacks:{

                                    label:function(context){

                                        const value =
                                            context.parsed.y;

                                        if(
                                            value === null ||
                                            value === undefined
                                        ){

                                            return label +
                                                ': Not recorded';

                                        }

                                        return label +
                                            ': ' +
                                            value +
                                            ' ' +
                                            unit;

                                    }

                                }

                            }

                        }

                    }

                }

            );

        }


        /*
        |--------------------------------------------------------------------------
        | WEIGHT
        |--------------------------------------------------------------------------
        */

        createChart(
            'weightChart',
            'Weight',
            weightData,
            'kg'
        );


        /*
        |--------------------------------------------------------------------------
        | HEIGHT
        |--------------------------------------------------------------------------
        */

        createChart(
            'heightChart',
            'Height / Length',
            heightData,
            'cm'
        );


        /*
        |--------------------------------------------------------------------------
        | BMI
        |--------------------------------------------------------------------------
        */

        createChart(
            'bmiChart',
            'BMI',
            bmiData,
            ''
        );


        /*
        |--------------------------------------------------------------------------
        | MUAC
        |--------------------------------------------------------------------------
        */

        createChart(
            'muacChart',
            'MUAC',
            muacData,
            'cm'
        );


    }
);

</script>


@endsection