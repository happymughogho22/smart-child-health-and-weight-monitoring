
@extends('layouts.app')

@section('title', 'Children')
@section('page-title', 'Children')
@section('page-subtitle', 'Manage registered children and monitor their growth')

@push('styles')
<style>
/* =========================================================
   CHILDREN / GROWTH MONITORING PAGE
========================================================= */

.gm-page {
    width: 100%;
    max-width: 100%;
    min-width: 0;
    margin: 0 auto;
    font-family: "Segoe UI", Arial, sans-serif;
    color: #1e293b;
    box-sizing: border-box;
}

.gm-page *,
.gm-page *::before,
.gm-page *::after {
    box-sizing: border-box;
}

/* PAGE HEADER */

.gm-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 22px;
    width: 100%;
}

.gm-header-left {
    flex: 1;
    min-width: 0;
}

.gm-header-left h1 {
    font-size: 23px;
    line-height: 1.3;
    font-weight: 700;
    color: #123b6d;
    margin: 0 0 6px;
}

.gm-header-left p {
    color: #64748b;
    font-size: 12px;
    margin: 0;
    line-height: 1.6;
}

.gm-header-actions {
    display: flex;
    gap: 8px;
    align-items: center;
    flex-wrap: wrap;
    flex-shrink: 0;
}

.gm-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 9px 13px;
    min-height: 35px;
    border-radius: 6px;
    border: 1px solid transparent;
    text-decoration: none !important;
    font-family: inherit;
    font-size: 11px;
    font-weight: 650;
    line-height: 1.4;
    cursor: pointer;
    transition: all .2s ease;
    white-space: nowrap;
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
}

.gm-btn-light {
    background: #fff;
    color: #475569 !important;
    border-color: #e2e8f0;
}

.gm-btn-light:hover {
    background: #f8fafc;
    color: #123b6d !important;
    border-color: #cbd5e1;
}

/* SUMMARY CARDS */

.gm-stat-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 22px;
    width: 100%;
}

.gm-stat-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 16px;
    position: relative;
    min-width: 0;
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
    gap: 8px;
}

.gm-stat-label {
    color: #64748b;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .3px;
}

.gm-stat-icon {
    width: 33px;
    height: 33px;
    flex-shrink: 0;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
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
    margin-top: 12px;
    font-size: 26px;
    line-height: 1.2;
    font-weight: 750;
    color: #1e293b;
}

.gm-stat-description {
    margin-top: 7px;
    color: #94a3b8;
    font-size: 10px;
    line-height: 1.5;
}

/* MAIN CONTENT */

.gm-main-grid {
    display: block;
    width: 100%;
    min-width: 0;
}

.gm-panel {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    width: 100%;
    min-width: 0;
}

.gm-panel-header {
    padding: 15px 17px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
}

.gm-panel-title {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    min-width: 0;
}

.gm-panel-title-icon {
    width: 33px;
    height: 33px;
    flex-shrink: 0;
    border-radius: 8px;
    background: #eaf2fb;
    color: #123b6d;
    display: flex;
    align-items: center;
    justify-content: center;
}

.gm-panel-title h3 {
    font-size: 12px;
    line-height: 1.4;
    color: #123b6d;
    font-weight: 700;
    margin: 0;
}

.gm-panel-title p {
    color: #94a3b8;
    font-size: 10px;
    line-height: 1.5;
    margin: 4px 0 0;
}

/* SEARCH FORM */

.gm-search {
    display: flex !important;
    align-items: center;
    gap: 5px;
    width: 360px;
    max-width: 100%;
    flex-shrink: 0;
    margin: 0;
}

.gm-search-input-wrap {
    position: relative;
    flex: 1;
    min-width: 0;
}

.gm-search input {
    display: block;
    width: 100%;
    height: 33px;
    box-sizing: border-box;
    border: 1px solid #d5deea;
    border-radius: 6px;
    padding: 0 9px 0 28px;
    outline: none;
    background: #fff;
    font-family: inherit;
    font-size: 11px;
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
    left: 9px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    pointer-events: none;
    font-size: 12px;
}

.gm-search-btn,
.gm-clear-btn {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    height: 33px;
    min-height: 33px;
    padding: 0 9px;
    border-radius: 6px;
    font-family: inherit;
    font-size: 10px;
    font-weight: 600;
    line-height: 1;
    flex-shrink: 0;
    white-space: nowrap;
    text-decoration: none !important;
}

.gm-search-btn {
    min-width: 54px;
}

.gm-clear-btn {
    min-width: 43px;
    background: #fff8e6;
    border: 1px solid #ead7a2;
    color: #795b12 !important;
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
    min-width: 760px;
    border-collapse: collapse;
}

.gm-table th {
    text-align: left;
    padding: 13px 14px;
    background: #f5f8fc;
    color: #64748b;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .35px;
    white-space: nowrap;
}

.gm-table td {
    padding: 13px 14px;
    border-top: 1px solid #edf1f6;
    color: #334155;
    font-size: 11px;
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
    gap: 9px;
}

