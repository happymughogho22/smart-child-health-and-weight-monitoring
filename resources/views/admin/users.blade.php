@extends('layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')
@section('page-subtitle', 'Manage system accounts, approvals and access roles')

@section('content')

<div class="admin-page">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            <span class="alert-icon">✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <span class="alert-icon">!</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Header --}}
    <div class="management-header">

        <div>
            <div class="eyebrow">SYSTEM ADMINISTRATION</div>
            <h2>System Users</h2>
            <p>
                Review registrations and manage access to the health monitoring system.
            </p>
        </div>

        <div class="user-count">
            <strong>{{ $users->count()  }}</strong>
            <span>Total Users</span>
        </div>

    </div>


    {{-- Search and Filters --}}
    <div class="filter-card">

        <form method="GET" action="{{ route('admin.users') }}" class="filter-form">

            <div class="search-box">

                <span class="search-icon">⌕</span>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name, email or position..."
                >

            </div>

            <select name="status" class="status-filter">

                <option value="">All statuses</option>

                <option value="pending"
                    {{ request('status') === 'pending' ? 'selected' : '' }}>
                    Pending
                </option>

                <option value="approved"
                    {{ request('status') === 'approved' ? 'selected' : '' }}>
                    Approved
                </option>

                <option value="rejected"
                    {{ request('status') === 'rejected' ? 'selected' : '' }}>
                    Rejected
                </option>

                <option value="suspended"
                    {{ request('status') === 'suspended' ? 'selected' : '' }}>
                    Suspended
                </option>

            </select>

            <button type="submit" class="filter-btn">
                Search
            </button>

            @if(request()->filled('search') || request()->filled('status'))
                <a href="{{ route('admin.users') }}" class="clear-btn">
                    Clear
                </a>
            @endif

        </form>

    </div>


    {{-- Users Table --}}
    <div class="users-card">

        <div class="table-header">

            <div>
                <h3>Registered Accounts</h3>
                <span>Review and manage system access</span>
            </div>

        </div>

        <div class="table-wrapper">

            <table class="users-table">

                <thead>

                    <tr>
                        <th>User</th>
                        <th>Position</th>
                        <th>System Role</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>

                </thead>

                <tbody>

                @forelse($users as $user)

                    <tr>

                        {{-- User --}}
                        <td>

                            <div class="user-cell">

                                <div class="user-avatar-small">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>

                                <div>

                                    <strong>
                                        {{ $user->name }}
                                    </strong>

                                    <span>
                                        {{ $user->email }}
                                    </span>

                                </div>

                            </div>

                        </td>


                        {{-- Position --}}
                        <td>
                            <span class="position-text">
                                {{ $user->position ?: 'Not specified' }}
                            </span>
                        </td>


                        {{-- Role --}}
                        <td>

                            @if($user->role === 'administrator')

                                <span class="role-badge administrator">
                                    Administrator
                                </span>

                            @elseif($user->role === 'health_worker')

                                <span class="role-badge health-worker">
                                    Health Worker
                                </span>

                            @elseif($user->role === 'data_officer')

                                <span class="role-badge data-officer">
                                    Data Officer
                                </span>

                            @else

                                <span class="role-badge unassigned">
                                    Not Assigned
                                </span>

                            @endif

                        </td>


                        {{-- Status --}}
                        <td>

                            <span class="account-status {{ $user->status }}">
                                <span class="status-dot-small"></span>
                                {{ ucfirst($user->status ?? 'unknown') }}
                            </span>

                        </td>


                        {{-- Date --}}
                        <td>

                            <span class="date-text">
                                {{ $user->created_at?->format('d M Y') }}
                            </span>

                        </td>


                        {{-- Actions --}}
                        <td>

                            @if($user->id === auth()->id())

                                <span class="current-user">
                                    Current account
                                </span>

                            @elseif($user->status === 'pending')

                                <div class="action-group">

                                    <button
                                        type="button"
                                        class="action-btn approve"
                                        onclick="openApprovalModal(
                                            {{ $user->id }},
                                            @js($user->name)
                                        )"
                                    >
                                        Approve
                                    </button>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.users.reject', $user) }}"
                                        onsubmit="return confirm('Reject this registration?');"
                                    >
                                        @csrf

                                        <button
                                            type="submit"
                                            class="action-btn reject"
                                        >
                                            Reject
                                        </button>

                                    </form>

                                </div>

                            @elseif($user->status === 'approved')

                                <div class="action-group">

                                    <button
                                        type="button"
                                        class="action-btn role"
                                        onclick="openRoleModal(
                                            {{ $user->id }},
                                            @js($user->name),
                                            @js($user->role)
                                        )"
                                    >
                                        Role
                                    </button>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.users.suspend', $user) }}"
                                        onsubmit="return confirm('Suspend this account?');"
                                    >
                                        @csrf

                                        <button
                                            type="submit"
                                            class="action-btn suspend"
                                        >
                                            Suspend
                                        </button>

                                    </form>

                                </div>

                            @else

                                <form
                                    method="POST"
                                    action="{{ route('admin.users.activate', $user) }}"
                                    onsubmit="return confirm('Activate this account?');"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        class="action-btn activate"
                                    >
                                        Activate
                                    </button>

                                </form>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6">

                            <div class="empty-users">

                                <div class="empty-icon">
                                    ♙
                                </div>

                                <strong>No users found</strong>

                                <span>
                                    No accounts match your current search or filter.
                                </span>

                            </div>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


    
    </div>

