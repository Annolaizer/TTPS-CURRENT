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
        Schema::table('trainings', function (Blueprint $table) {
            // Add columns only if they don't exist
            if (!Schema::hasColumn('trainings', 'verified_at')) {
                $table->timestamp('verified_at')->nullable();
            }
            if (!Schema::hasColumn('trainings', 'verified_by')) {
                $table->unsignedBigInteger('verified_by')->nullable();
                $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('trainings', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable();
            }
            if (!Schema::hasColumn('trainings', 'rejected_by')) {
                $table->unsignedBigInteger('rejected_by')->nullable();
                $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('trainings', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            // Only drop columns if they exist
            if (Schema::hasColumn('trainings', 'verified_by')) {
                $table->dropForeign(['verified_by']);
            }
            if (Schema::hasColumn('trainings', 'rejected_by')) {
                $table->dropForeign(['rejected_by']);
            }
            
            $columns = ['verified_at', 'verified_by', 'rejected_at', 'rejected_by', 'rejection_reason'];
            $existingColumns = collect($columns)->filter(function ($column) {
                return Schema::hasColumn('trainings', $column);
            })->toArray();
            
            if (!empty($existingColumns)) {
                $table->dropColumn($existingColumns);
            }
        });
    }
};
