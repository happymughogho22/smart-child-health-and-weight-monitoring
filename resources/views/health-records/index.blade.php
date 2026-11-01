@extends('layouts.app')

@section('title', 'Health Records | Child Weight Monitoring System')

@section('page-title', 'Health Records')

@section('page-subtitle', 'Clinical visits and growth monitoring')

@section('content')

<style>
    .health-page {
        max-width: 1200px;
        margin: 0 auto;
    }

    .breadcrumb {
        margin-bottom: 18px;
        font-size: 12px;
        color: #64748b;
    }

    .breadcrumb a {
        color: #123b6d;
        text-decoration: none;
        font-weight: 600;
    }

    .child-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 22px 24px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .child-info h1 {
        margin: 0 0 6px;
        color: #123b6d;
        font-size: 23px;
        font-weight: 700;
    }

    .child-info p {
        margin: 0 0 10px;
        color: #64748b;
        font-size: 12px;
    }

    .child-id {
        display: inline-block;
        background: #eaf2fb;
        color: #123b6d;
        padding: 6px 10px;
        border-radius: 5px;
        font-size: 10px;
        font-weight: 700;
    }

    .page-actions {
        display: flex;
        gap: 9px;
        align-items: center;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 36px;
        padding: 8px 13px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 11px;
        font-weight: 700;
        border: 1px solid transparent;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-profile {
        background: #ffffff;
        color: #123b6d;
        border-color: #cbd5e1;
    }

    .btn-profile:hover {
        background: #f8fafc;
    }

    .btn-add {
        background: #123b6d;
        color: #ffffff;
    }

    .btn-add:hover {
        background: #0f315a;
    }

    .btn-pdf {
        background: #f8fafc;
        color: #475569;
        border-color: #cbd5e1;
    }

    .btn-pdf:hover {
        background: #f1f5f9;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }

    .summary-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        padding: 18px;
    }

    .summary-label {
        color: #64748b;
        font-size: 10px;
        font-weight: 700;
        margin-bottom: 9px;
    }

    .summary-value {
        color: #123b6d;
        font-size: 21px;
        font-weight: 700;
    }

    .summary-note {
        color: #94a3b8;
        font-size: 9px;
        margin-top: 5px;
    }

    .records-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
    }

    .records-header {
        padding: 18px 20px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .records-header h3 {
        margin: 0;
        color: #123b6d;
        font-size: 15px;
    }

    .record-count {
        color: #94a3b8;
        font-size: 10px;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .health-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
        min-width: 850px;
    }

    .health-table th {
        padding: 13px 15px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
        color: #64748b;
        font-size: 9px;
        font-weight: 700;
        white-space: nowrap;
    }

    .health-table td {
        padding: 14px 15px;
        border-bottom: 1px solid #f1f5f9;
        color: #475569;
        vertical-align: middle;
    }

    .health-table tbody tr:hover {
        background: #fafcff;
    }

    .date-text {
        color: #123b6d;
        font-weight: 700;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 8px;
        border-radius: 5px;
        background: #eaf2fb;
        color: #123b6d;
        font-size: 9px;
        font-weight: 700;
        white-space: nowrap;
    }

    .actions-cell {
        min-width: 175px;
    }

    .record-actions {
        display: flex;
        align-items: center;
        gap: 7px;
        flex-wrap: nowrap;
    }

    .view-btn,
    .edit-btn,
    .delete-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 32px;
        padding: 0 10px;
        border-radius: 5px;
        font-size: 10px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        box-sizing: border-box;
    }

    .view-btn {
        background: #eaf2fb;
        color: #123b6d;
    }

    .view-btn:hover {
        background: #dcebf9;
    }

    .edit-btn {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
    }

    .edit-btn:hover {
        background: #e2e8f0;
    }

    .delete-form {
        margin: 0;
        padding: 0;
    }

    .delete-btn {
        background: #fff1f2;
        color: #be123c;
        border: 1px solid #fecdd3;
        cursor: pointer;
    }

    .delete-btn:hover {
        background: #ffe4e6;
    }

    .empty-state {
        padding: 55px 20px;
        text-align: center;
    }

    .empty-icon {
        font-size: 34px;
        color: #94a3b8;
        margin-bottom: 14px;
    }

    .empty-state h3 {
        color: #475569;
        font-size: 14px;
        margin: 0 0 7px;
    }

    .empty-state p {
        color: #94a3b8;
        font-size: 11px;
        margin: 0 auto 20px;
        max-width: 430px;
        line-height: 1.6;
    }

    .notice {
        background: #eaf2fb;
        border: 1px solid #d4e4f7;
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
        color: #365574;
        font-size: 10px;
        line-height: 1.6;
    }

    .notice strong {
        display: block;
        color: #123b6d;
        margin-bottom: 5px;
    }

    @media (max-width: 900px) {
        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .child-card {
            align-items: flex-start;
            flex-direction: column;
        }

        .page-actions {
            justify-content: flex-start;
        }
    }

    @media (max-width: 600px) {
        .summary-grid {
            grid-template-columns: 1fr;
        }

        .page-actions {
            width: 100%;
        }

        .action-btn {
            flex: 1;
        }
    }

    /* Growth Monitoring Charts */

