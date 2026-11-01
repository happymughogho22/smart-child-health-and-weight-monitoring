@extends('layouts.app')

@section('title', 'Reports')

@section('page-title', 'Reports')

@section('page-subtitle', 'Generate and review child growth monitoring reports')

@section('content')

<style>

    .reports-page {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
    }

    .reports-hero {
        margin-bottom: 24px;
    }

    .reports-hero h1 {
        margin: 0 0 7px;
        color: #123b6d;
        font-size: 25px;
        font-weight: 800;
        letter-spacing: -0.3px;
    }

    .reports-hero p {
        margin: 0;
        color: #64748b;
        font-size: 12px;
        line-height: 1.7;
        max-width: 760px;
    }

    /* =========================================================
       THREE REPORT CARDS
    ========================================================== */

    .report-types {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 22px;
    }

    .report-card {
        position: relative;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 21px;
        min-height: 265px;
        display: flex;
        flex-direction: column;
        transition: 0.2s ease;
        box-sizing: border-box;
    }

    .report-card:hover {
        border-color: #bfd0e2;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.07);
        transform: translateY(-2px);
    }

    .report-card-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: #eaf2fb;
        color: #123b6d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        margin-bottom: 16px;
    }

    .report-card:nth-child(2) .report-card-icon {
        background: #edf8f3;
        color: #15803d;
    }

    .report-card:nth-child(3) .report-card-icon {
        background: #fff7e8;
        color: #b45309;
    }

    .report-card h2 {
        margin: 0 0 7px;
        color: #1e293b;
        font-size: 15px;
        font-weight: 750;
    }

    .report-card p {
        margin: 0;
        color: #64748b;
        font-size: 10.5px;
        line-height: 1.7;
    }

    .report-features {
        list-style: none;
        padding: 0;
        margin: 16px 0 20px;
    }

    .report-features li {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 7px;
        color: #475569;
        font-size: 9.5px;
    }

    .report-features li::before {
        content: "✓";
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #123b6d;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
        font-weight: 800;
        flex-shrink: 0;
    }

    .report-card-button {
        margin-top: auto;
        width: 100%;
        height: 39px;
        border: 0;
        border-radius: 7px;
        background: #123b6d;
        color: #ffffff;
        font-family: inherit;
        font-size: 10.5px;
        font-weight: 700;
        cursor: pointer;
        transition: 0.2s;
    }

    .report-card-button:hover {
        background: #0c2d52;
    }

    .report-card:nth-child(2) .report-card-button {
        background: #15803d;
    }

    .report-card:nth-child(2) .report-card-button:hover {
        background: #166534;
    }

    .report-card:nth-child(3) .report-card-button {
        background: #b45309;
    }

    .report-card:nth-child(3) .report-card-button:hover {
        background: #92400e;
    }

    /* =========================================================
       REPORT SECTIONS
    ========================================================== */

    .report-section {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .report-section-header {
        padding: 17px 20px;
        border-bottom: 1px solid #e8edf3;
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .section-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background: #f1f5f9;
        color: #123b6d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .report-section-header h3 {
        margin: 0 0 3px;
        color: #1e293b;
        font-size: 13px;
        font-weight: 750;
    }

    .report-section-header p {
        margin: 0;
        color: #94a3b8;
        font-size: 9.5px;
    }

    .report-section-body {
        padding: 20px;
    }

    /* =========================================================
       FORMS
    ========================================================== */

    .report-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .field-group {
        margin-bottom: 5px;
    }

    .field-group label {
        display: block;
        margin-bottom: 7px;
        color: #475569;
        font-size: 10px;
        font-weight: 700;
    }

    .field-group select,
    .field-group input {
        width: 100%;
        height: 40px;
        border: 1px solid #dce3eb;
        border-radius: 7px;
        padding: 0 11px;
        background: #ffffff;
        color: #334155;
        font-family: inherit;
        font-size: 10.5px;
        outline: none;
        box-sizing: border-box;
    }

    .field-group select:focus,
    .field-group input:focus {
        border-color: #123b6d;
        box-shadow: 0 0 0 2px rgba(18, 59, 109, 0.08);
    }

    /* =========================================================
       PERIOD OPTIONS
    ========================================================== */

    .period-options {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 9px;
    }

    .period-option {
        position: relative;
    }

    .period-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .period-option label {
        display: block;
        padding: 11px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 7px;
        background: #ffffff;
        cursor: pointer;
        text-align: center;
        color: #475569;
        font-size: 9.5px;
        font-weight: 650;
        transition: 0.2s;
    }

    .period-option label:hover {
        border-color: #bfd0e2;
        background: #f8fbff;
    }

    .period-option input:checked + label {
        border-color: #123b6d;
        background: #f4f8fd;
        color: #123b6d;
        box-shadow: 0 0 0 2px rgba(18, 59, 109, 0.07);
    }

    /* =========================================================
       CUSTOM DATES
    ========================================================== */

    .custom-dates {
        display: none;
        margin-top: 13px;
        padding: 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #f8fafc;
    }

    .custom-dates.show {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    /* =========================================================
       BUTTONS
    ========================================================== */

    .action-row {
        display: flex;
        justify-content: flex-end;
        gap: 9px;
        margin-top: 18px;
    }

    .action-button {
        height: 40px;
        padding: 0 17px;
        border: 0;
        border-radius: 7px;
        font-family: inherit;
        font-size: 10px;
        font-weight: 700;
        cursor: pointer;
        transition: 0.2s;
    }

    .action-button.primary {
        background: #123b6d;
        color: #ffffff;
    }

    .action-button.primary:hover {
        background: #0c2d52;
    }

    .action-button.secondary {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .action-button.secondary:hover {
        background: #e8edf3;
    }

    /* =========================================================
       NOTES / INFORMATION
    ========================================================== */

    .nutrition-note {
        margin-top: 15px;
        padding: 12px 14px;
        background: #fff8eb;
        border: 1px solid #fde7b0;
        border-radius: 8px;
        color: #92400e;
        font-size: 9.5px;
        line-height: 1.6;
    }

    .report-info {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }

    .info-box {
        padding: 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #f8fafc;
    }

    .info-box strong {
        display: block;
        color: #334155;
        font-size: 10px;
        margin-bottom: 4px;
    }

    .info-box span {
        color: #94a3b8;
        font-size: 9px;
        line-height: 1.5;
    }

    .error-box {
        margin-bottom: 20px;
        padding: 12px 14px;
        border: 1px solid #fecaca;
        border-radius: 8px;
        background: #fef2f2;
        color: #991b1b;
        font-size: 10px;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 1050px) {

        .report-types {
            grid-template-columns: 1fr;
        }

        .report-card {
            min-height: auto;
        }

        .report-info {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 700px) {

        .report-grid,
        .custom-dates {
            grid-template-columns: 1fr;
        }

        .period-options {
            grid-template-columns: 1fr 1fr;
        }

        .report-info {
            grid-template-columns: 1fr;
        }

        .action-row {
            flex-direction: column;
        }

        .action-button {
            width: 100%;
        }
    }

</style>


<div class="reports-page">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="reports-hero">

        <h1>
            Growth Monitoring Reports
        </h1>

        <p>
            Generate structured reports for overall monitoring,
            individual child growth history, and nutrition or
            malnutrition screening.
        </p>

    </div>


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="error-box">

            <strong>
                Please correct the following:
            </strong>

            <ul style="margin:7px 0 0 17px;">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
         THREE REPORT TYPES
    ========================================================== --}}

    <div class="report-types">

        {{-- SYSTEM REPORT --}}

        <div class="report-card">

            <div class="report-card-icon">
                ▣
            </div>

            <h2>
                System Monitoring Report
            </h2>

            <p>
                Provides an overall picture of growth-monitoring
                activity across the system.
            </p>

            <ul class="report-features">

                <li>Children monitored</li>
                <li>Total assessments</li>
                <li>Normal and attention cases</li>
                <li>Children requiring attention</li>

            </ul>

            <button
                type="button"
                class="report-card-button"
                onclick="showReportSection('systemReport')"
            >
                Generate System Report
            </button>

        </div>


        {{-- CHILD REPORT --}}

        <div class="report-card">

            <div class="report-card-icon">
                ♙
            </div>

            <h2>
                Child Growth Report
            </h2>

            <p>
                Produces a detailed growth history and assessment
                report for one selected child.
            </p>

            <ul class="report-features">

                <li>Weight and height history</li>
                <li>MUAC and BMI history</li>
                <li>WHO growth assessments</li>
                <li>Monitoring recommendations</li>

            </ul>

            <button
                type="button"
                class="report-card-button"
                onclick="showReportSection('childReport')"
            >
                Generate Child Report
            </button>

        </div>


        {{-- NUTRITION REPORT --}}

        <div class="report-card">

            <div class="report-card-icon">
                !
            </div>

            <h2>
                Nutrition &amp; Malnutrition Report
            </h2>

            <p>
                Focuses on children whose growth assessments indicate
                nutritional attention or monitoring needs.
            </p>

            <ul class="report-features">

                <li>Nutrition status</li>
                <li>MUAC findings</li>
                <li>WHO growth indicators</li>
                <li>Children requiring attention</li>

            </ul>

            <button
                type="button"
                class="report-card-button"
                onclick="showReportSection('nutritionReport')"
            >
                Generate Nutrition Report
            </button>

        </div>

    </div>


    {{-- =========================================================
         SYSTEM REPORT
    ========================================================== --}}

    <section
        class="report-section"
        id="systemReport"
    >

        <div class="report-section-header">

            <div class="section-icon">
                ▣
            </div>

            <div>

                <h3>
                    System Monitoring Report
                </h3>

                <p>
                    Analyse monitoring activity across the selected period.
                </p>

            </div>

        </div>


        <div class="report-section-body">

            <form
                method="POST"
                action="{{ route('reports.generate') }}"
            >

                @csrf

                <div class="field-group">

                    <label>
                        Reporting Period
                    </label>

                    <div class="period-options">

                        @foreach([
                            '1_month' => '1 Month',
                            '2_months' => '2 Months',
                            '6_months' => '6 Months',
                            '1_year' => '1 Year',
                            '2_years' => '2 Years',
                            'custom' => 'Custom'
                        ] as $value => $label)

                            <div class="period-option">

                                <input
                                    type="radio"
                                    id="system_{{ $value }}"
                                    name="period"
                                    value="{{ $value }}"
                                    {{ $value === '6_months' ? 'checked' : '' }}
                                    onchange="toggleDates('system')"
                                >

                                <label for="system_{{ $value }}">
                                    {{ $label }}
                                </label>

                            </div>

                        @endforeach

                    </div>

                </div>


                <div
                    class="custom-dates"
                    id="systemDates"
                >

                    <div class="field-group">

                        <label for="system_start_date">
                            Start Date
                        </label>

                        <input
                            type="date"
                            name="start_date"
                            id="system_start_date"
                        >

                    </div>


                    <div class="field-group">

                        <label for="system_end_date">
                            End Date
                        </label>

                        <input
                            type="date"
                            name="end_date"
                            id="system_end_date"
                        >

                    </div>

                </div>


                <div class="action-row">

                    <button
                        type="button"
                        class="action-button secondary"
                        onclick="resetReportForms()"
                    >
                        Reset
                    </button>

                    <button
                        type="submit"
                        class="action-button primary"
                    >
                        Generate Report
                    </button>

                </div>

            </form>

        </div>

    </section>


    {{-- =========================================================
         CHILD REPORT
    ========================================================== --}}

    <section
        class="report-section"
        id="childReport"
        style="display:none;"
    >

        <div class="report-section-header">

            <div class="section-icon">
                ♙
            </div>

            <div>

                <h3>
                    Individual Child Growth Report
                </h3>

                <p>
                    Select a child and reporting period.
                </p>

            </div>

        </div>


        <div class="report-section-body">

            <form
                method="GET"
                id="childReportForm"
                action="{{ url('/reports/child') }}/__CHILD_ID__"
            >

                <div class="report-grid">

                    <div class="field-group">

                        <label for="child_id">
                            Select Child
                        </label>

                        <select
                            name="child_id"
                            id="child_id"
                            required
                        >

                            <option value="">
                                Select a child
                            </option>

                            @foreach($children as $child)

                                <option value="{{ $child->id }}">

                                    {{ $child->first_name }}
                                    {{ $child->last_name }}
                                    —
                                    {{ $child->child_id }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="field-group">

                        <label for="child_period">
                            Reporting Period
                        </label>

                        <select
                            name="period"
                            id="child_period"
                        >

                            <option value="1_month">
                                1 Month
                            </option>

                            <option value="2_months">
                                2 Months
                            </option>

                            <option value="6_months" selected>
                                6 Months
                            </option>

                            <option value="1_year">
                                1 Year
                            </option>

                            <option value="2_years">
                                2 Years
                            </option>

                            <option value="all">
                                All Available Records
                            </option>

                        </select>

                    </div>

                </div>


                <div class="action-row">

                    <button
                        type="button"
                        class="action-button secondary"
                        onclick="hideReportSections()"
                    >
                        Close
                    </button>

                    <button
                        type="submit"
                        class="action-button primary"
                    >
                        Generate Child Report
                    </button>

                </div>

            </form>

        </div>

    </section>


    {{-- =========================================================
         NUTRITION REPORT
    ========================================================== --}}

    <section
        class="report-section"
        id="nutritionReport"
        style="display:none;"
    >

        <div class="report-section-header">

            <div class="section-icon">
                !
            </div>

            <div>

                <h3>
                    Nutrition &amp; Malnutrition Report
                </h3>

                <p>
                    Review children with nutritional or growth-monitoring concerns.
                </p>

            </div>

        </div>


        <div class="report-section-body">

            <form
                method="GET"
                action="{{ route('reports.nutrition') }}"
            >

                <div class="field-group">

                    <label>
                        Reporting Period
                    </label>

                    <div class="period-options">

                        @foreach([
                            '1_month' => '1 Month',
                            '2_months' => '2 Months',
                            '6_months' => '6 Months',
                            '1_year' => '1 Year',
                            '2_years' => '2 Years',
                            'all' => 'All Records',
                            'custom' => 'Custom'
                        ] as $value => $label)

                            <div class="period-option">

                                <input
                                    type="radio"
                                    id="nutrition_{{ $value }}"
                                    name="period"
                                    value="{{ $value }}"
                                    {{ $value === '6_months' ? 'checked' : '' }}
                                    onchange="toggleDates('nutrition')"
                                >

                                <label for="nutrition_{{ $value }}">
                                    {{ $label }}
                                </label>

                            </div>

                        @endforeach

                    </div>

                </div>


                <div
                    class="custom-dates"
                    id="nutritionDates"
                >

                    <div class="field-group">

                        <label for="nutrition_start_date">
                            Start Date
                        </label>

                        <input
                            type="date"
                            name="start_date"
                            id="nutrition_start_date"
                        >

                    </div>


                    <div class="field-group">

                        <label for="nutrition_end_date">
                            End Date
                        </label>

                        <input
                            type="date"
                            name="end_date"
                            id="nutrition_end_date"
                        >

                    </div>

                </div>


                <div class="nutrition-note">

                    <strong>
                        Report focus:
                    </strong>

                    This report highlights recorded growth assessments
                    containing attention, severe, moderate, risk, wasting,
                    stunting, overweight, obesity or other nutritional
                    monitoring indicators. It is intended for screening
                    and monitoring, not diagnosis.

                </div>


                <div class="action-row">

                    <button
                        type="button"
                        class="action-button secondary"
                        onclick="hideReportSections()"
                    >
                        Close
                    </button>

                    <button
                        type="submit"
                        class="action-button primary"
                    >
                        Generate Nutrition Report
                    </button>

                </div>

            </form>

        </div>

    </section>


    {{-- =========================================================
         INFORMATION
    ========================================================== --}}

    <section class="report-section">

        <div class="report-section-header">

            <div class="section-icon">
                i
            </div>

            <div>

                <h3>
                    Report Types
                </h3>

                <p>
                    Use each report according to its monitoring purpose.
                </p>

            </div>

        </div>


        <div class="report-section-body">

            <div class="report-info">

                <div class="info-box">

                    <strong>
                        System Report
                    </strong>

                    <span>
                        Gives an overall view of monitoring activity.
                    </span>

                </div>


                <div class="info-box">

                    <strong>
                        Child Report
                    </strong>

                    <span>
                        Gives detailed growth and assessment history
                        for one child.
                    </span>

                </div>


                <div class="info-box">

                    <strong>
                        Nutrition Report
                    </strong>

                    <span>
                        Focuses on nutritional monitoring concerns
                        and attention cases.
                    </span>

                </div>


                <div class="info-box">

                    <strong>
                        PDF Reports
                    </strong>

                    <span>
                        Generated reports can be downloaded as
                        printable PDF documents.
                    </span>

                </div>

            </div>

        </div>

    </section>

</div>


<script>

    /*
    |--------------------------------------------------------------------------
    | HIDE ALL REPORT SECTIONS
    |--------------------------------------------------------------------------
    */

    function hideReportSections()
    {
        const system =
            document.getElementById('systemReport');

        const child =
            document.getElementById('childReport');

        const nutrition =
            document.getElementById('nutritionReport');


        if (system) {
            system.style.display = 'none';
        }

        if (child) {
            child.style.display = 'none';
        }

        if (nutrition) {
            nutrition.style.display = 'none';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW SELECTED REPORT SECTION
    |--------------------------------------------------------------------------
    */

    function showReportSection(sectionId)
    {
        hideReportSections();

        const section =
            document.getElementById(sectionId);

        if (!section) {
            return;
        }

        section.style.display = 'block';

        section.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });

        if (sectionId === 'systemReport') {
            toggleDates('system');
        }

        if (sectionId === 'nutritionReport') {
            toggleDates('nutrition');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SYSTEM / NUTRITION CUSTOM DATE TOGGLE
    |--------------------------------------------------------------------------
    */

    function toggleDates(type)
    {
        let radioId = '';
        let datesId = '';

        if (type === 'system') {

            radioId = 'system_custom';
            datesId = 'systemDates';

        } else if (type === 'nutrition') {

            radioId = 'nutrition_custom';
            datesId = 'nutritionDates';

        } else {

            return;
        }


        const customRadio =
            document.getElementById(radioId);

        const dates =
            document.getElementById(datesId);


        if (!customRadio || !dates) {
            return;
        }


        if (customRadio.checked) {

            dates.classList.add('show');

        } else {

            dates.classList.remove('show');

        }
    }


    /*
    |--------------------------------------------------------------------------
    | RESET SYSTEM REPORT FORM
    |--------------------------------------------------------------------------
    */

    function resetReportForms()
    {
        const systemForm =
            document.querySelector('#systemReport form');


        if (systemForm) {
            systemForm.reset();
        }


        const defaultRadio =
            document.getElementById('system_6_months');


        if (defaultRadio) {
            defaultRadio.checked = true;
        }


        toggleDates('system');
    }


    /*
    |--------------------------------------------------------------------------
    | CHILD REPORT FORM
    |--------------------------------------------------------------------------
    |
    | The child report route is:
    |
    | /reports/child/{child}
    |
    | The JavaScript inserts the selected child ID.
    |
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const childForm =
                document.getElementById('childReportForm');

            const childSelect =
                document.getElementById('child_id');

            const childPeriod =
                document.getElementById('child_period');


            if (!childForm || !childSelect || !childPeriod) {
                return;
            }


            childForm.addEventListener(
                'submit',
                function (event) {

                    const childId =
                        childSelect.value;

                    const period =
                        childPeriod.value;


                    if (!childId) {

                        event.preventDefault();

                        alert(
                            'Please select a child before generating the report.'
                        );

                        return;
                    }


                    /*
                    |----------------------------------------------------------
                    | Build the correct child report URL
                    |----------------------------------------------------------
                    */

                    childForm.action =
                        "{{ url('/reports/child') }}/" +
                        childId;


                    /*
                    |----------------------------------------------------------
                    | Make sure period is submitted
                    |----------------------------------------------------------
                    */

                    let periodInput =
                        childForm.querySelector(
                            'input[name="period"]'
                        );


                    if (!periodInput) {

                        periodInput =
                            document.createElement('input');

                        periodInput.type = 'hidden';
                        periodInput.name = 'period';

                        childForm.appendChild(periodInput);
                    }


                    periodInput.value = period;

                }
            );

        }
    );

</script>

@endsection