</div>


{{-- APPROVAL MODAL --}}
<div id="approvalModal" class="modal-overlay">

    <div class="modal-box">

        <button
            type="button"
            class="modal-close"
            onclick="closeApprovalModal()"
        >
            ×
        </button>

        <div class="modal-icon">
            ✓
        </div>

        <h3>Approve Account</h3>

        <p>
            Approve <strong id="approvalUserName"></strong>
            and assign their system role.
        </p>

        <form id="approvalForm" method="POST">

            @csrf

            <label for="approvalRole">
                System Role
            </label>

            <select
                name="role"
                id="approvalRole"
                required
            >

                <option value="">Select system role</option>

                <option value="health_worker">
                    Health Worker
                </option>

                <option value="data_officer">
                    Data Officer
                </option>

                <option value="administrator">
                    Administrator
                </option>

            </select>

            <button type="submit" class="modal-primary">
                Approve Account
            </button>

        </form>

    </div>

</div>


{{-- ROLE MODAL --}}
<div id="roleModal" class="modal-overlay">

    <div class="modal-box">

        <button
            type="button"
            class="modal-close"
            onclick="closeRoleModal()"
        >
            ×
        </button>

        <div class="modal-icon role-icon">
            ⚙
        </div>

        <h3>Change System Role</h3>

        <p>
            Update the system role assigned to
            <strong id="roleUserName"></strong>.
        </p>

        <form id="roleForm" method="POST">

            @csrf
            @method('PUT')

            <label for="userRole">
                System Role
            </label>

            <select
                name="role"
                id="userRole"
                required
            >

                <option value="health_worker">
                    Health Worker
                </option>

                <option value="data_officer">
                    Data Officer
                </option>

                <option value="administrator">
                    Administrator
                </option>

            </select>

            <button type="submit" class="modal-primary">
                Save Role
            </button>

        </form>

    </div>

</div>


<style>

/* =========================================================
   ADMIN USER MANAGEMENT
   ========================================================= */

.admin-page {
    padding: 28px 30px 40px;
    max-width: 1600px;
    margin: 0 auto;
}


/* HEADER */

.management-header {
    display: flex;
    justify-content: space-between;
    align-items: center;

    background: #ffffff;
    border: 1px solid #e6e9ef;
    border-radius: 14px;

    padding: 24px 26px;
    margin-bottom: 18px;

    box-shadow: 0 4px 16px rgba(11, 31, 58, 0.05);
}

.eyebrow {
    color: #c9a227;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.1px;
    margin-bottom: 7px;
}

.management-header h2 {
    margin: 0;
    color: #0b1f3a;
    font-size: 22px;
}

.management-header p {
    margin: 6px 0 0;
    color: #667085;
    font-size: 13px;
}

.user-count {
    min-width: 110px;
    text-align: center;
    padding: 12px 18px;

    background: #f7f8fa;
    border: 1px solid #e5e8ed;
    border-radius: 10px;
}

.user-count strong {
    display: block;
    color: #0b1f3a;
    font-size: 24px;
}

.user-count span {
    color: #667085;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .5px;
}


/* ALERTS */

.alert {
    padding: 12px 16px;
    border-radius: 9px;
    margin-bottom: 16px;

    display: flex;
    align-items: center;
    gap: 10px;

    font-size: 13px;
    font-weight: 600;
}

.alert-success {
    background: #eaf7f0;
    color: #18794e;
    border: 1px solid #ccebd9;
}

.alert-error {
    background: #fcebea;
    color: #a72e25;
    border: 1px solid #f3cdca;
}

.alert-icon {
    width: 23px;
    height: 23px;
    border-radius: 50%;

    display: flex;
    align-items: center;
    justify-content: center;

    background: rgba(255,255,255,.6);
}


