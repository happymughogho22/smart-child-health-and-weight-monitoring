
@extends('layouts.app')

@section('title', 'Growth Monitoring')
@section('page-title', 'Growth Monitoring')
@section('page-subtitle', 'Monitor child growth, measurements and nutritional progress')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | SUMMARY DATA
    |--------------------------------------------------------------------------
    */

    $totalChildrenCount = (int) ($totalChildren ?? 0);

    $childrenWithRecordsCount = (int) ($monitoredChildren ?? 0);

    $totalGrowthAssessmentsCount = (int) ($totalGrowthAssessments ?? 0);

    $childrenRequiringAttentionCount = (int) ($attentionChildren ?? 0);

    /*
    |--------------------------------------------------------------------------
    | PRESERVE THE ATTENTION COLLECTION
    |--------------------------------------------------------------------------
    */

    $attentionChildrenList = $childrenRequiringAttention ?? collect();

    if (!($attentionChildrenList instanceof \Illuminate\Support\Collection)) {
        $attentionChildrenList = collect($attentionChildrenList);
    }

    /*
    |--------------------------------------------------------------------------
    | MONITORING COVERAGE
    |--------------------------------------------------------------------------
    */

    $coverage = $totalChildrenCount > 0
        ? round(($childrenWithRecordsCount / $totalChildrenCount) * 100)
        : 0;

    $coverage = max(0, min(100, $coverage));

    $notMonitoredCount = max(
        0,
        $totalChildrenCount - $childrenWithRecordsCount
    );
@endphp

<style>
    .growth-page {
        width: 100%;
        min-width: 0;
    }

    .growth-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 25px;
        gap: 20px;
    }

    .growth-header-left h1 {
        font-size: 24px;
        font-weight: 750;
        color: #123b6d;
        margin-bottom: 6px;
    }

    .growth-header-left p {
        color: #64748b;
        font-size: 12px;
        line-height: 1.6;
    }

    .growth-header-actions {
        display: flex;
        gap: 9px;
        flex-wrap: wrap;
    }

    .growth-action {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 14px;
        border-radius: 7px;
        text-decoration: none;
        font-size: 11px;
        font-weight: 650;
        transition: 0.2s;
    }

    .growth-action-primary {
        background: #123b6d;
        color: white;
    }

    .growth-action-primary:hover {
        background: #0c2d52;
        color: white;
    }

    .growth-action-secondary {
        background: white;
        border: 1px solid #dce3eb;
        color: #475569;
    }

    .growth-action-secondary:hover {
        background: #f8fafc;
        color: #123b6d;
    }

    /* SUMMARY CARDS */

    .growth-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 15px;
        margin-bottom: 24px;
    }

    .growth-stat {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 18px;
        position: relative;
        overflow: hidden;
        transition: 0.2s;
    }

    .growth-stat:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(15, 23, 42, 0.06);
    }

    .growth-stat::after {
        content: "";
        position: absolute;
        right: -25px;
        top: -25px;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(18, 59, 109, 0.04);
        pointer-events: none;
    }

    .growth-stat-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 13px;
    }

    .growth-stat-icon {
        width: 35px;
        height: 35px;
        flex-shrink: 0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 700;
    }

    .icon-blue {
        background: #eaf2fb;
        color: #123b6d;
    }

    .icon-green {
        background: #ecfdf3;
        color: #16803c;
    }

    .icon-orange {
        background: #fff7ed;
        color: #b45309;
    }

    .icon-red {
        background: #fef2f2;
        color: #b91c1c;
    }

    .growth-stat-label {
        color: #64748b;
        font-size: 10px;
        font-weight: 650;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .growth-stat-value {
        font-size: 25px;
        font-weight: 750;
        color: #1e293b;
        line-height: 1.2;
        margin-bottom: 7px;
    }

    .growth-stat-description {
        color: #94a3b8;
        font-size: 10px;
    }

    /* MAIN GRID */

    
/* MAIN GRID — USE THE FULL AVAILABLE WIDTH */

.growth-main-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr);
    gap: 20px;
    align-items: start;
    width: 100%;
}

