```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>
        Child Growth Monitoring Report
    </title>
</head>

<body
    style="
        margin:0;
        padding:0;
        background:#f4f7fb;
        font-family:Arial, Helvetica, sans-serif;
        color:#1f2937;
    "
>

<table
    width="100%"
    cellpadding="0"
    cellspacing="0"
    style="padding:30px 15px;"
>

<tr>

<td align="center">

<table
    width="600"
    cellpadding="0"
    cellspacing="0"
    style="
        max-width:600px;
        background:#ffffff;
        border-radius:12px;
        overflow:hidden;
        border:1px solid #e5e7eb;
    "
>

<tr>

<td
    style="
        background:#123b6d;
        padding:28px;
        color:#ffffff;
    "
>

<h1
    style="
        margin:0;
        font-size:24px;
    "
>
    Child Growth Monitoring System
</h1>

<p
    style="
        margin:8px 0 0;
        font-size:14px;
        opacity:.9;
    "
>
    Growth monitoring report
</p>

</td>

</tr>


<tr>

<td style="padding:30px;">

<p>
    Dear
    {{ $child->guardian_name ?: 'Parent/Guardian' }},
</p>


<p>
    Please find attached the growth monitoring report
    for
    <strong>
        {{ $child->first_name }}
        {{ $child->last_name }}
    </strong>.
</p>


<p>
    The report contains the child's recorded health
    measurements, growth trends, screening results,
    follow-up information and system-generated
    recommendations.
</p>


<div
    style="
        background:#f8fafc;
        border-left:4px solid #123b6d;
        padding:15px;
        margin:20px 0;
    "
>

<strong>
    Important
</strong>

<p
    style="
        margin:8px 0 0;
        font-size:13px;
        line-height:1.6;
    "
>
    This report is intended for growth monitoring and
    screening purposes. It does not replace professional
    medical assessment or diagnosis.
</p>

</div>


<p>
    Please keep this report for your records and discuss
    any concerns with the appropriate health professional.
</p>


<p
    style="
        margin-top:30px;
    "
>
    Kind regards,
    <br>

    <strong>
        Child Weight Monitoring System
    </strong>
</p>

</td>

</tr>

</table>

</td>

</tr>

</table>

</body>
</html>
```