/* FILTER */

.filter-card {
    background: #ffffff;
    border: 1px solid #e6e9ef;
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 18px;
}

.filter-form {
    display: flex;
    gap: 10px;
    align-items: center;
}

.search-box {
    flex: 1;
    position: relative;
}

.search-box input {
    width: 100%;
    height: 40px;

    padding: 0 14px 0 38px;

    border: 1px solid #dfe3e8;
    border-radius: 8px;

    outline: none;
    font-family: inherit;
    font-size: 13px;

    transition: border-color .2s;
}

.search-box input:focus {
    border-color: #c9a227;
    box-shadow: 0 0 0 3px rgba(201,162,39,.08);
}

.search-icon {
    position: absolute;
    left: 13px;
    top: 10px;

    color: #98a2b3;
    font-size: 17px;
}

.status-filter {
    height: 40px;
    min-width: 145px;

    padding: 0 12px;

    border: 1px solid #dfe3e8;
    border-radius: 8px;

    background: white;
    color: #344054;

    font-family: inherit;
    font-size: 12px;
}

.filter-btn,
.clear-btn {
    height: 40px;
    padding: 0 16px;

    border-radius: 8px;

    font-family: inherit;
    font-size: 12px;
    font-weight: 650;

    cursor: pointer;
}

.filter-btn {
    border: none;
    background: #0b1f3a;
    color: white;
}

.filter-btn:hover {
    background: #12345b;
}

.clear-btn {
    display: flex;
    align-items: center;

    border: 1px solid #dfe3e8;
    color: #667085;
    background: white;
}


/* TABLE */

.users-card {
    background: #ffffff;
    border: 1px solid #e6e9ef;
    border-radius: 12px;

    overflow: hidden;

    box-shadow: 0 4px 16px rgba(11,31,58,.05);
}

.table-header {
    padding: 18px 20px;
    border-bottom: 1px solid #e8ebef;
}

.table-header h3 {
    margin: 0;
    color: #0b1f3a;
    font-size: 15px;
}

.table-header span {
    display: block;
    margin-top: 4px;
    color: #667085;
    font-size: 11px;
}

.table-wrapper {
    overflow-x: auto;
}

.users-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 950px;
}

.users-table th {
    padding: 11px 16px;

    background: #fafbfc;
    border-bottom: 1px solid #e8ebef;

    text-align: left;

    color: #7b8494;
    font-size: 10px;
    font-weight: 750;

    text-transform: uppercase;
    letter-spacing: .55px;
}

.users-table td {
    padding: 14px 16px;

    border-bottom: 1px solid #eef0f3;

    color: #344054;
    font-size: 12px;
}

.users-table tbody tr:hover {
    background: #fbfcfd;
}

.user-cell {
    display: flex;
    align-items: center;
    gap: 10px;
}

.user-avatar-small {
    width: 36px;
    height: 36px;

    border-radius: 50%;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #0b1f3a;
    color: white;

    font-size: 12px;
    font-weight: 700;
}

.user-cell strong {
    display: block;
    color: #0b1f3a;
    font-size: 12px;
}

.user-cell span {
    display: block;
    margin-top: 3px;
    color: #8a94a6;
    font-size: 10px;
}

.position-text {
    color: #475467;
}


/* ROLE BADGES */

.role-badge {
    display: inline-block;

    padding: 5px 9px;
    border-radius: 20px;

    font-size: 10px;
    font-weight: 700;
}

.role-badge.administrator {
    background: #eee9f8;
    color: #65479b;
}

.role-badge.health-worker {
    background: #e8f5ef;
    color: #18794e;
}

.role-badge.data-officer {
    background: #eaf2fb;
    color: #245b91;
}

.role-badge.unassigned {
    background: #f1f2f4;
    color: #667085;
}


/* ACCOUNT STATUS */

.account-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;

    font-size: 10px;
    font-weight: 700;
}

.status-dot-small {
    width: 7px;
    height: 7px;
    border-radius: 50%;
}

.account-status.pending {
    color: #9b7500;
}

.account-status.pending .status-dot-small {
    background: #d99a00;
}

.account-status.approved {
    color: #18794e;
}

.account-status.approved .status-dot-small {
    background: #198754;
}

.account-status.rejected,
.account-status.suspended {
    color: #a72e25;
}

.account-status.rejected .status-dot-small,
.account-status.suspended .status-dot-small {
    background: #c0392b;
}


/* ACTIONS */

.action-group {
    display: flex;
    align-items: center;
    gap: 6px;
}

.action-group form {
    margin: 0;
}

