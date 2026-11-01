<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register Child | Child Weight Monitoring System</title>

    <style>

        :root {
            --primary: #123b6d;
            --primary-dark: #0c2d52;
            --primary-light: #eaf2fb;
            --accent: #2f80ed;

            --background: #f5f7fb;
            --card: #ffffff;
            --border: #e2e8f0;

            --text: #1e293b;
            --muted: #64748b;
            --danger: #dc2626;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: var(--background);
            color: var(--text);
        }

        /* SIDEBAR */

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;

            width: 245px;
            height: 100vh;

            background: var(--primary);
            color: white;

            display: flex;
            flex-direction: column;
        }

        .logo-area {
            height: 78px;

            display: flex;
            align-items: center;

            padding: 0 22px;

            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .logo {
            width: 40px;
            height: 40px;

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
        }

        .nav-title {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;

            opacity: 0.5;

            padding: 0 12px;
            margin-bottom: 10px;
        }

        .nav-item {
            display: flex;
            align-items: center;

            gap: 12px;

            padding: 12px 13px;

            margin-bottom: 5px;

            border-radius: 7px;

            color: rgba(255,255,255,0.72);

            font-size: 13px;

            text-decoration: none;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.08);
            color: white;
        }

        .nav-item.active {
            background: rgba(255,255,255,0.13);
            color: white;
        }

        .nav-icon {
            width: 19px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 17px;

            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .system-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 34px;
            height: 34px;

            background: rgba(255,255,255,0.15);

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 12px;
            font-weight: bold;
        }

        .user-info strong {
            display: block;
            font-size: 12px;
        }

        .user-info span {
            display: block;

            font-size: 10px;

            opacity: 0.55;

            margin-top: 2px;
        }

        /* MAIN */

        .main {
            margin-left: 245px;
            min-height: 100vh;
        }

        .topbar {
            height: 78px;

            background: white;

            border-bottom: 1px solid var(--border);

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 0 32px;
        }

        .topbar h2 {
            font-size: 17px;
            font-weight: 650;
        }

        .topbar p {
            color: var(--muted);

            font-size: 11px;

            margin-top: 3px;
        }

        .system-status {
            display: flex;
            align-items: center;

            gap: 7px;

            color: #1f9d55;

            font-size: 11px;
            font-weight: 600;
        }

        .status-dot {
            width: 7px;
            height: 7px;

            border-radius: 50%;

            background: #1f9d55;
        }

        /* CONTENT */

        .content {
            padding: 30px 32px 50px;

            max-width: 1100px;
        }

        .breadcrumb {
            color: #94a3b8;

            font-size: 11px;

            margin-bottom: 18px;
        }

        .breadcrumb a {
            color: var(--primary);

            text-decoration: none;
        }

        .breadcrumb strong {
            color: var(--primary);
        }

        .page-header {
            margin-bottom: 25px;
        }

        .page-header h1 {
            font-size: 26px;

            color: var(--primary);

            margin-bottom: 6px;
        }

        .page-header p {
            color: var(--muted);

            font-size: 13px;
        }

        /* FORM CARD */

        .form-card {
            background: white;

            border: 1px solid var(--border);

            border-radius: 10px;

            overflow: hidden;
        }

        .form-section {
            padding: 24px;

            border-bottom: 1px solid var(--border);
        }

        .form-section:last-child {
            border-bottom: none;
        }

        .section-title {
            color: var(--primary);

            font-size: 14px;

            font-weight: 700;

            margin-bottom: 5px;
        }

        .section-description {
            color: var(--muted);

            font-size: 11px;

            margin-bottom: 22px;
        }

        .form-grid {
            display: grid;

            grid-template-columns: repeat(2, 1fr);

            gap: 18px;
        }

        .form-group {
            display: flex;

            flex-direction: column;
        }

        .form-group.full {
            grid-column: span 2;
        }

        label {
            font-size: 11px;

            font-weight: 650;

            color: #334155;

            margin-bottom: 7px;
        }

        .required {
            color: var(--danger);
        }

        input,
        select,
        textarea {
            width: 100%;

            border: 1px solid var(--border);

            border-radius: 6px;

            padding: 11px 12px;

            font-family: inherit;

            font-size: 12px;

            color: var(--text);

            outline: none;

            background: white;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: var(--accent);

            box-shadow: 0 0 0 3px rgba(47,128,237,0.08);
        }

        textarea {
            min-height: 85px;

            resize: vertical;
        }

        .input-help {
            font-size: 9px;

            color: var(--muted);

            margin-top: 5px;
        }

        .error {
            color: var(--danger);

            font-size: 10px;

            margin-top: 5px;
        }

        .general-error {
            background: #fef2f2;

            border: 1px solid #fecaca;

            color: #991b1b;

            border-radius: 7px;

            padding: 12px 15px;

            margin-bottom: 20px;

            font-size: 11px;
        }

        /* ACTIONS */

        .form-actions {
            padding: 20px 24px;

            background: #fafbfc;

            border-top: 1px solid var(--border);

            display: flex;

            justify-content: flex-end;

            gap: 10px;
        }

        .btn {
            padding: 11px 18px;

            border-radius: 6px;

            font-size: 11px;

            font-weight: 650;

            text-decoration: none;

            border: none;

            cursor: pointer;
        }

        .btn-cancel {
            background: #eef2f6;

            color: #475569;
        }

        .btn-primary {
            background: var(--primary);

            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        /* INFO BOX */

        .info-box {
            margin-top: 18px;

            padding: 14px 16px;

            background: var(--primary-light);

            border: 1px solid #d4e4f7;

            border-radius: 7px;

            color: #365574;

            font-size: 10px;

            line-height: 1.6;
        }

        .info-box strong {
            display: block;

            color: var(--primary);

            margin-bottom: 3px;
        }

        @media (max-width: 700px) {

            .sidebar {
                display: none;
            }

            .main {
                margin-left: 0;
            }

            .content {
                padding: 20px 15px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: span 1;
            }

        }

    </style>

</head>

<body>

<!-- SIDEBAR -->

<aside class="sidebar">

    <div class="logo-area">

        <div class="logo">
            CW
        </div>

        <div>

            <div class="logo-title">
                Child Weight
            </div>

            <div class="logo-subtitle">
                Monitoring System
            </div>

        </div>

    </div>


    <nav class="navigation">

        <div class="nav-title">
            Main Menu
        </div>

        <a href="{{ route('dashboard') }}" class="nav-item">

            <span class="nav-icon">▦</span>

            <span>Dashboard</span>

        </a>


        <a href="{{ route('children.create') }}" class="nav-item active">

            <span class="nav-icon">+</span>

            <span>Register Child</span>

        </a>


        <a href="{{ route('children.index') }}" class="nav-item">

            <span class="nav-icon">○</span>

            <span>Children</span>

        </a>


        <a href="#" class="nav-item">

            <span class="nav-icon">↗</span>

            <span>Weight Records</span>

        </a>


        <br>


        <div class="nav-title">
            Monitoring
        </div>


        <a href="#" class="nav-item">

            <span class="nav-icon">⌁</span>

            <span>Growth Charts</span>

        </a>


        <a href="#" class="nav-item">

            <span class="nav-icon">◈</span>

            <span>IoT Devices</span>

        </a>


        <a href="#" class="nav-item">

            <span class="nav-icon">▤</span>

            <span>Reports</span>

        </a>

    </nav>


    <div class="sidebar-footer">

        <div class="system-user">

            <div class="user-avatar">
                A
            </div>

            <div class="user-info">

                <strong>
                    Administrator
                </strong>

                <span>
                    System Administrator
                </span>

            </div>

        </div>

    </div>

</aside>


<!-- MAIN -->

<div class="main">

    <header class="topbar">

        <div>

            <h2>
                Register Child
            </h2>

            <p>
                Add a new child to the monitoring system
            </p>

        </div>


        <div class="system-status">

            <div class="status-dot"></div>

            System Online

        </div>

    </header>


    <main class="content">

        <div class="breadcrumb">

            <a href="{{ route('dashboard') }}">
                Home
            </a>

            /

            <a href="{{ route('children.index') }}">
                Children
            </a>

            /

            <strong>
                Register Child
            </strong>

        </div>


        <div class="page-header">

            <h1>
                Register New Child
            </h1>

            <p>
                Enter the child's basic information and guardian details.
            </p>

        </div>


        @if ($errors->any())

            <div class="general-error">

                <strong>
                    Please correct the following errors:
                </strong>

                <ul style="margin-top: 7px; padding-left: 18px;">

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form
            action="{{ route('children.store') }}"
            method="POST"
        >

            @csrf


            <div class="form-card">


                <!-- CHILD INFORMATION -->

                <section class="form-section">

                    <div class="section-title">
                        Child Information
                    </div>

                    <div class="section-description">
                        Provide the child's identification and demographic information.
                    </div>


                    <div class="form-grid">


                        <div class="form-group">

                            <label>
                                Child ID <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="child_id"
                                value="{{ old('child_id') }}"
                                placeholder="e.g. CWM-0001"
                                required
                            >

                            <div class="input-help">
                                Use a unique identification number for the child.
                            </div>

                            @error('child_id')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="form-group">

                            <label>
                                Sex <span class="required">*</span>
                            </label>

                            <select
                                name="sex"
                                required
                            >

                                <option value="">
                                    Select sex
                                </option>

                                <option
                                    value="Male"
                                    {{ old('sex') == 'Male' ? 'selected' : '' }}
                                >
                                    Male
                                </option>

                                <option
                                    value="Female"
                                    {{ old('sex') == 'Female' ? 'selected' : '' }}
                                >
                                    Female
                                </option>

                            </select>

                            @error('sex')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="form-group">

                            <label>
                                First Name <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="first_name"
                                value="{{ old('first_name') }}"
                                placeholder="Enter first name"
                                required
                            >

                            @error('first_name')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="form-group">

                            <label>
                                Last Name
                            </label>

                            <input
                                type="text"
                                name="last_name"
                                value="{{ old('last_name') }}"
                                placeholder="Enter last name"
                            >

                            @error('last_name')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="form-group">

                            <label>
                                Date of Birth <span class="required">*</span>
                            </label>

                            <input
                                type="date"
                                name="date_of_birth"
                                value="{{ old('date_of_birth') }}"
                                required
                            >

                            @error('date_of_birth')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="form-group">

                            <label>
                                Place of Birth
                            </label>

                            <input
                                type="text"
                                name="place_of_birth"
                                value="{{ old('place_of_birth') }}"
                                placeholder="e.g. Queen Elizabeth Central Hospital"
                            >

                            @error('place_of_birth')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </section>


                <!-- GUARDIAN INFORMATION -->

                <section class="form-section">

                    <div class="section-title">
                        Guardian Information
                    </div>

                    <div class="section-description">
                        Provide information about the parent or guardian responsible for the child.
                    </div>


                    <div class="form-grid">


                        <div class="form-group">

                            <label>
                                Guardian Name <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="guardian_name"
                                value="{{ old('guardian_name') }}"
                                placeholder="Enter parent or guardian name"
                                required
                            >

                            @error('guardian_name')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="form-group">

                            <label>
                                Guardian Phone
                                <div class="form-group">

    <label for="guardian_email">
        Guardian Email
    </label>

    <input
        type="email"
        id="guardian_email"
        name="guardian_email"
        value="{{ old('guardian_email') }}"
        placeholder="guardian@example.com"
    >

    @error('guardian_email')
        <small style="color:#dc2626;">
            {{ $message }}
        </small>
    @enderror

</div>
                            </label>

                            <input
                                type="text"
                                name="guardian_phone"
                                value="{{ old('guardian_phone') }}"
                                placeholder="e.g. 0999 000 000"
                            >

                            @error('guardian_phone')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="form-group full">

                            <label>
                                Address
                            </label>

                            <textarea
                                name="address"
                                placeholder="Enter the child's residential address"
                            >{{ old('address') }}</textarea>

                            @error('address')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </section>


                <!-- INFORMATION -->

                <section class="form-section">

                    <div class="info-box">

                        <strong>
                            Health Data Notice
                        </strong>

                        The information entered here will form part of the child's monitoring
                        record. Additional health and weight measurements will be recorded
                        separately and linked to this child.

                    </div>

                </section>


                <!-- ACTIONS -->

                <div class="form-actions">

                    <a
                        href="{{ route('children.index') }}"
                        class="btn btn-cancel"
                    >
                        Cancel
                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Register Child
                    </button>

                </div>


            </div>

        </form>

    </main>

</div>

</body>

</html>