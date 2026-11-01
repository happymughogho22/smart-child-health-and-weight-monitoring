<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('health_records', function (Blueprint $table) {
            $table->id();

            // Connect the health record to a child
            $table->foreignId('child_id')
                ->constrained('children')
                ->cascadeOnDelete();

            // Visit information
            $table->date('visit_date');

            // Growth measurements
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('height', 5, 2)->nullable();

            // Clinical information
            $table->text('symptoms')->nullable();
            $table->text('doctor_remarks')->nullable();
            $table->text('assessment')->nullable();

            // Nutritional / growth assessment
            $table->string('nutritional_status')->nullable();

            // Recommendations for parent/guardian
            $table->text('recommendations')->nullable();

            // Follow-up
            $table->date('follow_up_date')->nullable();

            // Additional notes
            $table->text('additional_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_records');
    }
};