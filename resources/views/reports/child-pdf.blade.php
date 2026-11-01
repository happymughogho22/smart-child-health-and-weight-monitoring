<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>Child Health Report</title>

<style>

@page{
    margin:28px 32px 38px 32px;
}

body{
    font-family:DejaVu Sans,Arial,sans-serif;
    color:#334155;
    font-size:9px;
    line-height:1.45;
}

.header{
    border-bottom:2px solid #123b6d;
    padding-bottom:12px;
    margin-bottom:16px;
}

.brand{
    font-size:10px;
    font-weight:700;
    color:#64748b;
    text-transform:uppercase;
}

.title{
    font-size:20px;
    color:#123b6d;
    font-weight:700;
    margin:4px 0;
}

.subtitle{
    font-size:9px;
    color:#64748b;
}

.section{
    border:1px solid #dbe3ec;
    border-radius:6px;
    margin-bottom:13px;
    page-break-inside:avoid;
}

.section-title{
    background:#f8fafc;
    border-bottom:1px solid #dbe3ec;
    color:#123b6d;
    font-weight:700;
    font-size:10px;
    padding:8px 10px;
}

.section-body{
    padding:10px;
}

.grid2{
    width:100%;
    border-collapse:collapse;
}

.grid2 td{
    width:50%;
    padding:6px 8px;
    border-bottom:1px solid #eef2f7;
    vertical-align:top;
}

.label{
    display:block;
    color:#64748b;
    font-size:7px;
    font-weight:700;
    text-transform:uppercase;
}

.value{
    display:block;
    color:#334155;
    font-size:9px;
    font-weight:600;
    margin-top:2px;
}

.summary{
    width:100%;
    border-collapse:collapse;
}

.summary td{
    width:25%;
    padding:8px;
    text-align:center;
    border:1px solid #e2e8f0;
}

.summary .big{
    font-size:15px;
    color:#123b6d;
    font-weight:700;
}

.summary .small{
    font-size:7px;
    color:#64748b;
    text-transform:uppercase;
}

.status{
    width:100%;
    border-collapse:collapse;
    margin-top:8px;
}

.status td{
    width:33.33%;
    text-align:center;
    padding:8px;
    border:1px solid #e2e8f0;
}

.status .n{
    font-size:17px;
    font-weight:700;
}

.status .l{
    font-size:7px;
    font-weight:700;
}


/*
|--------------------------------------------------------------------------
| PNG GROWTH CHARTS
|--------------------------------------------------------------------------
|
| The charts are generated with PHP GD and embedded as PNG data.
|
| This is intentional:
|
| - No Chart.js
| - No SVG
| - No JavaScript
| - No controller-generated chart variables
| - Fully compatible with DomPDF
|
*/

.chart{
    border:1px solid #e2e8f0;
    padding:7px;
    margin-bottom:10px;
    page-break-inside:avoid;
}

.chart-title{
    color:#334155;
    font-size:9px;
    font-weight:700;
    margin-bottom:5px;
}

.chart-image{
    display:block;
    width:100%;
    height:auto;
}

.chart-empty{
    height:130px;
    text-align:center;
    color:#94a3b8;
    font-size:8px;
    padding-top:55px;
}


/*
|--------------------------------------------------------------------------
| Other report sections
|--------------------------------------------------------------------------
*/

.recommendation{
    padding:7px 8px;
    margin-bottom:5px;
    background:#f8fafc;
    border:1px solid #e2e8f0;
    border-radius:4px;
}

.history{
    width:100%;
    border-collapse:collapse;
    font-size:7px;
}

.history th{
    background:#f8fafc;
    color:#64748b;
    text-align:left;
    padding:6px;
    border-bottom:1px solid #dbe3ec;
}

.history td{
    padding:6px;
    border-bottom:1px solid #eef2f7;
}

.notice{
    background:#eaf2fb;
    border:1px solid #d4e4f7;
    padding:9px;
    color:#365574;
    font-size:8px;
}

