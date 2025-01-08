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
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('approval_letter_path')->nullable()->after('status');
            $table->timestamp('approval_letter_uploaded_at')->nullable()->after('approval_letter_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('approval_letter_uploaded_at');
            $table->dropColumn('approval_letter_path');
        });
    }
};