.charts-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    padding: 28px;
    margin-bottom: 28px;
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
}

.section-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.section-heading h2 {
    margin: 0;
    color: #111827;
    font-size: 22px;
    font-weight: 800;
}

.section-heading p {
    margin: 6px 0 0;
    color: #6b7280;
    font-size: 14px;
}

.charts-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 24px;
}

.chart-box {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 20px;
}

.chart-box:last-child {
    grid-column: span 2;
}

.chart-box h3 {
    margin: 0;
    color: #111827;
    font-size: 17px;
    font-weight: 750;
}

.chart-description {
    color: #6b7280;
    font-size: 13px;
    margin: 6px 0 18px;
}

.chart-container {
    position: relative;
    width: 100%;
    height: 280px;
}

.chart-container canvas {
    max-width: 100%;
}

@media (max-width: 850px) {
    .charts-grid {
        grid-template-columns: 1fr;
    }

    .chart-box:last-child {
        grid-column: span 1;
    }
}

@media (max-width: 600px) {
    .charts-card {
        padding: 18px;
    }

    .chart-box {
        padding: 14px;
    }

    .chart-container {
        height: 240px;
    }
}
</style>

<div class="health-page">

    <!-- BREADCRUMB -->

    <div class="breadcrumb">

        <a href="{{ route('dashboard') }}">
            Home
        </a>

        <span> / </span>

        <a href="{{ route('children.index') }}">
            Children
        </a>

        <span> / </span>

        <a href="{{ route('children.show', $child) }}">
            {{ $child->child_id }}
        </a>

        <span> / </span>

        <strong>Health Records</strong>

    </div>


    <!-- CHILD HEADER -->

    <section class="child-card">

        <div class="child-info">

            <h1>
                {{ $child->first_name }}
                {{ $child->last_name }}
            </h1>

            <p>
                Clinical visits and growth monitoring history
            </p>

            <span class="child-id">
                ID: {{ $child->child_id }}
            </span>

        </div>


        <div class="page-actions">

            <a
                href="{{ route('children.show', $child) }}"
                class="action-btn btn-profile"
            >
                Child Profile
            </a>


            <a
                href="{{ route('health-records.create', $child) }}"
                class="action-btn btn-add"
            >
                + Add Health Record
            </a>


            @if($healthRecords->count() > 0)

                <a
                    href="{{ route('health-records.report', $child) }}"
                    class="action-btn btn-pdf"
                >
                    Generate PDF
                </a>

            @endif

        </div>

    </section>


    <!-- SUMMARY -->

    <div class="summary-grid">

        <!-- TOTAL VISITS -->

        <div class="summary-card">

            <div class="summary-label">
                TOTAL VISITS
            </div>

            <div class="summary-value">
                {{ $healthRecords->count() }}
            </div>

            <div class="summary-note">
                Recorded clinical visits
            </div>

        </div>


        <!-- LATEST WEIGHT -->

        <div class="summary-card">

            <div class="summary-label">
                LATEST WEIGHT
            </div>

            <div class="summary-value">

                @if($healthRecords->last()?->weight !== null)

                    {{ $healthRecords->last()->weight }} kg

                @else

                    —

                @endif

            </div>

            <div class="summary-note">
                Most recent measurement
            </div>

        </div>


        <!-- LATEST HEIGHT -->

        <div class="summary-card">

            <div class="summary-label">
                LATEST HEIGHT
            </div>

            <div class="summary-value">

                @if($healthRecords->last()?->height !== null)

                    {{ $healthRecords->last()->height }} cm

                @else

                    —

                @endif

            </div>

            <div class="summary-note">
                Most recent measurement
            </div>

        </div>


        <!-- MONITORING STATUS -->

        <div class="summary-card">

            <div class="summary-label">
                MONITORING STATUS
            </div>

            <div class="summary-value" style="font-size:16px;">

                @if($healthRecords->last()?->monitoring_status)

                    {{ $healthRecords->last()->monitoring_status }}

                @elseif($healthRecords->last()?->nutritional_status)

                    {{ $healthRecords->last()->nutritional_status }}

                @elseif($healthRecords->last()?->nutrition_status)

                    {{ $healthRecords->last()->nutrition_status }}

                @else

                    —

                @endif

            </div>

            <div class="summary-note">
                Based on latest record
            </div>

        </div>

    </div>


    <!-- HEALTH RECORDS -->

    <div class="records-card">

        <div class="records-header">

            <h3>
                Clinical Visit History
            </h3>

            <span class="record-count">
                {{ $healthRecords->count() }}
                {{ $healthRecords->count() == 1 ? 'record' : 'records' }}
            </span>

        </div>
{{-- Growth Monitoring Charts --}}
@if($healthRecords->count() > 0)

    <section class="charts-card">

        <div class="section-heading">
            <div>
                <h2>Growth Monitoring</h2>
                <p>Track the child's growth changes across health visits.</p>
            </div>
        </div>

        <div class="charts-grid">

            {{-- Weight Chart --}}
            <div class="chart-box">
                <h3>Weight Progress</h3>
                <p class="chart-description">Weight measurement in kilograms.</p>

                <div class="chart-container">
                    <canvas id="weightChart"></canvas>
                </div>
            </div>

            {{-- Height Chart --}}
            <div class="chart-box">
                <h3>Height Progress</h3>
                <p class="chart-description">Height measurement in centimetres.</p>

                <div class="chart-container">
                    <canvas id="heightChart"></canvas>
                </div>
            </div>

            {{-- MUAC Chart --}}
            <div class="chart-box">
                <h3>MUAC Progress</h3>
                <p class="chart-description">Mid-upper arm circumference in centimetres.</p>

                <div class="chart-container">
                    <canvas id="muacChart"></canvas>
                </div>
            </div>

        </div>

    </section>

