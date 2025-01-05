<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update the type column to allow the new values
        DB::statement("ALTER TABLE organizations MODIFY COLUMN type ENUM('ministry_of_education', 'other_government', 'ngo', 'private')");

        // Update Ministry of Education organizations
        DB::table('organizations')
            ->where('type', 'government')
            ->where('name', 'Ministry of Education')
            ->update(['type' => 'ministry_of_education']);

        // Update other government organizations
        DB::table('organizations')
            ->where('type', 'government')
            ->where('name', '!=', 'Ministry of Education')
            ->update(['type' => 'other_government']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, update all ministry_of_education and other_government back to government
        DB::table('organizations')
            ->whereIn('type', ['ministry_of_education', 'other_government'])
            ->update(['type' => 'government']);

        // Then modify the column back to original enum
        DB::statement("ALTER TABLE organizations MODIFY COLUMN type ENUM('government', 'ngo', 'private')");
    }
};
