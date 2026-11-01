<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Edit Health Record |
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
            border-bottom: 1px solid rgba(255,255,255,.1);
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
            opacity: .65;
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
            opacity: .5;
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
            color: rgba(255,255,255,.72);
            font-size: 13px;
            text-decoration: none;
        }


        .nav-item:hover {
            background: rgba(255,255,255,.08);
            color: white;
        }


        .nav-item.active {
            background: rgba(255,255,255,.13);
            color: white;
        }


        .nav-icon {
            width: 19px;
            text-align: center;
        }


        .sidebar-footer {
            padding: 17px;
            border-top: 1px solid rgba(255,255,255,.1);
        }


        .system-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }


        .user-avatar {
            width: 34px;
            height: 34px;
            background: rgba(255,255,255,.15);
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
            opacity: .55;
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


        /* HEADER */

        .page-header {
            background: white;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 22px 24px;
            margin-bottom: 20px;
        }


        .page-header h1 {
            color: var(--primary);
            font-size: 22px;
            margin-bottom: 5px;
        }


        .page-header p {
            color: var(--muted);
            font-size: 11px;
        }


        .child-tag {
            display: inline-block;
            margin-top: 10px;
            background: var(--primary-light);
            color: var(--primary);
            padding: 5px 9px;
            border-radius: 5px;
            font-size: 10px;
            font-weight: 700;
        }


        /* CARD */

        .card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 10px;
            margin-bottom: 20px;
            overflow: hidden;
        }


        .card-header {
            padding: 17px 20px;
            border-bottom: 1px solid var(--border);
        }


        .card-header h3 {
            color: var(--primary);
            font-size: 13px;
            font-weight: 700;
        }


        .card-header p {
            color: var(--muted);
            font-size: 10px;
            margin-top: 4px;
        }


        .card-body {
            padding: 22px;
        }


        /* FORM */

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px 20px;
        }


        .form-group {
            display: flex;
            flex-direction: column;
        }


        .form-group.full {
            grid-column: 1 / -1;
        }


        label {
            font-size: 11px;
            font-weight: 650;
            color: #475569;
            margin-bottom: 7px;
        }


        .required {
            color: var(--danger);
        }


        input,
        textarea,
        select {
            width: 100%;
            border: 1px solid #d9e1ea;
            border-radius: 6px;
            padding: 10px 11px;
            font-family: inherit;
            font-size: 11px;
            color: var(--text);
            background: white;
            outline: none;
        }


        input:focus,
        textarea:focus,
        select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(18,59,109,.08);
        }


        textarea {
            min-height: 100px;
            resize: vertical;
        }


        .help-text {
            font-size: 9px;
            color: #94a3b8;
            margin-top: 5px;
        }


        .info-box {
            grid-column: 1 / -1;
            background: var(--primary-light);
            border: 1px solid #d7e5f5;
            border-radius: 7px;
            padding: 11px 13px;
            color: #486581;
            font-size: 10px;
            line-height: 1.5;
        }


        .info-box strong {
            color: var(--primary);
        }


        .error {
            color: var(--danger);
            font-size: 10px;
            margin-top: 5px;
        }


        /* ACTIONS */

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 9px;
            padding-top: 5px;
        }


        .btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 6px;
            border: none;
            text-decoration: none;
            font-family: inherit;
            font-size: 11px;
            font-weight: 650;
            cursor: pointer;
        }


        .btn-primary {
            background: var(--primary);
            color: white;
        }


        .btn-secondary {
            background: #eef2f6;
            color: #475569;
        }


        .btn-primary:hover {
            background: var(--primary-dark);
        }


        .btn-secondary:hover {
            background: #e2e8f0;
        }


        /* RESPONSIVE */

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

            .form-group.full,
            .info-box {
                grid-column: auto;
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

            <span class="nav-icon">
                ▦
            </span>

            Dashboard

        </a>


        <a href="{{ route('children.create') }}"
           class="nav-item">

            <span class="nav-icon">
                +
            </span>

            Register Child

        </a>


        <a href="{{ route('children.index') }}"
           class="nav-item">

            <span class="nav-icon">
                ○
            </span>

            Children

        </a>


        <a href="{{ route('health-records.index', $child) }}"
           class="nav-item active">

            <span class="nav-icon">
                ⌁
            </span>

            Health Records

        </a>


        <br>


        <div class="nav-title">
            Monitoring
        </div>


        <a href="#"
           class="nav-item">

            <span class="nav-icon">
                ↗
            </span>

            Growth Charts

        </a>


        <a href="#"
           class="nav-item">

            <span class="nav-icon">
                ◈
            </span>

            IoT Devices

        </a>


        <a href="#"
           class="nav-item">

            <span class="nav-icon">
                ▤
            </span>

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


    <!-- TOPBAR -->

    <header class="topbar">


        <div>

            <h2>
                Edit Health Record
            </h2>

            <p>
                Update the child's clinical and growth monitoring information
            </p>

        </div>


        <div class="system-status">

            <div class="status-dot"></div>

            System Online

        </div>


    </header>



    <!-- CONTENT -->

    <main class="content">


        <!-- BREADCRUMB -->

        <div class="breadcrumb">

            <a href="{{ route('dashboard') }}">
                Home
            </a>

            &nbsp; / &nbsp;

            <a href="{{ route('children.index') }}">
                Children
            </a>

            &nbsp; / &nbsp;

            <a href="{{ route('health-records.index', $child) }}">
                Health Records
            </a>

            &nbsp; / &nbsp;

            Edit Record

        </div>



        <!-- HEADER -->

        <section class="page-header">


            <h1>
                Edit Health Record
            </h1>


            <p>
                Update the information recorded during this child's health visit.
            </p>


            <span class="child-tag">

                {{ $child->first_name }}

                {{ $child->last_name }}

                &nbsp; • &nbsp;

                ID: {{ $child->child_id }}

            </span>


        </section>



        <!-- FORM -->

        <form
            action="{{ route('health-records.update', [$child, $healthRecord]) }}"
            method="POST"
        >

            @csrf

            @method('PUT')



            <!-- VISIT INFORMATION -->

            <section class="card">


                <div class="card-header">

                    <h3>
                        Visit Information
                    </h3>

                    <p>
                        Information about the health facility and health worker.
                    </p>

                </div>


                <div class="card-body">


                    <div class="form-grid">


                        <div class="form-group">

                            <label>
                                Visit Date
                                <span class="required">*</span>
                            </label>


                            <input
                                type="date"
                                name="visit_date"
                                value="{{ old('visit_date', $healthRecord->visit_date ? \Carbon\Carbon::parse($healthRecord->visit_date)->format('Y-m-d') : '') }}"
                                required
                            >


                            @error('visit_date')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>



                        <div class="form-group">

                            <label>
                                Health Facility
                            </label>


                            <input
                                type="text"
                                name="facility_name"
                                value="{{ old('facility_name', $healthRecord->facility_name) }}"
                                placeholder="e.g. QECH"
                            >


                            @error('facility_name')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>



                        <div class="form-group">

                            <label>
                                Health Worker
                            </label>


                            <input
                                type="text"
                                name="health_worker_name"
                                value="{{ old('health_worker_name', $healthRecord->health_worker_name) }}"
                                placeholder="e.g. Dr. Test"
                            >


                            @error('health_worker_name')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                    </div>

                </div>

            </section>



            <!-- MEASUREMENTS -->

            <section class="card">


                <div class="card-header">

                    <h3>
                        Growth Measurements
                    </h3>

                    <p>
                        Update the child's physical measurements recorded during the visit.
                    </p>

                </div>


                <div class="card-body">


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
                                value="{{ old('weight', $healthRecord->weight) }}"
                                placeholder="e.g. 12.50"
                            >


                            <div class="help-text">
                                Enter the child's weight in kilograms.
                            </div>


                            @error('weight')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>



                        <!-- MEASUREMENT TYPE -->

                        <div class="form-group">

                            <label for="measurement_type">
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
                                    {{ old('measurement_type', $healthRecord->measurement_type) === 'length' ? 'selected' : '' }}
                                >
                                    Length — measured lying down
                                </option>


                                <option
                                    value="height"
                                    {{ old('measurement_type', $healthRecord->measurement_type) === 'height' ? 'selected' : '' }}
                                >
                                    Height — measured standing
                                </option>

                            </select>


                            <div class="help-text">
                                Select Length when the child is measured lying down, or Height when measured standing.
                            </div>


                            @error('measurement_type')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>



                        <!-- LENGTH / HEIGHT -->

                        <div class="form-group">

                            <label for="height">
                                Length / Height (cm)
                            </label>


                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="height"
                                id="height"
                                value="{{ old('height', $healthRecord->height) }}"
                                placeholder="e.g. 85.27"
                            >


                            <div class="help-text">
                                Enter the child's measured length or height in centimetres.
                            </div>


                            @error('height')

                                <div class="error">
                                    {{ $message }}
                                </div>

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
                                value="{{ old('muac', $healthRecord->muac) }}"
                                placeholder="e.g. 13.00"
                            >


                            <div class="help-text">
                                Mid-Upper Arm Circumference.
                            </div>


                            @error('muac')

                                <div class="error">
                                    {{ $message }}
                                </div>

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
                                min="25"
                                max="45"
                                name="temperature"
                                value="{{ old('temperature', $healthRecord->temperature) }}"
                                placeholder="e.g. 36.7"
                            >


                            @error('temperature')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>



                        <!-- INFORMATION BOX -->

                        <div class="info-box">

                            <strong>Growth measurement guidance:</strong>

                            For children under 24 months, WHO growth standards use
                            <strong>recumbent length</strong> where applicable.

                            For children 24 months and older, WHO standards generally use
                            <strong>standing height</strong>.

                            The selected measurement type is stored with the health record
                            so the system can apply the appropriate growth reference later.

                        </div>


                    </div>

                </div>

            </section>



            <!-- CLINICAL INFORMATION -->

            <section class="card">


                <div class="card-header">

                    <h3>
                        Clinical Information
                    </h3>

                    <p>
                        Update observations and professional clinical remarks.
                    </p>

                </div>


                <div class="card-body">


                    <div class="form-grid">


                        <div class="form-group full">

                            <label>
                                Clinical Observations
                            </label>


                            <textarea
                                name="clinical_observations"
                                placeholder="Enter clinical observations..."
                            >{{ old('clinical_observations', $healthRecord->clinical_observations) }}</textarea>


                            @error('clinical_observations')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>



                        <div class="form-group full">

                            <label>
                                Doctor's Remarks
                            </label>


                            <textarea
                                name="doctor_remarks"
                                placeholder="Enter doctor's remarks..."
                            >{{ old('doctor_remarks', $healthRecord->doctor_remarks) }}</textarea>


                            @error('doctor_remarks')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>



                        <div class="form-group full">

                            <label>
                                Recommendations
                            </label>


                            <textarea
                                name="recommendations"
                                placeholder="Enter recommendations..."
                            >{{ old('recommendations', $healthRecord->recommendations) }}</textarea>


                            @error('recommendations')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                    </div>

                </div>

            </section>



            <!-- FOLLOW UP -->

            <section class="card">


                <div class="card-header">

                    <h3>
                        Follow-up
                    </h3>

                    <p>
                        Schedule the child's next monitoring visit if required.
                    </p>

                </div>


                <div class="card-body">


                    <div class="form-grid">


                        <div class="form-group">

                            <label>
                                Follow-up Date
                            </label>


                            <input
                                type="date"
                                name="follow_up_date"
                                value="{{ old('follow_up_date', $healthRecord->follow_up_date ? \Carbon\Carbon::parse($healthRecord->follow_up_date)->format('Y-m-d') : '') }}"
                            >


                            @error('follow_up_date')

                                <div class="error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                    </div>

                </div>

            </section>



            <!-- ACTIONS -->

            <div class="form-actions">


                <a
                    href="{{ route('health-records.show', [$child, $healthRecord]) }}"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Save Changes
                </button>


            </div>


        </form>


    </main>

</div>


</body>

</html>