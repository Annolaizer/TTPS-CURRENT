<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            // Add new fields
            $table->integer('training_phase')->nullable()->after('education_level');
            $table->time('start_time')->nullable()->after('start_date');
        });

        // Modify status enum to include 'ongoing'
        DB::statement("ALTER TABLE trainings MODIFY COLUMN status ENUM('draft', 'pending', 'verified', 'rejected', 'completed', 'ongoing') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First revert the status enum
        DB::statement("ALTER TABLE trainings MODIFY COLUMN status ENUM('draft', 'pending', 'verified', 'rejected', 'completed') DEFAULT 'draft'");

        Schema::table('trainings', function (Blueprint $table) {
            $table->dropColumn(['training_phase', 'start_time']);
        });
    }
};
