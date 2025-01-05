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
        Schema::create('teacher_profiles', function (Blueprint $table) {
            $table->uuid('teacher_id')->primary();
            $table->string('user_id', 36);
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->string('registration_number', 50)->unique();
            $table->string('education_level', 100)->nullable();
            $table->string('teaching_subject', 100)->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->string('current_school', 255)->nullable();
            $table->foreignId('ward_id')->nullable()->constrained('wards', 'ward_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_profiles');
    }
};