@endif

        @if($healthRecords->count() > 0)

            <div class="table-wrapper">

                <table class="health-table">

                    <thead>

                        <tr>

                            <th>VISIT DATE</th>

                            <th>FACILITY</th>

                            <th>WEIGHT</th>

                            <th>HEIGHT</th>

                            <th>MUAC</th>

                            <th>STATUS</th>

                            <th class="actions-cell">ACTIONS</th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($healthRecords as $record)

                            <tr>

                                <!-- DATE -->

                                <td>

                                    <span class="date-text">

                                        {{ \Carbon\Carbon::parse($record->visit_date)->format('d M Y') }}

                                    </span>

                                </td>


                                <!-- FACILITY -->

                                <td>

                                    {{ $record->facility_name ?? $record->facility ?? '—' }}

                                </td>


                                <!-- WEIGHT -->

                                <td>

                                    @if($record->weight !== null)

                                        {{ $record->weight }} kg

                                    @else

                                        —

                                    @endif

                                </td>


                                <!-- HEIGHT -->

                                <td>

                                    @if($record->height !== null)

                                        {{ $record->height }} cm

                                    @else

                                        —

                                    @endif

                                </td>


                                <!-- MUAC -->

                                <td>

                                    @if($record->muac !== null)

                                        {{ $record->muac }} cm

                                    @else

                                        —

                                    @endif

                                </td>


                                <!-- STATUS -->

                                <td>

                                    @if($record->monitoring_status)

                                        <span class="status-badge">
                                            {{ $record->monitoring_status }}
                                        </span>

                                    @elseif($record->nutrition_status)

                                        <span class="status-badge">
                                            {{ $record->nutrition_status }}
                                        </span>

                                    @elseif($record->nutritional_status)

                                        <span class="status-badge">
                                            {{ $record->nutritional_status }}
                                        </span>

                                    @else

                                        —

                                    @endif

                                </td>


                                <!-- ACTIONS -->

                                <td class="actions-cell">

                                    <div class="record-actions">

                                        <!-- VIEW -->

                                        <a
                                            href="{{ route('health-records.show', [$child, $record]) }}"
                                            class="view-btn"
                                        >
                                            View
                                        </a>


                                        <!-- EDIT -->

                                        <a
                                            href="{{ route('health-records.edit', [$child, $record]) }}"
                                            class="edit-btn"
                                        >
                                            Edit
                                        </a>


                                        <!-- DELETE -->

                                        <form
                                            action="{{ route('health-records.destroy', [$child, $record]) }}"
                                            method="POST"
                                            class="delete-form"
                                            onsubmit="return confirm('Are you sure you want to delete this health record?');"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="delete-btn"
                                            >
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <!-- EMPTY STATE -->

            <div class="empty-state">

                <div class="empty-icon">
                    ◉
                </div>

                <h3>
                    No health records yet
                </h3>

                <p>
                    Add the child's first clinical visit to begin
                    monitoring growth, nutrition and health progress.
                </p>

                <a
                    href="{{ route('health-records.create', $child) }}"
                    class="action-btn btn-add"
                >
                    + Add First Health Record
                </a>

            </div>

        @endif

    </div>


    <!-- INFORMATION NOTICE -->

    <div class="notice">

        <strong>
            Health Monitoring
        </strong>

        Clinical visit information recorded for this child contributes
        to growth monitoring, nutrition assessment, recommendations
        and future health reporting.

    </div>

