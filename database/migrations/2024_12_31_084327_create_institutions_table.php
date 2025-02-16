<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->uuid('institution_id')->primary();
            $table->string('registration_number')->unique();
            $table->string('name');
            $table->enum('type', ['Primary', 'Secondary', 'College', 'University']);
            $table->string('region');
            $table->string('district');
            $table->unsignedBigInteger('ward_id');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
