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
        Schema::create('children', function (Blueprint $table) {

            $table->id();

            // Child identification
            $table->string('child_id', 50)->unique();

            // Basic child information
            $table->string('first_name', 100);
            $table->string('last_name', 100)->nullable();
            $table->enum('sex', ['Male', 'Female']);
            $table->date('date_of_birth');
            $table->string('place_of_birth', 150)->nullable();

            // Guardian information
            $table->string('guardian_name', 150);
            $table->string('guardian_phone', 30)->nullable();
            $table->string('address', 255)->nullable();

            // System timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};