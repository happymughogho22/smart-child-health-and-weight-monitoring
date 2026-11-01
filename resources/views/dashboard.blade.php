<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | Child Health and Weight Monitoring System</title>

    <style>
        /* =========================================================
           SMART CHILD HEALTH AND WEIGHT MONITORING SYSTEM
           PROFESSIONAL ADMIN DASHBOARD
        ========================================================= */

        :root {
            --navy: #0b1f3a;
            --navy-dark: #07162a;
            --navy-light: #12345b;

            --gold: #c9a227;
            --gold-light: #e0bd4f;

            --background: #f4f6f9;
            --card: #ffffff;

            --text: #172033;
            --text-light: #667085;
            --muted: #8a94a6;

            --border: #e5e8ed;

            --success: #198754;
            --warning: #c88a00;
            --danger: #c0392b;

            --shadow: 0 4px 18px rgba(11, 31, 58, 0.06);
            --shadow-hover: 0 9px 25px rgba(11, 31, 58, 0.10);

            --radius: 12px;
            --sidebar-width: 250px;
        }


        /* =========================================================
           RESET
        ========================================================= */

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
        }

        body {
            background: var(--background);
            color: var(--text);
            font-family: "Segoe UI", Arial, sans-serif;
            font-size: 14px;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button,
        input,
        select {
            font-family: inherit;
        }


        /* =========================================================
           SIDEBAR
        ========================================================= */

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;

            width: var(--sidebar-width);

            background: var(--navy);
            color: #ffffff;

            display: flex;
            flex-direction: column;

            z-index: 1000;

            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.08);
        }


        /* =========================================================
           LOGO
        ========================================================= */

        .logo-area {
            min-height: 84px;

            padding: 18px;

            display: flex;
            align-items: center;

            gap: 12px;

            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .logo {
            width: 43px;
            height: 43px;

            border-radius: 10px;

            background: var(--gold);
            color: var(--navy);

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 13px;
            font-weight: 800;

            flex-shrink: 0;
        }

        .logo-title {
            color: #ffffff;

            font-size: 14px;
            font-weight: 700;

            line-height: 1.2;
        }

        .logo-subtitle {
            margin-top: 4px;

            color: rgba(255, 255, 255, 0.48);

            font-size: 10px;
            font-weight: 500;
        }


        /* =========================================================
           NAVIGATION
        ========================================================= */

        .navigation {
            flex: 1;

            padding: 20px 12px;

            overflow-y: auto;
        }

        .navigation br {
            display: none;
        }

        .nav-title {
            margin: 14px 10px 8px;

            color: rgba(255, 255, 255, 0.40);

            font-size: 9px;
            font-weight: 800;

            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-item {
            position: relative;

            min-height: 42px;

            padding: 0 12px;

            margin: 3px 0;

            border-radius: 8px;

            display: flex;
            align-items: center;

            gap: 11px;

            color: rgba(255, 255, 255, 0.68);

            font-size: 12px;
            font-weight: 600;

            transition: all 0.2s ease;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.07);

            color: #ffffff;

            transform: translateX(2px);
        }

        .nav-item.active {
            background: rgba(201, 162, 39, 0.13);

            color: #ffffff;
        }

        .nav-item.active::before {
            content: "";

            position: absolute;

            left: 0;
            top: 8px;
            bottom: 8px;

            width: 3px;

            border-radius: 0 3px 3px 0;

            background: var(--gold);
        }

        .nav-icon {
            width: 27px;
            height: 27px;

            border-radius: 7px;

            background: rgba(255, 255, 255, 0.06);

            display: flex;
            align-items: center;
            justify-content: center;

            color: rgba(255, 255, 255, 0.72);

            font-size: 13px;

            flex-shrink: 0;
        }

        .nav-item.active .nav-icon {
            background: rgba(201, 162, 39, 0.18);

            color: var(--gold-light);
        }


        /* =========================================================
           ADMINISTRATION NAVIGATION
        ========================================================= */

        .admin-nav {
            background: rgba(201, 162, 39, 0.08);
        }

        .admin-nav:hover {
            background: rgba(201, 162, 39, 0.15);
        }

        .admin-nav .nav-icon {
            color: var(--gold-light);
        }


        /* =========================================================
           SIDEBAR USER
        ========================================================= */

        .sidebar-footer {
            padding: 15px;

            border-top: 1px solid rgba(255, 255, 255, 0.08);

            background: rgba(0, 0, 0, 0.08);
        }

        .system-user {
            display: flex;
            align-items: center;

            gap: 10px;
        }

        .user-avatar {
            width: 37px;
            height: 37px;

            border-radius: 50%;

            background: var(--gold);
            color: var(--navy);

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 13px;
            font-weight: 800;

            flex-shrink: 0;
        }

        .user-info {
            min-width: 0;
        }

        .user-info strong {
            display: block;

            color: #ffffff;

            font-size: 11px;
            font-weight: 700;

            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-info span {
            display: block;

            margin-top: 3px;

            color: rgba(255, 255, 255, 0.48);

            font-size: 9px;
        }


        /* =========================================================
           LOGOUT
        ========================================================= */

        .logout-form {
            width: 100%;
            margin-top: 12px;
        }

        .logout-btn {
            width: 100%;

            min-height: 36px;

            padding: 8px 12px;

            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 7px;

            background: rgba(255, 255, 255, 0.05);

            color: rgba(255, 255, 255, 0.72);

            font-size: 11px;
            font-weight: 600;

            cursor: pointer;

            display: flex;
            align-items: center;
            justify-content: center;

            gap: 7px;

            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.12);

            color: #ffffff;

            border-color: rgba(255, 255, 255, 0.18);
        }

        .logout-icon {
            font-size: 14px;
        }


        /* =========================================================
           MAIN AREA
        ========================================================= */

        .main {
            margin-left: var(--sidebar-width);

            min-height: 100vh;

            background: var(--background);
        }


        /* =========================================================
           TOP BAR
        ========================================================= */

        .topbar {
            min-height: 76px;

            padding: 0 30px;

            background: #ffffff;

            border-bottom: 1px solid var(--border);

            display: flex;
            align-items: center;
            justify-content: space-between;

            position: sticky;
            top: 0;

            z-index: 100;
        }

        .topbar-title h2 {
            margin: 0;

            color: var(--navy);

            font-size: 19px;
            font-weight: 700;
        }

        .topbar-title p {
            margin: 4px 0 0;

            color: var(--text-light);

            font-size: 11px;
        }

        .topbar-right {
            display: flex;
            align-items: center;

            gap: 18px;
        }

        .system-status {
            display: flex;
            align-items: center;

            gap: 7px;

            color: var(--success);

            font-size: 11px;
            font-weight: 650;
        }

        .status-dot {
            width: 8px;
            height: 8px;

            border-radius: 50%;

            background: #38c878;

            box-shadow: 0 0 0 4px rgba(56, 200, 120, 0.10);
        }

        .date-display {
            padding-left: 18px;

            border-left: 1px solid var(--border);

            color: var(--text-light);

            font-size: 11px;
            font-weight: 600;
        }


        /* =========================================================
           CONTENT
        ========================================================= */

        .content {
            min-height: calc(100vh - 76px);

            padding-bottom: 30px;
        }


        /* =========================================================
           BREADCRUMB
        ========================================================= */

        .breadcrumb {
            padding: 13px 30px;

            background: #fafbfc;

            border-bottom: 1px solid var(--border);

            color: var(--muted);

            font-size: 10px;
        }

        .breadcrumb strong {
            color: var(--navy);
        }


        /* =========================================================
           PAGE HEADER
        ========================================================= */

        .page-header {
            min-height: 94px;

            padding: 22px 30px;

            background: #ffffff;

            border-bottom: 1px solid var(--border);

            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .page-header h1 {
            margin: 0;

            color: var(--navy);

            font-size: 23px;
            font-weight: 700;
        }

        .page-header p {
            margin: 5px 0 0;

            color: var(--text-light);

            font-size: 11px;
        }

        .register-btn {
            min-height: 39px;

            padding: 0 16px;

            border-radius: 7px;

            background: var(--navy);
            color: #ffffff;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            font-size: 11px;
            font-weight: 700;

            transition: all 0.2s ease;
        }

        .register-btn:hover {
            background: var(--navy-light);

            transform: translateY(-1px);

            box-shadow: 0 5px 15px rgba(11, 31, 58, 0.15);
        }


        /* =========================================================
           CONTENT SPACING
        ========================================================= */

        .section-title,
        .admin-welcome,
        .stats,
        .approval-panel,
        .dashboard-grid,
        .quick-actions {
            margin-left: 30px;
            margin-right: 30px;
        }


        /* =========================================================
           ADMIN WELCOME
        ========================================================= */

        .admin-welcome {
            position: relative;

            overflow: hidden;

            margin-top: 22px;

            min-height: 135px;

            padding: 26px 28px;

            border-radius: 14px;

            background:
                linear-gradient(
                    135deg,
                    var(--navy) 0%,
                    #102f51 55%,
                    #163c65 100%
                );

            color: #ffffff;

            display: flex;
            align-items: center;
            justify-content: space-between;

            box-shadow: 0 8px 24px rgba(11, 31, 58, 0.12);
        }

        .admin-welcome::before {
            content: "";

            position: absolute;

            width: 190px;
            height: 190px;

            right: -55px;
            top: -95px;

            border: 1px solid rgba(201, 162, 39, 0.22);

            border-radius: 50%;
        }

        .admin-welcome::after {
            content: "";

            position: absolute;

            width: 110px;
            height: 110px;

            right: 130px;
            bottom: -70px;

            border: 1px solid rgba(201, 162, 39, 0.15);

            border-radius: 50%;
        }

        .admin-welcome h3 {
            position: relative;
            z-index: 2;

            margin: 0;

            color: #ffffff;

            font-size: 21px;
            font-weight: 700;
        }

        .admin-welcome p {
            position: relative;
            z-index: 2;

            margin: 7px 0 0;

            max-width: 700px;

            color: rgba(255, 255, 255, 0.72);

            font-size: 11px;

            line-height: 1.6;
        }

        .admin-welcome-badge {
            position: relative;
            z-index: 2;

            padding: 8px 13px;

            border: 1px solid rgba(201, 162, 39, 0.35);

            border-radius: 20px;

            background: rgba(201, 162, 39, 0.10);

            color: var(--gold-light);

            font-size: 9px;
            font-weight: 800;

            letter-spacing: 0.7px;

            white-space: nowrap;
        }


        /* =========================================================
           SECTION TITLES
        ========================================================= */

        .section-title {
            margin-top: 25px;
            margin-bottom: 12px;

            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-title h3 {
            margin: 0;

            color: var(--navy);

            font-size: 14px;
            font-weight: 700;
        }

        .section-title span {
            color: var(--muted);

            font-size: 10px;
        }


        /* =========================================================
           STATISTICS
        ========================================================= */

        .stats {
            display: grid;

            grid-template-columns: repeat(4, 1fr);

            gap: 15px;

            margin-bottom: 22px;
        }

        .stat-card {
            min-height: 145px;

            padding: 18px;

            background: var(--card);

            border: 1px solid var(--border);

            border-radius: 11px;

            box-shadow: var(--shadow);

            transition: all 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);

            box-shadow: var(--shadow-hover);

            border-color: rgba(201, 162, 39, 0.30);
        }

        .stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-label {
            color: var(--text-light);

            font-size: 10px;
            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: 0.4px;
        }

        .stat-icon {
            width: 38px;
            height: 38px;

            border-radius: 9px;

            background: rgba(11, 31, 58, 0.07);

            color: var(--navy);

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 13px;
            font-weight: 800;
        }

        .stat-number {
            margin-top: 16px;

            color: var(--navy);

            font-size: 28px;
            font-weight: 750;

            line-height: 1;
        }

        .stat-description {
            margin-top: 7px;

            color: var(--muted);

            font-size: 10px;
        }

        .stat-warning .stat-icon {
            background: rgba(217, 154, 0, 0.11);
            color: var(--warning);
        }

        .stat-success .stat-icon {
            background: rgba(25, 135, 84, 0.10);
            color: var(--success);
        }

        .stat-danger .stat-icon {
            background: rgba(192, 57, 43, 0.09);
            color: var(--danger);
        }


        /* =========================================================
           APPROVAL PANEL
        ========================================================= */

        .approval-panel {
            margin-top: 22px;
            margin-bottom: 25px;

            background: #ffffff;

            border: 1px solid var(--border);

            border-radius: 12px;

            overflow: hidden;

            box-shadow: var(--shadow);
        }

        .approval-header {
            padding: 18px 20px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            border-bottom: 1px solid var(--border);
        }

        .approval-header h3 {
            margin: 0;

            color: var(--navy);

            font-size: 14px;
            font-weight: 700;
        }

        .approval-header p {
            margin: 5px 0 0;

            color: var(--text-light);

            font-size: 10px;
        }

        .review-btn {
            padding: 8px 13px;

            border-radius: 7px;

            background: var(--navy);
            color: #ffffff;

            font-size: 10px;
            font-weight: 700;

            white-space: nowrap;

            transition: background 0.2s ease;
        }

        .review-btn:hover {
            background: var(--navy-light);
        }


        /* =========================================================
           PENDING TABLE
        ========================================================= */

        .pending-table {
            width: 100%;

            border-collapse: collapse;
        }

        .pending-table th {
            padding: 10px 16px;

            background: #fafbfc;

            border-bottom: 1px solid var(--border);

            color: #7b8494;

            font-size: 9px;
            font-weight: 750;

            text-align: left;

            text-transform: uppercase;

            letter-spacing: 0.5px;
        }

        .pending-table td {
            padding: 12px 16px;

            border-bottom: 1px solid #eef0f3;

            color: #475467;

            font-size: 11px;
        }

        .pending-table tbody tr {
            transition: background 0.15s ease;
        }

        .pending-table tbody tr:hover {
            background: #fafbfd;
        }

        .pending-user-name {
            color: var(--navy);

            font-weight: 650;
        }

        .pending-status {
            display: inline-flex;

            align-items: center;

            padding: 4px 8px;

            border-radius: 15px;

            background: #fff6dc;

            color: #956f00;

            font-size: 9px;
            font-weight: 750;
        }

        .approval-empty {
            padding: 22px 20px;

            color: var(--success);

            font-size: 11px;
        }


        /* =========================================================
           LOWER DASHBOARD GRID
        ========================================================= */

        .dashboard-grid {
            display: grid;

            grid-template-columns:
                minmax(0, 1.5fr)
                minmax(280px, 0.8fr);

            gap: 18px;

            margin-top: 22px;
        }


        /* =========================================================
           PANELS
        ========================================================= */

        .panel {
            background: #ffffff;

            border: 1px solid var(--border);

            border-radius: 11px;

            overflow: hidden;

            box-shadow: var(--shadow);
        }

        .panel-header {
            padding: 17px 19px;

            border-bottom: 1px solid var(--border);

            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .panel-header h3 {
            margin: 0;

            color: var(--navy);

            font-size: 14px;
            font-weight: 700;
        }

        .panel-header span {
            color: var(--muted);

            font-size: 10px;
        }

        .panel-body {
            padding: 18px;
        }


        /* =========================================================
           CHILD TABLE
        ========================================================= */

        .dashboard-table {
            width: 100%;

            border-collapse: collapse;
        }

        .dashboard-table th {
            padding: 10px 11px;

            background: #fafbfc;

            border-bottom: 1px solid var(--border);

            color: #7b8494;

            font-size: 9px;
            font-weight: 750;

            text-align: left;

            text-transform: uppercase;

            letter-spacing: 0.4px;
        }

        .dashboard-table td {
            padding: 12px 11px;

            border-bottom: 1px solid #eef0f3;

            color: #475467;

            font-size: 11px;
        }

        .dashboard-table tr:last-child td {
            border-bottom: none;
        }

        .dashboard-table tbody tr {
            transition: background 0.15s ease;
        }

        .dashboard-table tbody tr:hover {
            background: #fafbfd;
        }

        .child-name {
            color: var(--navy);

            font-weight: 650;
        }

        .gender {
            display: inline-flex;

            padding: 4px 8px;

            border-radius: 5px;

            background: rgba(11, 31, 58, 0.07);

            color: var(--navy);

            font-size: 9px;
            font-weight: 700;
        }


        /* =========================================================
           GENDER DISTRIBUTION
        ========================================================= */

        .gender-item {
            margin-bottom: 20px;
        }

        .gender-item:last-child {
            margin-bottom: 0;
        }

        .gender-label {
            display: flex;

            justify-content: space-between;

            margin-bottom: 7px;

            color: #475467;

            font-size: 10px;
            font-weight: 600;
        }

        .gender-label span:last-child {
            color: var(--muted);
        }

        .progress {
            width: 100%;
            height: 8px;

            background: #edf0f3;

            border-radius: 20px;

            overflow: hidden;
        }

        .progress-bar {
            height: 100%;

            background: var(--navy);

            border-radius: 20px;

            transition: width 0.4s ease;
        }


        /* =========================================================
           QUICK ACTIONS
        ========================================================= */

        .quick-actions {
            display: grid;

            grid-template-columns: repeat(2, 1fr);

            gap: 10px;

            margin-top: 18px;
        }

        .quick-action {
            min-height: 55px;

            padding: 11px;

            border: 1px solid var(--border);

            border-radius: 9px;

            background: #ffffff;

            display: flex;
            align-items: center;

            gap: 9px;

            color: var(--navy);

            transition: all 0.2s ease;
        }

        .quick-action:hover {
            border-color: var(--gold);

            background: #fffdf7;

            transform: translateY(-2px);
        }

        .quick-action-icon {
            width: 31px;
            height: 31px;

            border-radius: 7px;

            background: rgba(201, 162, 39, 0.12);

            color: #9b7712;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 12px;
            font-weight: 800;

            flex-shrink: 0;
        }

        .quick-action span {
            font-size: 10px;
            font-weight: 650;
        }


        /* =========================================================
           EMPTY STATE
        ========================================================= */

        .empty-state {
            padding: 35px 15px;

            color: var(--muted);

            text-align: center;

            font-size: 10px;
        }

        .empty-state strong {
            display: block;

            margin-bottom: 6px;

            color: var(--navy);

            font-size: 11px;
        }


        /* =========================================================
           FOOTER
        ========================================================= */

        .footer {
            margin-top: 28px;

            padding: 20px 30px;

            border-top: 1px solid var(--border);

            color: #98a2b3;

            text-align: center;

            font-size: 9px;
        }


        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 1100px) {

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }


        @media (max-width: 800px) {

            :root {
                --sidebar-width: 220px;
            }

            .topbar {
                padding: 0 20px;
            }

            .page-header {
                padding: 20px;
            }

            .section-title,
            .admin-welcome,
            .stats,
            .approval-panel,
            .dashboard-grid,
            .quick-actions {
                margin-left: 20px;
                margin-right: 20px;
            }

            .admin-welcome {
                align-items: flex-start;

                flex-direction: column;

                gap: 15px;
            }
        }


        @media (max-width: 650px) {

            :root {
                --sidebar-width: 0px;
            }

            .sidebar {
                position: relative;

                width: 100%;

                min-height: auto;
            }

            .main {
                margin-left: 0;
            }

            .navigation {
                max-height: none;
            }

            .topbar {
                position: relative;

                padding: 15px 18px;

                min-height: auto;
            }

            .topbar-right {
                display: none;
            }

            .page-header {
                align-items: flex-start;

                flex-direction: column;

                gap: 15px;
            }

            .register-btn {
                width: 100%;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .dashboard-table {
                min-width: 600px;
            }

            .panel-body {
                overflow-x: auto;
            }

            .approval-header {
                align-items: flex-start;

                flex-direction: column;

                gap: 12px;
            }

            .review-btn {
                width: 100%;

                text-align: center;
            }

            .quick-actions {
                grid-template-columns: 1fr;
            }

            .footer {
                padding: 18px 15px;
            }
        }
    </style>
</head>


<body>

    {{-- =========================================================
         SIDEBAR
    ========================================================== --}}

    <aside class="sidebar">

        {{-- BRAND --}}
        <div class="logo-area">

            <div class="logo">
                CW
            </div>

            <div>
                <div class="logo-title">
                    Child Health
                </div>

                <div class="logo-subtitle">
                    Weight Monitoring System
                </div>
            </div>

        </div>


        {{-- NAVIGATION --}}
        <nav class="navigation">

            <div class="nav-title">
                Main Menu
            </div>


            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               class="nav-item active">

                <span class="nav-icon">▦</span>

                <span>Dashboard</span>

            </a>


            {{-- Register Child --}}
            <a href="{{ route('children.create') }}"
               class="nav-item">

                <span class="nav-icon">+</span>

                <span>Register Child</span>

            </a>


            {{-- Children --}}
            <a href="{{ route('children.index') }}"
               class="nav-item">

                <span class="nav-icon">○</span>

                <span>Children</span>

            </a>


            {{-- Weight Records --}}
            <a href="{{ route('growth-monitoring.index') }}"
               class="nav-item">

                <span class="nav-icon">↗</span>

                <span>Weight Records</span>

            </a>


            <div class="nav-title">
                Monitoring
            </div>


            {{-- Growth Charts --}}
            <a href="{{ route('growth-monitoring.index') }}"
               class="nav-item">

                <span class="nav-icon">⌁</span>

                <span>Growth Charts</span>

            </a>


            {{-- IoT Devices --}}
            <a href="#"
               class="nav-item">

                <span class="nav-icon">◈</span>

                <span>IoT Devices</span>

            </a>


            {{-- Reports --}}
            <a href="{{ route('reports.index') }}"
               class="nav-item">

                <span class="nav-icon">▤</span>

                <span>Reports</span>

            </a>


            {{-- =================================================
                 ADMINISTRATION
            ================================================== --}}

            @if(auth()->user()->role === 'administrator')

                <div class="nav-title">
                    Administration
                </div>

                <a href="{{ route('admin.users') }}"
                   class="nav-item admin-nav">

                    <span class="nav-icon">♙</span>

                    <span>User Management</span>

                </a>

            @endif

        </nav>


        {{-- =====================================================
             SIDEBAR USER + LOGOUT
        ====================================================== --}}

        <div class="sidebar-footer">

            <div class="system-user">

                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>

                <div class="user-info">

                    <strong>
                        {{ auth()->user()->name ?? 'Administrator' }}
                    </strong>

                    <span>
                        {{ auth()->user()->role === 'administrator'
                            ? 'System Administrator'
                            : (auth()->user()->position ?? 'System User') }}
                    </span>

                </div>

            </div>


            <form method="POST"
                  action="{{ route('logout') }}"
                  class="logout-form">

                @csrf

                <button type="submit"
                        class="logout-btn">

                    <span class="logout-icon">↪</span>

                    Logout

                </button>

            </form>

        </div>

    </aside>


    {{-- =========================================================
         MAIN AREA
    ========================================================== --}}

    <div class="main">


        {{-- =====================================================
             TOP BAR
        ====================================================== --}}

        <header class="topbar">

            <div class="topbar-title">

                <h2>
                    Dashboard
                </h2>

                <p>
                    Child health data monitoring and management
                </p>

            </div>


            <div class="topbar-right">

                <div class="system-status">

                    <span class="status-dot"></span>

                    System Online

                </div>


                <div class="date-display">
                    {{ date('d M Y') }}
                </div>

            </div>

        </header>


        {{-- =====================================================
             CONTENT
        ====================================================== --}}

        <main class="content">


            {{-- BREADCRUMB --}}

            <div class="breadcrumb">

                Home /

                <strong>
                    Dashboard
                </strong>

            </div>


            {{-- =================================================
                 PAGE HEADER
            ================================================== --}}

            <div class="page-header">

                <div>

                    <h1>
                        Dashboard
                    </h1>

                    <p>
                        Overview of child registration and monitoring activities.
                    </p>

                </div>


                <a href="{{ route('children.create') }}"
                   class="register-btn">

                    + Register New Child

                </a>

            </div>


            {{-- =================================================
                 ADMINISTRATOR SECTION
            ================================================== --}}

            @if(auth()->user()->role === 'administrator')

                {{-- WELCOME --}}
                <div class="admin-welcome">

                    <div>

                        <h3>
                            Welcome, {{ auth()->user()->name }}
                        </h3>

                        <p>
                            You are signed in as the System Administrator.
                            Review user registrations and monitor the overall
                            child health monitoring system.
                        </p>

                    </div>


                    <div class="admin-welcome-badge">
                        SYSTEM ADMINISTRATOR
                    </div>

                </div>


                {{-- USER MANAGEMENT TITLE --}}
                <div class="section-title">

                    <h3>
                        User Management Overview
                    </h3>

                    <span>
                        Current system access
                    </span>

                </div>


                {{-- USER STATISTICS --}}
                <div class="stats">

                    {{-- TOTAL USERS --}}
                    <div class="stat-card">

                        <div class="stat-top">

                            <span class="stat-label">
                                Total Users
                            </span>

                            <div class="stat-icon">
                                U
                            </div>

                        </div>

                        <div class="stat-number">
                            {{ $totalUsers }}
                        </div>

                        <div class="stat-description">
                            Registered system users
                        </div>

                    </div>


                    {{-- PENDING USERS --}}
                    <div class="stat-card stat-warning">

                        <div class="stat-top">

                            <span class="stat-label">
                                Pending Approvals
                            </span>

                            <div class="stat-icon">
                                !
                            </div>

                        </div>

                        <div class="stat-number">
                            {{ $pendingUsers }}
                        </div>

                        <div class="stat-description">
                            Accounts awaiting review
                        </div>

                    </div>


                    {{-- APPROVED USERS --}}
                    <div class="stat-card stat-success">

                        <div class="stat-top">

                            <span class="stat-label">
                                Approved Users
                            </span>

                            <div class="stat-icon">
                                ✓
                            </div>

                        </div>

                        <div class="stat-number">
                            {{ $approvedUsers }}
                        </div>

                        <div class="stat-description">
                            Users with system access
                        </div>

                    </div>


                    {{-- SUSPENDED USERS --}}
                    <div class="stat-card stat-danger">

                        <div class="stat-top">

                            <span class="stat-label">
                                Suspended Users
                            </span>

                            <div class="stat-icon">
                                S
                            </div>

                        </div>

                        <div class="stat-number">
                            {{ $suspendedUsers }}
                        </div>

                        <div class="stat-description">
                            Restricted system accounts
                        </div>

                    </div>

                </div>


                {{-- =================================================
                     PENDING APPROVALS
                ================================================== --}}

                <div class="approval-panel">

                    <div class="approval-header">

                        <div>

                            <h3>
                                Pending User Registrations
                            </h3>

                            <p>
                                Review accounts waiting for administrator approval.
                            </p>

                        </div>


                        <a href="{{ route('admin.users') }}"
                           class="review-btn">

                            Review Users →

                        </a>

                    </div>


                    @if($pendingUsers > 0)

                        <div style="overflow-x:auto;">

                            <table class="pending-table">

                                <thead>

                                    <tr>

                                        <th>
                                            User
                                        </th>

                                        <th>
                                            Position
                                        </th>

                                        <th>
                                            Registered
                                        </th>

                                        <th>
                                            Status
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach(
                                        $recentUsers->where('status', 'pending')
                                        as $user
                                    )

                                        <tr>

                                            <td>

                                                <span class="pending-user-name">
                                                    {{ $user->name }}
                                                </span>

                                            </td>


                                            <td>
                                                {{ $user->position ?? '—' }}
                                            </td>


                                            <td>
                                                {{ $user->created_at->format('d M Y') }}
                                            </td>


                                            <td>

                                                <span class="pending-status">
                                                    Pending
                                                </span>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="approval-empty">

                            ✓ There are currently no user accounts waiting for approval.

                        </div>

                    @endif

                </div>

            @endif


            {{-- =================================================
                 CHILD HEALTH MONITORING
            ================================================== --}}

            <div class="section-title">

                <h3>
                    Child Health Monitoring Overview
                </h3>

                <span>
                    Current child records
                </span>

            </div>


            {{-- CHILD STATISTICS --}}
            <div class="stats">

                {{-- TOTAL CHILDREN --}}
                <div class="stat-card">

                    <div class="stat-top">

                        <span class="stat-label">
                            Total Children
                        </span>

                        <div class="stat-icon">
                            C
                        </div>

                    </div>

                    <div class="stat-number">
                        {{ $totalChildren }}
                    </div>

                    <div class="stat-description">
                        Registered in the system
                    </div>

                </div>


                {{-- MALE --}}
                <div class="stat-card">

                    <div class="stat-top">

                        <span class="stat-label">
                            Male Children
                        </span>

                        <div class="stat-icon">
                            M
                        </div>

                    </div>

                    <div class="stat-number">
                        {{ $maleChildren }}
                    </div>

                    <div class="stat-description">
                        Registered male children
                    </div>

                </div>


                {{-- FEMALE --}}
                <div class="stat-card">

                    <div class="stat-top">

                        <span class="stat-label">
                            Female Children
                        </span>

                        <div class="stat-icon">
                            F
                        </div>

                    </div>

                    <div class="stat-number">
                        {{ $femaleChildren }}
                    </div>

                    <div class="stat-description">
                        Registered female children
                    </div>

                </div>


                {{-- WEIGHT RECORDS --}}
                <div class="stat-card">

                    <div class="stat-top">

                        <span class="stat-label">
                            Weight Records
                        </span>

                        <div class="stat-icon">
                            W
                        </div>

                    </div>

                    <div class="stat-number">
                        0
                    </div>

                    <div class="stat-description">
                        Measurements recorded
                    </div>

                </div>

            </div>


            {{-- =================================================
                 LOWER DASHBOARD
            ================================================== --}}

            <div class="dashboard-grid">


                {{-- =================================================
                     RECENT CHILDREN
                ================================================== --}}

                <div class="panel">

                    <div class="panel-header">

                        <h3>
                            Recently Registered Children
                        </h3>

                        <span>
                            Latest 5
                        </span>

                    </div>


                    <div class="panel-body">

                        @if($recentChildren->count() > 0)

                            <div style="overflow-x:auto;">

                                <table class="dashboard-table">

                                    <thead>

                                        <tr>

                                            <th>
                                                Child
                                            </th>

                                            <th>
                                                Sex
                                            </th>

                                            <th>
                                                Date of Birth
                                            </th>

                                            <th>
                                                Registered
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        @foreach($recentChildren as $child)

                                            <tr>

                                                <td>

                                                    <span class="child-name">

                                                        {{ $child->first_name }}

                                                        @if($child->last_name)
                                                            {{ $child->last_name }}
                                                        @endif

                                                    </span>

                                                </td>


                                                <td>

                                                    <span class="gender">
                                                        {{ $child->sex }}
                                                    </span>

                                                </td>


                                                <td>

                                                    {{ \Carbon\Carbon::parse(
                                                        $child->date_of_birth
                                                    )->format('d M Y') }}

                                                </td>


                                                <td>

                                                    {{ $child->created_at->format('d M Y') }}

                                                </td>

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        @else

                            <div class="empty-state">

                                <strong>
                                    No children registered yet
                                </strong>

                                Start by registering the first child in the system.

                            </div>

                        @endif

                    </div>

                </div>


                {{-- =================================================
                     CHILD DISTRIBUTION
                ================================================== --}}

                <div class="panel">

                    <div class="panel-header">

                        <h3>
                            Child Distribution
                        </h3>

                        <span>
                            By sex
                        </span>

                    </div>


                    <div class="panel-body">

                        @php

                            $total = $totalChildren;

                            $malePercentage = $total > 0
                                ? round(($maleChildren / $total) * 100)
                                : 0;

                            $femalePercentage = $total > 0
                                ? round(($femaleChildren / $total) * 100)
                                : 0;

                        @endphp


                        {{-- MALE --}}
                        <div class="gender-item">

                            <div class="gender-label">

                                <span>
                                    Male
                                </span>

                                <span>
                                    {{ $maleChildren }}
                                    ({{ $malePercentage }}%)
                                </span>

                            </div>


                            <div class="progress">

                                <div class="progress-bar"
                                     style="width: {{ $malePercentage }}%;">
                                </div>

                            </div>

                        </div>


                        {{-- FEMALE --}}
                        <div class="gender-item">

                            <div class="gender-label">

                                <span>
                                    Female
                                </span>

                                <span>
                                    {{ $femaleChildren }}
                                    ({{ $femalePercentage }}%)
                                </span>

                            </div>


                            <div class="progress">

                                <div class="progress-bar"
                                     style="width: {{ $femalePercentage }}%;">
                                </div>

                            </div>

                        </div>


                        {{-- QUICK ACTIONS --}}
                        <div class="quick-actions">

                            <a href="{{ route('children.create') }}"
                               class="quick-action">

                                <div class="quick-action-icon">
                                    +
                                </div>

                                <span>
                                    Register Child
                                </span>

                            </a>


                            <a href="{{ route('growth-monitoring.index') }}"
                               class="quick-action">

                                <div class="quick-action-icon">
                                    ↗
                                </div>

                                <span>
                                    Weight Records
                                </span>

                            </a>


                            <a href="{{ route('reports.index') }}"
                               class="quick-action">

                                <div class="quick-action-icon">
                                    ▤
                                </div>

                                <span>
                                    View Reports
                                </span>

                            </a>


                            @if(auth()->user()->role === 'administrator')

                                <a href="{{ route('admin.users') }}"
                                   class="quick-action">

                                    <div class="quick-action-icon">
                                        ♙
                                    </div>

                                    <span>
                                        Manage Users
                                    </span>

                                </a>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 FOOTER
            ================================================== --}}

            <div class="footer">

                Child Health and Weight Monitoring System

                &nbsp;•&nbsp;

                Health Data Collection Platform

                &nbsp;•&nbsp;

                {{ date('Y') }}

            </div>


        </main>

    </div>

</body>

</html>