.footer{
    position:fixed;
    bottom:-22px;
    left:0;
    right:0;
    text-align:center;
    color:#94a3b8;
    font-size:7px;
}

</style>

</head>

<body>

@php

    /*
    |--------------------------------------------------------------------------
    | Normalize measurement data
    |--------------------------------------------------------------------------
    */

    $measurementData = collect($measurementData ?? [])->values();


    /*
    |--------------------------------------------------------------------------
    | PNG chart generator using PHP GD
    |--------------------------------------------------------------------------
    */

    $renderTrendChart = function (
        string $title,
        string $field,
        string $unit,
        $items
    ) {

        $items = collect($items)->values();


        /*
        |--------------------------------------------------------------------------
        | Collect valid measurements
        |--------------------------------------------------------------------------
        */

        $points = [];

        foreach ($items as $index => $item) {

            $value = data_get($item, $field);

            if ($value === null || $value === '') {
                continue;
            }

            if (!is_numeric($value)) {
                continue;
            }

            $points[] = [
                'index' => $index,
                'date' => (string) data_get($item, 'date', '—'),
                'value' => (float) $value,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | No data
        |--------------------------------------------------------------------------
        */

        if (count($points) === 0) {

            return null;
        }


        /*
        |--------------------------------------------------------------------------
        | Image dimensions
        |--------------------------------------------------------------------------
        */

        $width = 1100;
        $height = 400;

        $left = 90;
        $right = 35;
        $top = 25;
        $bottom = 80;

        $plotWidth = $width - $left - $right;
        $plotHeight = $height - $top - $bottom;


        /*
        |--------------------------------------------------------------------------
        | Create image
        |--------------------------------------------------------------------------
        */

        $image = imagecreatetruecolor($width, $height);

        imageantialias($image, true);


        /*
        |--------------------------------------------------------------------------
        | Colors
        |--------------------------------------------------------------------------
        */

        $white = imagecolorallocate(
            $image,
            255,
            255,
            255
        );

        $textColor = imagecolorallocate(
            $image,
            100,
            116,
            139
        );

        $axisColor = imagecolorallocate(
            $image,
            203,
            213,
            225
        );

        $gridColor = imagecolorallocate(
            $image,
            226,
            232,
            240
        );

        /*
        | Chart.js-style blue
        */

        $blue = imagecolorallocate(
            $image,
            54,
            162,
            235
        );

        $blueDark = imagecolorallocate(
            $image,
            38,
            140,
            210
        );


        /*
        |--------------------------------------------------------------------------
        | White background
        |--------------------------------------------------------------------------
        */

        imagefill(
            $image,
            0,
            0,
            $white
        );


        /*
        |--------------------------------------------------------------------------
        | Determine Y-axis range
        |--------------------------------------------------------------------------
        */

        $values = array_column(
            $points,
            'value'
        );

        $dataMin = min($values);
        $dataMax = max($values);

        if ($dataMin === $dataMax) {

            $padding = max(
                abs($dataMin) * 0.10,
                1
            );

        } else {

            $padding =
                ($dataMax - $dataMin)
                * 0.15;
        }

        $scaleMin = $dataMin - $padding;
        $scaleMax = $dataMax + $padding;


        /*
        |--------------------------------------------------------------------------
        | Coordinate functions
        |--------------------------------------------------------------------------
        */

        $getX = function ($index) use (
            $items,
            $left,
            $plotWidth
        ) {

            $count = max(
                $items->count(),
                1
            );

            if ($count === 1) {

                return $left
                    + ($plotWidth / 2);
            }

            return $left
                + (
                    ($index / ($count - 1))
                    * $plotWidth
                );
        };


        $getY = function ($value) use (
            $top,
            $plotHeight,
            $scaleMin,
            $scaleMax
        ) {

            $range =
                $scaleMax - $scaleMin;

            if ($range <= 0) {

                return $top
                    + ($plotHeight / 2);
            }

            return $top
                + $plotHeight
                - (
                    (
                        ($value - $scaleMin)
                        / $range
                    )
                    * $plotHeight
                );
        };


        /*
        |--------------------------------------------------------------------------
        | Y-axis grid and labels
        |--------------------------------------------------------------------------
        */

        $tickCount = 5;

        for (
            $tick = 0;
            $tick < $tickCount;
            $tick++
        ) {

            $ratio =
                $tick / ($tickCount - 1);

            $value =
                $scaleMax
                - (
                    ($scaleMax - $scaleMin)
                    * $ratio
                );

            $y =
                $top
                + (
                    $plotHeight
                    * $ratio
                );


            /*
            | Horizontal grid
            */

            imageline(
                $image,
                $left,
                (int) $y,
                $left + $plotWidth,
                (int) $y,
                $gridColor
            );


            /*
            | Y-axis label
            */

            $label =
                number_format(
                    $value,
                    2
                );


            imagestring(
                $image,
                2,
                8,
                (int) ($y - 6),
                $label,
                $textColor
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Axes
        |--------------------------------------------------------------------------
        */

        imageline(
            $image,
            $left,
            $top,
            $left,
            $top + $plotHeight,
            $axisColor
        );

        imageline(
            $image,
            $left,
            $top + $plotHeight,
            $left + $plotWidth,
            $top + $plotHeight,
            $axisColor
        );


        /*
        |--------------------------------------------------------------------------
        | Build coordinates
        |--------------------------------------------------------------------------
        */

        $coordinates = [];

        foreach ($points as $point) {

            $x = $getX(
                $point['index']
            );

            $y = $getY(
                $point['value']
            );

            $coordinates[] = [
                'x' => (int) round($x),
                'y' => (int) round($y),
                'value' => $point['value'],
                'date' => $point['date'],
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Draw trend line
        |--------------------------------------------------------------------------
        */

        for (
            $i = 1;
            $i < count($coordinates);
            $i++
        ) {

            $previous =
                $coordinates[$i - 1];

            $current =
                $coordinates[$i];


            /*
            | Main blue line
            */

            imagesetthickness(
                $image,
                4
            );

            imageline(
                $image,
                $previous['x'],
                $previous['y'],
                $current['x'],
                $current['y'],
                $blue
            );

            imagesetthickness(
                $image,
                1
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Draw measurement points
        |--------------------------------------------------------------------------
        */

        foreach ($coordinates as $coordinate) {

            /*
            | Outer white circle
            */

            imagefilledellipse(
                $image,
                $coordinate['x'],
                $coordinate['y'],
                13,
                13,
                $white
            );


            /*
            | Blue point
            */

            imagefilledellipse(
                $image,
                $coordinate['x'],
                $coordinate['y'],
                9,
                9,
                $blue
            );
        }


        /*
        |--------------------------------------------------------------------------
        | X-axis dates
        |--------------------------------------------------------------------------
        */

        $pointCount =
            count($coordinates);

        $labelPositions = [];


        if ($pointCount <= 6) {

            for (
                $i = 0;
                $i < $pointCount;
                $i++
            ) {

                $labelPositions[] = $i;
            }

        } else {

            $maxLabels = 6;

            $step =
                ($pointCount - 1)
                / ($maxLabels - 1);

            for (
                $i = 0;
                $i < $maxLabels;
                $i++
            ) {

                $position =
                    (int) round(
                        $i * $step
                    );

                if (
                    !in_array(
                        $position,
                        $labelPositions,
                        true
                    )
                ) {

                    $labelPositions[] =
                        $position;
                }
            }
        }


        foreach (
            $labelPositions as $position
        ) {

            if (
                !isset(
                    $coordinates[$position]
                )
            ) {
                continue;
            }

            $coordinate =
                $coordinates[$position];

            $date =
                $coordinate['date'];

            /*
            | Date label
            */

            imagestring(
                $image,
                2,
                max(
                    0,
                    $coordinate['x'] - 38
                ),
                $top + $plotHeight + 15,
                $date,
                $textColor
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Axis labels
        |--------------------------------------------------------------------------
        */

        imagestring(
            $image,
            2,
            (int) (
                ($width / 2) - 35
            ),
            $height - 28,
            'Visit Date',
            $textColor
        );


        /*
        | Unit label
        |
        | We keep this horizontal for maximum
        | compatibility with DomPDF.
        */

        imagestring(
            $image,
            2,
            10,
            8,
            $unit,
            $textColor
        );


        /*
        |--------------------------------------------------------------------------
        | Convert PNG to data URI
        |--------------------------------------------------------------------------
        */

        ob_start();

        imagepng(
            $image,
            null,
            6
        );

        $pngData =
            ob_get_clean();


        imagedestroy(
            $image
        );


        return
            'data:image/png;base64,'
            . base64_encode(
                $pngData
            );
    };

@endphp


<div class="header">

    <div class="brand">
        Child Weight Monitoring System
    </div>

    <div class="title">
        Child Health &amp; Growth Monitoring Report
    </div>

    <div class="subtitle">

        {{ $child->first_name }}
        {{ $child->last_name }}

        |

        Child ID:
        {{ $child->child_id }}

        |

        Health Facility:
        {{ $facilityName ?? '—' }}

    </div>

</div>


<div class="section">

    <div class="section-title">
        Child Information
    </div>

    <div class="section-body">

        <table class="grid2">

            <tr>

                <td>
                    <span class="label">
                        Child Name
                    </span>

                    <span class="value">
                        {{ $child->first_name }}
                        {{ $child->last_name }}
                    </span>
                </td>

                <td>
                    <span class="label">
                        Child ID
                    </span>

                    <span class="value">
                        {{ $child->child_id }}
                    </span>
                </td>

            </tr>

            <tr>

                <td>
                    <span class="label">
                        Sex
                    </span>

                    <span class="value">
                        {{ $child->sex ?? '—' }}
                    </span>
                </td>

                <td>
                    <span class="label">
                        Date of Birth
                    </span>

                    <span class="value">

                        {{
                            $child->date_of_birth
                                ? \Carbon\Carbon::parse(
                                    $child->date_of_birth
                                )->format('d F Y')
                                : '—'
                        }}

                    </span>

                </td>

            </tr>

            <tr>

                <td>
                    <span class="label">
                        Guardian
                    </span>

                    <span class="value">
                        {{ $child->guardian_name ?? '—' }}
                    </span>
                </td>

                <td>
                    <span class="label">
                        Guardian Phone
                    </span>

                    <span class="value">
                        {{ $child->guardian_phone ?? '—' }}
                    </span>
                </td>

            </tr>

            <tr>

                <td>
                    <span class="label">
                        Guardian Email
                    </span>

                    <span class="value">
                        {{ $child->guardian_email ?? '—' }}
                    </span>
                </td>

                <td>
                    <span class="label">
                        Address
                    </span>

                    <span class="value">
                        {{ $child->address ?? '—' }}
                    </span>
                </td>

            </tr>

        </table>

    </div>

</div>


<div class="section">

    <div class="section-title">
        Report Information
    </div>

    <div class="section-body">

        <table class="grid2">

            <tr>

                <td>

                    <span class="label">
                        Reporting Period
                    </span>

                    <span class="value">

                        @if($reportStart && $reportEnd)

                            {{ $reportStart->format('d M Y') }}
                            –
                            {{ $reportEnd->format('d M Y') }}

                        @else

                            {{ $periodLabel ?? 'All Available Records' }}

                        @endif

                    </span>

                </td>

                <td>

                    <span class="label">
                        Generated
                    </span>

                    <span class="value">
                        {{ ($generatedAt ?? now())->format('d F Y H:i') }}
                    </span>

                </td>

            </tr>

        </table>

    </div>

</div>


<div class="section">

    <div class="section-title">
        Monitoring Summary
    </div>

    <div class="section-body">

        <table class="summary">

            <tr>

                <td>

                    <div class="big">

                        {{
                            $latestRecord
                            && $latestRecord->weight !== null
                                ? number_format(
                                    (float)$latestRecord->weight,
                                    2
                                )
                                : '—'
                        }}

                    </div>

                    <div class="small">
                        Latest Weight kg
                    </div>

                </td>

                <td>

                    <div class="big">

                        {{
                            $latestRecord
                            && $latestRecord->height !== null
                                ? number_format(
                                    (float)$latestRecord->height,
                                    2
                                )
                                : '—'
                        }}

                    </div>

                    <div class="small">
                        Latest Height cm
                    </div>

                </td>

                <td>

                    <div class="big">

                        {{
                            $latestRecord
                            && $latestRecord->muac !== null
                                ? number_format(
                                    (float)$latestRecord->muac,
                                    1
                                )
                                : '—'
                        }}

                    </div>

                    <div class="small">
                        Latest MUAC cm
                    </div>

                </td>

                <td>

                    <div class="big">
                        {{ $measurementData->count() }}
                    </div>

                    <div class="small">
                        Visits
                    </div>

                </td>

            </tr>

        </table>


        <table class="status">

            <tr>

                <td>

                    <div class="n">
                        {{ $normalCount }}
                    </div>

                    <div class="l">
                        Normal
                    </div>

                </td>

                <td>

                    <div class="n">
                        {{ $monitorCount }}
                    </div>

                    <div class="l">
                        Monitor / Risk
                    </div>

                </td>

                <td>

                    <div class="n">
                        {{ $attentionCount }}
                    </div>

                    <div class="l">
                        Attention
                    </div>

                </td>

            </tr>

        </table>

    </div>

</div>


<div class="section">

    <div class="section-title">
        Growth Trend Visualization
    </div>

    <div class="section-body">

        <div
            style="
                color:#64748b;
                font-size:8px;
                margin-bottom:8px
            "
        >
            Recorded measurements are shown chronologically using
            the same visit-date based trend structure used by the
            growth-monitoring charts.
        </div>


        {{-- Weight --}}
        <div class="chart">

            <div class="chart-title">
                Weight Trend
            </div>

            @php
                $weightImage = $renderTrendChart(
                    'Weight Trend',
                    'weight',
                    'Weight (kg)',
                    $measurementData
                );
            @endphp

            @if($weightImage)

                <img
                    src="{{ $weightImage }}"
                    class="chart-image"
                    alt="Weight Trend"
                >

            @else

                <div class="chart-empty">
                    No recorded weight measurements.
                </div>

            @endif

        </div>


        {{-- Height --}}
        <div class="chart">

            <div class="chart-title">
                Height / Length Trend
            </div>

            @php
                $heightImage = $renderTrendChart(
                    'Height / Length Trend',
                    'height',
                    'Height / Length (cm)',
                    $measurementData
                );
            @endphp

            @if($heightImage)

                <img
                    src="{{ $heightImage }}"
                    class="chart-image"
                    alt="Height / Length Trend"
                >

            @else

                <div class="chart-empty">
                    No recorded height or length measurements.
                </div>

            @endif

        </div>


        {{-- BMI --}}
        <div class="chart">

            <div class="chart-title">
                BMI Trend
            </div>

            @php
                $bmiImage = $renderTrendChart(
                    'BMI Trend',
                    'bmi',
                    'BMI',
                    $measurementData
                );
            @endphp

            @if($bmiImage)

                <img
                    src="{{ $bmiImage }}"
                    class="chart-image"
                    alt="BMI Trend"
                >

            @else

                <div class="chart-empty">
                    No recorded BMI measurements.
                </div>

            @endif

        </div>


        {{-- MUAC --}}
        <div class="chart">

            <div class="chart-title">
                MUAC Trend
            </div>

            @php
                $muacImage = $renderTrendChart(
                    'MUAC Trend',
                    'muac',
                    'MUAC (cm)',
                    $measurementData
                );
            @endphp

            @if($muacImage)

                <img
                    src="{{ $muacImage }}"
                    class="chart-image"
                    alt="MUAC Trend"
                >

            @else

                <div class="chart-empty">
                    No recorded MUAC measurements.
                </div>

            @endif

        </div>

    </div>

</div>


<div class="section">

    <div class="section-title">
        Latest Growth Assessment
    </div>

    <div class="section-body">

        @if($latestAssessment)

            <table class="grid2">

                <tr>

                    <td>

                        <span class="label">
                            Overall Status
                        </span>

                        <span class="value">

                            {{
                                $latestAssessment->overall_status
                                ?? $latestAssessment->status
                                ?? 'Not assessed'
                            }}

                        </span>

                    </td>

                    <td>

                        <span class="label">
                            Attention Required
                        </span>

                        <span class="value">

                            {{
                                $latestAssessment->requires_attention
                                    ? 'Yes'
                                    : 'No'
                            }}

                        </span>

                    </td>

                </tr>


                <tr>

                    <td>

                        <span class="label">
                            Weight-for-Age
                        </span>

                        <span class="value">

                            {{
                                $latestAssessment->weight_for_age_status
                                ?? 'Not assessed'
                            }}

                            @if(
                                $latestAssessment
                                ->weight_for_age_zscore !== null
                            )

                                —
                                {{
                                    number_format(
                                        (float)
                                        $latestAssessment
                                        ->weight_for_age_zscore,
                                        3
                                    )
                                }}

                            @endif

                        </span>

                    </td>


                    <td>

                        <span class="label">
                            Height-for-Age
                        </span>

                        <span class="value">

                            {{
                                $latestAssessment->height_for_age_status
                                ?? 'Not assessed'
                            }}

                            @if(
                                $latestAssessment
                                ->height_for_age_zscore !== null
                            )

                                —
                                {{
                                    number_format(
                                        (float)
                                        $latestAssessment
                                        ->height_for_age_zscore,
                                        3
                                    )
                                }}

                            @endif

                        </span>

                    </td>

                </tr>


                <tr>

                    <td>

                        <span class="label">
                            Weight-for-Length / Height
                        </span>

                        <span class="value">

                            {{
                                $latestAssessment
                                ->weight_for_length_status
                                ?? $latestAssessment
                                ->weight_for_height_status
                                ?? 'Not assessed'
                            }}

                        </span>

                    </td>


                    <td>

                        <span class="label">
                            BMI-for-Age
                        </span>

                        <span class="value">

                            {{
                                $latestAssessment
                                ->bmi_for_age_status
                                ?? 'Not assessed'
                            }}

                        </span>

                    </td>

                </tr>


                <tr>

                    <td>

                        <span class="label">
                            MUAC
                        </span>

                        <span class="value">

                            {{
                                $latestAssessment->muac_status
                                ?? 'Not assessed'
                            }}

                        </span>

                    </td>


                    <td>

                        <span class="label">
                            Age at Assessment
                        </span>

                        <span class="value">

                            {{
                                $latestAssessment->age_in_months !== null
                                    ? $latestAssessment
                                        ->age_in_months
                                        .' months'
                                    : '—'
                            }}

                        </span>

                    </td>

                </tr>

            </table>

        @else

            <div>
                No growth assessment has been generated
                for the latest health record.
            </div>

        @endif

    </div>

</div>


<div class="section">

    <div class="section-title">
        Monitoring Recommendations
    </div>

    <div class="section-body">

        @forelse(
            $recommendations
            as $index => $recommendation
        )

            <div class="recommendation">

                <strong>
                    {{ $index + 1 }}.
                </strong>

                {{ $recommendation }}

            </div>

        @empty

            <div>
                No monitoring recommendations are available.
            </div>

        @endforelse

    </div>

</div>


<div class="section">

    <div class="section-title">
        Complete Growth History
    </div>

    <div class="section-body">

        @if($measurementData->count())

            <table class="history">

                <thead>

                    <tr>
                        <th>Date</th>
                        <th>Weight</th>
                        <th>Height / Length</th>
                        <th>MUAC</th>
                        <th>BMI</th>
                        <th>Nutrition</th>
                        <th>Assessment</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach(
                        $measurementData
                        as $record
                    )

                        <tr>

                            <td>
                                {{ $record['date'] }}
                            </td>

                            <td>
                                {{
                                    $record['weight'] !== null
                                        ? $record['weight'].' kg'
                                        : '—'
                                }}
                            </td>

                            <td>
                                {{
                                    $record['height'] !== null
                                        ? $record['height'].' cm'
                                        : '—'
                                }}
                            </td>

                            <td>
                                {{
                                    $record['muac'] !== null
                                        ? $record['muac'].' cm'
                                        : '—'
                                }}
                            </td>

                            <td>
                                {{
                                    $record['bmi'] !== null
                                        ? $record['bmi']
                                        : '—'
                                }}
                            </td>

                            <td>
                                {{
                                    $record['nutrition_status']
                                    ?? '—'
                                }}
                            </td>

                            <td>
                                {{
                                    $record['overall_status']
                                    ?? 'Not assessed'
                                }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <div>
                No health records available.
            </div>

        @endif

    </div>

</div>


@if($latestRecord)

<div class="section">

    <div class="section-title">
        Latest Clinical Visit
    </div>

    <div class="section-body">

        <table class="grid2">

            <tr>

                <td>
                    <span class="label">
                        Visit Date
                    </span>

                    <span class="value">

                        {{
                            $latestRecord->visit_date
                                ? \Carbon\Carbon::parse(
                                    $latestRecord->visit_date
                                )->format('d F Y')
                                : '—'
                        }}

                    </span>
                </td>

                <td>
                    <span class="label">
                        Health Facility
                    </span>

                    <span class="value">
                        {{
                            $latestRecord->health_facility
                            ?? $latestRecord->facility_name
                            ?? '—'
                        }}
                    </span>
                </td>

            </tr>


            <tr>

                <td>
                    <span class="label">
                        Health Worker
                    </span>

                    <span class="value">
                        {{
                            $latestRecord->health_worker
                            ?? $latestRecord->health_worker_name
                            ?? '—'
                        }}
                    </span>
                </td>

                <td>
                    <span class="label">
                        Temperature
                    </span>

                    <span class="value">

                        {{
                            $latestRecord->temperature !== null
                                ? $latestRecord->temperature.' °C'
                                : '—'
                        }}

                    </span>

                </td>

            </tr>


            <tr>

                <td>
                    <span class="label">
                        Clinical Observations
                    </span>

                    <span class="value">

                        {{
                            $latestRecord->clinical_observations
                            ?? $latestRecord->clinical_remarks
                            ?? '—'
                        }}

                    </span>

                </td>

                <td>
                    <span class="label">
                        Symptoms
                    </span>

                    <span class="value">
                        {{ $latestRecord->symptoms ?? '—' }}
                    </span>
                </td>

            </tr>


            <tr>

                <td>
                    <span class="label">
                        Doctor's Remarks
                    </span>

                    <span class="value">

                        {{
                            $latestRecord->doctor_remarks
                            ?? $latestRecord->clinical_remarks
                            ?? '—'
                        }}

                    </span>

                </td>

                <td>
                    <span class="label">
                        Follow-Up Date
                    </span>

                    <span class="value">

                        {{
                            $latestRecord->follow_up_date
                                ? \Carbon\Carbon::parse(
                                    $latestRecord->follow_up_date
                                )->format('d F Y')
                                : '—'
                        }}

                    </span>

                </td>

            </tr>

        </table>

    </div>

</div>

@endif


<div class="notice">

    <strong>
        Important monitoring notice:
    </strong>

    This report summarizes recorded child health and growth
    monitoring information. Growth indicators are intended
    to support screening and monitoring and should not be
    treated as a medical diagnosis. Any concerning result
    should be reviewed by an appropriately qualified
    health worker.

</div>


<div class="footer">

    Child Weight Monitoring System |
    {{ $facilityName ?? 'Health Facility' }} |
    Generated
    {{ ($generatedAt ?? now())->format('d M Y H:i') }}

</div>

</body>
</html>