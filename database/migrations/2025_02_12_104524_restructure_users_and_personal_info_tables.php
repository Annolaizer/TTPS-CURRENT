<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RestructureUsersAndPersonalInfoTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, add new name fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('title', 10)->after('name');
            $table->string('first_name', 50)->after('title');
            $table->string('middle_name', 50)->nullable()->after('first_name');
            $table->string('last_name', 50)->after('middle_name');
        });

        // Copy name data from personal_info to users
        DB::statement("
            UPDATE users u
            INNER JOIN personal_info pi ON u.user_id = pi.user_id
            SET 
                u.title = pi.title,
                u.first_name = pi.first_name,
                u.middle_name = pi.middle_name,
                u.last_name = pi.last_name
        ");

        // Drop old name columns from personal_info
        Schema::table('personal_info', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'first_name',
                'middle_name',
                'last_name'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back name columns to personal_info
        Schema::table('personal_info', function (Blueprint $table) {
            $table->string('title', 10)->after('user_id');
            $table->string('first_name', 50)->after('title');
            $table->string('middle_name', 50)->nullable()->after('first_name');
            $table->string('last_name', 50)->after('middle_name');
        });

        // Copy name data back from users to personal_info
        DB::statement("
            UPDATE personal_info pi
            INNER JOIN users u ON pi.user_id = u.user_id
            SET 
                pi.title = u.title,
                pi.first_name = u.first_name,
                pi.middle_name = u.middle_name,
                pi.last_name = u.last_name
        ");

        // Drop name columns from users
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'first_name',
                'middle_name',
                'last_name'
            ]);
        });
    }
}
