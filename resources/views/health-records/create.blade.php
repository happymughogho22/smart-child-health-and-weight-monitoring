<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Add Health Record |
        {{ $child->first_name }} {{ $child->last_name }}
    </title>

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
            --danger: #b91c1c;
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

        .nav-item:hover,
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

            color: var(--success);

            font-size: 11px;

            font-weight: 600;
        }

        .status-dot {
            width: 7px;
            height: 7px;

            border-radius: 50%;

            background: var(--success);
        }

        /* CONTENT */

        .content {
            padding: 30px 32px 50px;

            max-width: 1150px;
        }

        .breadcrumb {
            color: #94a3b8;

            font-size: 11px;

            margin-bottom: 20px;
        }

        .breadcrumb a {
            color: var(--primary);

            text-decoration: none;
        }

        /* CHILD SUMMARY */

        .child-summary {
            background: white;

            border: 1px solid var(--border);

            border-radius: 10px;

            padding: 18px 20px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            margin-bottom: 20px;
        }

        .child-summary-left {
            display: flex;

            align-items: center;

            gap: 14px;
        }

        .child-avatar {
            width: 48px;
            height: 48px;

            border-radius: 50%;

            background: var(--primary-light);

            color: var(--primary);

            display: flex;

            align-items: center;

            justify-content: center;

            font-weight: 700;

            font-size: 18px;
        }

        .child-name {
            font-size: 16px;

            font-weight: 700;

            color: var(--primary);
        }

        .child-meta {
            color: var(--muted);

            font-size: 11px;

            margin-top: 3px;
        }

        .child-id {
            color: var(--primary);

            background: var(--primary-light);

            padding: 5px 9px;

            border-radius: 5px;

            font-size: 10px;

            font-weight: 700;
        }

        /* FORM */

        .form-card {
            background: white;

            border: 1px solid var(--border);

            border-radius: 10px;

            overflow: hidden;
        }

        .section-header {
            padding: 18px 22px;

            border-bottom: 1px solid var(--border);

            background: #fbfcfe;
        }

        .section-header h3 {
            color: var(--primary);

            font-size: 14px;

            margin-bottom: 4px;
        }

        .section-header p {
            color: var(--muted);

            font-size: 11px;
        }

        .form-body {
            padding: 25px;
        }

        .form-section {
            background: #ffffff;

            border: 1px solid #e2e8f0;

            border-radius: 10px;

            padding: 24px;

            margin-bottom: 20px;
        }

        .form-section:last-child {
            margin-bottom: 0;
        }

        .section-title {
            color: #123b6d;

            font-size: 14px;

            font-weight: 700;

            margin-bottom: 6px;
        }

        .section-description {
            color: #64748b;

            font-size: 11px;

            margin-bottom: 20px;
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
            grid-column: 1 / -1;

            margin-top: 18px;
        }

        .form-group label {
            font-size: 11px;

            font-weight: 650;

            color: #334155;

            margin-bottom: 7px;
        }

        .form-group label span {
            color: #dc2626;
        }

        input,
        select,
        textarea {
            width: 100%;

            border: 1px solid #d8e0ea;

            border-radius: 6px;

            padding: 11px 12px;

            font-family: inherit;

            font-size: 12px;

            color: #1e293b;

            background: #ffffff;

            outline: none;

            transition: border-color .2s, box-shadow .2s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #123b6d;

            box-shadow: 0 0 0 3px rgba(18, 59, 109, .08);
        }

        textarea {
            resize: vertical;

            min-height: 100px;

            line-height: 1.5;
        }

        input::placeholder,
        textarea::placeholder {
            color: #94a3b8;
        }

        .help {
            color: #94a3b8;

            font-size: 9px;

            margin-top: 5px;
        }

        .error {
            color: #dc2626;

            font-size: 10px;

            margin-top: 5px;
        }

        /* ACTIONS */

        .form-actions {
            display: flex;

            justify-content: flex-end;

            gap: 10px;

            margin-top: 5px;

            padding-top: 20px;
        }

        .btn {
            display: inline-flex;

            align-items: center;

            justify-content: center;

            border: none;

            cursor: pointer;

            padding: 11px 18px;

            border-radius: 6px;

            text-decoration: none;

            font-size: 11px;

            font-weight: 650;
        }

        .btn-primary {
            background: #123b6d;

            color: white;
        }

        .btn-primary:hover {
            background: #0c2d52;
        }

        .btn-secondary {
            background: #eef2f6;

            color: #475569;
        }

        /* ALERT */

        .alert {
            background: #fff7ed;

            border: 1px solid #fed7aa;

            color: #9a3412;

            border-radius: 7px;

            padding: 12px 15px;

            font-size: 11px;

            margin-bottom: 20px;
        }

        /* MEASUREMENT INFORMATION */

        .measurement-info {
            grid-column: 1 / -1;

            background: #f8fafc;

            border: 1px solid #e2e8f0;

            border-radius: 7px;

            padding: 11px 13px;

            color: #64748b;

            font-size: 10px;

            line-height: 1.5;
        }

        @media(max-width:850px) {

            .sidebar {
                display: none;
            }

            .main {
                margin-left: 0;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: auto;
            }

            .measurement-info {
                grid-column: auto;
            }

            .child-summary {
                flex-direction: column;

                align-items: flex-start;

                gap: 12px;
            }

        }

        @media (max-width: 750px) {

            .form-grid {
                grid-template-columns: 1fr;
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


        <a href="{{ route('dashboard') }}"
           class="nav-item">

            <span class="nav-icon">▦</span>

            Dashboard

        </a>


        <a href="{{ route('children.create') }}"
           class="nav-item">

            <span class="nav-icon">+</span>

            Register Child

        </a>


        <a href="{{ route('children.index') }}"
           class="nav-item active">

            <span class="nav-icon">○</span>

            Children

        </a>


        <a href="{{ route('children.show', $child) }}"
           class="nav-item">

            <span class="nav-icon">●</span>

            Child Profile

        </a>


        <a href="{{ route('health-records.index', $child) }}"
           class="nav-item active">

            <span class="nav-icon">⌁</span>

            Health Records

        </a>


        <br>


        <div class="nav-title">
            Monitoring
        </div>


        <a href="#"
           class="nav-item">

            <span class="nav-icon">⌁</span>

            Growth Charts

        </a>


        <a href="#"
           class="nav-item">

            <span class="nav-icon">◈</span>

            IoT Devices

        </a>


        <a href="#"
           class="nav-item">

            <span class="nav-icon">▤</span>

            Reports

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
                Add Health Record
            </h2>

            <p>
                Record clinical and growth monitoring information
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

            <a href="{{ route('children.show', $child) }}">
                {{ $child->child_id }}
            </a>

            /

            Health Record

        </div>


        <!-- CHILD SUMMARY -->

        <section class="child-summary">

            <div class="child-summary-left">

                <div class="child-avatar">

                    {{ strtoupper(substr($child->first_name, 0, 1)) }}

                </div>

                <div>

                    <div class="child-name">

                        {{ $child->first_name }}
                        {{ $child->last_name }}

                    </div>

                    <div class="child-meta">

                        {{ $child->sex }}

                        &nbsp; • &nbsp;

                        {{ \Carbon\Carbon::parse($child->date_of_birth)->age }}
                        years old

                    </div>

                </div>

            </div>


            <span class="child-id">

                ID: {{ $child->child_id }}

            </span>

        </section>


        @if($errors->any())

            <div class="alert">

                <strong>
                    Please check the information entered.
                </strong>

                @foreach($errors->all() as $error)

                    <div>
                        {{ $error }}
                    </div>

                @endforeach

            </div>

        @endif


        <!-- FORM -->

        <form
            action="{{ route('health-records.store', $child) }}"
            method="POST"
        >

            @csrf


            <!-- VISIT INFORMATION -->

            <div class="form-section">

                <div class="section-title">
                    Visit Information
                </div>

                <div class="form-grid">

                    <div class="form-group">

                        <label>
                            Visit Date
                            <span>*</span>
                        </label>

                        <input
                            type="date"
                            name="visit_date"
                            value="{{ old('visit_date', date('Y-m-d')) }}"
                            max="{{ date('Y-m-d') }}"
                            required
                        >

                        @error('visit_date')

                            <small class="error">
                                {{ $message }}
                            </small>

                        @enderror

                    </div>


                    <div class="form-group">

                        <label>
                            Health Facility
                            <span>*</span>
                        </label>

                        <input
                            type="text"
                            name="facility_name"
                            value="{{ old('facility_name') }}"
                            placeholder="e.g. Queen Elizabeth Central Hospital"
                            required
                        >

                        @error('facility_name')

                            <small class="error">
                                {{ $message }}
                            </small>

                        @enderror

                    </div>


                    <div class="form-group">

                        <label>
                            Health Worker / Doctor
                        </label>

                        <input
                            type="text"
                            name="health_worker_name"
                            value="{{ old('health_worker_name') }}"
                            placeholder="Name of attending health worker"
                        >

                    </div>

                </div>

            </div>


            <!-- GROWTH MEASUREMENTS -->

            <div class="form-section">

                <div class="section-title">
                    Growth & Nutrition Measurements
                </div>

                <div class="section-description">
                    Enter the measurements taken during this visit.
                </div>


                <div class="form-grid">


                    <!-- WEIGHT -->

                    <div class="form-group">

                        <label>
                            Weight (kg)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="weight"
                            value="{{ old('weight') }}"
                            placeholder="e.g. 12.50"
                        >

                        @error('weight')

                            <small class="error">
                                {{ $message }}
                            </small>

                        @enderror

                    </div>


                    <!-- MEASUREMENT TYPE -->

                    <div class="form-group">

                        <label>
                            Measurement Type
                        </label>

                        <select
                            name="measurement_type"
                            id="measurement_type"
                        >

                            <option value="">
                                Select measurement type
                            </option>

                            <option
                                value="length"
                                {{ old('measurement_type') === 'length' ? 'selected' : '' }}
                            >
                                Length — measured lying down
                            </option>

                            <option
                                value="height"
                                {{ old('measurement_type') === 'height' ? 'selected' : '' }}
                            >
                                Height — measured standing
                            </option>

                        </select>

                        <small class="help">
                            Select how the child's body measurement was taken.
                        </small>

                        @error('measurement_type')

                            <small class="error">
                                {{ $message }}
                            </small>

                        @enderror

                    </div>


                    <!-- LENGTH / HEIGHT -->

                    <div class="form-group">

                        <label>
                            Length / Height (cm)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="height"
                            value="{{ old('height') }}"
                            placeholder="e.g. 85.50"
                        >

                        <small class="help">
                            Enter the measured length or standing height in centimetres.
                        </small>

                        @error('height')

                            <small class="error">
                                {{ $message }}
                            </small>

                        @enderror

                    </div>


                    <!-- MUAC -->

                    <div class="form-group">

                        <label>
                            MUAC (cm)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="muac"
                            value="{{ old('muac') }}"
                            placeholder="Optional"
                        >

                        <small class="help">
                            Mid-upper arm circumference.
                        </small>

                        @error('muac')

                            <small class="error">
                                {{ $message }}
                            </small>

                        @enderror

                    </div>


                    <!-- TEMPERATURE -->

                    <div class="form-group">

                        <label>
                            Temperature (°C)
                        </label>

                        <input
                            type="number"
                            step="0.1"
                            name="temperature"
                            value="{{ old('temperature') }}"
                            placeholder="Optional"
                        >

                        @error('temperature')

                            <small class="error">
                                {{ $message }}
                            </small>

                        @enderror

                    </div>


                    <!-- INFORMATION BOX -->

                    <div class="measurement-info">

                        <strong>Measurement guidance:</strong>

                        Choose <strong>Length</strong> when the child is measured
                        lying down. Choose <strong>Height</strong> when the child
                        is measured standing.

                        This information will later allow the system to apply
                        the appropriate growth reference during growth assessment.

                    </div>

                </div>

            </div>


            <!-- CLINICAL INFORMATION -->

            <div class="form-section">

                <div class="section-title">
                    Clinical Assessment
                </div>


                <div class="form-group full">

                    <label>
                        Clinical Observations
                    </label>

                    <textarea
                        name="clinical_observations"
                        rows="4"
                        placeholder="Record relevant clinical observations..."
                    >{{ old('clinical_observations') }}</textarea>

                    @error('clinical_observations')

                        <small class="error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                <div class="form-group full">

                    <label>
                        Doctor's / Health Worker's Remarks
                        <span>*</span>
                    </label>

                    <textarea
                        name="doctor_remarks"
                        rows="5"
                        placeholder="Enter the health worker's assessment or remarks..."
                        required
                    >{{ old('doctor_remarks') }}</textarea>

                    @error('doctor_remarks')

                        <small class="error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>


                <div class="form-group full">

                    <label>
                        Recommendations for Parent / Guardian
                    </label>

                    <textarea
                        name="recommendations"
                        rows="5"
                        placeholder="Enter advice and recommendations given to the parent or guardian..."
                    >{{ old('recommendations') }}</textarea>

                    @error('recommendations')

                        <small class="error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>

            </div>


            <!-- FOLLOW UP -->

            <div class="form-section">

                <div class="section-title">
                    Follow-up
                </div>

                <div class="form-grid">

                    <div class="form-group">

                        <label>
                            Follow-up Date
                        </label>

                        <input
                            type="date"
                            name="follow_up_date"
                            value="{{ old('follow_up_date') }}"
                        >

                        @error('follow_up_date')

                            <small class="error">
                                {{ $message }}
                            </small>

                        @enderror

                    </div>

                </div>

            </div>


            <!-- ACTIONS -->

            <div class="form-actions">

                <a
                    href="{{ route('health-records.index', $child) }}"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Save Health Record
                </button>

            </div>

        </form>


    </main>

</div>

</body>

</html>