
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Child Weight Monitoring System')</title>

    <style>
        :root {
            --primary: #123b6d;
            --primary-dark: #0c2d52;
            --primary-light: #eaf2fb;

            --background: #f5f7fb;
            --card: #ffffff;
            --border: #e2e8f0;

            --text: #1e293b;
            --muted: #64748b;

            --success: #16803c;
            --success-bg: #ecfdf3;

            --warning: #b45309;
            --warning-bg: #fff7ed;

            --danger: #b91c1c;
            --danger-bg: #fef2f2;

            --blue: #2563eb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            min-height: 100%;
        }

        body {
            min-height: 100vh;
            font-family: "Segoe UI", Arial, sans-serif;
            background: var(--background);
            color: var(--text);
        }

        /* =========================================
           LEFT SIDEBAR
        ========================================= */

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 245px;
            height: 100vh;
            height: 100dvh;
            background: var(--primary);
            color: white;
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .logo-area {
            min-height: 78px;
            display: flex;
            align-items: center;
            padding: 0 22px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }

        .logo {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            background: white;
            color: var(--primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 800;
            margin-right: 11px;
        }

        .logo-title {
            font-size: 15px;
            font-weight: 700;
        }

        .logo-subtitle {
            font-size: 10px;
            opacity: 0.65;
            margin-top: 3px;
        }

        .navigation {
            padding: 22px 12px;
            flex: 1;
            min-height: 0;
            overflow-y: auto;
        }

        .nav-title {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.5;
            padding: 0 12px;
            margin-bottom: 10px;
            margin-top: 8px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 13px;
            margin-bottom: 5px;
            border-radius: 7px;
            color: rgba(255, 255, 255, 0.72);
            font-size: 13px;
            text-decoration: none;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.08);
            color: white;
        }

        .nav-item.active {
            background: rgba(255, 255, 255, 0.13);
            color: white;
        }

        .nav-icon {
            width: 19px;
            flex-shrink: 0;
            text-align: center;
            font-size: 14px;
        }

        .sidebar-footer {
            padding: 17px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
            background: var(--primary);
        }

        .system-user {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }

        .user-info {
            min-width: 0;
            flex: 1;
        }

        .user-info strong {
            display: block;
            font-size: 12px;
            overflow-wrap: anywhere;
        }

        .user-info span {
            display: block;
            font-size: 10px;
            opacity: 0.65;
            margin-top: 3px;
            overflow-wrap: anywhere;
        }

        .logout-form {
            width: 100%;
            margin-top: 13px;
        }

        .logout-btn {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 7px;
            background: rgba(255, 255, 255, 0.06);
            color: rgba(255, 255, 255, 0.85);
            font-family: inherit;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.14);
            color: #ffffff;
        }

        .logout-icon {
            font-size: 15px;
        }

        /* =========================================
           MAIN AREA
        ========================================= */

        .main {
            margin-left: 245px;
            width: calc(100% - 245px);
            min-width: 0;
            min-height: 100vh;
        }

        /* =========================================
           TOP BAR
        ========================================= */

        .topbar {
            min-height: 78px;
            background: white;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 16px 32px;
        }

        .topbar h2 {
            font-size: 17px;
            font-weight: 650;
            line-height: 1.4;
        }

        .topbar p {
            color: var(--muted);
            font-size: 11px;
            margin-top: 4px;
            line-height: 1.5;
        }

        .system-status {
            display: flex;
            align-items: center;
            gap: 7px;
            color: var(--success);
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            flex-shrink: 0;
            border-radius: 50%;
            background: var(--success);
        }

        /* =========================================
           PAGE CONTENT
        ========================================= */

        .content {
            width: 100%;
            max-width: none;
            min-width: 0;
            padding: 30px 32px 50px;
        }

        .content > * {
            min-width: 0;
        }

        /* Prevent wide tables from expanding the layout. */
        .content table {
            max-width: 100%;
        }

        /* =========================================
           BREADCRUMB
        ========================================= */

        .breadcrumb {
            color: #94a3b8;
            font-size: 11px;
            margin-bottom: 20px;
        }

        .breadcrumb a {
            color: var(--primary);
            text-decoration: none;
        }

        .breadcrumb strong {
            color: var(--primary);
        }

        /* =========================================
           CARDS
        ========================================= */

        .card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
        }

        .card-header {
            padding: 17px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .card-header h3 {
            color: var(--primary);
            font-size: 13px;
            font-weight: 700;
        }

        .card-body {
            padding: 20px;
        }

        /* =========================================
           BUTTONS
        ========================================= */

        .btn {
            display: inline-block;
            padding: 10px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 11px;
            font-weight: 650;
            border: none;
            cursor: pointer;
            font-family: inherit;
            transition: background 0.2s ease;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-secondary {
            background: #eef2f6;
            color: #475569;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-warning {
            background: var(--warning);
            color: white;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        /* =========================================
           RESPONSIVE
        ========================================= */

        @media (max-width: 1100px) {
            .topbar {
                padding-left: 24px;
                padding-right: 24px;
            }

            .content {
                padding: 24px;
            }
        }

        @media (max-width: 850px) {
            .sidebar {
                display: none;
            }

            .main {
                margin-left: 0;
                width: 100%;
            }

            .topbar {
                padding: 16px 20px;
            }

            .content {
                padding: 20px;
            }
        }

        @media (max-width: 480px) {
            .topbar {
                align-items: flex-start;
                gap: 12px;
                padding: 16px;
            }

            .topbar h2 {
                font-size: 15px;
            }

            .topbar p {
                font-size: 10px;
            }

            .system-status {
                font-size: 10px;
            }

            .content {
                padding: 16px;
            }

            .card-header {
                padding: 14px;
                flex-wrap: wrap;
            }

            .card-body {
                padding: 14px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- =========================================
         LEFT SIDEBAR
    ========================================= -->

    <aside class="sidebar">

        <!-- LOGO -->
        <div class="logo-area">
            <div class="logo">CW</div>

            <div>
                <div class="logo-title">Child Weight</div>
                <div class="logo-subtitle">Monitoring System</div>
            </div>
        </div>

        <!-- NAVIGATION -->
        <nav class="navigation">

            <div class="nav-title">Main Menu</div>

            <!-- DASHBOARD -->
            <a
                href="{{ route('dashboard') }}"
                class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
            >
                <span class="nav-icon">▦</span>
                <span>Dashboard</span>
            </a>

            <!-- REGISTER CHILD -->
            <a
                href="{{ route('children.create') }}"
                class="nav-item {{ request()->routeIs('children.create') ? 'active' : '' }}"
            >
                <span class="nav-icon">+</span>
                <span>Register Child</span>
            </a>

            <!-- CHILDREN -->
            <a
                href="{{ route('children.index') }}"
                class="nav-item {{ request()->routeIs('children.index') || request()->routeIs('children.show') || request()->routeIs('children.edit') ? 'active' : '' }}"
            >
                <span class="nav-icon">○</span>
                <span>Children</span>
            </a>

            <!-- WEIGHT RECORDS -->
            <a
                href="{{ route('weight-records.index') }}"
                class="nav-item {{ request()->routeIs('health-records.*') || request()->routeIs('weight-records.*') ? 'active' : '' }}"
            >
                <span class="nav-icon">⚖</span>
                <span>Weight Records</span>
            </a>

            <div class="nav-title">Monitoring</div>

            <!-- GROWTH CHARTS -->
            <a
                href="{{ route('growth-charts.index') }}"
                class="nav-item {{ request()->routeIs('growth-monitoring.*') || request()->routeIs('children.growth-charts') || request()->routeIs('growth-charts.*') ? 'active' : '' }}"
            >
                <span class="nav-icon">↗</span>
                <span>Growth Charts</span>
            </a>

            <!-- IOT DEVICES -->
            <a href="#" class="nav-item">
                <span class="nav-icon">◈</span>
                <span>IoT Devices</span>
            </a>

            <!-- REPORTS -->
            <a
                href="{{ route('reports.index') }}"
                class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}"
            >
                <span class="nav-icon">▤</span>
                <span>Reports</span>
            </a>

        </nav>

        <!-- SIDEBAR FOOTER -->
        <div class="sidebar-footer">

            <div class="system-user">

                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>

                <div class="user-info">
                    <strong>
                        {{ auth()->user()->name ?? 'User' }}
                    </strong>

                    <span>
                        {{ auth()->user()->email ?? '' }}
                    </span>
                </div>

            </div>

            <!-- LOGOUT -->
            <form
                method="POST"
                action="{{ route('logout') }}"
                class="logout-form"
            >
                @csrf

                <button type="submit" class="logout-btn">
                    <span class="logout-icon">↪</span>
                    <span>Logout</span>
                </button>
            </form>

        </div>

    </aside>

    <!-- =========================================
         MAIN AREA
    ========================================= -->

    <div class="main">

        <!-- TOP BAR -->
        <header class="topbar">

            <div>
                <h2>
                    @yield('page-title', 'Child Weight Monitoring System')
                </h2>

                <p>
                    @yield('page-subtitle', 'Child information and monitoring system')
                </p>
            </div>

            <div class="system-status">
                <span class="status-dot"></span>
                <span>System Online</span>
            </div>

        </header>

        <!-- PAGE CONTENT -->
        <main class="content">
            @yield('content')
        </main>

    </div>

    @stack('scripts')

</body>
</html>
