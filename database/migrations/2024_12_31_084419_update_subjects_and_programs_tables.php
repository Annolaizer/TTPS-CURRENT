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
        // Create or update subjects table
        if (!Schema::hasTable('subjects')) {
            Schema::create('subjects', function (Blueprint $table) {
                $table->id('subject_id');
                $table->string('subject_name')->unique();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('subjects', function (Blueprint $table) {
                if (!Schema::hasColumn('subjects', 'created_at')) {
                    $table->timestamps();
                }
            });
        }

        // Create or update programs table
        if (!Schema::hasTable('programs')) {
            Schema::create('programs', function (Blueprint $table) {
                $table->id();
                $table->string('program_name');
                $table->text('description')->nullable();
                $table->unsignedBigInteger('subject_id');
                $table->foreign('subject_id')->references('subject_id')->on('subjects')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
        Schema::dropIfExists('subjects');
    }
};
