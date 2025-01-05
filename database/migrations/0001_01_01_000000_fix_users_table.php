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
        // Drop all dependent tables in the correct order
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('training_facilitators');
        Schema::dropIfExists('training_enrollments');
        Schema::dropIfExists('training_locations');
        Schema::dropIfExists('training_subjects');
        Schema::dropIfExists('trainings');
        Schema::dropIfExists('facilitators');
        Schema::dropIfExists('teacher_profiles');
        Schema::dropIfExists('personal_info');
        Schema::dropIfExists('organizations');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        
        // Now we can safely drop and recreate the users table
        Schema::dropIfExists('users');
        
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_id', 36)->unique();
            $table->string('email')->unique();
            $table->string('name');
            $table->string('password');
            $table->enum('role', ['super_administrator', 'admin', 'teacher', 'cpd_facilitator', 'organization']);
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');
            $table->timestamp('last_login')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
