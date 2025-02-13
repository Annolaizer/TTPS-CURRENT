<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePersonalInfoFields extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, create a temporary table to store existing data
        Schema::create('personal_info_temp', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 36);
            $table->enum('gender', ['male', 'female']);
            $table->date('date_of_birth');
            $table->string('phone_number', 20);
            $table->boolean('disability_status')->default(false);
            $table->string('disability_type', 50)->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        // Copy existing data to temp table
        DB::statement("
            INSERT INTO personal_info_temp (
                user_id, gender, date_of_birth, phone_number, 
                disability_status, disability_type, created_at, updated_at
            )
            SELECT 
                user_id, gender, date_of_birth, phone_number,
                disability_status, disability_type, created_at, updated_at
            FROM personal_info
        ");

        // Drop the original table
        Schema::dropIfExists('personal_info');

        // Rename temp table to original
        Schema::rename('personal_info_temp', 'personal_info');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Create original table structure
        Schema::create('personal_info_temp', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 36);
            $table->string('title', 10);
            $table->string('first_name', 50);
            $table->string('middle_name', 50)->nullable();
            $table->string('last_name', 50);
            $table->enum('gender', ['male', 'female']);
            $table->date('date_of_birth');
            $table->string('phone_number', 20);
            $table->boolean('disability_status')->default(false);
            $table->string('disability_type', 50)->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        // Copy data back with default values for name fields
        DB::statement("
            INSERT INTO personal_info_temp (
                user_id, title, first_name, middle_name, last_name,
                gender, date_of_birth, phone_number, 
                disability_status, disability_type, created_at, updated_at
            )
            SELECT 
                user_id, '', '', '', '',
                gender, date_of_birth, phone_number,
                disability_status, disability_type, created_at, updated_at
            FROM personal_info
        ");

        // Drop current table
        Schema::dropIfExists('personal_info');

        // Rename temp table to original
        Schema::rename('personal_info_temp', 'personal_info');
    }
}
