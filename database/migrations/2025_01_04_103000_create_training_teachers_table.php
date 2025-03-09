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
        Schema::create('training_teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained('trainings')->onDelete('cascade');
            $table->string('teacher_id', 36);
            $table->foreign('teacher_id')->references('teacher_id')->on('teacher_profiles')->onDelete('cascade');
            
            // Comprehensive status tracking
            $table->enum('status', [
                'pending',      // Initial invitation state
                'invited',      // Explicitly invited
                'accepted',     // Teacher accepted the invitation
                'rejected',     // Teacher rejected the invitation
                'attended',     // Teacher attended the training
                'partially_attended', // Teacher partially attended
                'absent',       // Teacher did not attend
                'completed',    // Training requirements fully met
                'failed'        // Did not meet training requirements
            ])->default('pending');

            // Detailed tracking fields
            $table->text('invitation_remarks')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('attendance_remarks')->nullable();
            
            // Timestamps for various states
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('attendance_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            // Report and evaluation fields
            $table->string('report_path')->nullable();
            $table->text('report_remarks')->nullable();
            $table->timestamp('report_submitted_at')->nullable();
            $table->boolean('report_approved')->nullable();
            
            // Performance and evaluation
            $table->decimal('attendance_percentage', 5, 2)->nullable();
            $table->decimal('performance_score', 5, 2)->nullable();
            
            $table->timestamps();
            
            // Prevent duplicate assignments
            $table->unique(['training_id', 'teacher_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_teachers');
    }
};
