
@extends('layouts.app')

@section('title', 'Growth Charts')
@section('page-title', 'Growth Charts')
@section('page-subtitle', 'Monitor children’s growth measurements and nutritional progress')

@push('styles')
<style>
/* =========================================================
   GROWTH CHARTS PAGE
========================================================= */

.gm-page {
    width: 100%;
    min-width: 0;
    font-family: "Segoe UI", Arial, sans-serif;
    color: #1e293b;
}

.gm-page * {
    box-sizing: border-box;
}

/* HEADER */

.gm-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 25px;
}

.gm-header-left h1 {
    font-size: 24px;
    font-weight: 700;
    color: #123b6d;
    margin: 0 0 6px;
}

.gm-header-left p {
    color: #64748b;
    font-size: 13px;
    margin: 0;
    line-height: 1.6;
}

.gm-header-actions {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

.gm-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 11px 17px;
    min-height: 38px;
    border-radius: 7px;
    border: 1px solid transparent;
    text-decoration: none !important;
    font-family: inherit;
    font-size: 12px;
    font-weight: 650;
    line-height: 1.4;
    cursor: pointer;
    white-space: nowrap;
    transition: all .2s ease;
}

.gm-btn-primary {
    background: #123b6d;
    border-color: #123b6d;
    color: #fff !important;
}

.gm-btn-primary:hover {
    background: #0c2d52;
    border-color: #0c2d52;
    color: #fff !important;
    transform: translateY(-1px);
}

.gm-btn-light {
    background: #fff;
    color: #475569 !important;
    border-color: #e2e8f0;
}

.gm-btn-light:hover {
    background: #f8fafc;
    color: #123b6d !important;
}

/* SUMMARY CARDS */

.gm-stat-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.gm-stat-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 11px;
    padding: 19px;
    position: relative;
    overflow: hidden;
    transition: .2s ease;
}

.gm-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(15, 23, 42, .06);
}

.gm-stat-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.gm-stat-label {
    color: #64748b;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .3px;
}

.gm-stat-icon {
    width: 35px;
    height: 35px;
    flex-shrink: 0;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.gm-icon-blue {
    background: #eaf2fb;
    color: #123b6d;
}

.gm-icon-green {
    background: #ecfdf3;
    color: #16803c;
}

.gm-icon-orange {
    background: #fff7ed;
    color: #b45309;
}

.gm-icon-purple {
    background: #f5f3ff;
    color: #6d28d9;
}

.gm-stat-number {
    margin-top: 13px;
    font-size: 27px;
    line-height: 1.2;
    font-weight: 750;
    color: #1e293b;
}

.gm-stat-description {
    margin-top: 8px;
    color: #94a3b8;
    font-size: 10px;
}

/* MAIN PANEL */

.gm-main-grid {
    display: block;
    width: 100%;
}

.gm-main-grid > .gm-panel {
    width: 100%;
    min-width: 0;
}

.gm-panel {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 11px;
    overflow: hidden;
}

.gm-panel-header {
    padding: 17px 20px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
}

.gm-panel-title {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}

.gm-panel-title-icon {
    width: 34px;
    height: 34px;
    flex-shrink: 0;
    border-radius: 8px;
    background: #eaf2fb;
    color: #123b6d;
    display: flex;
    align-items: center;
    justify-content: center;
}

.gm-panel-title h3 {
    font-size: 13px;
    color: #123b6d;
    font-weight: 700;
    margin: 0;
}

.gm-panel-title p {
    color: #94a3b8;
    font-size: 10px;
    margin: 4px 0 0;
}

/* SEARCH */

.gm-search {
    display: flex;
    align-items: stretch;
    gap: 8px;
    width: 390px;
    max-width: 100%;
    flex-shrink: 0;
}

.gm-search-input-wrap {
    position: relative;
    flex: 1;
    min-width: 0;
}

.gm-search input {
    display: block;
    width: 100%;
    height: 38px;
    border: 1px solid #d5deea;
    border-radius: 7px;
    padding: 0 12px 0 34px;
    outline: none;
    background: #fff;
    font-family: inherit;
    font-size: 12px;
    color: #334155;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.gm-search input::placeholder {
    color: #94a3b8;
}

.gm-search input:focus {
    border-color: #123b6d;
    box-shadow: 0 0 0 3px rgba(18, 59, 109, .08);
}

.gm-search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    pointer-events: none;
    font-size: 13px;
}

