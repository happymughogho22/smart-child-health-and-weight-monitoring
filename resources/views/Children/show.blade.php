@extends('layouts.app')

@section('title', 'Child Growth History | Child Weight Monitoring System')

@section('page-title', 'Child Growth History')

@section('page-subtitle', 'Child information, growth monitoring and longitudinal health history')

@section('content')

<style>
    .child-page {
        width: 100%;
        box-sizing: border-box;
    }

    .child-page *,
    .child-page *::before,
    .child-page *::after {
        box-sizing: border-box;
    }

    /* =========================================================
       BREADCRUMB
       ========================================================= */

    .child-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        margin-bottom: 18px;
        color: #94a3b8;
        font-size: 11px;
    }

    .child-breadcrumb a {
        color: #123b6d;
        text-decoration: none;
        font-weight: 600;
    }

    .child-breadcrumb a:hover {
        text-decoration: underline;
    }

    /* =========================================================
       PROFILE HEADER
       ========================================================= */

    .profile-header {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 22px;
        margin-bottom: 20px;

        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .profile-left {
        display: flex;
        align-items: center;
        gap: 16px;
        min-width: 0;
    }

    .child-avatar {
        width: 58px;
        height: 58px;
        min-width: 58px;
        border-radius: 50%;
        background: #eaf2fb;
        color: #123b6d;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 21px;
        font-weight: 800;
    }

    .profile-title {
        margin: 0 0 5px;
        color: #123b6d;
        font-size: 23px;
        font-weight: 700;
    }

    .profile-subtitle {
        margin: 0 0 10px;
        color: #64748b;
        font-size: 11px;
    }

    .profile-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
    }

    .meta-badge {
        display: inline-flex;
        align-items: center;

        background: #f1f5f9;
        color: #334155;

        padding: 6px 9px;
        border-radius: 5px;

        font-size: 10px;
        font-weight: 700;
    }

    .profile-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .action-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-height: 36px;
        padding: 8px 13px;

        border-radius: 6px;
        text-decoration: none;

        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .action-primary {
        background: #123b6d;
        color: #ffffff;
    }

    .action-secondary {
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #e2e8f0;
    }

    .action-button:hover {
        opacity: .9;
    }

    /* =========================================================
       INFORMATION CARDS
       ========================================================= */

    .info-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 20px;
    }

    .info-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        padding: 16px;
        min-width: 0;
    }

    .info-label {
        color: #64748b;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;
        margin-bottom: 7px;
    }

    .info-value {
        color: #123b6d;
        font-size: 18px;
        font-weight: 700;
        line-height: 1.3;
    }

    .info-small {
        color: #64748b;
        font-size: 10px;
        font-weight: 500;
    }

    /* =========================================================
       GROWTH MONITORING OVERVIEW
       ========================================================= */

    .overview-section {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .overview-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 14px 17px;

        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .overview-header h3 {
        margin: 0;
        color: #123b6d;
        font-size: 13px;
        font-weight: 700;
    }

    .overview-header p {
        margin: 3px 0 0;
        color: #64748b;
        font-size: 9px;
    }

    .overview-body {
        padding: 17px;
    }

    .overview-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 12px;
    }

    .overview-card {
        background: #f8fafc;
        border: 1px solid #eef2f7;
        border-radius: 8px;
        padding: 13px;
        min-width: 0;
    }

    .overview-card-label {
        color: #64748b;
        font-size: 8px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;
        margin-bottom: 7px;
    }

    .overview-card-value {
        color: #123b6d;
        font-size: 18px;
        font-weight: 800;
        line-height: 1.2;
    }

    .overview-card-subtitle {
        color: #64748b;
        font-size: 9px;
        margin-top: 4px;
    }

    .overview-card.attention {
        background: #fff7ed;
        border-color: #fed7aa;
    }

    .overview-card.attention .overview-card-value {
        color: #c2410c;
    }

    .overview-card.status {
        background: #f0fdf4;
        border-color: #bbf7d0;
    }

    .overview-card.status .overview-card-value {
        color: #166534;
        font-size: 14px;
    }

    /* =========================================================
       TWO COLUMN LAYOUT
       ========================================================= */

    .two-column {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    .section-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        overflow: hidden;
        min-width: 0;
    }

    .section-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 14px 17px;

        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .section-header h3 {
        margin: 0;
        color: #123b6d;
        font-size: 13px;
        font-weight: 700;
    }

    .section-body {
        padding: 17px;
    }

    /* =========================================================
       DETAILS
       ========================================================= */

    .detail-row {
        display: grid;
        grid-template-columns: 135px minmax(0, 1fr);
        gap: 14px;

        padding: 9px 0;

        border-bottom: 1px solid #f1f5f9;
    }

    .detail-row:first-child {
        padding-top: 0;
    }

    .detail-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .detail-label {
        color: #64748b;
        font-size: 10px;
        font-weight: 600;
    }

    .detail-value {
        color: #334155;
        font-size: 11px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    /* =========================================================
       STATUS
       ========================================================= */

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        padding: 5px 9px;
        border-radius: 999px;

        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-normal {
        background: #dcfce7;
        color: #166534;
    }

    .status-risk {
        background: #fef9c3;
        color: #854d0e;
    }

    .status-moderate {
        background: #ffedd5;
        color: #9a3412;
    }

    .status-severe {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-monitoring {
        background: #eaf2fb;
        color: #123b6d;
    }

    .status-not-assessed {
        background: #e2e8f0;
        color: #475569;
    }

    /* =========================================================
       LATEST ASSESSMENT
       ========================================================= */

    .assessment-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 16px;
    }

    .assessment-box {
        background: #f8fafc;
        border: 1px solid #eef2f7;
        border-radius: 7px;
        padding: 13px;
    }

    .assessment-label {
        color: #64748b;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .assessment-value {
        color: #123b6d;
        font-size: 15px;
        font-weight: 700;
    }

    .assessment-indicators {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .assessment-indicator {
        border: 1px solid #e2e8f0;
        border-radius: 7px;
        padding: 12px;
    }

    .indicator-title {
        color: #123b6d;
        font-size: 11px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .indicator-row {
        display: flex;
        justify-content: space-between;
        gap: 10px;

        padding: 6px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .indicator-row:last-child {
        border-bottom: none;
    }

    .indicator-label {
        color: #64748b;
        font-size: 9px;
        font-weight: 600;
    }

    .indicator-value {
        color: #334155;
        font-size: 10px;
        font-weight: 700;
        text-align: right;
    }

    .attention-box {
        margin-top: 14px;
        padding: 12px 14px;

        border-radius: 7px;
        border: 1px solid #fecaca;

        background: #fef2f2;
        color: #991b1b;

        font-size: 10px;
        line-height: 1.5;
    }

    .safe-box {
        margin-top: 14px;
        padding: 12px 14px;

        border-radius: 7px;
        border: 1px solid #bbf7d0;

        background: #f0fdf4;
        color: #166534;

        font-size: 10px;
        line-height: 1.5;
    }

    .assessment-note {
        margin-top: 14px;
        padding: 12px 14px;

        border-radius: 7px;
        border: 1px solid #d4e4f7;

        background: #eaf2fb;
        color: #365574;

        font-size: 10px;
        line-height: 1.6;
    }

    /* =========================================================
       CHART SECTION
       ========================================================= */

    .chart-section {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .chart-body {
        padding: 18px;
    }

    .chart-container {
        position: relative;
        width: 100%;
        height: 300px;
    }

    .chart-help {
        margin-bottom: 18px;
        color: #64748b;
        font-size: 10px;
        line-height: 1.5;
    }

    .chart-block {
        margin-bottom: 35px;
    }

    .chart-block:last-child {
        margin-bottom: 0;
    }

    .chart-title {
        color: #123b6d;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    /* =========================================================
       GROWTH HISTORY
       ========================================================= */

    .history-section {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .history-body {
        padding: 0;
        overflow-x: auto;
    }

    .history-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1100px;
    }

    .history-table th {
        background: #f8fafc;
        color: #64748b;

        padding: 11px 12px;

        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;

        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .history-table td {
        padding: 12px;

        color: #334155;
        font-size: 10px;
        font-weight: 600;

        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .history-table tr:last-child td {
        border-bottom: none;
    }

    .history-table tbody tr:hover {
        background: #fafcff;
    }

    .attention-row {
        background: #fffaf8;
    }

    .record-link {
        color: #123b6d;
        text-decoration: none;
        font-weight: 700;
    }

    .record-link:hover {
        text-decoration: underline;
    }

    .attention-indicator {
        display: inline-flex;
        align-items: center;
        gap: 5px;

        color: #b91c1c;
        font-size: 9px;
        font-weight: 700;
    }

    .attention-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #dc2626;
    }

    /* =========================================================
       EMPTY STATE
       ========================================================= */

    .empty-state {
        text-align: center;
        padding: 30px 20px;
        color: #64748b;
        font-size: 11px;
    }

    .empty-state strong {
        display: block;
        color: #334155;
        font-size: 13px;
        margin-bottom: 5px;
    }

    /* =========================================================
       NOTICE
       ========================================================= */

    .information-notice {
        background: #eaf2fb;
        border: 1px solid #d4e4f7;
        border-radius: 8px;
        padding: 15px;

        color: #365574;
        font-size: 10px;
        line-height: 1.6;
    }

    .information-notice strong {
        display: block;
        color: #123b6d;
        margin-bottom: 5px;
    }

    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 1100px) {

        .overview-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .info-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .two-column {
            grid-template-columns: 1fr;
        }

        .profile-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .profile-actions {
            width: 100%;
        }
    }

    @media (max-width: 700px) {

        .overview-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .profile-left {
            align-items: flex-start;
        }

        .assessment-summary {
            grid-template-columns: 1fr;
        }

        .assessment-indicators {
            grid-template-columns: 1fr;
        }

        .detail-row {
            grid-template-columns: 1fr;
            gap: 4px;
        }

        .profile-actions {
            flex-direction: column;
        }

        .action-button {
            width: 100%;
        }

        .chart-container {
            height: 270px;
        }
    }

    @media (max-width: 450px) {

        .overview-grid {
            grid-template-columns: 1fr;
        }
    }
</style>


<div class="child-page">

    {{-- =====================================================
         BREADCRUMB
         ====================================================== --}}

    <div class="child-breadcrumb">

        <a href="{{ route('dashboard') }}">
            Home
        </a>

        <span>/</span>

        <a href="{{ route('children.index') }}">
            Children
        </a>

        <span>/</span>

        <strong>
            {{ $child->child_id }}
        </strong>

    </div>


    {{-- =====================================================
         PROFILE HEADER
         ====================================================== --}}

    <section class="profile-header">

        <div class="profile-left">

            <div class="child-avatar">
                {{ strtoupper(substr($child->first_name, 0, 1)) }}
            </div>

            <div>

                <h1 class="profile-title">
                    {{ $child->first_name }}
                    {{ $child->last_name }}
                </h1>

                <p class="profile-subtitle">
                    Child profile and longitudinal growth monitoring record
                </p>

                <div class="profile-meta">

                    <span class="meta-badge">
                        ID: {{ $child->child_id }}
                    </span>

                    <span class="meta-badge">
                        {{ $child->sex }}
                    </span>

                    @if($child->date_of_birth)

                        <span class="meta-badge">
                            DOB:
                            {{ \Carbon\Carbon::parse($child->date_of_birth)->format('d M Y') }}
                        </span>

                    @endif

                    @if(isset($ageLabel))

                        <span class="meta-badge">
                            Age: {{ $ageLabel }}
                        </span>

                    @endif

                </div>

            </div>

        </div>


        <div class="profile-actions">

            <a
                href="{{ route('children.index') }}"
                class="action-button action-secondary"
            >
                ← Back to Children
            </a>

            <a
                href="{{ route('children.edit', $child) }}"
                class="action-button action-primary"
            >
                Edit Child
            </a>

            <a
                href="{{ route('health-records.create', $child) }}"
                class="action-button action-primary"
            >
                + Add Health Record
            </a>

            <a
                href="{{ route('children.growth-charts', $child) }}"
                class="action-button action-secondary"
            >
                View Growth Charts
            </a>

        </div>

    </section>


    {{-- =====================================================
         GROWTH MONITORING OVERVIEW
         ====================================================== --}}

    <section class="overview-section">

        <div class="overview-header">

            <div>

                <h3>
                    Growth Monitoring Overview
                </h3>

                <p>
                    Longitudinal summary of recorded growth and WHO assessments
                </p>

            </div>

            @if($latestGrowthAssessment)

                @if($latestGrowthAssessment->requires_attention)

                    <span class="status-badge status-severe">
                        Attention Required
                    </span>

                @else

                    <span class="status-badge status-normal">
                        Monitoring Active
                    </span>

                @endif

            @else

                <span class="status-badge status-not-assessed">
                    Awaiting Assessment
                </span>

            @endif

        </div>


        <div class="overview-body">

            <div class="overview-grid">

                <div class="overview-card">

                    <div class="overview-card-label">
                        Current Age
                    </div>

                    <div class="overview-card-value">
                        {{ $ageLabel ?? '—' }}
                    </div>

                    <div class="overview-card-subtitle">
                        Based on date of birth
                    </div>

                </div>


                <div class="overview-card">

                    <div class="overview-card-label">
                        Total Visits
                    </div>

                    <div class="overview-card-value">
                        {{ $totalVisits ?? 0 }}
                    </div>

                    <div class="overview-card-subtitle">
                        Recorded health visits
                    </div>

                </div>


                <div class="overview-card">

                    <div class="overview-card-label">
                        WHO Assessments
                    </div>

                    <div class="overview-card-value">
                        {{ $totalAssessments ?? 0 }}
                    </div>

                    <div class="overview-card-subtitle">
                        Processed growth assessments
                    </div>

                </div>


                <div class="overview-card {{ ($attentionCount ?? 0) > 0 ? 'attention' : '' }}">

                    <div class="overview-card-label">
                        Attention Flags
                    </div>

                    <div class="overview-card-value">
                        {{ $attentionCount ?? 0 }}
                    </div>

                    <div class="overview-card-subtitle">
                        Assessments requiring review
                    </div>

                </div>


                <div class="overview-card status">

                    <div class="overview-card-label">
                        Current Nutrition Status
                    </div>

                    <div class="overview-card-value">
                        {{ $latestNutritionStatus ?? 'Awaiting Assessment' }}
                    </div>

                    <div class="overview-card-subtitle">
                        Latest available assessment
                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
         BASIC CHILD INFORMATION
         ====================================================== --}}

    <div class="two-column">

        <section class="section-card">

            <div class="section-header">

                <h3>
                    Child Information
                </h3>

            </div>

            <div class="section-body">

                <div class="detail-row">

                    <div class="detail-label">
                        Full Name
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
                        {{ $child->sex }}
                    </div>

                </div>

                <div class="detail-row">

                    <div class="detail-label">
                        Date of Birth
                    </div>

                    <div class="detail-value">

                        @if($child->date_of_birth)

                            {{ \Carbon\Carbon::parse($child->date_of_birth)->format('d F Y') }}

                        @else

                            —

                        @endif

                    </div>

                </div>

                <div class="detail-row">

                    <div class="detail-label">
                        Place of Birth
                    </div>

                    <div class="detail-value">
                        {{ $child->place_of_birth ?? '—' }}
                    </div>

                </div>

            </div>

        </section>


        <section class="section-card">

            <div class="section-header">

                <h3>
                    Guardian Information
                </h3>

            </div>

            <div class="section-body">

                <div class="detail-row">

                    <div class="detail-label">
                        Guardian
                    </div>

                    <div class="detail-value">
                        {{ $child->guardian_name ?? '—' }}
                    </div>

                </div>

                <div class="detail-row">

                    <div class="detail-label">
                        Phone
                    </div>

                    <div class="detail-value">
                        {{ $child->guardian_phone ?? '—' }}
                    </div>

                </div>

                <div class="detail-row">

                    <div class="detail-label">
                        Email
                    </div>

                    <div class="detail-value">
                        {{ $child->guardian_email ?? '—' }}
                    </div>

                </div>

                <div class="detail-row">

                    <div class="detail-label">
                        Address
                    </div>

                    <div class="detail-value">
                        {{ $child->address ?? '—' }}
                    </div>

                </div>

            </div>

        </section>

    </div>


    {{-- =====================================================
         LATEST MEASUREMENTS
         ====================================================== --}}

    @php
        $latestRecord = $latestRecord ?? $child->healthRecords
            ->sortByDesc('visit_date')
            ->first();
    @endphp


    <div class="info-grid">

        <div class="info-card">

            <div class="info-label">
                Latest Weight
            </div>

            <div class="info-value">

                @if($latestRecord && $latestRecord->weight !== null)

                    {{ $latestRecord->weight }}

                    <span class="info-small">
                        kg
                    </span>

                @else

                    —

                @endif

            </div>

        </div>


        <div class="info-card">

            <div class="info-label">
                Latest Height / Length
            </div>

            <div class="info-value">

                @if($latestRecord && $latestRecord->height !== null)

                    {{ $latestRecord->height }}

                    <span class="info-small">
                        cm
                    </span>

                @else

                    —

                @endif

            </div>

        </div>


        <div class="info-card">

            <div class="info-label">
                Latest BMI
            </div>

            <div class="info-value">

                @if($latestRecord && $latestRecord->bmi !== null)

                    {{ $latestRecord->bmi }}

                @else

                    —

                @endif

            </div>

        </div>


        <div class="info-card">

            <div class="info-label">
                Latest MUAC
            </div>

            <div class="info-value">

                @if($latestRecord && $latestRecord->muac !== null)

                    {{ $latestRecord->muac }}

                    <span class="info-small">
                        cm
                    </span>

                @else

                    —

                @endif

            </div>

        </div>

    </div>


    {{-- =====================================================
         CURRENT MONITORING STATUS
         ====================================================== --}}

    <section class="section-card" style="margin-bottom:20px;">

        <div class="section-header">

            <h3>
                Current Monitoring Status
            </h3>

        </div>

        <div class="section-body">

            <div class="assessment-summary">

                <div class="assessment-box">

                    <div class="assessment-label">
                        Nutrition Status
                    </div>

                    <div class="assessment-value">
                        {{ $latestNutritionStatus ?? 'Awaiting Assessment' }}
                    </div>

                </div>


                <div class="assessment-box">

                    <div class="assessment-label">
                        Monitoring Status
                    </div>

                    <div class="assessment-value">
                        {{ $latestMonitoringStatus ?? 'Awaiting Measurement' }}
                    </div>

                </div>


                <div class="assessment-box">

                    <div class="assessment-label">
                        Latest Visit
                    </div>

                    <div class="assessment-value">

                        @if($latestRecord && $latestRecord->visit_date)

                            {{ $latestRecord->visit_date->format('d M Y') }}

                        @else

                            —

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
         LATEST WHO ASSESSMENT
         ====================================================== --}}

    <section class="section-card" style="margin-bottom:20px;">

        <div class="section-header">

            <h3>
                Latest WHO Growth Assessment
            </h3>

            @if($latestGrowthAssessment)

                @if($latestGrowthAssessment->overall_status === 'Normal')

                    <span class="status-badge status-normal">
                        Normal
                    </span>

                @elseif($latestGrowthAssessment->overall_status === 'At Risk')

                    <span class="status-badge status-risk">
                        At Risk
                    </span>

                @elseif($latestGrowthAssessment->overall_status === 'Moderate Malnutrition')

                    <span class="status-badge status-moderate">
                        Moderate Malnutrition
                    </span>

                @elseif($latestGrowthAssessment->overall_status === 'Severe Malnutrition')

                    <span class="status-badge status-severe">
                        Severe Malnutrition
                    </span>

                @else

                    <span class="status-badge status-not-assessed">
                        {{ $latestGrowthAssessment->overall_status ?? 'Not Assessed' }}
                    </span>

                @endif

            @else

                <span class="status-badge status-not-assessed">
                    Not Assessed
                </span>

            @endif

        </div>


        <div class="section-body">

            @if($latestGrowthAssessment)

                <div class="assessment-summary">

                    <div class="assessment-box">

                        <div class="assessment-label">
                            Age at Visit
                        </div>

                        <div class="assessment-value">

                            {{ $latestGrowthAssessment->age_in_months }}

                            <span class="info-small">
                                months
                            </span>

                        </div>

                    </div>


                    <div class="assessment-box">

                        <div class="assessment-label">
                            Overall Status
                        </div>

                        <div class="assessment-value">

                            {{ $latestGrowthAssessment->overall_status ?? 'Not Assessed' }}

                        </div>

                    </div>


                    <div class="assessment-box">

                        <div class="assessment-label">
                            Attention Required
                        </div>

                        <div class="assessment-value">

                            @if($latestGrowthAssessment->requires_attention)

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


                <div class="assessment-indicators">

                    {{-- Weight for age --}}

                    <div class="assessment-indicator">

                        <div class="indicator-title">
                            Weight-for-Age
                        </div>

                        <div class="indicator-row">

                            <span class="indicator-label">
                                Z-score
                            </span>

                            <span class="indicator-value">

                                @if($latestGrowthAssessment->weight_for_age_zscore !== null)

                                    {{ number_format((float) $latestGrowthAssessment->weight_for_age_zscore, 3) }}

                                @else

                                    Not assessed

                                @endif

                            </span>

                        </div>

                        <div class="indicator-row">

                            <span class="indicator-label">
                                Status
                            </span>

                            <span class="indicator-value">

                                {{ $latestGrowthAssessment->weight_for_age_status ?? 'Not assessed' }}

                            </span>

                        </div>

                    </div>


                    {{-- Height for age --}}

                    <div class="assessment-indicator">

                        <div class="indicator-title">
                            Height-for-Age
                        </div>

                        <div class="indicator-row">

                            <span class="indicator-label">
                                Z-score
                            </span>

                            <span class="indicator-value">

                                @if($latestGrowthAssessment->height_for_age_zscore !== null)

                                    {{ number_format((float) $latestGrowthAssessment->height_for_age_zscore, 3) }}

                                @else

                                    Not assessed

                                @endif

                            </span>

                        </div>

                        <div class="indicator-row">

                            <span class="indicator-label">
                                Status
                            </span>

                            <span class="indicator-value">

                                {{ $latestGrowthAssessment->height_for_age_status ?? 'Not assessed' }}

                            </span>

                        </div>

                    </div>


                    {{-- Weight for length --}}

                    <div class="assessment-indicator">

                        <div class="indicator-title">
                            Weight-for-Length
                        </div>

                        <div class="indicator-row">

                            <span class="indicator-label">
                                Z-score
                            </span>

                            <span class="indicator-value">

                                @if($latestGrowthAssessment->weight_for_length_zscore !== null)

                                    {{ number_format((float) $latestGrowthAssessment->weight_for_length_zscore, 3) }}

                                @else

                                    Not assessed

                                @endif

                            </span>

                        </div>

                        <div class="indicator-row">

                            <span class="indicator-label">
                                Status
                            </span>

                            <span class="indicator-value">

                                {{ $latestGrowthAssessment->weight_for_length_status ?? 'Not assessed' }}

                            </span>

                        </div>

                    </div>


                    {{-- Weight for height --}}

                    <div class="assessment-indicator">

                        <div class="indicator-title">
                            Weight-for-Height
                        </div>

                        <div class="indicator-row">

                            <span class="indicator-label">
                                Z-score
                            </span>

                            <span class="indicator-value">

                                @if($latestGrowthAssessment->weight_for_height_zscore !== null)

                                    {{ number_format((float) $latestGrowthAssessment->weight_for_height_zscore, 3) }}

                                @else

                                    Not assessed

                                @endif

                            </span>

                        </div>

                        <div class="indicator-row">

                            <span class="indicator-label">
                                Status
                            </span>

                            <span class="indicator-value">

                                {{ $latestGrowthAssessment->weight_for_height_status ?? 'Not assessed' }}

                            </span>

                        </div>

                    </div>


                    {{-- BMI for age --}}

                    <div class="assessment-indicator">

                        <div class="indicator-title">
                            BMI-for-Age
                        </div>

                        <div class="indicator-row">

                            <span class="indicator-label">
                                Z-score
                            </span>

                            <span class="indicator-value">

                                @if($latestGrowthAssessment->bmi_for_age_zscore !== null)

                                    {{ number_format((float) $latestGrowthAssessment->bmi_for_age_zscore, 3) }}

                                @else

                                    Not assessed

                                @endif

                            </span>

                        </div>

                        <div class="indicator-row">

                            <span class="indicator-label">
                                Status
                            </span>

                            <span class="indicator-value">

                                {{ $latestGrowthAssessment->bmi_for_age_status ?? 'Not assessed' }}

                            </span>

                        </div>

                    </div>


                    {{-- MUAC --}}

                    <div class="assessment-indicator">

                        <div class="indicator-title">
                            MUAC Screening
                        </div>

                        <div class="indicator-row">

                            <span class="indicator-label">
                                Measurement
                            </span>

                            <span class="indicator-value">

                                @if($latestRecord && $latestRecord->muac !== null)

                                    {{ $latestRecord->muac }} cm

                                @else

                                    Not recorded

                                @endif

                            </span>

                        </div>

                        <div class="indicator-row">

                            <span class="indicator-label">
                                Status
                            </span>

                            <span class="indicator-value">

                                {{ $latestGrowthAssessment->muac_status ?? 'Not assessed' }}

                            </span>

                        </div>

                    </div>

                </div>


                @if($latestGrowthAssessment->requires_attention)

                    <div class="attention-box">

                        <strong>
                            Growth Assessment Requires Attention
                        </strong>

                        One or more indicators from the latest
                        assessment have been flagged for further
                        review.

                    </div>

                @else

                    <div class="safe-box">

                        <strong>
                            No Growth Attention Flag
                        </strong>

                        The latest growth assessment does not
                        contain an attention flag.

                    </div>

                @endif


                @if($latestGrowthAssessment->assessment_notes)

                    <div class="assessment-note">

                        <strong>
                            Assessment Notes:
                        </strong>

                        {{ $latestGrowthAssessment->assessment_notes }}

                    </div>

                @endif


            @else

                <div class="empty-state">

                    <strong>
                        WHO Growth Assessment Not Available
                    </strong>

                    A growth assessment will appear here when
                    a health record has been processed for WHO
                    growth assessment.

                </div>

            @endif

        </div>

    </section>


    {{-- =====================================================
         GROWTH CHARTS
         ====================================================== --}}

    <section class="chart-section">

        <div class="section-header">

            <h3>
                Longitudinal Growth Trends
            </h3>

            <span class="info-small">
                {{ $totalVisits ?? 0 }} visit(s)
            </span>

        </div>

        <div class="chart-body">

            @if($child->healthRecords->count() > 0)

                <div class="chart-help">

                    These charts display recorded measurements
                    chronologically across the child's health
                    visits. They are intended for growth monitoring
                    and screening.

                </div>


                {{-- WEIGHT CHART --}}

                <div class="chart-block">

                    <div class="chart-title">
                        Weight Trend
                    </div>

                    <div class="chart-container">
                        <canvas id="weightChart"></canvas>
                    </div>

                </div>


                {{-- HEIGHT CHART --}}

                <div class="chart-block">

                    <div class="chart-title">
                        Height / Length Trend
                    </div>

                    <div class="chart-container">
                        <canvas id="heightChart"></canvas>
                    </div>

                </div>


                {{-- BMI CHART --}}

                <div class="chart-block">

                    <div class="chart-title">
                        BMI Trend
                    </div>

                    <div class="chart-container">
                        <canvas id="bmiChart"></canvas>
                    </div>

                </div>


                {{-- MUAC CHART --}}

                <div class="chart-block">

                    <div class="chart-title">
                        MUAC Trend
                    </div>

                    <div class="chart-container">
                        <canvas id="muacChart"></canvas>
                    </div>

                </div>

            @else

                <div class="empty-state">

                    <strong>
                        No Growth Data Available
                    </strong>

                    Growth charts will appear after health
                    records with measurements are recorded.

                </div>

            @endif

        </div>

    </section>


    {{-- =====================================================
         COMPLETE GROWTH HISTORY
         ====================================================== --}}

    <section class="history-section">

        <div class="section-header">

            <h3>
                Complete Growth History
            </h3>

            <span class="info-small">
                {{ $totalVisits ?? $child->healthRecords->count() }}
                recorded visit(s)
            </span>

        </div>


        <div class="history-body">

            @if(isset($growthHistory) && $growthHistory->count() > 0)

                <table class="history-table">

                    <thead>

                        <tr>

                            <th>
                                Visit Date
                            </th>

                            <th>
                                Weight
                            </th>

                            <th>
                                Height / Length
                            </th>

                            <th>
                                BMI
                            </th>

                            <th>
                                MUAC
                            </th>

                            <th>
                                Nutrition
                            </th>

                            <th>
                                WHO Assessment
                            </th>

                            <th>
                                Attention
                            </th>

                            <th>
                                Follow-up
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($growthHistory->sortByDesc('visit_date_raw') as $history)

                            <tr class="{{ $history['requires_attention'] ? 'attention-row' : '' }}">

                                <td>
                                    {{ $history['visit_date'] }}
                                </td>


                                <td>

                                    @if($history['weight'] !== null)

                                        {{ number_format($history['weight'], 2) }} kg

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    @if($history['height'] !== null)

                                        {{ number_format($history['height'], 2) }} cm

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    @if($history['bmi'] !== null)

                                        {{ number_format($history['bmi'], 2) }}

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    @if($history['muac'] !== null)

                                        {{ number_format($history['muac'], 1) }} cm

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    @if($history['nutrition_status'] === 'Normal')

                                        <span class="status-badge status-normal">
                                            Normal
                                        </span>

                                    @elseif($history['nutrition_status'] === 'At Risk')

                                        <span class="status-badge status-risk">
                                            At Risk
                                        </span>

                                    @elseif($history['nutrition_status'] === 'Moderate Malnutrition')

                                        <span class="status-badge status-moderate">
                                            Moderate
                                        </span>

                                    @elseif($history['nutrition_status'] === 'Severe Malnutrition')

                                        <span class="status-badge status-severe">
                                            Severe
                                        </span>

                                    @elseif($history['nutrition_status'])

                                        <span class="status-badge status-not-assessed">
                                            {{ $history['nutrition_status'] }}
                                        </span>

                                    @else

                                        <span class="status-badge status-not-assessed">
                                            Not Assessed
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @if($history['overall_status'] === 'Normal')

                                        <span class="status-badge status-normal">
                                            Normal
                                        </span>

                                    @elseif($history['overall_status'] === 'At Risk')

                                        <span class="status-badge status-risk">
                                            At Risk
                                        </span>

                                    @elseif($history['overall_status'] === 'Moderate Malnutrition')

                                        <span class="status-badge status-moderate">
                                            Moderate
                                        </span>

                                    @elseif($history['overall_status'] === 'Severe Malnutrition')

                                        <span class="status-badge status-severe">
                                            Severe
                                        </span>

                                    @else

                                        <span class="status-badge status-not-assessed">
                                            {{ $history['overall_status'] ?? 'Not Assessed' }}
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @if($history['requires_attention'])

                                        <span class="attention-indicator">

                                            <span class="attention-dot"></span>

                                            Required

                                        </span>

                                    @else

                                        <span class="status-badge status-normal">
                                            No
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @if($history['follow_up_date'])

                                        {{ $history['follow_up_date'] }}

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    <a
                                        href="{{ route('health-records.show', [$child, $history['id']]) }}"
                                        class="record-link"
                                    >
                                        View
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @elseif($child->healthRecords->count() > 0)

                {{-- Fallback to existing records if growthHistory is unavailable --}}

                <table class="history-table">

                    <thead>

                        <tr>

                            <th>Visit Date</th>
                            <th>Weight</th>
                            <th>Height / Length</th>
                            <th>BMI</th>
                            <th>MUAC</th>
                            <th>Nutrition</th>
                            <th>WHO Assessment</th>
                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($child->healthRecords->sortByDesc('visit_date') as $record)

                            <tr>

                                <td>
                                    {{ $record->visit_date
                                        ? $record->visit_date->format('d M Y')
                                        : '—'
                                    }}
                                </td>

                                <td>
                                    {{ $record->weight !== null
                                        ? $record->weight . ' kg'
                                        : '—'
                                    }}
                                </td>

                                <td>
                                    {{ $record->height !== null
                                        ? $record->height . ' cm'
                                        : '—'
                                    }}
                                </td>

                                <td>
                                    {{ $record->bmi !== null
                                        ? $record->bmi
                                        : '—'
                                    }}
                                </td>

                                <td>
                                    {{ $record->muac !== null
                                        ? $record->muac . ' cm'
                                        : '—'
                                    }}
                                </td>

                                <td>
                                    {{ $record->nutrition_status ?? 'Not Assessed' }}
                                </td>

                                <td>
                                    {{ $record->growthAssessment?->overall_status ?? 'Not Available' }}
                                </td>

                                <td>

                                    <a
                                        href="{{ route('health-records.show', [$child, $record]) }}"
                                        class="record-link"
                                    >
                                        View
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @else

                <div class="empty-state">

                    <strong>
                        No Health Records
                    </strong>

                    There are currently no health records
                    registered for this child.

                </div>

            @endif

        </div>

    </section>


    {{-- =====================================================
         LATEST VISIT INFORMATION
         ====================================================== --}}

    @if($latestRecord)

        <div class="two-column">

            <section class="section-card">

                <div class="section-header">

                    <h3>
                        Latest Visit
                    </h3>

                </div>

                <div class="section-body">

                    <div class="detail-row">

                        <div class="detail-label">
                            Visit Date
                        </div>

                        <div class="detail-value">

                            {{ $latestRecord->visit_date
                                ? $latestRecord->visit_date->format('d F Y')
                                : '—'
                            }}

                        </div>

                    </div>


                    <div class="detail-row">

                        <div class="detail-label">
                            Health Facility
                        </div>

                        <div class="detail-value">

                            {{ $latestRecord->facility_name ?? '—' }}

                        </div>

                    </div>


                    <div class="detail-row">

                        <div class="detail-label">
                            Health Worker
                        </div>

                        <div class="detail-value">

                            {{ $latestRecord->health_worker_name ?? '—' }}

                        </div>

                    </div>


                    <div class="detail-row">

                        <div class="detail-label">
                            Monitoring Status
                        </div>

                        <div class="detail-value">

                            @if($latestRecord->monitoring_status)

                                <span class="status-badge status-monitoring">
                                    {{ $latestRecord->monitoring_status }}
                                </span>

                            @else

                                —

                            @endif

                        </div>

                    </div>


                    <div class="detail-row">

                        <div class="detail-label">
                            Temperature
                        </div>

                        <div class="detail-value">

                            @if($latestRecord->temperature !== null)

                                {{ $latestRecord->temperature }} °C

                            @else

                                —

                            @endif

                        </div>

                    </div>

                </div>

            </section>


            <section class="section-card">

                <div class="section-header">

                    <h3>
                        Latest Clinical Information
                    </h3>

                </div>

                <div class="section-body">

                    <div class="detail-row">

                        <div class="detail-label">
                            Clinical Observations
                        </div>

                        <div class="detail-value">

                            {{ $latestRecord->clinical_observations ?? '—' }}

                        </div>

                    </div>


                    <div class="detail-row">

                        <div class="detail-label">
                            Symptoms
                        </div>

                        <div class="detail-value">

                            {{ $latestRecord->symptoms ?? '—' }}

                        </div>

                    </div>


                    <div class="detail-row">

                        <div class="detail-label">
                            Doctor's Remarks
                        </div>

                        <div class="detail-value">

                            {{ $latestRecord->doctor_remarks ?? '—' }}

                        </div>

                    </div>


                    <div class="detail-row">

                        <div class="detail-label">
                            Recommendations
                        </div>

                        <div class="detail-value">

                            {{ $latestRecord->recommendations ?? '—' }}

                        </div>

                    </div>

                </div>

            </section>

        </div>

    @endif


    {{-- =====================================================
         INFORMATION NOTICE
         ====================================================== --}}

    <div class="information-notice">

        <strong>
            Growth Monitoring Notice
        </strong>

        The information displayed on this page is based on
        measurements and health records entered into the
        Child Weight Monitoring System.

        <br><br>

        <strong>
            WHO Growth Assessment
        </strong>

        Growth indicators and assessment results are intended
        for screening and growth monitoring. They should be
        reviewed by an appropriately qualified health
        professional and should not be treated as a standalone
        medical diagnosis.

    </div>

</div>


{{-- =========================================================
     CHART.JS
     ========================================================== --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Data supplied by ChildController
    |--------------------------------------------------------------------------
    */

    const labels = @json($chartLabels ?? []);

    const weightData = @json($weightData ?? []);

    const heightData = @json($heightData ?? []);

    const bmiData = @json($bmiData ?? []);

    const muacData = @json($muacData ?? []);


    /*
    |--------------------------------------------------------------------------
    | Common chart configuration
    |--------------------------------------------------------------------------
    */

    const commonOptions = {

        responsive: true,

        maintainAspectRatio: false,

        interaction: {
            intersect: false,
            mode: 'index'
        },

        plugins: {

            legend: {
                display: true,
                position: 'top'
            },

            tooltip: {
                enabled: true
            }

        },

        scales: {

            x: {

                title: {
                    display: true,
                    text: 'Visit Date'
                },

                grid: {
                    display: false
                }

            },

            y: {

                beginAtZero: true,

                title: {
                    display: true,
                    text: 'Recorded Value'
                }

            }

        }

    };


    /*
    |--------------------------------------------------------------------------
    | Weight Chart
    |--------------------------------------------------------------------------
    */

    const weightCanvas = document.getElementById('weightChart');

    if (weightCanvas) {

        new Chart(weightCanvas, {

            type: 'line',

            data: {

                labels: labels,

                datasets: [{

                    label: 'Weight (kg)',

                    data: weightData,

                    borderWidth: 2,

                    tension: 0.3,

                    spanGaps: true,

                    pointRadius: 4,

                    pointHoverRadius: 6

                }]

            },

            options: {

                ...commonOptions,

                scales: {

                    ...commonOptions.scales,

                    y: {

                        ...commonOptions.scales.y,

                        title: {
                            display: true,
                            text: 'Weight (kg)'
                        }

                    }

                }

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Height Chart
    |--------------------------------------------------------------------------
    */

    const heightCanvas = document.getElementById('heightChart');

    if (heightCanvas) {

        new Chart(heightCanvas, {

            type: 'line',

            data: {

                labels: labels,

                datasets: [{

                    label: 'Height / Length (cm)',

                    data: heightData,

                    borderWidth: 2,

                    tension: 0.3,

                    spanGaps: true,

                    pointRadius: 4,

                    pointHoverRadius: 6

                }]

            },

            options: {

                ...commonOptions,

                scales: {

                    ...commonOptions.scales,

                    y: {

                        ...commonOptions.scales.y,

                        title: {
                            display: true,
                            text: 'Height / Length (cm)'
                        }

                    }

                }

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | BMI Chart
    |--------------------------------------------------------------------------
    */

    const bmiCanvas = document.getElementById('bmiChart');

    if (bmiCanvas) {

        new Chart(bmiCanvas, {

            type: 'line',

            data: {

                labels: labels,

                datasets: [{

                    label: 'BMI',

                    data: bmiData,

                    borderWidth: 2,

                    tension: 0.3,

                    spanGaps: true,

                    pointRadius: 4,

                    pointHoverRadius: 6

                }]

            },

            options: {

                ...commonOptions,

                scales: {

                    ...commonOptions.scales,

                    y: {

                        ...commonOptions.scales.y,

                        title: {
                            display: true,
                            text: 'BMI'
                        }

                    }

                }

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | MUAC Chart
    |--------------------------------------------------------------------------
    */

    const muacCanvas = document.getElementById('muacChart');

    if (muacCanvas) {

        new Chart(muacCanvas, {

            type: 'line',

            data: {

                labels: labels,

                datasets: [{

                    label: 'MUAC (cm)',

                    data: muacData,

                    borderWidth: 2,

                    tension: 0.3,

                    spanGaps: true,

                    pointRadius: 4,

                    pointHoverRadius: 6

                }]

            },

            options: {

                ...commonOptions,

                scales: {

                    ...commonOptions.scales,

                    y: {

                        ...commonOptions.scales.y,

                        title: {
                            display: true,
                            text: 'MUAC (cm)'
                        }

                    }

                }

            }

        });

    }

});

</script>

@endsection