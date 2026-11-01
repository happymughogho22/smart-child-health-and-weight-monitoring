<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>
        Child Health Monitoring Report
    </title>

</head>


<body
    style="
        margin:0;
        padding:0;
        background:#f1f5f9;
        font-family:Arial, Helvetica, sans-serif;
        color:#334155;
    "
>

<div
    style="
        max-width:650px;
        margin:30px auto;
        background:#ffffff;
        border:1px solid #e2e8f0;
        border-radius:8px;
        overflow:hidden;
    "
>


    <div
        style="
            background:#123b6d;
            color:#ffffff;
            padding:25px;
        "
    >

        <h2
            style="
                margin:0;
                font-size:20px;
            "
        >
            Child Health Monitoring Report
        </h2>

    </div>


    <div
        style="
            padding:25px;
        "
    >

        <p>
            Dear Guardian,
        </p>


        <p>

            Please find attached the health monitoring report
            for

            <strong>
                {{ $child->first_name }}
                {{ $child->last_name }}
            </strong>.

        </p>


        <p>

            The report contains the child's recorded growth
            measurements, monitoring history, growth assessment
            information and recommendations.

        </p>


        <div
            style="
                background:#f8fafc;
                border:1px solid #e2e8f0;
                border-radius:6px;
                padding:15px;
                margin:20px 0;
            "
        >

            <strong>
                Child ID:
            </strong>

            {{ $child->child_id }}

            <br><br>

            <strong>
                Monitoring records:
            </strong>

            {{ $reportData['measurementData']->count() }}

        </div>


        <p>

            Please discuss the report and any flagged findings
            with an appropriate qualified health worker.

        </p>


        <p>

            This report is intended for growth monitoring and
            screening support and should not be treated as a
            medical diagnosis.

        </p>


        <p>
            Kind regards,
            <br>
            Child Weight Monitoring System
        </p>

    </div>

</div>

</body>

</html>