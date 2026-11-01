 @extends('layouts.app')

@section('title', 'Weight Records')
@section('page-title', 'Weight Records')
@section('page-subtitle', 'View and manage health measurements for all registered children')

@push('styles')
<style>

/* Weight Records Search */
.wr-search-panel {
    padding: 18px 20px;
    background: #ffffff;
    border-bottom: 1px solid #e2e8f0;
}

.wr-search-form {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.wr-search-input {
    flex: 1;
    min-width: 220px;
    height: 42px;
    padding: 0 13px;
    border: 1px solid #cbd5e1;
    border-radius: 7px;
    color: #123b6d;
    font-size: 12px;
    outline: none;
    box-sizing: border-box;
}

.wr-search-input:focus {
    border-color: #123b6d;
    box-shadow: 0 0 0 3px rgba(18, 59, 109, 0.10);
}

.wr-search-button {
    height: 42px;
    padding: 0 18px;
    border: 1px solid #123b6d;
    border-radius: 7px;
    background: #123b6d;
    color: #ffffff;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
}

.wr-search-button:hover {
    background: #0f315a;
}

.wr-clear-button {
    height: 42px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 15px;
    border: 1px solid #cbd5e1;
    border-radius: 7px;
    background: #ffffff;
    color: #123b6d;
    font-size: 12px;
    font-weight: 650;
    text-decoration: none;
}

.wr-search-hint {
    margin-top: 9px;
    color: #64748b;
    font-size: 11px;
}

.wr-search-result {
    padding: 10px 20px;
    background: #f8fafc;
    color: #475569;
    font-size: 11px;
    border-bottom: 1px solid #e2e8f0;
}

@media (max-width: 650px) {
    .wr-search-panel {
        padding: 15px;
    }

    .wr-search-form {
        align-items: stretch;
    }

    .wr-search-input {
        flex-basis: 100%;
        min-width: 0;
    }

    .wr-search-button,
    .wr-clear-button {
        flex: 1;
    }
}


    .wr-page {
        width: 100%;
    }

    .wr-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 24px;
    }

    .wr-heading h1 {
        color: #123b6d;
        font-size: 25px;
        margin-bottom: 7px;
    }

    .wr-heading p {
        color: #64748b;
        font-size: 13px;
    }

    .wr-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 10px 14px;
        border-radius: 7px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 650;
        border: 1px solid #dbe3ee;
        white-space: nowrap;
    }

    .wr-primary {
        background: #123b6d;
        color: white;
        border-color: #123b6d;
    }

    .wr-secondary {
        background: white;
        color: #123b6d;
    }

    .wr-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 22px;
    }

    .wr-card-header {
        padding: 18px 20px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .wr-card-header h2 {
        color: #123b6d;
        font-size: 15px;
    }

    .wr-card-header p {
        color: #64748b;
        font-size: 11px;
        margin-top: 5px;
    }

    .wr-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .wr-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 850px;
    }

    .wr-table th {
        background: #f8fafc;
        color: #52647d;
        font-size: 10px;
        font-weight: 700;
        text-align: left;
        padding: 14px 16px;
        text-transform: uppercase;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .wr-table td {
        padding: 15px 16px;
        font-size: 12px;
        color: #334155;
        border-bottom: 1px solid #edf1f6;
        vertical-align: middle;
    }

    .wr-table tbody tr:hover {
        background: #f8fbff;
    }

    .wr-child-name {
        color: #123b6d;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .wr-muted {
        color: #94a3b8;
        font-size: 10px;
    }

    .wr-status {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        background: #eff6ff;
        color: #1d4ed8;
    }

    .wr-actions {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .wr-action {
        display: inline-block;
        padding: 7px 9px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .wr-view {
        background: #eff6ff;
        color: #123b6d;
    }

    .wr-add {
        background: #123b6d;
        color: white;
    }

    .wr-empty {
        text-align: center;
        padding: 45px 20px;
        color: #64748b;
    }

    .wr-empty h3 {
        color: #123b6d;
        font-size: 16px;
        margin-bottom: 8px;
    }

    .wr-empty p {
        font-size: 12px;
        margin-bottom: 18px;
    }

    .wr-alert {
        padding: 13px 16px;
        border-radius: 7px;
        margin-bottom: 18px;
        font-size: 12px;
    }

    .wr-success {
        background: #ecfdf3;
        color: #166534;
    }

    .wr-error {
        background: #fef2f2;
        color: #991b1b;
    }

    .wr-pagination {
        padding: 16px 20px;
    }

    @media (max-width: 650px) {
        .wr-heading h1 {
            font-size: 21px;
        }

        .wr-heading > div:last-child {
            width: 100%;
        }

        .wr-heading .wr-button {
            width: 100%;
        }

        .wr-card-header {
            padding: 15px;
        }
    }
</style>
@endpush

@section('content')
<div class="wr-page">

    <div class="wr-heading">
        <div>
            <h1>Weight Records</h1>
            <p>Review recorded measurements and access each child's health history.</p>
        </div>

        <div>
            <a href="{{ route('children.index') }}" class="wr-button wr-secondary">
                View Children
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="wr-alert wr-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="wr-alert wr-error">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="wr-alert wr-error">
            Please check the form for errors.
        </div>
    @endif

    <div class="wr-card">
        <div class="wr-card-header">
            <div>
                <h2>All Recorded Measurements</h2>
                <p>Health records from all registered children</p>
            </div>

            
        {{-- SEARCH WEIGHT RECORDS --}}
        <div class="wr-search-panel">
            <form
                action="{{ url()->current() }}"
                method="GET"
                class="wr-search-form"
            >
                <input
                    type="search"
                    name="search"
                    class="wr-search-input"
                    placeholder="Search child name, Child ID, facility, date or status..."
                    value="{{ $search ?? request('search', '') }}"
                    aria-label="Search weight records"
                >

                <button type="submit" class="wr-search-button">
                    Search
                </button>

                @if(!empty($search))
                    <a
                        href="{{ url()->current() }}"
                        class="wr-clear-button"
                    >
                        Clear
                    </a>
                @endif
            </form>

            <div class="wr-search-hint">
                Search by child's name, Child ID, facility, nutrition status,
                or visit date in YYYY-MM-DD format.
            </div>
        </div>

        @if(!empty($search))
            <div class="wr-search-result">
                Search results for
                <strong>{{ $search }}</strong>:
                {{ $healthRecords->total() }}
                {{ $healthRecords->total() === 1 ? 'record found' : 'records found' }}.
            </div>
        @endif


            <span class="wr-muted">
                {{ $healthRecords->total() }} record(s)
            </span>
        </div>

        @if($healthRecords->count() > 0)

            <div class="wr-table-wrap">
                <table class="wr-table">
                    <thead>
                        <tr>
                            <th>Child</th>
                            <th>Visit Date</th>
                            <th>Weight</th>
                            <th>Height / Length</th>
                            <th>Facility</th>
                            <th>Nutrition Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($healthRecords as $record)
                            <tr>
                                <td>
                                    <div class="wr-child-name">
                                        @php
                                            $child = $record->child;

                                            $childName = $child
                                                ? ($child->name
                                                    ?? $child->full_name
                                                    ?? trim(
                                                        ($child->first_name ?? '') . ' ' .
                                                        ($child->last_name ?? '')
                                                    ))
                                                : 'Unknown child';
                                        @endphp

                                        {{ $childName ?: 'Unnamed child' }}
                                    </div>

                                    <div class="wr-muted">
                                        ID:
                                        {{ $child->child_id ?? 'Not available' }}
                                    </div>
                                </td>

                                <td>
                                    {{ $record->visit_date
                                        ? \Carbon\Carbon::parse($record->visit_date)->format('d M Y')
                                        : 'Not recorded' }}
                                </td>

                                <td>
                                    @if($record->weight !== null)
                                        <strong>{{ number_format((float) $record->weight, 2) }} kg</strong>
                                    @else
                                        <span class="wr-muted">Not recorded</span>
                                    @endif
                                </td>

                                <td>
                                    @if($record->height !== null)
                                        {{ number_format((float) $record->height, 2) }} cm

                                        <div class="wr-muted">
                                            {{ ($record->measurement_type ?? '') === 'length'
                                                ? 'Length'
                                                : 'Height' }}
                                        </div>
                                    @else
                                        <span class="wr-muted">Not recorded</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $record->facility_name ?: 'Not recorded' }}
                                </td>

                                <td>
                                    <span class="wr-status">
                                        {{ $record->nutrition_status ?: 'Not assessed' }}
                                    </span>
                                </td>

                                <td>
                                    <div class="wr-actions">
                                        @if($child)
                                            <a
                                                href="{{ route('health-records.show', [$child, $record]) }}"
                                                class="wr-action wr-view"
                                            >
                                                View
                                            </a>

                                            <a
                                                href="{{ route('health-records.create', $child) }}"
                                                class="wr-action wr-add"
                                            >
                                                + Record
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="wr-pagination">
                {{ $healthRecords->links() }}
            </div>

       
        @else

            <div class="wr-empty">
                @if(!empty($search))
                    <h3>No Matching Records Found</h3>

                    <p>
                        No health records matched
                        <strong>{{ $search }}</strong>.
                        Try another child name, Child ID, facility, date,
                        or nutrition status.
                    </p>

                    <a
                        href="{{ url()->current() }}"
                        class="wr-button wr-primary"
                    >
                        Clear Search
                    </a>
                @else
                    <h3>No Weight Records Yet</h3>

                    <p>
                        No health measurements have been recorded for any child.
                        Register a child and add a health record to begin monitoring.
                    </p>

                    <a
                        href="{{ route('children.index') }}"
                        class="wr-button wr-primary"
                    >
                        View Registered Children
                    </a>
                @endif
            </div>

        @endif

    </div>

</div>
@endsection