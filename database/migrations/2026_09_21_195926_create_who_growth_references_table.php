<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('who_growth_references', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Indicator
            |--------------------------------------------------------------------------
            |
            | weight_for_age
            | height_for_age
            | weight_for_length_height
            | bmi_for_age
            |
            */

            $table->string('indicator', 50);

            /*
            |--------------------------------------------------------------------------
            | Sex
            |--------------------------------------------------------------------------
            */

            $table->enum('sex', [
                'male',
                'female'
            ]);

            /*
            |--------------------------------------------------------------------------
            | Age
            |--------------------------------------------------------------------------
            |
            | WHO expanded age tables use age in days.
            |
            */

            $table->unsignedInteger('age_days')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Measurement
            |--------------------------------------------------------------------------
            |
            | Used for weight-for-length/height.
            |
            | For example:
            |
            | 85.0 cm
            | 85.1 cm
            | 85.2 cm
            |
            */

            $table->decimal('measurement_value', 6, 2)->nullable();

            /*
            |--------------------------------------------------------------------------
            | LMS parameters
            |--------------------------------------------------------------------------
            */

            $table->decimal('l', 12, 8)->nullable();
            $table->decimal('m', 12, 8)->nullable();
            $table->decimal('s', 12, 8)->nullable();

            /*
            |--------------------------------------------------------------------------
            | WHO reference values
            |--------------------------------------------------------------------------
            */

            $table->decimal('sd4neg', 12, 4)->nullable();
            $table->decimal('sd3neg', 12, 4)->nullable();
            $table->decimal('sd2neg', 12, 4)->nullable();
            $table->decimal('sd1neg', 12, 4)->nullable();

            $table->decimal('sd0', 12, 4)->nullable();

            $table->decimal('sd1', 12, 4)->nullable();
            $table->decimal('sd2', 12, 4)->nullable();
            $table->decimal('sd3', 12, 4)->nullable();
            $table->decimal('sd4', 12, 4)->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index([
                'indicator',
                'sex',
                'age_days'
            ]);

            $table->index([
                'indicator',
                'sex',
                'measurement_value'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('who_growth_references');
    }
};