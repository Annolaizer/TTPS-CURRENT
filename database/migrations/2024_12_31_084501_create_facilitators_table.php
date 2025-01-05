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
        Schema::create('facilitators', function (Blueprint $table) {
            $table->uuid('facilitator_id')->primary();
            $table->string('user_id', 36);
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->string('specialization', 100);
            $table->text('qualifications');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilitators');
    }
};