.growth-main-grid > * {
    min-width: 0;
    width: 100%;

    .growth-panel {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .growth-panel-header {
        min-height: 62px;
        padding: 15px 18px;
        border-bottom: 1px solid #e8edf3;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .growth-panel-title {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .growth-panel-title-icon {
        width: 32px;
        height: 32px;
        flex-shrink: 0;
        border-radius: 7px;
        background: #eaf2fb;
        color: #123b6d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .growth-panel-title h3 {
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 3px;
    }

    .growth-panel-title p {
        font-size: 10px;
        color: #94a3b8;
    }

    /* SEARCH */

    .child-search {
        position: relative;
        flex-shrink: 0;
    }

    .child-search input {
        width: 210px;
        height: 36px;
        border: 1px solid #dce3eb;
        border-radius: 6px;
        padding: 0 11px 0 32px;
        outline: none;
        font-family: inherit;
        font-size: 11px;
        color: #334155;
    }

    .child-search input:focus {
        border-color: #123b6d;
        box-shadow: 0 0 0 2px rgba(18, 59, 109, 0.08);
    }

    .child-search span {
        position: absolute;
        left: 11px;
        top: 10px;
        color: #94a3b8;
        font-size: 12px;
        pointer-events: none;
    }

    /* TABLE */

    .growth-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .growth-table {
        width: 100%;
        border-collapse: collapse;
    }

    .growth-table thead {
        background: #f8fafc;
    }

    .growth-table th {
        padding: 11px 15px;
        text-align: left;
        color: #64748b;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.45px;
        border-bottom: 1px solid #e8edf3;
        white-space: nowrap;
    }

    .growth-table td {
        padding: 13px 15px;
        border-bottom: 1px solid #f0f3f7;
        font-size: 11px;
        color: #334155;
        vertical-align: middle;
    }

    .growth-table tbody tr {
        transition: 0.15s;
    }

    .growth-table tbody tr:hover {
        background: #f8fbff;
    }

    .child-profile {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 150px;
    }

    .child-avatar {
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

    .child-name {
        font-weight: 700;
        color: #1e293b;
        font-size: 11px;
    }

    .child-id {
        color: #94a3b8;
        font-size: 9px;
        margin-top: 3px;
    }

    .measurement-value {
        font-weight: 650;
        color: #334155;
        white-space: nowrap;
    }

    .muted-value {
        color: #94a3b8;
    }

    /* STATUS BADGES */

    .growth-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 8px;
        border-radius: 20px;
        font-size: 9px;
        font-weight: 700;
        white-space: nowrap;
    }

    .growth-status-dot {
        width: 5px;
        height: 5px;
        flex-shrink: 0;
        border-radius: 50%;
    }

    .status-normal {
        color: #16803c;
        background: #ecfdf3;
    }

    .status-normal .growth-status-dot {
        background: #16803c;
    }

    .status-monitor {
        color: #b45309;
        background: #fff7ed;
    }

    .status-monitor .growth-status-dot {
        background: #b45309;
    }

    .status-attention {
        color: #b91c1c;
        background: #fef2f2;
    }

    .status-attention .growth-status-dot {
        background: #b91c1c;
    }

    /* ACTION BUTTON */

    .view-growth {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 7px 10px;
        border-radius: 5px;
        background: #f1f5f9;
        color: #123b6d;
        text-decoration: none;
        font-size: 9px;
        font-weight: 700;
        transition: 0.2s;
        white-space: nowrap;
    }

    .view-growth:hover {
        background: #eaf2fb;
        color: #123b6d;
    }

    /* MONITORING COVERAGE */

    .monitoring-summary {
        padding: 18px;
    }

    .summary-progress {
        margin-bottom: 20px;
    }

    .summary-progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }

    .summary-progress-header span:first-child {
        font-size: 10px;
        color: #64748b;
        font-weight: 600;
    }

    .summary-progress-header span:last-child {
        font-size: 10px;
        color: #123b6d;
        font-weight: 750;
    }

    .progress-track {
        width: 100%;
        height: 7px;
        border-radius: 20px;
        background: #edf2f7;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: #123b6d;
        border-radius: 20px;
        transition: width 0.3s ease;
    }

    /* ATTENTION LIST */

    .attention-list {
        padding: 4px 18px 18px;
    }

    .attention-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 11px 0;
        border-bottom: 1px solid #edf1f5;
    }

    .attention-item:last-child {
        border-bottom: none;
    }

    .attention-child {
        display: flex;
        align-items: center;
        gap: 9px;
        min-width: 0;
    }

    .attention-avatar {
        width: 30px;
        height: 30px;
        flex-shrink: 0;
        border-radius: 7px;
        background: #fef2f2;
        color: #b91c1c;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 750;
    }

    .attention-name {
        font-size: 10px;
        font-weight: 700;
        color: #334155;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 175px;
    }

    .attention-reason {
        font-size: 9px;
        color: #94a3b8;
        margin-top: 2px;
    }

    .attention-arrow {
        color: #94a3b8;
        font-size: 12px;
        text-decoration: none;
    }

    .no-attention {
        padding: 22px 0;
        text-align: center;
        color: #94a3b8;
        font-size: 10px;
    }

    /* QUICK ACTIONS */

    .quick-actions {
        padding: 15px;
        display: grid;
        gap: 8px;
    }

    .quick-action {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px;
        border: 1px solid #edf1f5;
        border-radius: 7px;
        text-decoration: none;
        transition: 0.2s;
    }

    .quick-action:hover {
        background: #f8fafc;
        border-color: #dbe4ee;
    }

    .quick-action-icon {
        width: 31px;
        height: 31px;
        flex-shrink: 0;
        border-radius: 7px;
        background: #eaf2fb;
        color: #123b6d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
    }

    .quick-action strong {
        display: block;
        color: #334155;
        font-size: 10px;
        margin-bottom: 2px;
    }

    .quick-action span {
        display: block;
        color: #94a3b8;
        font-size: 8px;
    }

    /* EMPTY STATE */

    .empty-growth {
        padding: 45px 20px;
        text-align: center;
    }

    .empty-growth-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: #eaf2fb;
        color: #123b6d;
        margin: 0 auto 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .empty-growth h3 {
        color: #334155;
        font-size: 13px;
        margin-bottom: 5px;
    }

    .empty-growth p {
        color: #94a3b8;
        font-size: 10px;
    }

    /* PAGINATION */

    .growth-pagination {
        padding: 14px 18px;
        border-top: 1px solid #edf1f5;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 9px;
        color: #94a3b8;
        gap: 15px;
        flex-wrap: wrap;
    }

    .growth-pagination nav {
        max-width: 100%;
    }

    /* RESPONSIVE */

    @media (max-width: 1050px) {
        .growth-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .growth-main-grid {
            grid-template-columns: minmax(0, 1fr);
        }
    }

    @media (max-width: 700px) {
        .growth-header {
            flex-direction: column;
        }

        .growth-header-actions {
            width: 100%;
        }

        .growth-stats {
            grid-template-columns: minmax(0, 1fr);
        }

        .growth-panel-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .child-search,
        .child-search input {
            width: 100%;
        }

        .growth-pagination {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="growth-page">

    {{-- PAGE HEADER --}}

    <div class="growth-header">
        <div class="growth-header-left">
            <h1>Growth Monitoring</h1>
            <p>
                Track children's physical growth, review measurements,
                and identify children who may require further attention.
            </p>
        </div>

        <div class="growth-header-actions">
            <a href="{{ route('children.create') }}"
               class="growth-action growth-action-primary">
                <span>+</span>
                Register Child
            </a>

            <a href="{{ route('children.index') }}"
               class="growth-action growth-action-secondary">
                <span>○</span>
                View Children
            </a>
        </div>
    </div>

    {{-- SUMMARY CARDS --}}

    <div class="growth-stats">

        <div class="growth-stat">
            <div class="growth-stat-top">
                <span class="growth-stat-label">Total Children</span>
                <div class="growth-stat-icon icon-blue">👤</div>
            </div>

            <div class="growth-stat-value">
                {{ number_format($totalChildrenCount) }}
            </div>

            <div class="growth-stat-description">
                Children registered in the system
            </div>
        </div>

        <div class="growth-stat">
            <div class="growth-stat-top">
                <span class="growth-stat-label">Being Monitored</span>
                <div class="growth-stat-icon icon-green">✓</div>
            </div>

            <div class="growth-stat-value">
                {{ number_format($childrenWithRecordsCount) }}
            </div>

            <div class="growth-stat-description">
                Children with health records
            </div>
        </div>

        <div class="growth-stat">
            <div class="growth-stat-top">
                <span class="growth-stat-label">Assessments</span>
                <div class="growth-stat-icon icon-orange">↗</div>
            </div>

            <div class="growth-stat-value">
                {{ number_format($totalGrowthAssessmentsCount) }}
            </div>

            <div class="growth-stat-description">
                Growth assessments recorded
            </div>
        </div>

        <div class="growth-stat">
            <div class="growth-stat-top">
                <span class="growth-stat-label">Requires Attention</span>
                <div class="growth-stat-icon icon-red">!</div>
            </div>

            <div class="growth-stat-value">
                {{ number_format($childrenRequiringAttentionCount) }}
            </div>

            <div class="growth-stat-description">
                Children flagged for review
            </div>
        </div>

    </div>

    {{-- MAIN CONTENT --}}

    <div class="growth-main-grid">

        {{-- CHILDREN MONITORING TABLE --}}

        <div class="growth-panel">

            <div class="growth-panel-header">
                <div class="growth-panel-title">
                    <div class="growth-panel-title-icon">↗</div>

                    <div>
                        <h3>Children Growth Monitoring</h3>
                        <p>Recent children and their latest monitoring status</p>
                    </div>
                </div>

                <div class="child-search">
                    <span>⌕</span>
                    <input
                        type="text"
                        id="childSearch"
                        placeholder="Search children..."
                        aria-label="Search children"
                    >
                </div>
            </div>

            <div class="growth-table-wrapper">
                <table class="growth-table">
                    <thead>
                        <tr>
                            <th>Child</th>
                            <th>Sex</th>
                            <th>Latest Visit</th>
                            <th>Weight</th>
                            <th>Height</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody id="childrenTable">

                    @forelse($children as $child)

                        @php
                            /*
                             * Controller eager-loads records in descending
                             * visit_date order, so first() is the latest.
                             */
                            $latestRecord = $child->healthRecords->first();

                            $firstName = $child->first_name ?? '';
                            $lastName = $child->last_name ?? '';

                            $fullName = trim($firstName . ' ' . $lastName);

                            $initials = strtoupper(
                                substr($firstName, 0, 1) .
                                substr($lastName, 0, 1)
                            );

                            $weight = $latestRecord
                                ? $latestRecord->weight
                                : null;

                            $height = $latestRecord
                                ? $latestRecord->height
                                : null;

                            $visitDate = $latestRecord && $latestRecord->visit_date
                                ? \Carbon\Carbon::parse(
                                    $latestRecord->visit_date
                                )->format('d M Y')
                                : null;

                            $assessment = $latestRecord
                                ? $latestRecord->growthAssessment
                                : null;

                            /*
                             * Do not automatically label a child normal
                             * when no assessment exists.
                             */
                            $rawStatus = null;

                            if ($assessment) {
                                $rawStatus = $assessment->status
                                    ?? $assessment->overall_status
                                    ?? $assessment->risk_level
                                    ?? null;
                            }

                            $status = strtolower(
                                str_replace('_', ' ', (string) $rawStatus)
                            );

                            if (!$assessment || $status === '') {
                                $statusClass = 'status-monitor';
                                $statusText = 'Not assessed';
                            } elseif (
                                str_contains($status, 'danger') ||
                                str_contains($status, 'severe') ||
                                str_contains($status, 'critical') ||
                                str_contains($status, 'malnutrition')
                            ) {
                                $statusClass = 'status-attention';
                                $statusText = 'Attention';
                            } elseif (
                                str_contains($status, 'moderate') ||
                                str_contains($status, 'monitor') ||
                                str_contains($status, 'warning') ||
                                str_contains($status, 'risk') ||
                                str_contains($status, 'attention')
                            ) {
                                $statusClass = 'status-monitor';
                                $statusText = 'Monitor';
                            } else {
                                $statusClass = 'status-normal';
                                $statusText = ucfirst($status);
                            }
                        @endphp

                        <tr
                            data-child-name="{{ strtolower($fullName) }}"
                            data-child-id="{{ strtolower($child->child_id ?? '') }}"
                        >
                            <td>
                                <div class="child-profile">
                                    <div class="child-avatar">
                                        {{ $initials ?: '?' }}
                                    </div>

                                    <div>
                                        <div class="child-name">
                                            {{ $fullName ?: 'Unnamed Child' }}
                                        </div>

                                        <div class="child-id">
                                            ID: {{ $child->child_id ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>{{ $child->sex ?? '—' }}</td>

                            <td>
                                @if($visitDate)
                                    {{ $visitDate }}
                                @else
                                    <span class="muted-value">No visit</span>
                                @endif
                            </td>

                            <td>
                                @if($weight !== null)
                                    <span class="measurement-value">
                                        {{ number_format((float) $weight, 1) }} kg
                                    </span>
                                @else
                                    <span class="muted-value">—</span>
                                @endif
                            </td>

                            <td>
                                @if($height !== null)
                                    <span class="measurement-value">
                                        {{ number_format((float) $height, 1) }} cm
                                    </span>
                                @else
                                    <span class="muted-value">—</span>
                                @endif
                            </td>

                            <td>
                                <span class="growth-status {{ $statusClass }}">
                                    <span class="growth-status-dot"></span>
                                    {{ $statusText }}
                                </span>
                            </td>

                            {{-- Profile only. Growth charts are in the
                                 separate Growth Charts sidebar section. --}}
                            <td>
                                <a
                                    href="{{ route('children.show', $child) }}"
                                    class="view-growth"
                                >
                                    View Profile →
                                </a>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="7">
                                <div class="empty-growth">
                                    <div class="empty-growth-icon">+</div>
                                    <h3>No children registered</h3>
                                    <p>
                                        Register a child to begin growth monitoring.
                                    </p>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}

            @if(method_exists($children, 'links'))
                <div class="growth-pagination">
                    <span>
                        Showing {{ $children->firstItem() ?? 0 }}
                        – {{ $children->lastItem() ?? 0 }}
                        of {{ $children->total() ?? 0 }} children
                    </span>

                    <div>
                        {{ $children->links() }}
                    </div>
                </div>
            @endif

        </div>

       
</script>
</div>
@endsection