.gm-child-avatar {
    width: 34px;
    height: 34px;
    flex-shrink: 0;
    border-radius: 8px;
    background: #eaf2fb;
    color: #123b6d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 750;
    font-size: 11px;
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
    gap: 5px;
    padding: 5px 8px;
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

/* PROFILE BUTTON */

.gm-row-actions {
    display: flex;
    align-items: center;
    gap: 7px;
}

.gm-view-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 7px 10px;
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
    padding: 45px 20px;
}

.gm-empty-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    margin: 0 auto 12px;
    background: #f1f5f9;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}

.gm-empty h4 {
    font-size: 14px;
    color: #475569;
    margin: 0;
}

.gm-empty p {
    margin-top: 7px;
    color: #94a3b8;
    font-size: 11px;
    line-height: 1.6;
}

/* PAGINATION */

.gm-pagination {
    padding: 14px 17px;
    border-top: 1px solid #f1f5f9;
}

/* RESPONSIVE LAYOUT */

@media (max-width: 1100px) {
    .gm-stat-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .gm-panel-header {
        flex-direction: column;
        align-items: stretch;
    }

    .gm-search {
        width: 100%;
        max-width: 100%;
    }
}

@media (max-width: 700px) {
    .gm-header {
        flex-direction: column;
        align-items: stretch;
    }

    .gm-header-actions {
        width: 100%;
    }

    .gm-header-actions .gm-btn {
        flex: 1;
    }

    .gm-panel-header {
        padding: 14px;
    }

    .gm-search {
        width: 100%;
        flex-wrap: wrap;
    }

    .gm-search-input-wrap {
        flex-basis: 100%;
    }

    .gm-search-btn,
    .gm-clear-btn {
        flex: 0 0 auto;
    }

    .gm-table th,
    .gm-table td {
        padding: 11px 12px;
    }
}

@media (max-width: 480px) {
    .gm-stat-grid {
        grid-template-columns: 1fr;
    }

    .gm-search {
        display: grid !important;
        grid-template-columns: minmax(0, 1fr) auto auto;
        gap: 5px;
    }

    .gm-search-input-wrap {
        grid-column: 1 / -1;
    }

    .gm-search-btn,
    .gm-clear-btn {
        width: auto;
    }
}
</style>
@endpush

@section('content')
<div class="gm-page">

    {{-- PAGE HEADER --}}
    <div class="gm-header">
        <div class="gm-header-left">
            <h1>Growth Monitoring</h1>
            <p>
                Monitor children's growth measurements and identify
                potential nutritional concerns.
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

    {{-- CHILDREN TABLE --}}
    <div class="gm-main-grid">
        <section class="gm-panel">

            <div class="gm-panel-header">
                <div class="gm-panel-title">
                    <div class="gm-panel-title-icon">👥</div>
                    <div>
                        <h3>Children Growth Monitoring</h3>
                        <p>
                            Select a child to view their profile or growth charts.
                        </p>
                    </div>
                </div>

                {{-- SEARCH FORM --}}
                <form
                    method="GET"
                    action="{{ route('children.index') }}"
                    class="gm-search"
                    role="search"
                >
                    <div class="gm-search-input-wrap">
                        <span class="gm-search-icon" aria-hidden="true">⌕</span>

                        <input
                            type="search"
                            name="search"
                            value="{{ request('search', '') }}"
                            placeholder="Search by child name or ID..."
                            aria-label="Search by child name or ID"
                        >
                    </div>

                    <button type="submit" class="gm-btn gm-btn-primary gm-search-btn">
                        Search
                    </button>

                    <a
                        href="{{ route('children.index') }}"
                        class="gm-btn gm-clear-btn"
                    >
                        Clear
                    </a>
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
                                {{-- CHILD --}}
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

                                {{-- AGE --}}
                                <td>
                                    @if($age !== null)
                                        {{ $age }}
                                        {{ $age === 1 ? 'year' : 'years' }}
                                    @else
                                        —
                                    @endif
                                </td>

                                {{-- SEX --}}
                                <td>{{ $child->sex ?? '—' }}</td>

                                {{-- WEIGHT --}}
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

                                {{-- HEIGHT --}}
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

                                {{-- BMI --}}
                                <td>
                                    @if($latestRecord && $latestRecord->bmi !== null)
                                        {{ number_format((float) $latestRecord->bmi, 1) }}
                                    @else
                                        —
                                    @endif
                                </td>

                                {{-- ASSESSMENT --}}
                                <td>
                                    <span class="gm-status {{ $statusClass }}">
                                        <span class="gm-status-dot"></span>
                                        {{ $statusText }}
                                    </span>
                                </td>

                                {{-- PROFILE ACTION --}}
                                <td>
                                    <div class="gm-row-actions">
                                        <a
                                            href="{{ route('children.show', $child) }}"
                                            class="gm-view-btn"
                                        >
                                            View Profile →
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
                    <div class="gm-empty-icon">👶</div>

                    <h4>No children found</h4>

                    @if(trim((string) ($search ?? request('search', ''))) !== '')
                        <p>
                            No registered children match
                            "{{ $search ?? request('search', '') }}".
                            Check the spelling or try searching by child ID.
                        </p>

                        <a
                            href="{{ route('children.index') }}"
                            class="gm-btn gm-clear-btn"
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