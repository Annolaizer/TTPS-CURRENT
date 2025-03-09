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
            $table->foreignId('training_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('subject_id');
            $table->foreign('subject_id')->references('subject_id')->on('subjects')->onDelete('cascade');
            $table->primary(['training_id', 'subject_id']);
            $table->timestamps();
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
