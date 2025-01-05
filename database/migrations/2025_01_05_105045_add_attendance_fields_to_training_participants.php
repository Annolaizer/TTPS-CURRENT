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
        Schema::table('training_teachers', function (Blueprint $table) {
            $table->enum('attendance_status', ['not_started', 'attended', 'not_attended'])
                  ->default('not_started')
                  ->after('status');
            $table->string('report_file')->nullable()->after('attendance_status');
        });

        Schema::table('training_facilitators', function (Blueprint $table) {
            $table->enum('attendance_status', ['not_started', 'attended', 'not_attended'])
                  ->default('not_started')
                  ->after('status');
            $table->string('report_file')->nullable()->after('attendance_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training_teachers', function (Blueprint $table) {
            $table->dropColumn(['attendance_status', 'report_file']);
        });

        Schema::table('training_facilitators', function (Blueprint $table) {
            $table->dropColumn(['attendance_status', 'report_file']);
        });
    }
};
