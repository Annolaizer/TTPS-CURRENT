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
        // Add indexes to trainings table
        Schema::table('trainings', function (Blueprint $table) {
            $table->index('status');
            $table->index('start_date');
            $table->index('education_level');
            $table->index('training_phase');
            $table->index(['organization_id', 'status']); // For filtering by org and status
            $table->fullText(['title', 'description']); // For better text search
        });

        // Add indexes to training_locations table
        Schema::table('training_locations', function (Blueprint $table) {
            $table->index('ward_id');
        });

        // Add index to subjects table
        Schema::table('subjects', function (Blueprint $table) {
            $table->index('subject_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes from trainings table
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['start_date']);
            $table->dropIndex(['education_level']);
            $table->dropIndex(['training_phase']);
            $table->dropIndex(['organization_id', 'status']);
            $table->dropFullText(['title', 'description']);
        });

        // Remove indexes from training_locations table
        Schema::table('training_locations', function (Blueprint $table) {
            $table->dropIndex(['ward_id']);
        });

        // Remove index from subjects table
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropIndex(['subject_name']);
        });
    }
};
