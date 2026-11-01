<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('health_records', function (Blueprint $table) {

            if (!Schema::hasColumn('health_records', 'facility_name')) {
                $table->string('facility_name')->nullable();
            }

            if (!Schema::hasColumn('health_records', 'health_worker_name')) {
                $table->string('health_worker_name')->nullable();
            }

            if (!Schema::hasColumn('health_records', 'weight')) {
                $table->decimal('weight', 6, 2)->nullable();
            }

            if (!Schema::hasColumn('health_records', 'height')) {
                $table->decimal('height', 6, 2)->nullable();
            }

            if (!Schema::hasColumn('health_records', 'temperature')) {
                $table->decimal('temperature', 4, 1)->nullable();
            }

            if (!Schema::hasColumn('health_records', 'muac')) {
                $table->string('muac')->nullable();
            }

            if (!Schema::hasColumn('health_records', 'clinical_observations')) {
                $table->text('clinical_observations')->nullable();
            }

            if (!Schema::hasColumn('health_records', 'doctor_remarks')) {
                $table->text('doctor_remarks')->nullable();
            }

            if (!Schema::hasColumn('health_records', 'recommendations')) {
                $table->text('recommendations')->nullable();
            }

            if (!Schema::hasColumn('health_records', 'follow_up_date')) {
                $table->date('follow_up_date')->nullable();
            }

            if (!Schema::hasColumn('health_records', 'bmi')) {
                $table->decimal('bmi', 5, 2)->nullable();
            }

            if (!Schema::hasColumn('health_records', 'monitoring_status')) {
                $table->string('monitoring_status')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('health_records', function (Blueprint $table) {

            $columns = [
                'facility_name',
                'health_worker_name',
                'weight',
                'height',
                'temperature',
                'muac',
                'clinical_observations',
                'doctor_remarks',
                'recommendations',
                'follow_up_date',
                'bmi',
                'monitoring_status',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('health_records', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};