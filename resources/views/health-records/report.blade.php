<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>
Child Health Report
</title>

<style>

body {
    font-family: DejaVu Sans, Arial, sans-serif;
    color:#1e293b;
    font-size:10px;
}

.header {
    border-bottom:3px solid #123b6d;
    padding-bottom:15px;
    margin-bottom:20px;
}

.title {
    color:#123b6d;
    font-size:20px;
    font-weight:bold;
}

.subtitle {
    color:#64748b;
    margin-top:4px;
}

.section {
    margin-top:18px;
    margin-bottom:18px;
}

.section-title {
    background:#123b6d;
    color:white;
    padding:8px;
    font-size:11px;
    font-weight:bold;
}

.info-table {
    width:100%;
    border-collapse:collapse;
}

.info-table td {
    border:1px solid #e2e8f0;
    padding:7px;
}

.label {
    background:#f8fafc;
    font-weight:bold;
    width:30%;
}

.records {
    width:100%;
    border-collapse:collapse;
}

.records th {
    background:#123b6d;
    color:white;
    padding:7px;
    text-align:left;
    font-size:8px;
}

.records td {
    border:1px solid #e2e8f0;
    padding:6px;
    font-size:8px;
}

.box {
    border:1px solid #e2e8f0;
    background:#f8fafc;
    padding:10px;
    line-height:1.6;
}

.recommendation {
    background:#ecfdf3;
    border:1px solid #bbf7d0;
    padding:10px;
    line-height:1.6;
}

.assessment {
    background:#fff7ed;
    border:1px solid #fed7aa;
    padding:10px;
    line-height:1.6;
}

.footer {
    margin-top:30px;
    border-top:1px solid #e2e8f0;
    padding-top:10px;
    color:#64748b;
    font-size:8px;
}

</style>

</head>

<body>


<div class="header">

<div class="title">
CHILD HEALTH & GROWTH MONITORING REPORT
</div>

<div class="subtitle">
Child Weight Monitoring System
</div>

</div>


<div class="section">

<div class="section-title">
CHILD INFORMATION
</div>

<table class="info-table">

<tr>

<td class="label">
Child ID
</td>

<td>
{{ $child->child_id }}
</td>

</tr>

<tr>

<td class="label">
Full Name
</td>

<td>
{{ $child->first_name }}
{{ $child->last_name }}
</td>

</tr>

<tr>

<td class="label">
Sex
</td>

<td>
{{ $child->sex }}
</td>

</tr>

<tr>

<td class="label">
Date of Birth
</td>

<td>
{{ $child->date_of_birth->format('d M Y') }}
</td>

</tr>

<tr>

<td class="label">
Guardian
</td>

<td>
{{ $child->guardian_name }}
</td>

</tr>

<tr>

<td class="label">
Guardian Phone
</td>

<td>
{{ $child->guardian_phone ?: 'Not recorded' }}
</td>

</tr>

</table>

</div>


<div class="section">

<div class="section-title">
LATEST ASSESSMENT
</div>

<table class="info-table">

<tr>

<td class="label">
Latest Visit
</td>

<td>

{{ $latestRecord
    ? $latestRecord->visit_date->format('d M Y')
    : 'No visit recorded' }}

</td>

</tr>

<tr>

<td class="label">
Latest Weight
</td>

<td>

{{ $latestRecord && $latestRecord->weight
    ? $latestRecord->weight.' kg'
    : 'Not recorded' }}

</td>

</tr>

<tr>

<td class="label">
Latest Height / Length
</td>

<td>

{{ $latestRecord && $latestRecord->height
    ? $latestRecord->height.' cm'
    : 'Not recorded' }}

</td>

</tr>

<tr>

<td class="label">
MUAC
</td>

<td>

{{ $latestRecord && $latestRecord->muac
    ? $latestRecord->muac.' cm'
    : 'Not recorded' }}

</td>

</tr>

<tr>

<td class="label">
Nutrition Status
</td>

<td>

{{ $latestRecord
    ? $latestRecord->nutrition_status
    : 'Not assessed' }}

</td>

</tr>

<tr>

<td class="label">
Growth Status
</td>

<td>

{{ $latestRecord
    ? $latestRecord->growth_status
    : 'Not assessed' }}

</td>

</tr>

</table>

</div>


<div class="section">

<div class="section-title">
CLINICAL REMARKS
</div>

<div class="box">

{{ $latestRecord
    ? ($latestRecord->clinical_remarks ?: 'No clinical remarks recorded.')
    : 'No clinical visit has been recorded.' }}

</div>

</div>


<div class="section">

<div class="section-title">
MONITORING ASSESSMENT
</div>

<div class="assessment">

{{ $latestRecord
    ? $latestRecord->risk_assessment
    : 'No automated monitoring assessment available.' }}

</div>

</div>


<div class="section">

<div class="section-title">
RECOMMENDATIONS FOR PARENT / GUARDIAN
</div>

<div class="recommendation">

{{ $latestRecord
    ? ($latestRecord->recommendations ?: 'No recommendations recorded.')
    : 'No recommendations available.' }}

</div>

</div>


<div class="section">

<div class="section-title">
HEALTH RECORD HISTORY
</div>

<table class="records">

<thead>

<tr>

<th>
Date
</th>

<th>
Facility
</th>

<th>
Weight
</th>

<th>
Height
</th>

<th>
MUAC
</th>

<th>
Nutrition
</th>

<th>
Remarks
</th>

</tr>

</thead>

<tbody>

@forelse($healthRecords as $record)

<tr>

<td>
{{ $record->visit_date->format('d/m/Y') }}
</td>

<td>
{{ $record->health_facility ?: '—' }}
</td>

<td>
{{ $record->weight ? $record->weight.' kg' : '—' }}
</td>

<td>
{{ $record->height ? $record->height.' cm' : '—' }}
</td>

<td>
{{ $record->muac ? $record->muac.' cm' : '—' }}
</td>

<td>
{{ $record->nutrition_status ?: '—' }}
</td>

<td>
{{ $record->clinical_remarks ?: '—' }}
</td>

</tr>

@empty

<tr>

<td colspan="7">
No health records available.
</td>

</tr>

@endforelse

</tbody>

</table>

</div>


<div class="section">

<div class="section-title">
IMPORTANT NOTICE
</div>

<div class="box">

This report is generated from information recorded in the Child Weight
Monitoring System. Automated assessments are intended to support health
monitoring and do not replace professional medical examination, diagnosis,
or treatment. Parents and guardians should follow the advice of qualified
health professionals.

</div>

</div>


<div class="footer">

Generated by Child Weight Monitoring System

<br>

Report generated:
{{ now()->format('d M Y H:i') }}

</div>


</body>

</html>