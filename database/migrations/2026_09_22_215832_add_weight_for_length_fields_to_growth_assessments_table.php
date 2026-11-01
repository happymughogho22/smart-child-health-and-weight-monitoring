<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('growth_assessments', function (Blueprint $table) {
            $table->decimal('weight_for_length_zscore', 6, 3)
                ->nullable()
                ->after('weight_for_height_status');

            $table->string('weight_for_length_status')
                ->nullable()
                ->after('weight_for_length_zscore');
        });
    }

    public function down(): void
    {
        Schema::table('growth_assessments', function (Blueprint $table) {
            $table->dropColumn([
                'weight_for_length_zscore',
                'weight_for_length_status',
            ]);
        });
    }
};