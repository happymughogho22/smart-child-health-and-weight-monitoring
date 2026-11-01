<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Growth Charts —
        {{ $child->child_id }}
    </title>

    {{-- =========================================================
         FONTS
    ========================================================== --}}

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    {{-- =========================================================
         CHART.JS
    ========================================================== --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <style>

        /* =========================================================
           GLOBAL
        ========================================================== */

        * {
            box-sizing: border-box;
        }

        :root {

            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #eff6ff;
            --primary-border: #dbeafe;

            --success: #15803d;
            --success-bg: #f0fdf4;
            --success-border: #bbf7d0;

            --warning: #b45309;
            --warning-bg: #fffbeb;
            --warning-border: #fde68a;

            --danger: #dc2626;
            --danger-bg: #fef2f2;
            --danger-border: #fecaca;

            --purple: #7c3aed;
            --purple-bg: #f5f3ff;

            --text: #111827;
            --text-secondary: #374151;
            --muted: #6b7280;
            --muted-light: #9ca3af;

            --border: #e5e7eb;
            --border-light: #eef0f3;

            --background: #f5f7fa;

            --white: #ffffff;

            --shadow:
                0 2px 8px rgba(15, 23, 42, 0.04);

            --shadow-hover:
                0 8px 22px rgba(15, 23, 42, 0.08);

            --radius: 12px;
        }


        html {
            scroll-behavior: smooth;
        }


        body {

            margin: 0;
            padding: 0;

            background:
                var(--background);

            color:
                var(--text);

            font-family:
                "Inter",
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;

            font-size:
                14px;

            line-height:
                1.5;

            -webkit-font-smoothing:
                antialiased;

            text-rendering:
                optimizeLegibility;
        }


        button,
        input,
        textarea,
        select {

            font-family:
                inherit;
        }


        /* =========================================================
           PAGE CONTAINER
        ========================================================== */

        .page-container {

            width: 100%;

            max-width:
                1400px;

            margin:
                0 auto;

            padding:
                28px;
        }


        /* =========================================================
           TOP NAVIGATION
        ========================================================== */

        .top-navigation {

            display:
                flex;

            align-items:
                center;

            justify-content:
                space-between;

            margin-bottom:
                18px;
        }


        .back-link {

            display:
                inline-flex;

            align-items:
                center;

            gap:
                8px;

            color:
                var(--text-secondary);

            text-decoration:
                none;

            font-size:
                13px;

            font-weight:
                600;

            transition:
                color 0.2s ease;
        }


        .back-link:hover {

            color:
                var(--primary);
        }


        .back-arrow {

            display:
                inline-flex;

            align-items:
                center;

            justify-content:
                center;

            width:
                28px;

            height:
                28px;

            border:
                1px solid var(--border);

            border-radius:
                8px;

            background:
                var(--white);

            font-size:
                15px;
        }


        /* =========================================================
           CHILD HEADER
        ========================================================== */

        .child-header {

            background:
                var(--white);

            border:
                1px solid var(--border);

            border-radius:
                var(--radius);

            box-shadow:
                var(--shadow);

            padding:
                24px;

            margin-bottom:
                20px;
        }


        .child-header-main {

            display:
                flex;

            justify-content:
                space-between;

            align-items:
                flex-start;

            gap:
                24px;
        }


        .child-identity {

            display:
                flex;

            align-items:
                center;

            gap:
                16px;
        }


        .child-avatar {

            width:
                58px;

            height:
                58px;

            flex-shrink:
                0;

            border-radius:
                14px;

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            background:
                var(--primary-light);

            border:
                1px solid var(--primary-border);

            color:
                var(--primary);

            font-size:
                22px;

            font-weight:
                700;
        }


        .child-name {

            margin:
                0 0 3px;

            color:
                var(--text);

            font-size:
                24px;

            line-height:
                1.25;

            font-weight:
                700;

            letter-spacing:
                -0.45px;
        }


        .child-id {

            color:
                var(--muted);

            font-size:
                12px;

            font-weight:
                500;
        }


        .child-id strong {

            color:
                var(--text-secondary);

            font-weight:
                600;
        }


        /* =========================================================
           HEADER ACTIONS
        ========================================================== */

        .header-actions {

            display:
                flex;

            align-items:
                center;

            gap:
                8px;

            flex-wrap:
                wrap;
        }


        .header-button {

            display:
                inline-flex;

            align-items:
                center;

            justify-content:
                center;

            gap:
                6px;

            min-height:
                36px;

            padding:
                8px 13px;

            border:
                1px solid var(--border);

            border-radius:
                8px;

            background:
                var(--white);

            color:
                var(--text-secondary);

            text-decoration:
                none;

            font-size:
                12px;

            font-weight:
                600;

            transition:
                all 0.2s ease;
        }


        .header-button:hover {

            border-color:
                var(--primary-border);

            background:
                var(--primary-light);

            color:
                var(--primary);
        }


        .header-button.primary {

            background:
                var(--primary);

            border-color:
                var(--primary);

            color:
                var(--white);
        }


        .header-button.primary:hover {

            background:
                var(--primary-dark);

            border-color:
                var(--primary-dark);

            color:
                var(--white);
        }


        /* =========================================================
           CHILD META
        ========================================================== */

        .child-meta {

            display:
                grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap:
                10px;

            margin-top:
                20px;

            padding-top:
                18px;

            border-top:
                1px solid var(--border-light);
        }


        .meta-item {

            padding:
                12px 14px;

            background:
                #f8fafc;

            border:
                1px solid #f1f5f9;

            border-radius:
                9px;
        }


        .meta-label {

            display:
                block;

            margin-bottom:
                3px;

            color:
                var(--muted);

            font-size:
                10px;

            line-height:
                1.4;

            font-weight:
                700;

            text-transform:
                uppercase;

            letter-spacing:
                0.55px;
        }


        .meta-value {

            color:
                var(--text);

            font-size:
                13px;

            font-weight:
                600;
        }


        /* =========================================================
           WHO BANNER
        ========================================================== */

        .who-banner {

            display:
                flex;

            align-items:
                flex-start;

            gap:
                13px;

            padding:
                14px 16px;

            margin-bottom:
                22px;

            background:
                var(--primary-light);

            border:
                1px solid var(--primary-border);

            border-radius:
                10px;

            color:
                #1e3a8a;
        }


        .who-icon {

            width:
                32px;

            height:
                32px;

            flex-shrink:
                0;

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            border-radius:
                8px;

            background:
                #dbeafe;

            color:
                var(--primary);

            font-size:
                14px;

            font-weight:
                800;
        }


        .who-banner strong {

            display:
                block;

            margin-bottom:
                2px;

            color:
                #1e3a8a;

            font-size:
                13px;

            font-weight:
                700;
        }


        .who-banner span {

            display:
                block;

            color:
                #36518a;

            font-size:
                12px;

            line-height:
                1.6;
        }


        /* =========================================================
           SECTION TITLE
        ========================================================== */

        .section-title {

            margin:
                0 0 11px;

            color:
                var(--text);

            font-size:
                15px;

            line-height:
                1.4;

            font-weight:
                700;

            letter-spacing:
                -0.15px;
        }


        /* =========================================================
           SUMMARY CARDS
        ========================================================== */

        .summary-grid {

            display:
                grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap:
                12px;

            margin-bottom:
                22px;
        }


        .summary-card {

            min-width:
                0;

            background:
                var(--white);

            border:
                1px solid var(--border);

            border-radius:
                11px;

            padding:
                16px;

            box-shadow:
                var(--shadow);

            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }


        .summary-card:hover {

            transform:
                translateY(-1px);

            box-shadow:
                var(--shadow-hover);
        }


        .summary-card-top {

            display:
                flex;

            align-items:
                center;

            justify-content:
                space-between;

            margin-bottom:
                11px;
        }


        .summary-label {

            color:
                var(--muted);

            font-size:
                11px;

            font-weight:
                600;
        }


        .summary-icon {

            width:
                32px;

            height:
                32px;

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            border-radius:
                8px;

            font-size:
                14px;

            font-weight:
                700;
        }


        .icon-blue {

            background:
                var(--primary-light);

            color:
                var(--primary);
        }


        .icon-green {

            background:
                var(--success-bg);

            color:
                var(--success);
        }


        .icon-purple {

            background:
                var(--purple-bg);

            color:
                var(--purple);
        }


        .icon-orange {

            background:
                var(--warning-bg);

            color:
                var(--warning);
        }


        .summary-value {

            min-height:
                30px;

            color:
                var(--text);

            font-size:
                20px;

            line-height:
                1.35;

            font-weight:
                700;

            letter-spacing:
                -0.25px;
        }


        .summary-subtitle {

            margin-top:
                3px;

            color:
                var(--muted-light);

            font-size:
                10px;

            font-weight:
                500;
        }


        /* =========================================================
           STATUS COLORS
        ========================================================== */

        .status-normal {

            color:
                var(--success);
        }


        .status-warning {

            color:
                var(--warning);
        }


        .status-danger {

            color:
                var(--danger);
        }


        .status-neutral {

            color:
                var(--text-secondary);
        }


        /* =========================================================
           ASSESSMENT CARD
        ========================================================== */

        .assessment-card {

            background:
                var(--white);

            border:
                1px solid var(--border);

            border-radius:
                var(--radius);

            box-shadow:
                var(--shadow);

            margin-bottom:
                22px;

            overflow:
                hidden;
        }


        .assessment-header {

            display:
                flex;

            align-items:
                center;

            justify-content:
                space-between;

            gap:
                15px;

            padding:
                15px 18px;

            border-bottom:
                1px solid var(--border-light);
        }


        .assessment-header h2 {

            margin:
                0;

            color:
                var(--text);

            font-size:
                14px;

            font-weight:
                700;
        }


        .assessment-header span {

            color:
                var(--muted);

            font-size:
                11px;

            font-weight:
                500;
        }


        .assessment-body {

            padding:
                18px;
        }


        .assessment-grid {

            display:
                grid;

            grid-template-columns:
                1.1fr 2fr;

            gap:
                14px;
        }


        .overall-status {

            padding:
                18px;

            background:
                #f8fafc;

            border:
                1px solid var(--border-light);

            border-radius:
                10px;
        }


        .status-label {

            margin-bottom:
                7px;

            color:
                var(--muted);

            font-size:
                10px;

            line-height:
                1.4;

            font-weight:
                700;

            text-transform:
                uppercase;

            letter-spacing:
                0.5px;
        }


        .status-value {

            font-size:
                19px;

            line-height:
                1.35;

            font-weight:
                700;

            letter-spacing:
                -0.2px;
        }


        .assessment-message {

            margin-top:
                7px;

            color:
                var(--muted);

            font-size:
                11px;

            line-height:
                1.6;
        }


        /* =========================================================
           INDICATORS
        ========================================================== */

        .indicator-grid {

            display:
                grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap:
                9px;
        }


        .indicator {

            padding:
                11px 12px;

            background:
                var(--white);

            border:
                1px solid var(--border);

            border-radius:
                8px;
        }


        .indicator-top {

            display:
                flex;

            align-items:
                center;

            justify-content:
                space-between;

            gap:
                10px;
        }


        .indicator-name {

            color:
                var(--muted);

            font-size:
                11px;

            font-weight:
                500;
        }


        .indicator-status {

            color:
                var(--text-secondary);

            font-size:
                10px;

            font-weight:
                700;

            text-align:
                right;
        }


        /* =========================================================
           CHART SECTION
        ========================================================== */

        .chart-section {

            margin-top:
                22px;
        }


        /* =========================================================
           CHART CARD
        ========================================================== */

        .chart-card {

            background:
                var(--white);

            border:
                1px solid var(--border);

            border-radius:
                var(--radius);

            box-shadow:
                var(--shadow);

            margin-bottom:
                18px;

            overflow:
                hidden;
        }


        .chart-card-header {

            display:
                flex;

            align-items:
                flex-start;

            justify-content:
                space-between;

            gap:
                15px;

            padding:
                17px 20px;

            border-bottom:
                1px solid var(--border-light);
        }


        .chart-title-group {

            min-width:
                0;
        }


        .chart-title-group h2 {

            margin:
                0 0 3px;

            color:
                var(--text);

            font-size:
                15px;

            line-height:
                1.4;

            font-weight:
                700;

            letter-spacing:
                -0.15px;
        }


        .chart-title-group p {

            margin:
                0;

            color:
                var(--muted);

            font-size:
                11px;

            line-height:
                1.5;

            font-weight:
                400;
        }


        .chart-badge {

            display:
                inline-flex;

            align-items:
                center;

            justify-content:
                center;

            flex-shrink:
                0;

            min-height:
                27px;

            padding:
                5px 9px;

            border:
                1px solid var(--border);

            border-radius:
                7px;

            background:
                #f8fafc;

            color:
                var(--muted);

            font-size:
                10px;

            font-weight:
                600;
        }


        .chart-body {

            padding:
                18px 20px 16px;
        }


        .chart-wrapper {

            position:
                relative;

            width:
                100%;

            height:
                400px;
        }


        .chart-footer {

            margin-top:
                14px;

            padding:
                10px 12px;

            background:
                #f8fafc;

            border:
                1px solid var(--border-light);

            border-radius:
                8px;

            color:
                var(--muted);

            font-size:
                10px;

            line-height:
                1.6;
        }


        .chart-footer strong {

            color:
                var(--text-secondary);

            font-weight:
                600;
        }


        /* =========================================================
           EMPTY STATE
        ========================================================== */

        .empty-state {

            background:
                var(--white);

            border:
                1px solid var(--border);

            border-radius:
                var(--radius);

            box-shadow:
                var(--shadow);

            padding:
                60px 30px;

            text-align:
                center;
        }


        .empty-icon {

            width:
                58px;

            height:
                58px;

            margin:
                0 auto 14px;

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            border-radius:
                14px;

            background:
                var(--primary-light);

            color:
                var(--primary);

            font-size:
                23px;
        }


        .empty-state h2 {

            margin:
                0 0 7px;

            color:
                var(--text);

            font-size:
                17px;

            font-weight:
                700;
        }


        .empty-state p {

            max-width:
                480px;

            margin:
                0 auto;

            color:
                var(--muted);

            font-size:
                12px;

            line-height:
                1.6;
        }


        /* =========================================================
           CLINICAL NOTE
        ========================================================== */

        .clinical-note {

            margin-top:
                20px;

            padding:
                13px 15px;

            background:
                var(--warning-bg);

            border:
                1px solid var(--warning-border);

            border-radius:
                9px;

            color:
                #78350f;

            font-size:
                11px;

            line-height:
                1.6;
        }


        .clinical-note strong {

            display:
                block;

            margin-bottom:
                2px;

            color:
                #78350f;

            font-weight:
                700;
        }


        /* =========================================================
           RESPONSIVE
        ========================================================== */

        @media (max-width: 1050px) {

            .child-meta {

                grid-template-columns:
                    repeat(2, 1fr);
            }


            .summary-grid {

                grid-template-columns:
                    repeat(2, 1fr);
            }


            .assessment-grid {

                grid-template-columns:
                    1fr;
            }

        }


        @media (max-width: 720px) {

            .page-container {

                padding:
                    16px;
            }


            .child-header {

                padding:
                    18px;
            }


            .child-header-main {

                flex-direction:
                    column;
            }


            .header-actions {

                width:
                    100%;
            }


            .header-button {

                flex:
                    1;
            }


            .child-name {

                font-size:
                    21px;
            }


            .child-meta {

                grid-template-columns:
                    1fr;
            }


            .summary-grid {

                grid-template-columns:
                    1fr;
            }


            .indicator-grid {

                grid-template-columns:
                    1fr;
            }


            .chart-card-header {

                flex-direction:
                    column;
            }


            .chart-badge {

                align-self:
                    flex-start;
            }


            .chart-wrapper {

                height:
                    330px;
            }


            .chart-body {

                padding:
                    14px;
            }

        }


        @media (max-width: 480px) {

            .page-container {

                padding:
                    12px;
            }


            .child-identity {

                align-items:
                    flex-start;
            }


            .child-avatar {

                width:
                    50px;

                height:
                    50px;

                font-size:
                    19px;
            }


            .child-name {

                font-size:
                    19px;
            }


            .header-actions {

                display:
                    grid;

                grid-template-columns:
                    1fr;
            }


            .header-button {

                width:
                    100%;
            }


            .chart-wrapper {

                height:
                    300px;
            }

        }

    </style>

</head>


<body>


<div class="page-container">


    {{-- =========================================================
         TOP NAVIGATION
    ========================================================== --}}

    <div class="top-navigation">

        <a
            href="{{ route('children.show', $child) }}"
            class="back-link"
        >

            <span class="back-arrow">
                ←
            </span>

            Back to Child Profile

        </a>

    </div>


    {{-- =========================================================
         LATEST DATA
    ========================================================== --}}

    @php

        /*
        |--------------------------------------------------------------------------
        | Convert measurement data to collection
        |--------------------------------------------------------------------------
        */

        $measurementCollection =
            collect($measurementData ?? []);


        /*
        |--------------------------------------------------------------------------
        | Latest measurement
        |--------------------------------------------------------------------------
        */

        $latestData =
            $measurementCollection
                ->sortBy(function ($measurement) {
                    return $measurement['date'] ?? null;
                })
                ->last();


        /*
        |--------------------------------------------------------------------------
        | Latest health record
        |--------------------------------------------------------------------------
        */

        $latestRecord =
            $records
                ->sortBy(function ($record) {

                    return
                        $record->recorded_at
                        ?? $record->date
                        ?? $record->created_at;

                })
                ->last();


        /*
        |--------------------------------------------------------------------------
        | Latest growth assessment
        |--------------------------------------------------------------------------
        */

        $latestAssessment =
            $latestRecord
                ? $latestRecord->growthAssessment
                : null;


        /*
        |--------------------------------------------------------------------------
        | Latest measurement values
        |--------------------------------------------------------------------------
        */

        $latestAgeMonths =
            $latestData['age_months'] ?? null;


        $latestOverallStatus =
            $latestData['overall_status'] ?? null;


        $latestWeight =
            $latestData['weight'] ?? null;


        $latestHeight =
            $latestData['height'] ?? null;


        $latestBmi =
            $latestData['bmi'] ?? null;


        $latestDate =
            $latestData['date'] ?? null;


    @endphp


    {{-- =========================================================
         CHILD HEADER
    ========================================================== --}}

    <section class="child-header">


        <div class="child-header-main">


            <div class="child-identity">


                <div class="child-avatar">

                    {{ strtoupper(
                        substr(
                            $child->first_name,
                            0,
                            1
                        )
                    ) }}

                </div>


                <div>

                    <h1 class="child-name">

                        {{ $child->first_name }}
                        {{ $child->last_name }}

                    </h1>


                    <div class="child-id">

                        Child ID:

                        <strong>
                            {{ $child->child_id }}
                        </strong>

                    </div>

                </div>


            </div>


            {{-- =================================================
                 HEADER ACTIONS
            ================================================== --}}

            <div class="header-actions">


                <a
                    href="{{ route('children.show', $child) }}"
                    class="header-button"
                >
                    ← Profile
                </a>


                <a
                    href="{{ route('health-records.index', $child) }}"
                    class="header-button"
                >
                    Health Records
                </a>


                <a
                    href="{{ route('children.growth-charts', $child) }}"
                    class="header-button primary"
                >
                    📈 Growth Charts
                </a>


                <a
                    href="{{ route('children.edit', $child) }}"
                    class="header-button"
                >
                    Edit Child
                </a>


            </div>


        </div>


        {{-- =====================================================
             CHILD META
        ====================================================== --}}

        <div class="child-meta">


            <div class="meta-item">

                <span class="meta-label">
                    Sex
                </span>

                <span class="meta-value">
                    {{ ucfirst($child->sex) }}
                </span>

            </div>


            <div class="meta-item">

                <span class="meta-label">
                    Growth Records
                </span>

                <span class="meta-value">
                    {{ $records->count() }}
                </span>

            </div>


            <div class="meta-item">

                <span class="meta-label">
                    Latest Visit
                </span>

                <span class="meta-value">

                    {{ $latestDate ?? 'No visit recorded' }}

                </span>

            </div>


            <div class="meta-item">

                <span class="meta-label">
                    Age at Latest Visit
                </span>

                <span class="meta-value">


                    @if($latestAgeMonths !== null)

                        {{ number_format(
                            (float) $latestAgeMonths,
                            1
                        ) }}

                        months

                    @else

                        Not available

                    @endif


                </span>

            </div>


        </div>


    </section>


    {{-- =========================================================
         WHO INFORMATION
    ========================================================== --}}

    <div class="who-banner">


        <div class="who-icon">
            ✓
        </div>


        <div>

            <strong>
                WHO Child Growth Standards
            </strong>


            <span>

                Growth measurements recorded during health visits
                are plotted against the configured WHO reference
                curves using Z-score boundaries of −3, −2, 0, +2
                and +3.

            </span>

        </div>


    </div>


    {{-- =========================================================
         EMPTY STATE
    ========================================================== --}}

    @if($records->isEmpty())


        <div class="empty-state">


            <div class="empty-icon">
                📈
            </div>


            <h2>
                No Growth Records Available
            </h2>


            <p>

                This child does not yet have health records
                containing growth measurements. Add a health
                record to begin monitoring growth over time.

            </p>


        </div>


    @else


        {{-- =====================================================
             SUMMARY
        ====================================================== --}}

        <h2 class="section-title">
            Latest Growth Summary
        </h2>


        <div class="summary-grid">


            {{-- =================================================
                 WEIGHT
            ================================================== --}}

            <div class="summary-card">


                <div class="summary-card-top">

                    <span class="summary-label">
                        Latest Weight
                    </span>


                    <div class="summary-icon icon-blue">
                        ⚖
                    </div>

                </div>


                <div class="summary-value">


                    @if($latestWeight !== null)

                        {{ number_format(
                            (float) $latestWeight,
                            2
                        ) }}

                        kg

                    @else

                        —

                    @endif


                </div>


                <div class="summary-subtitle">
                    Most recent recorded weight
                </div>


            </div>


            {{-- =================================================
                 HEIGHT
            ================================================== --}}

            <div class="summary-card">


                <div class="summary-card-top">

                    <span class="summary-label">
                        Length / Height
                    </span>


                    <div class="summary-icon icon-green">
                        ↕
                    </div>

                </div>


                <div class="summary-value">


                    @if($latestHeight !== null)

                        {{ number_format(
                            (float) $latestHeight,
                            2
                        ) }}

                        cm

                    @else

                        —

                    @endif


                </div>


                <div class="summary-subtitle">
                    Latest recorded measurement
                </div>


            </div>


            {{-- =================================================
                 BMI
            ================================================== --}}

            <div class="summary-card">


                <div class="summary-card-top">

                    <span class="summary-label">
                        BMI
                    </span>


                    <div class="summary-icon icon-purple">
                        ◈
                    </div>

                </div>


                <div class="summary-value">


                    @if($latestBmi !== null)

                        {{ number_format(
                            (float) $latestBmi,
                            2
                        ) }}

                    @else

                        —

                    @endif


                </div>


                <div class="summary-subtitle">
                    Latest calculated BMI
                </div>


            </div>


            {{-- =================================================
                 STATUS
            ================================================== --}}

            <div class="summary-card">


                <div class="summary-card-top">

                    <span class="summary-label">
                        Overall Status
                    </span>


                    <div class="summary-icon icon-orange">
                        !
                    </div>

                </div>


                <div
                    class="
                        summary-value

                        @if($latestOverallStatus === 'Normal')
                            status-normal

                        @elseif(
                            str_contains(
                                strtolower(
                                    (string) $latestOverallStatus
                                ),
                                'severe'
                            )
                        )
                            status-danger

                        @elseif(
                            str_contains(
                                strtolower(
                                    (string) $latestOverallStatus
                                ),
                                'moderate'
                            )
                        )
                            status-warning

                        @else
                            status-neutral
                        @endif
                    "
                >

                    {{ $latestOverallStatus ?? 'Not assessed' }}

                </div>


                <div class="summary-subtitle">
                    Latest WHO assessment
                </div>


            </div>


        </div>


        {{-- =====================================================
             LATEST ASSESSMENT
        ====================================================== --}}

        @if($latestAssessment)


            <section class="assessment-card">


                <div class="assessment-header">

                    <h2>
                        Latest Growth Assessment
                    </h2>


                    <span>
                        {{ $latestDate ?? 'Latest visit' }}
                    </span>

                </div>


                <div class="assessment-body">


                    <div class="assessment-grid">


                        {{-- =====================================
                             OVERALL STATUS
                        ====================================== --}}

                        <div class="overall-status">


                            <div class="status-label">
                                Overall nutritional status
                            </div>


                            <div
                                class="
                                    status-value

                                    @if($latestOverallStatus === 'Normal')
                                        status-normal

                                    @elseif(
                                        str_contains(
                                            strtolower(
                                                (string)
                                                $latestOverallStatus
                                            ),
                                            'severe'
                                        )
                                    )
                                        status-danger

                                    @elseif(
                                        str_contains(
                                            strtolower(
                                                (string)
                                                $latestOverallStatus
                                            ),
                                            'moderate'
                                        )
                                    )
                                        status-warning

                                    @else
                                        status-neutral
                                    @endif
                                "
                            >

                                {{ $latestOverallStatus ?? 'Not assessed' }}

                            </div>


                            <div class="assessment-message">

                                WHO-based screening result from
                                the latest available growth record.

                            </div>


                        </div>


                        {{-- =====================================
                             INDICATORS
                        ====================================== --}}

                        <div class="indicator-grid">


                            @if(
                                $latestAssessment->weight_for_age_status
                                !== null
                            )

                                <div class="indicator">

                                    <div class="indicator-top">

                                        <span class="indicator-name">
                                            Weight-for-Age
                                        </span>

                                        <span class="indicator-status">
                                            {{ $latestAssessment->weight_for_age_status }}
                                        </span>

                                    </div>

                                </div>

                            @endif


                            @if(
                                $latestAssessment->height_for_age_status
                                !== null
                            )

                                <div class="indicator">

                                    <div class="indicator-top">

                                        <span class="indicator-name">
                                            Length/Height-for-Age
                                        </span>

                                        <span class="indicator-status">
                                            {{ $latestAssessment->height_for_age_status }}
                                        </span>

                                    </div>

                                </div>

                            @endif


                            @if(
                                $latestAssessment->weight_for_length_status
                                !== null
                            )

                                <div class="indicator">

                                    <div class="indicator-top">

                                        <span class="indicator-name">
                                            Weight-for-Length
                                        </span>

                                        <span class="indicator-status">
                                            {{ $latestAssessment->weight_for_length_status }}
                                        </span>

                                    </div>

                                </div>

                            @endif


                            @if(
                                $latestAssessment->weight_for_height_status
                                !== null
                            )

                                <div class="indicator">

                                    <div class="indicator-top">

                                        <span class="indicator-name">
                                            Weight-for-Height
                                        </span>

                                        <span class="indicator-status">
                                            {{ $latestAssessment->weight_for_height_status }}
                                        </span>

                                    </div>

                                </div>

                            @endif


                            @if(
                                $latestAssessment->bmi_for_age_status
                                !== null
                            )

                                <div class="indicator">

                                    <div class="indicator-top">

                                        <span class="indicator-name">
                                            BMI-for-Age
                                        </span>

                                        <span class="indicator-status">
                                            {{ $latestAssessment->bmi_for_age_status }}
                                        </span>

                                    </div>

                                </div>

                            @endif


                            @if(
                                $latestAssessment->muac_status
                                !== null
                            )

                                <div class="indicator">

                                    <div class="indicator-top">

                                        <span class="indicator-name">
                                            MUAC
                                        </span>

                                        <span class="indicator-status">
                                            {{ $latestAssessment->muac_status }}
                                        </span>

                                    </div>

                                </div>

                            @endif


                        </div>


                    </div>


                </div>


            </section>


        @endif


        {{-- =====================================================
             GROWTH CHARTS
        ====================================================== --}}

        <div class="chart-section">


            <h2 class="section-title">
                WHO Growth Charts
            </h2>


            {{-- =================================================
                 WEIGHT FOR AGE
            ================================================== --}}

            <section class="chart-card">


                <div class="chart-card-header">


                    <div class="chart-title-group">

                        <h2>
                            Weight-for-Age
                        </h2>

                        <p>
                            Weight progression compared with WHO
                            weight-for-age reference curves.
                        </p>

                    </div>


                    <div class="chart-badge">
                        WHO standard
                    </div>


                </div>


                <div class="chart-body">


                    <div class="chart-wrapper">

                        <canvas
                            id="weightForAgeChart"
                        ></canvas>

                    </div>


                    <div class="chart-footer">

                        <strong>
                            Interpretation:
                        </strong>

                        The child's measurements are shown against
                        WHO reference curves from −3 Z through +3 Z.
                        Hover over a child measurement point to see
                        the recorded date, age and value.

                    </div>


                </div>


            </section>


            {{-- =================================================
                 LENGTH / HEIGHT FOR AGE
            ================================================== --}}

            <section class="chart-card">


                <div class="chart-card-header">


                    <div class="chart-title-group">

                        <h2>
                            Length/Height-for-Age
                        </h2>

                        <p>
                            Linear growth progression compared with
                            WHO length/height-for-age standards.
                        </p>

                    </div>


                    <div class="chart-badge">
                        WHO standard
                    </div>


                </div>


                <div class="chart-body">


                    <div class="chart-wrapper">

                        <canvas
                            id="heightForAgeChart"
                        ></canvas>

                    </div>


                    <div class="chart-footer">

                        <strong>
                            Measurement:
                        </strong>

                        Length or height is plotted according to
                        the measurement recorded during each
                        health visit.

                    </div>


                </div>


            </section>


            {{-- =================================================
                 WEIGHT FOR LENGTH
            ================================================== --}}

            @if($showWeightForLength)


                <section class="chart-card">


                    <div class="chart-card-header">


                        <div class="chart-title-group">

                            <h2>
                                Weight-for-Length
                            </h2>

                            <p>
                                Weight relative to body length using
                                WHO reference standards.
                            </p>

                        </div>


                        <div class="chart-badge">
                            Under 24 months
                        </div>


                    </div>


                    <div class="chart-body">


                        <div class="chart-wrapper">

                            <canvas
                                id="weightForLengthChart"
                            ></canvas>

                        </div>


                        <div class="chart-footer">

                            <strong>
                                WHO reference:
                            </strong>

                            Weight-for-length is displayed for
                            children below 24 months when length
                            measurements are available.

                        </div>


                    </div>


                </section>


            @endif


            {{-- =================================================
                 WEIGHT FOR HEIGHT
            ================================================== --}}

            @if($showWeightForHeight)


                <section class="chart-card">


                    <div class="chart-card-header">


                        <div class="chart-title-group">

                            <h2>
                                Weight-for-Height
                            </h2>

                            <p>
                                Weight relative to body height using
                                WHO reference standards.
                            </p>

                        </div>


                        <div class="chart-badge">
                            24 months and older
                        </div>


                    </div>


                    <div class="chart-body">


                        <div class="chart-wrapper">

                            <canvas
                                id="weightForHeightChart"
                            ></canvas>

                        </div>


                        <div class="chart-footer">

                            <strong>
                                WHO reference:
                            </strong>

                            Weight-for-height is displayed for
                            children aged 24 months and above when
                            height measurements are available.

                        </div>


                    </div>


                </section>


            @endif


            {{-- =================================================
                 BMI FOR AGE
            ================================================== --}}

            <section class="chart-card">


                <div class="chart-card-header">


                    <div class="chart-title-group">

                        <h2>
                            BMI-for-Age
                        </h2>

                        <p>
                            Body mass index progression compared with
                            WHO BMI-for-age reference curves.
                        </p>

                    </div>


                    <div class="chart-badge">
                        WHO standard
                    </div>


                </div>


                <div class="chart-body">


                    <div class="chart-wrapper">

                        <canvas
                            id="bmiForAgeChart"
                        ></canvas>

                    </div>


                    <div class="chart-footer">

                        <strong>
                            Calculation:
                        </strong>

                        BMI is calculated from the recorded weight
                        and height/length and plotted according to
                        the child's age.

                    </div>


                </div>


            </section>


        </div>


        {{-- =====================================================
             CLINICAL NOTE
        ====================================================== --}}

        <div class="clinical-note">

            <strong>
                Monitoring note
            </strong>

            These charts are intended to support growth monitoring
            and screening. They should be interpreted together with
            the child's health history, clinical observations and
            other assessment findings by an appropriately qualified
            health professional.

        </div>


    @endif


</div>


<script>

    /* ============================================================
       DATA FROM LARAVEL
    ============================================================ */

    const measurementData =
        @json($measurementData ?? []);


    const weightForAgeReferences =
        @json($weightForAgeReferences ?? []);


    const heightForAgeReferences =
        @json($heightForAgeReferences ?? []);


    const bmiForAgeReferences =
        @json($bmiForAgeReferences ?? []);


    const weightForLengthReferences =
        @json($weightForLengthReferences ?? []);


    const weightForHeightReferences =
        @json($weightForHeightReferences ?? []);


    /* ============================================================
       WHO CURVE BUILDER
    ============================================================ */

    function buildWhoDatasets(
        references,
        labels
    ) {

        references =
            references || {};


        return labels.map(function (z) {

            const key =
                String(z);


            return {

                label:
                    'WHO ' +
                    (
                        z > 0
                            ? '+' + z
                            : z
                    ) +
                    ' Z',

                data:
                    Array.isArray(
                        references[key]
                    )
                        ? references[key]
                        : [],

                parsing: {

                    xAxisKey:
                        'x',

                    yAxisKey:
                        'y'

                },

                tension:
                    0.15,

                pointRadius:
                    0,

                pointHoverRadius:
                    0,

                borderWidth:
                    1.4,

                fill:
                    false

            };

        });

    }


    /* ============================================================
       CHILD DATASET
    ============================================================ */

    function actualDataset(
        label,
        data
    ) {

        return {

            label:
                label,

            data:
                data,

            parsing: {

                xAxisKey:
                    'x',

                yAxisKey:
                    'y'

            },

            pointRadius:
                5,

            pointHoverRadius:
                8,

            pointHitRadius:
                15,

            borderWidth:
                3,

            tension:
                0.2,

            showLine:
                true,

            spanGaps:
                true

        };

    }


    /* ============================================================
       CHILD TOOLTIP
    ============================================================ */

    function childTooltipLabel(
        context
    ) {

        const point =
            context.raw;


        if (
            point &&
            point.date
        ) {

            return [

                'Value: ' +
                    Number(
                        point.y
                    ).toFixed(2) +
                    (
                        point.unit
                            ? ' ' +
                              point.unit
                            : ''
                    ),

                'Age: ' +
                    point.age,

                'Visit: ' +
                    point.date

            ];

        }


        if (
            context.parsed &&
            context.parsed.y !== null
        ) {

            return (

                context.dataset.label +
                ': ' +
                Number(
                    context.parsed.y
                ).toFixed(2)

            );

        }


        return context.dataset.label;

    }


    /* ============================================================
       COMMON CHART OPTIONS
    ============================================================ */

    function commonChartOptions(
        xTitle,
        yTitle
    ) {

        return {

            responsive:
                true,

            maintainAspectRatio:
                false,

            interaction: {

                mode:
                    'nearest',

                intersect:
                    false

            },

            animation: {

                duration:
                    650

            },


            scales: {

                x: {

                    type:
                        'linear',

                    grid: {

                        color:
                            'rgba(148, 163, 184, 0.12)',

                        drawBorder:
                            false

                    },

                    ticks: {

                        color:
                            '#6b7280',

                        font: {

                            family:
                                'Inter',

                            size:
                                10,

                            weight:
                                '500'

                        }

                    },

                    title: {

                        display:
                            true,

                        text:
                            xTitle,

                        color:
                            '#4b5563',

                        font: {

                            family:
                                'Inter',

                            size:
                                11,

                            weight:
                                '600'

                        }

                    }

                },


                y: {

                    grid: {

                        color:
                            'rgba(148, 163, 184, 0.12)',

                        drawBorder:
                            false

                    },

                    ticks: {

                        color:
                            '#6b7280',

                        font: {

                            family:
                                'Inter',

                            size:
                                10,

                            weight:
                                '500'

                        }

                    },

                    title: {

                        display:
                            true,

                        text:
                            yTitle,

                        color:
                            '#4b5563',

                        font: {

                            family:
                                'Inter',

                            size:
                                11,

                            weight:
                                '600'

                        }

                    }

                }

            },


            plugins: {

                legend: {

                    position:
                        'bottom',

                    labels: {

                        usePointStyle:
                            true,

                        pointStyle:
                            'line',

                        padding:
                            14,

                        color:
                            '#4b5563',

                        font: {

                            family:
                                'Inter',

                            size:
                                10,

                            weight:
                                '500'

                        }

                    }

                },


                tooltip: {

                    backgroundColor:
                        '#111827',

                    titleColor:
                        '#ffffff',

                    bodyColor:
                        '#e5e7eb',

                    padding:
                        11,

                    cornerRadius:
                        7,

                    displayColors:
                        true,

                    titleFont: {

                        family:
                            'Inter',

                        size:
                            11,

                        weight:
                            '600'

                    },

                    bodyFont: {

                        family:
                            'Inter',

                        size:
                            10,

                        weight:
                            '400'

                    },

                    callbacks: {

                        label:
                            childTooltipLabel

                    }

                }

            }

        };

    }


    /* ============================================================
       HELPER — CHECK WHETHER CANVAS EXISTS
    ============================================================ */

    function canvasExists(id) {

        return document.getElementById(id) !== null;

    }


    /* ============================================================
       1. WEIGHT FOR AGE
    ============================================================ */

    if (
        canvasExists(
            'weightForAgeChart'
        )
    ) {

        const weightForAgeWho =
            buildWhoDatasets(
                weightForAgeReferences,
                [-3, -2, 0, 2, 3]
            );


        const weightForAgeActual =
            measurementData

                .filter(function (record) {

                    return (

                        record.age_months !== null &&
                        record.age_months !== undefined &&

                        record.weight !== null &&
                        record.weight !== undefined

                    );

                })

                .map(function (record) {

                    return {

                        x:
                            Number(
                                record.age_months
                            ),

                        y:
                            Number(
                                record.weight
                            ),

                        date:
                            record.date,

                        age:
                            Number(
                                record.age_months
                            ).toFixed(1)
                            +
                            ' months',

                        unit:
                            'kg'

                    };

                });


        new Chart(

            document.getElementById(
                'weightForAgeChart'
            ),

            {

                type:
                    'line',

                data: {

                    datasets: [

                        ...weightForAgeWho,

                        actualDataset(
                            'Child Weight',
                            weightForAgeActual
                        )

                    ]

                },

                options:
                    commonChartOptions(
                        'Age (completed months)',
                        'Weight (kg)'
                    )

            }

        );

    }


    /* ============================================================
       2. LENGTH / HEIGHT FOR AGE
    ============================================================ */

    if (
        canvasExists(
            'heightForAgeChart'
        )
    ) {

        const heightForAgeWho =
            buildWhoDatasets(
                heightForAgeReferences,
                [-3, -2, 0, 2, 3]
            );


        const heightForAgeActual =
            measurementData

                .filter(function (record) {

                    return (

                        record.age_months !== null &&
                        record.age_months !== undefined &&

                        record.height !== null &&
                        record.height !== undefined

                    );

                })

                .map(function (record) {

                    return {

                        x:
                            Number(
                                record.age_months
                            ),

                        y:
                            Number(
                                record.height
                            ),

                        date:
                            record.date,

                        age:
                            Number(
                                record.age_months
                            ).toFixed(1)
                            +
                            ' months',

                        unit:
                            'cm'

                    };

                });


        new Chart(

            document.getElementById(
                'heightForAgeChart'
            ),

            {

                type:
                    'line',

                data: {

                    datasets: [

                        ...heightForAgeWho,

                        actualDataset(
                            'Child Length/Height',
                            heightForAgeActual
                        )

                    ]

                },

                options:
                    commonChartOptions(
                        'Age (completed months)',
                        'Length / Height (cm)'
                    )

            }

        );

    }


    /* ============================================================
       3. WEIGHT FOR LENGTH
    ============================================================ */

    @if($showWeightForLength)

        if (
            canvasExists(
                'weightForLengthChart'
            )
        ) {

            const weightForLengthWho =
                buildWhoDatasets(
                    weightForLengthReferences,
                    [-3, -2, 0, 2, 3]
                );


            const weightForLengthActual =
                measurementData

                    .filter(function (record) {

                        if (
                            record.height === null ||
                            record.height === undefined ||

                            record.weight === null ||
                            record.weight === undefined ||

                            record.age_months === null ||
                            record.age_months === undefined
                        ) {

                            return false;

                        }


                        if (
                            record.measurement_type ===
                            'length'
                        ) {

                            return true;

                        }


                        if (
                            record.measurement_type ===
                            'height'
                        ) {

                            return false;

                        }


                        return (
                            Number(
                                record.age_months
                            ) < 24
                        );

                    })

                    .map(function (record) {

                        return {

                            x:
                                Number(
                                    record.height
                                ),

                            y:
                                Number(
                                    record.weight
                                ),

                            date:
                                record.date,

                            age:
                                Number(
                                    record.age_months
                                ).toFixed(1)
                                +
                                ' months',

                            unit:
                                'kg'

                        };

                    });


            new Chart(

                document.getElementById(
                    'weightForLengthChart'
                ),

                {

                    type:
                        'line',

                    data: {

                        datasets: [

                            ...weightForLengthWho,

                            actualDataset(
                                'Child Weight',
                                weightForLengthActual
                            )

                        ]

                    },

                    options:
                        commonChartOptions(
                            'Length (cm)',
                            'Weight (kg)'
                        )

                }

            );

        }

    @endif


    /* ============================================================
       4. WEIGHT FOR HEIGHT
    ============================================================ */

    @if($showWeightForHeight)

        if (
            canvasExists(
                'weightForHeightChart'
            )
        ) {

            const weightForHeightWho =
                buildWhoDatasets(
                    weightForHeightReferences,
                    [-3, -2, 0, 2, 3]
                );


            const weightForHeightActual =
                measurementData

                    .filter(function (record) {

                        if (
                            record.height === null ||
                            record.height === undefined ||

                            record.weight === null ||
                            record.weight === undefined ||

                            record.age_months === null ||
                            record.age_months === undefined
                        ) {

                            return false;

                        }


                        if (
                            record.measurement_type ===
                            'height'
                        ) {

                            return true;

                        }


                        if (
                            record.measurement_type ===
                            'length'
                        ) {

                            return false;

                        }


                        return (
                            Number(
                                record.age_months
                            ) >= 24
                        );

                    })

                    .map(function (record) {

                        return {

                            x:
                                Number(
                                    record.height
                                ),

                            y:
                                Number(
                                    record.weight
                                ),

                            date:
                                record.date,

                            age:
                                Number(
                                    record.age_months
                                ).toFixed(1)
                                +
                                ' months',

                            unit:
                                'kg'

                        };

                    });


            new Chart(

                document.getElementById(
                    'weightForHeightChart'
                ),

                {

                    type:
                        'line',

                    data: {

                        datasets: [

                            ...weightForHeightWho,

                            actualDataset(
                                'Child Weight',
                                weightForHeightActual
                            )

                        ]

                    },

                    options:
                        commonChartOptions(
                            'Height (cm)',
                            'Weight (kg)'
                        )

                }

            );

        }

    @endif


    /* ============================================================
       5. BMI FOR AGE
    ============================================================ */

    if (
        canvasExists(
            'bmiForAgeChart'
        )
    ) {

        const bmiForAgeWho =
            buildWhoDatasets(
                bmiForAgeReferences,
                [-3, -2, 0, 2, 3]
            );


        const bmiForAgeActual =
            measurementData

                .filter(function (record) {

                    return (

                        record.age_months !== null &&
                        record.age_months !== undefined &&

                        record.bmi !== null &&
                        record.bmi !== undefined

                    );

                })

                .map(function (record) {

                    return {

                        x:
                            Number(
                                record.age_months
                            ),

                        y:
                            Number(
                                record.bmi
                            ),

                        date:
                            record.date,

                        age:
                            Number(
                                record.age_months
                            ).toFixed(1)
                            +
                            ' months',

                        unit:
                            'kg/m²'

                    };

                });


        new Chart(

            document.getElementById(
                'bmiForAgeChart'
            ),

            {

                type:
                    'line',

                data: {

                    datasets: [

                        ...bmiForAgeWho,

                        actualDataset(
                            'Child BMI',
                            bmiForAgeActual
                        )

                    ]

                },

                options:
                    commonChartOptions(
                        'Age (completed months)',
                        'BMI (kg/m²)'
                    )

            }

        );

    }


</script>


</body>

</html>