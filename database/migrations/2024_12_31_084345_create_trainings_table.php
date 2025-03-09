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
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('training_code', 20)->unique();
            $table->foreignId('organization_id')->constrained('organizations', 'organization_id');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('education_level', 50);
            $table->integer('duration_days');
            $table->integer('max_participants');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['draft', 'pending', 'verified', 'rejected', 'completed'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
