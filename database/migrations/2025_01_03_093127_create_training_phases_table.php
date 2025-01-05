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
        Schema::create('training_phases', function (Blueprint $table) {
            $table->id('phase_id');
            $table->foreignId('training_id')->constrained('trainings', 'training_id')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->text('schedule');
            $table->string('location');
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time');
            $table->integer('max_participants');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_phases');
    }
};
