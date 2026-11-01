<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('children', 'guardian_email')) {
            Schema::table('children', function (Blueprint $table) {
                $table->string('guardian_email')
                    ->nullable()
                    ->after('guardian_phone');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('children', 'guardian_email')) {
            Schema::table('children', function (Blueprint $table) {
                $table->dropColumn('guardian_email');
            });
        }
    }
};