.gm-search-btn,
.gm-clear-btn {
    min-height: 38px;
    padding: 0 14px;
    flex-shrink: 0;
}

.gm-clear-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #fff8e6;
    border: 1px solid #ead7a2;
    border-radius: 7px;
    color: #795b12 !important;
    text-decoration: none !important;
    font-size: 12px;
    font-weight: 650;
}

.gm-clear-btn:hover {
    background: #f8edca;
    border-color: #d4a72c;
}

/* TABLE */

.gm-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.gm-table {
    width: 100%;
    border-collapse: collapse;
}

.gm-table th {
    text-align: left;
    padding: 14px 16px;
    background: #f5f8fc;
    color: #64748b;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
    white-space: nowrap;
}

.gm-table td {
    padding: 15px 16px;
    border-top: 1px solid #edf1f6;
    color: #334155;
    font-size: 12px;
    white-space: nowrap;
    vertical-align: middle;
}

.gm-table tbody tr:hover td {
    background: #fafcff;
}

/* CHILD DETAILS */

.gm-child {
    display: flex;
    align-items: center;
    gap: 10px;
}

.gm-child-avatar {
    width: 36px;
    height: 36px;
    flex-shrink: 0;
    border-radius: 9px;
    background: #eaf2fb;
    color: #123b6d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 750;
    font-size: 12px;
}

.gm-child-name {
    font-weight: 650;
    color: #1e293b;
}

.gm-child-id {
    color: #94a3b8;
    font-size: 10px;
    margin-top: 3px;
}

/* ASSESSMENT STATUS */

.gm-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
}

.gm-status-dot {
    width: 6px;
    height: 6px;
    flex-shrink: 0;
    border-radius: 50%;
}

.gm-status-normal {
    color: #16803c;
    background: #ecfdf3;
}

.gm-status-normal .gm-status-dot {
    background: #16803c;
}

.gm-status-warning {
    color: #b45309;
    background: #fff7ed;
}

.gm-status-warning .gm-status-dot {
    background: #b45309;
}

.gm-status-danger {
    color: #b91c1c;
    background: #fef2f2;
}

.gm-status-danger .gm-status-dot {
    background: #b91c1c;
}

.gm-status-neutral {
    color: #64748b;
    background: #f1f5f9;
}

.gm-status-neutral .gm-status-dot {
    background: #94a3b8;
}

/* VIEW CHARTS BUTTON */

.gm-row-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.gm-view-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 8px 11px;
    border-radius: 6px;
    color: #123b6d !important;
    background: #eaf2fb;
    border: 1px solid transparent;
    text-decoration: none !important;
    font-size: 10px;
    font-weight: 700;
    transition: .2s ease;
}

.gm-view-btn:hover {
    background: #123b6d;
    color: #fff !important;
}

/* EMPTY STATE */

.gm-empty {
    text-align: center;
    padding: 55px 20px;
}

.gm-empty-icon {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    margin: 0 auto 12px;
    background: #f1f5f9;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 23px;
}

.gm-empty h4 {
    font-size: 14px;
    color: #475569;
    margin: 0;
}

.gm-empty p {
    margin: 6px 0 15px;
    color: #94a3b8;
    font-size: 11px;
}

/* PAGINATION */

.gm-pagination {
    padding: 15px 20px;
    border-top: 1px solid #f1f5f9;
}

/* RESPONSIVE */

@media (max-width: 1100px) {
    .gm-stat-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 700px) {
    .gm-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .gm-header-actions {
        width: 100%;
    }

    .gm-panel-header {
        flex-direction: column;
        align-items: stretch;
    }

    .gm-search {
        width: 100%;
    }

    .gm-table th,
    .gm-table td {
        padding: 12px;
    }
}

@media (max-width: 480px) {
    .gm-stat-grid {
        grid-template-columns: 1fr;
    }

    .gm-search {
        flex-wrap: wrap;
    }

    .gm-search-input-wrap {
        flex-basis: 100%;
    }

    .gm-search-btn,
    .gm-clear-btn {
        flex: 1;
    }

    .gm-header-actions .gm-btn {
        flex: 1;
    }
}
</style>
@endpush

