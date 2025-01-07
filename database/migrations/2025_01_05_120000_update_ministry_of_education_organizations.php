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
        DB::statement("ALTER TABLE organizations MODIFY COLUMN type ENUM('Government', 'Other Government', 'NGO', 'Private')");

        // Update Ministry of Education organizations
        DB::table('organizations')
            ->where('name', 'like', '%Ministry of Education%')
            ->update(['type' => 'Government']);

        // Update other government organizations
        DB::table('organizations')
            ->where('type', 'government')
            ->where('name', '!=', 'Ministry of Education')
            ->update(['type' => 'Other Government']);

        DB::table('organizations')
            ->where('type', 'ngo')
            ->update(['type' => 'NGO']);

        DB::table('organizations')
            ->where('type', 'private')
            ->update(['type' => 'Private']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, update all Government and Other Government back to government
        DB::table('organizations')
            ->whereIn('type', ['Government', 'Other Government'])
            ->update(['type' => 'government']);

        // Then modify the column back to original enum
        DB::statement("ALTER TABLE organizations MODIFY COLUMN type ENUM('government', 'ngo', 'private')");
    }
};