</div>

{{-- Chart.js Library --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const visitLabels = @json(
        $healthRecords->map(function ($record) {
            return \Carbon\Carbon::parse($record->visit_date)->format('d M Y');
        })
    );

    const weightData = @json(
        $healthRecords->map(function ($record) {
            return $record->weight;
        })
    );

    const heightData = @json(
        $healthRecords->map(function ($record) {
            return $record->height;
        })
    );

    const muacData = @json(
        $healthRecords->map(function ($record) {
            return $record->muac;
        })
    );

    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,

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
            y: {
                beginAtZero: false,
                ticks: {
                    precision: 2
                }
            }
        }
    };

    // Weight Chart
    const weightCanvas = document.getElementById('weightChart');

    if (weightCanvas) {
        new Chart(weightCanvas, {
            type: 'line',

            data: {
                labels: visitLabels,

                datasets: [{
                    label: 'Weight (kg)',
                    data: weightData,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.12)',
                    borderWidth: 3,
                    tension: 0.35,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },

            options: chartOptions
        });
    }

    // Height Chart
    const heightCanvas = document.getElementById('heightChart');

    if (heightCanvas) {
        new Chart(heightCanvas, {
            type: 'line',

            data: {
                labels: visitLabels,

                datasets: [{
                    label: 'Height (cm)',
                    data: heightData,
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22, 163, 74, 0.12)',
                    borderWidth: 3,
                    tension: 0.35,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },

            options: chartOptions
        });
    }

    // MUAC Chart
    const muacCanvas = document.getElementById('muacChart');

    if (muacCanvas) {
        new Chart(muacCanvas, {
            type: 'line',

            data: {
                labels: visitLabels,

                datasets: [{
                    label: 'MUAC (cm)',
                    data: muacData,
                    borderColor: '#ea580c',
                    backgroundColor: 'rgba(234, 88, 12, 0.12)',
                    borderWidth: 3,
                    tension: 0.35,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },

            options: chartOptions
        });
    }
</script>

@endsection