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
        Schema::create('training_locations', function (Blueprint $table) {
            $table->foreignId('training_id')->constrained()->onDelete('cascade');
            $table->foreignId('ward_id')->constrained('wards', 'ward_id');
            $table->string('venue_name', 255);
            $table->primary(['training_id', 'ward_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_locations');
    }
};