.action-btn {
    border: none;
    border-radius: 6px;

    padding: 7px 10px;

    font-family: inherit;
    font-size: 10px;
    font-weight: 700;

    cursor: pointer;
    white-space: nowrap;
}

.action-btn.approve {
    background: #e8f5ef;
    color: #18794e;
}

.action-btn.reject {
    background: #fcebea;
    color: #a72e25;
}

.action-btn.role {
    background: #eaf2fb;
    color: #245b91;
}

.action-btn.suspend {
    background: #fff5df;
    color: #956f00;
}

.action-btn.activate {
    background: #e8f5ef;
    color: #18794e;
}

.current-user {
    color: #98a2b3;
    font-size: 10px;
    font-style: italic;
}


/* EMPTY */

.empty-users {
    padding: 50px 20px;
    text-align: center;
}

.empty-icon {
    width: 48px;
    height: 48px;
    margin: 0 auto 12px;

    border-radius: 50%;

    background: #f1f3f5;

    display: flex;
    align-items: center;
    justify-content: center;

    color: #8a94a6;
}

.empty-users strong {
    display: block;
    color: #344054;
}

.empty-users span {
    display: block;
    margin-top: 5px;
    color: #98a2b3;
    font-size: 11px;
}


/* MODAL */

.modal-overlay {
    position: fixed;
    inset: 0;

    background: rgba(11, 31, 58, .55);

    display: none;
    align-items: center;
    justify-content: center;

    z-index: 9999;

    padding: 20px;
}

.modal-overlay.active {
    display: flex;
}

.modal-box {
    position: relative;

    width: 100%;
    max-width: 420px;

    background: white;
    border-radius: 15px;

    padding: 28px;

    box-shadow: 0 20px 60px rgba(0,0,0,.2);
}

.modal-close {
    position: absolute;
    top: 12px;
    right: 14px;

    border: none;
    background: none;

    font-size: 24px;
    color: #98a2b3;

    cursor: pointer;
}

.modal-icon {
    width: 45px;
    height: 45px;

    border-radius: 50%;

    background: #eaf7f0;
    color: #18794e;

    display: flex;
    align-items: center;
    justify-content: center;

    font-weight: 800;

    margin-bottom: 15px;
}

.role-icon {
    background: #eaf2fb;
    color: #245b91;
}

.modal-box h3 {
    margin: 0;
    color: #0b1f3a;
    font-size: 19px;
}

.modal-box p {
    margin: 7px 0 20px;
    color: #667085;
    font-size: 12px;
    line-height: 1.5;
}

.modal-box label {
    display: block;
    margin-bottom: 7px;

    color: #344054;
    font-size: 11px;
    font-weight: 700;
}

.modal-box select {
    width: 100%;
    height: 42px;

    padding: 0 12px;

    border: 1px solid #dfe3e8;
    border-radius: 8px;

    background: white;

    font-family: inherit;
    font-size: 12px;

    margin-bottom: 16px;
}

.modal-primary {
    width: 100%;
    height: 42px;

    border: none;
    border-radius: 8px;

    background: #0b1f3a;
    color: white;

    font-family: inherit;
    font-size: 12px;
    font-weight: 700;

    cursor: pointer;
}

.modal-primary:hover {
    background: #12345b;
}


/* MOBILE */

@media(max-width: 700px) {

    .admin-page {
        padding: 20px 15px 30px;
    }

    .management-header {
        align-items: flex-start;
        gap: 15px;
        flex-direction: column;
    }

    .filter-form {
        flex-direction: column;
        align-items: stretch;
    }

    .status-filter,
    .filter-btn,
    .clear-btn {
        width: 100%;
    }

}

</style>


<script>

function openApprovalModal(userId, userName)
{
    document.getElementById('approvalUserName').textContent = userName;

    document.getElementById('approvalForm').action =
        '/admin/users/' + userId + '/approve';

    document.getElementById('approvalModal')
        .classList.add('active');
}

function closeApprovalModal()
{
    document.getElementById('approvalModal')
        .classList.remove('active');
}


function openRoleModal(userId, userName, currentRole)
{
    document.getElementById('roleUserName').textContent = userName;

    document.getElementById('roleForm').action =
        '/admin/users/' + userId + '/role';

    document.getElementById('userRole').value =
        currentRole || '';

    document.getElementById('roleModal')
        .classList.add('active');
}

function closeRoleModal()
{
    document.getElementById('roleModal')
        .classList.remove('active');
}


document.addEventListener('click', function(event)
{
    if (event.target.classList.contains('modal-overlay')) {

        event.target.classList.remove('active');

    }
});

</script>

@endsection