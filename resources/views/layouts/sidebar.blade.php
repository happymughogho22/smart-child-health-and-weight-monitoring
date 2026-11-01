{{-- =========================================================
     SHARED APPLICATION SIDEBAR
     Used by all authenticated pages
     ========================================================= --}}

<style>
    .app-sidebar {
        width: 245px;
        background: #123b6d;
        color: #ffffff;
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
    }

    .app-sidebar-header {
        height: 118px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid rgba(255,255,255,0.10);
    }

    .app-logo {
        width: 40px;
        height: 40px;
        background: #ffffff;
        color: #123b6d;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 800;
        flex-shrink: 0;
    }

    .app-brand-title {
        font-size: 15px;
        font-weight: 700;
        line-height: 1.2;
    }

    .app-brand-subtitle {
        margin-top: 5px;
        font-size: 9px;
        color: rgba(255,255,255,0.65);
    }

    .app-sidebar-content {
        flex: 1;
        padding: 25px 12px 20px;
    }

    .app-sidebar-section {
        margin-bottom: 28px;
    }

    .app-sidebar-section-title {
        padding: 0 12px;
        margin-bottom: 12px;
        font-size: 10px;
        font-weight: 700;
        color: rgba(255,255,255,0.45);
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .app-sidebar-link {
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
        padding: 13px 14px;
        margin-bottom: 4px;
        border-radius: 7px;
        color: rgba(255,255,255,0.85);
        text-decoration: none;
        font-size: 13px;
        transition: all 0.2s ease;
    }

    .app-sidebar-link:hover {
        background: rgba(255,255,255,0.08);
        color: #ffffff;
    }

    .app-sidebar-link.active {
        background: rgba(255,255,255,0.14);
        color: #ffffff;
        font-weight: 600;
    }

    .app-sidebar-icon {
        width: 18px;
        text-align: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .app-sidebar-footer {
        padding: 15px 17px;
        border-top: 1px solid rgba(255,255,255,0.10);
    }

    .app-system-user {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 13px;
    }

    .app-user-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: rgba(255,255,255,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .app-user-info {
        min-width: 0;
    }

    .app-user-name {
        font-size: 12px;
        font-weight: 700;
        color: #ffffff;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .app-user-email {
        margin-top: 3px;
        font-size: 9px;
        color: rgba(255,255,255,0.55);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .app-logout-form {
        width: 100%;
    }

    .app-logout-btn {
        width: 100%;
        border: 1px solid rgba(255,255,255,0.15);
        background: rgba(255,255,255,0.06);
        color: rgba(255,255,255,0.85);
        border-radius: 7px;
        padding: 9px 12px;
        cursor: pointer;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .app-logout-btn:hover {
        background: rgba(255,255,255,0.13);
        color: #ffffff;
    }

    @media (max-width: 700px) {
        .app-sidebar {
            width: 210px;
        }
    }
</style>


<aside class="app-sidebar">

    {{-- BRAND --}}
    <div class="app-sidebar-header">

        <div class="app-logo">
            CW
        </div>

        <div>
            <div class="app-brand-title">
                Child Weight
            </div>

            <div class="app-brand-subtitle">
                Monitoring System
            </div>
        </div>

    </div>


    {{-- NAVIGATION --}}
    <div class="app-sidebar-content">

        {{-- MAIN MENU --}}
        <div class="app-sidebar-section">

            <div class="app-sidebar-section-title">
                Main Menu
            </div>


            {{-- DASHBOARD --}}
            <a
                href="{{ route('dashboard') }}"
                class="app-sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
            >
                <span class="app-sidebar-icon">▦</span>
                <span>Dashboard</span>
            </a>


            {{-- REGISTER CHILD --}}
            <a
                href="{{ route('children.create') }}"
                class="app-sidebar-link {{ request()->routeIs('children.create') ? 'active' : '' }}"
            >
                <span class="app-sidebar-icon">+</span>
                <span>Register Child</span>
            </a>


            {{-- CHILDREN --}}
            <a
                href="{{ route('children.index') }}"
                class="app-sidebar-link {{ request()->routeIs('children.index', 'children.show', 'children.edit') ? 'active' : '' }}"
            >
                <span class="app-sidebar-icon">○</span>
                <span>Children</span>
            </a>


            {{-- WEIGHT RECORDS --}}
            <a
                href="{{ route('growth-monitoring.index') }}"
                class="app-sidebar-link {{ request()->routeIs('growth-monitoring.index', 'health-records.*') ? 'active' : '' }}"
            >
                <span class="app-sidebar-icon">↗</span>
                <span>Weight Records</span>
            </a>

        </div>


        {{-- MONITORING --}}
        <div class="app-sidebar-section">

            <div class="app-sidebar-section-title">
                Monitoring
            </div>


            {{-- GROWTH CHARTS --}}
            <a
                href="{{ route('growth-monitoring.index') }}"
                class="app-sidebar-link {{ request()->routeIs('growth-monitoring.*', 'children.growth-charts') ? 'active' : '' }}"
            >
                <span class="app-sidebar-icon">↗</span>
                <span>Growth Charts</span>
            </a>


            {{-- IOT DEVICES --}}
            <a
                href="#"
                class="app-sidebar-link"
                onclick="return false;"
                title="IoT device integration"
            >
                <span class="app-sidebar-icon">◇</span>
                <span>IoT Devices</span>
            </a>


            {{-- REPORTS --}}
            <a
                href="{{ route('reports.index') }}"
                class="app-sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
            >
                <span class="app-sidebar-icon">▤</span>
                <span>Reports</span>
            </a>

        </div>

    </div>


    {{-- USER + LOGOUT --}}
    <div class="app-sidebar-footer">

        <div class="app-system-user">

            <div class="app-user-avatar">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>

            <div class="app-user-info">

                <div class="app-user-name">
                    {{ auth()->user()->name ?? 'User' }}
                </div>

                <div class="app-user-email">
                    {{ auth()->user()->email ?? '' }}
                </div>

            </div>

        </div>


        {{-- LOGOUT --}}
        <form
            method="POST"
            action="{{ route('logout') }}"
            class="app-logout-form"
        >

            @csrf

            <button
                type="submit"
                class="app-logout-btn"
            >
                <span>↪</span>
                <span>Logout</span>
            </button>

        </form>

    </div>

</aside>