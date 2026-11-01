<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportWhoGrowthReferences extends Command
{
    protected $signature = 'who:import';

    protected $description = 'Import WHO child growth z-score reference tables from Excel files';

    public function handle(): int
    {
        $basePath = storage_path('app/who');

        /*
        |--------------------------------------------------------------------------
        | WHO Excel files
        |--------------------------------------------------------------------------
        |
        | These names match the actual files in storage/app/who.
        |
        */

        $files = [
            [
                'file' => 'wfa-boys.xlsx',
                'indicator' => 'weight_for_age',
                'sex' => 'male',
                'type' => 'age',
            ],
            [
                'file' => 'wfa-girls.xlsx',
                'indicator' => 'weight_for_age',
                'sex' => 'female',
                'type' => 'age',
            ],
            [
                'file' => 'lhfa_boys.xlsx',
                'indicator' => 'length_height_for_age',
                'sex' => 'male',
                'type' => 'age',
            ],
            [
                'file' => 'lhfa_girls.xlsx',
                'indicator' => 'length_height_for_age',
                'sex' => 'female',
                'type' => 'age',
            ],
            [
                'file' => 'wfl-boys.xlsx',
                'indicator' => 'weight_for_length',
                'sex' => 'male',
                'type' => 'measurement',
            ],
            [
                'file' => 'wfl-girls.xlsx',
                'indicator' => 'weight_for_length',
                'sex' => 'female',
                'type' => 'measurement',
            ],
            [
                'file' => 'wfh-boys.xlsx',
                'indicator' => 'weight_for_height',
                'sex' => 'male',
                'type' => 'measurement',
            ],
            [
                'file' => 'wfh-girls.xlsx',
                'indicator' => 'weight_for_height',
                'sex' => 'female',
                'type' => 'measurement',
            ],
            [
                'file' => 'bfa-boys.xlsx',
                'indicator' => 'bmi_for_age',
                'sex' => 'male',
                'type' => 'age',
            ],
            [
                'file' => 'bfa-girls.xlsx',
                'indicator' => 'bmi_for_age',
                'sex' => 'female',
                'type' => 'age',
            ],
        ];

        $this->info('Starting WHO growth reference import...');
        $this->newLine();

        /*
        |--------------------------------------------------------------------------
        | Clear the existing WHO reference data
        |--------------------------------------------------------------------------
        |
        | This table contains only imported WHO reference standards.
        | It does NOT contain children or health records.
        |
        */

        DB::table('who_growth_references')->truncate();

        $totalImported = 0;

        foreach ($files as $definition) {

            $filePath = $basePath . DIRECTORY_SEPARATOR . $definition['file'];

            if (!file_exists($filePath)) {
                $this->error("File not found: {$definition['file']}");
                return self::FAILURE;
            }

            $this->line("Importing {$definition['file']}...");

            try {

                $reader = IOFactory::createReaderForFile($filePath);
                $reader->setReadDataOnly(true);

                $spreadsheet = $reader->load($filePath);

                $worksheet = $spreadsheet->getActiveSheet();

                $rows = $worksheet->toArray(
                    null,
                    true,
                    true,
                    true
                );

                if (empty($rows)) {
                    $this->warn("No rows found in {$definition['file']}.");

                    $spreadsheet->disconnectWorksheets();
                    unset($spreadsheet);

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Read header row
                |--------------------------------------------------------------------------
                */

                $header = array_shift($rows);

                $headerMap = [];

                foreach ($header as $column => $name) {

                    if ($name === null) {
                        continue;
                    }

                    $headerMap[
                        strtolower(trim((string) $name))
                    ] = $column;
                }

                /*
                |--------------------------------------------------------------------------
                | Required LMS columns
                |--------------------------------------------------------------------------
                */

                foreach (['l', 'm', 's'] as $requiredColumn) {

                    if (!isset($headerMap[$requiredColumn])) {

                        $this->error(
                            "Missing required column '{$requiredColumn}' in {$definition['file']}."
                        );

                        $spreadsheet->disconnectWorksheets();
                        unset($spreadsheet);

                        return self::FAILURE;
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Determine main reference column
                |--------------------------------------------------------------------------
                */

                $valueColumn = null;

                if ($definition['type'] === 'age') {

                    if (!isset($headerMap['day'])) {

                        $this->error(
                            "Missing 'Day' column in {$definition['file']}."
                        );

                        $spreadsheet->disconnectWorksheets();
                        unset($spreadsheet);

                        return self::FAILURE;
                    }

                    $valueColumn = $headerMap['day'];

                } else {

                    if (isset($headerMap['length'])) {

                        $valueColumn = $headerMap['length'];

                    } elseif (isset($headerMap['height'])) {

                        $valueColumn = $headerMap['height'];

                    } else {

                        $this->error(
                            "Missing Length/Height column in {$definition['file']}."
                        );

                        $spreadsheet->disconnectWorksheets();
                        unset($spreadsheet);

                        return self::FAILURE;
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Build rows for bulk insertion
                |--------------------------------------------------------------------------
                */

                $insertRows = [];
                $imported = 0;

                foreach ($rows as $row) {

                    $mainValue = $row[$valueColumn] ?? null;

                    $l = $row[$headerMap['l']] ?? null;
                    $m = $row[$headerMap['m']] ?? null;
                    $s = $row[$headerMap['s']] ?? null;

                    if (
                        $mainValue === null ||
                        $mainValue === '' ||
                        $l === null ||
                        $m === null ||
                        $s === null
                    ) {
                        continue;
                    }

                    $data = [
                        'indicator' => $definition['indicator'],
                        'sex' => $definition['sex'],

                        'age_days' => null,
                        'measurement_value' => null,

                        'l' => $this->numberOrNull($l),
                        'm' => $this->numberOrNull($m),
                        's' => $this->numberOrNull($s),

                        'sd4neg' => $this->getValue(
                            $row,
                            $headerMap,
                            'sd4neg'
                        ),

                        'sd3neg' => $this->getValue(
                            $row,
                            $headerMap,
                            'sd3neg'
                        ),

                        'sd2neg' => $this->getValue(
                            $row,
                            $headerMap,
                            'sd2neg'
                        ),

                        'sd1neg' => $this->getValue(
                            $row,
                            $headerMap,
                            'sd1neg'
                        ),

                        'sd0' => $this->getValue(
                            $row,
                            $headerMap,
                            'sd0'
                        ),

                        'sd1' => $this->getValue(
                            $row,
                            $headerMap,
                            'sd1'
                        ),

                        'sd2' => $this->getValue(
                            $row,
                            $headerMap,
                            'sd2'
                        ),

                        'sd3' => $this->getValue(
                            $row,
                            $headerMap,
                            'sd3'
                        ),

                        'sd4' => $this->getValue(
                            $row,
                            $headerMap,
                            'sd4'
                        ),

                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    /*
                    |--------------------------------------------------------------------------
                    | Age-based references
                    |--------------------------------------------------------------------------
                    */

                    if ($definition['type'] === 'age') {

                        $data['age_days'] = (int) round(
                            (float) $mainValue
                        );

                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Measurement-based references
                    |--------------------------------------------------------------------------
                    */

                    else {

                        $data['measurement_value'] = round(
                            (float) $mainValue,
                            2
                        );
                    }

                    $insertRows[] = $data;

                    $imported++;

                    /*
                    |--------------------------------------------------------------------------
                    | Bulk insert every 1000 rows
                    |--------------------------------------------------------------------------
                    */

                    if (count($insertRows) >= 1000) {

                        DB::table('who_growth_references')
                            ->insert($insertRows);

                        $insertRows = [];
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Insert remaining rows
                |--------------------------------------------------------------------------
                */

                if (!empty($insertRows)) {

                    DB::table('who_growth_references')
                        ->insert($insertRows);
                }

                $totalImported += $imported;

                $this->info(
                    "  Imported: {$imported} rows."
                );

                $spreadsheet->disconnectWorksheets();

                unset($spreadsheet);

            } catch (\Throwable $e) {

                $this->error(
                    "Failed to import {$definition['file']}:"
                );

                $this->error(
                    $e->getMessage()
                );

                return self::FAILURE;
            }
        }

        $this->newLine();

        $this->info(
            'WHO import completed successfully.'
        );

        $this->info(
            "Total rows imported: {$totalImported}"
        );

        return self::SUCCESS;
    }

    /*
    |--------------------------------------------------------------------------
    | Get numeric value
    |--------------------------------------------------------------------------
    */

    private function getValue(
        array $row,
        array $headerMap,
        string $column
    ): ?float {

        if (!isset($headerMap[$column])) {
            return null;
        }

        $value = $row[$headerMap[$column]] ?? null;

        return $this->numberOrNull($value);
    }

    /*
    |--------------------------------------------------------------------------
    | Convert value to number
    |--------------------------------------------------------------------------
    */

    private function numberOrNull($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (!is_numeric($value)) {
            return null;
        }

        return (float) $value;
    }
}