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
        Schema::create('training_subjects', function (Blueprint $table) {
            $table->foreignId('training_id')->constrained('trainings', 'training_id');
            $table->foreignId('subject_id')->constrained('subjects', 'subject_id');
            $table->primary(['training_id', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_subjects');
    }
};
