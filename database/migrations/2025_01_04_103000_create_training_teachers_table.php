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
            $table->foreignId('training_id')->constrained('trainings', 'training_id')->onDelete('cascade');
            $table->string('teacher_id', 36);
            $table->foreign('teacher_id')->references('teacher_id')->on('teacher_profiles')->onDelete('cascade');
            $table->enum('status', ['active', 'inactive'])->default('active');
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