@section('content')
<div class="gm-page">

    {{-- PAGE HEADER --}}
    <div class="gm-header">
        <div class="gm-header-left">
            <h1>Growth Charts & Monitoring</h1>
            <p>
                Monitor children's growth measurements, review growth charts
                and identify potential nutritional concerns.
            </p>
        </div>

        <div class="gm-header-actions">
            <a href="{{ route('children.index') }}" class="gm-btn gm-btn-light">
                ← Children
            </a>

            <a href="{{ route('children.create') }}" class="gm-btn gm-btn-primary">
                + Register Child
            </a>
        </div>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="gm-stat-grid">

        <div class="gm-stat-card">
            <div class="gm-stat-top">
                <div class="gm-stat-label">Total Children</div>
                <div class="gm-stat-icon gm-icon-blue">👶</div>
            </div>
            <div class="gm-stat-number">
                {{ number_format($totalChildren ?? 0) }}
            </div>
            <div class="gm-stat-description">
                Children registered in the system
            </div>
        </div>

        <div class="gm-stat-card">
            <div class="gm-stat-top">
                <div class="gm-stat-label">Children Monitored</div>
                <div class="gm-stat-icon gm-icon-green">📈</div>
            </div>
            <div class="gm-stat-number">
                {{ number_format($monitoredChildren ?? 0) }}
            </div>
            <div class="gm-stat-description">
                Children with health records
            </div>
        </div>

        <div class="gm-stat-card">
            <div class="gm-stat-top">
                <div class="gm-stat-label">Requiring Attention</div>
                <div class="gm-stat-icon gm-icon-orange">⚠</div>
            </div>
            <div class="gm-stat-number">
                {{ number_format(is_numeric($attentionChildren ?? null) ? $attentionChildren : ($childrenRequiringAttention ?? 0)) }}
            </div>
            <div class="gm-stat-description">
                Children requiring follow-up
            </div>
        </div>

        <div class="gm-stat-card">
            <div class="gm-stat-top">
                <div class="gm-stat-label">Growth Assessments</div>
                <div class="gm-stat-icon gm-icon-purple">🩺</div>
            </div>
            <div class="gm-stat-number">
                {{ number_format($totalGrowthAssessments ?? 0) }}
            </div>
            <div class="gm-stat-description">
                Recorded growth assessments
            </div>
        </div>

    </div>

    {{-- CHILDREN GROWTH CHARTS TABLE --}}
    <div class="gm-main-grid">
        <section class="gm-panel">

            <div class="gm-panel-header">

                <div class="gm-panel-title">
                    <div class="gm-panel-title-icon">📈</div>
                    <div>
                        <h3>Children Growth Overview</h3>
                        <p>Select a child to view their individual growth charts.</p>
                    </div>
                </div>

                {{-- SEARCH --}}
                <form
                    method="GET"
                    action="{{ route('growth-charts.index') }}"
                    class="gm-search"
                    role="search"
                >
                    <div class="gm-search-input-wrap">
                        <span class="gm-search-icon" aria-hidden="true">⌕</span>

                        <input
                            type="search"
                            name="search"
                            value="{{ $search ?? request('search', '') }}"
                            placeholder="Search by child name or ID..."
                            aria-label="Search by child name or ID"
                        >
                    </div>

                    <button type="submit" class="gm-btn gm-btn-primary gm-search-btn">
                        Search
                    </button>

                    @if(request()->filled('search'))
                        <a
                            href="{{ route('growth-charts.index') }}"
                            class="gm-btn gm-clear-btn"
                        >
                            Clear
                        </a>
                    @endif
                </form>

            </div>

            @if($children->count())

                <div class="gm-table-wrapper">
                    <table class="gm-table">
                        <thead>
                            <tr>
                                <th>Child</th>
                                <th>Age</th>
                                <th>Sex</th>
                                <th>Weight</th>
                                <th>Height</th>
                                <th>BMI</th>
                                <th>Assessment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                        @foreach($children as $child)

                            @php
                                $latestRecord = $child->healthRecords
                                    ->sortByDesc(function ($record) {
                                        return $record->visit_date;
                                    })
                                    ->first();

                                $assessment = $latestRecord
                                    ? $latestRecord->growthAssessment
                                    : null;

                                $childName = trim(
                                    ($child->first_name ?? '') . ' ' .
                                    ($child->last_name ?? '')
                                );

                                $initials = strtoupper(
                                    substr($child->first_name ?? '', 0, 1) .
                                    (
                                        $child->last_name
                                            ? substr($child->last_name, 0, 1)
                                            : ''
                                    )
                                );

                                $age = null;

                                if ($child->date_of_birth) {
                                    try {
                                        $age = \Carbon\Carbon::parse(
                                            $child->date_of_birth
                                        )->age;
                                    } catch (\Throwable $e) {
                                        $age = null;
                                    }
                                }

                                $assessmentStatus = $assessment->status
                                    ?? $assessment->overall_status
                                    ?? $assessment->risk_level
                                    ?? null;

                                $statusText = $assessmentStatus
                                    ? ucfirst(str_replace('_', ' ', $assessmentStatus))
                                    : 'Not assessed';

                                $statusClass = 'gm-status-neutral';

                                if ($assessmentStatus) {
                                    $lowerStatus = strtolower((string) $assessmentStatus);

                                    if (in_array($lowerStatus, [
                                        'normal',
                                        'healthy',
                                        'normal growth',
                                    ], true)) {
                                        $statusClass = 'gm-status-normal';
                                    } elseif (in_array($lowerStatus, [
                                        'attention',
                                        'at risk',
                                        'at_risk',
                                        'moderate',
                                        'high',
                                    ], true)) {
                                        $statusClass = 'gm-status-warning';
                                    } elseif (in_array($lowerStatus, [
                                        'danger',
                                        'severe',
                                        'malnutrition',
                                        'critical',
                                    ], true)) {
                                        $statusClass = 'gm-status-danger';
                                    }
                                }
                            @endphp

                            <tr>
                                <td>
                                    <div class="gm-child">
                                        <div class="gm-child-avatar">
                                            {{ $initials ?: '?' }}
                                        </div>

                                        <div>
                                            <div class="gm-child-name">
                                                {{ $childName ?: 'Unnamed Child' }}
                                            </div>

                                            <div class="gm-child-id">
                                                {{ $child->child_id ?? 'No ID' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    @if($age !== null)
                                        {{ $age }} {{ $age === 1 ? 'year' : 'years' }}
                                    @else
                                        —
                                    @endif
                                </td>

                                <td>{{ $child->sex ?? '—' }}</td>

                                <td>
                                    @if($latestRecord && $latestRecord->weight !== null)
                                        <strong>
                                            {{ number_format((float) $latestRecord->weight, 1) }}
                                        </strong>
                                        <span style="color:#94a3b8;">kg</span>
                                    @else
                                        —
                                    @endif
                                </td>

                                <td>
                                    @if($latestRecord && $latestRecord->height !== null)
                                        <strong>
                                            {{ number_format((float) $latestRecord->height, 1) }}
                                        </strong>
                                        <span style="color:#94a3b8;">cm</span>
                                    @else
                                        —
                                    @endif
                                </td>

                                <td>
                                    @if($latestRecord && $latestRecord->bmi !== null)
                                        {{ number_format((float) $latestRecord->bmi, 1) }}
                                    @else
                                        —
                                    @endif
                                </td>

                                <td>
                                    <span class="gm-status {{ $statusClass }}">
                                        <span class="gm-status-dot"></span>
                                        {{ $statusText }}
                                    </span>
                                </td>

                                <td>
                                    <div class="gm-row-actions">
                                        <a
                                            href="{{ route('children.growth-charts', $child) }}"
                                            class="gm-view-btn"
                                        >
                                            View Charts →
                                        </a>
                                    </div>
                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                @if($children->hasPages())
                    <div class="gm-pagination">
                        {{ $children->withQueryString()->links() }}
                    </div>
                @endif

            @else

                {{-- EMPTY STATE --}}
                <div class="gm-empty">
                    <div class="gm-empty-icon">📈</div>

                    <h4>No children found</h4>

                    @if(trim((string) ($search ?? request('search', ''))) !== '')

                        <p>
                            No registered children match
                            "{{ $search ?? request('search', '') }}".
                            Try another name or child ID.
                        </p>

                        <a
                            href="{{ route('growth-charts.index') }}"
                            class="gm-btn gm-btn-light"
                        >
                            Clear Search
                        </a>

                    @else

                        <p>
                            No children have been registered yet.
                            Register a child to begin monitoring growth.
                        </p>

                        <a
                            href="{{ route('children.create') }}"
                            class="gm-btn gm-btn-primary"
                        >
                            + Register Child
                        </a>

                    @endif
                </div>

            @endif

        </section>
    </div>

</div>
@endsection
