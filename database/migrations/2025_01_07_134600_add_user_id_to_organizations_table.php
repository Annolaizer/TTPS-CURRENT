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
        // Create a default admin user if it doesn't exist
        DB::table('users')->insertOrIgnore([
            'user_id' => '00000000-0000-0000-0000-000000000001',
            'name' => 'System Admin',
            'email' => 'admin@ttp.go.tz',
            'password' => bcrypt('admin123'), // Change this in production
            'role' => 'admin',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Check if user_id column exists
        if (!Schema::hasColumn('organizations', 'user_id')) {
            // First add the column without constraints
            Schema::table('organizations', function (Blueprint $table) {
                $table->uuid('user_id')->after('organization_id')->nullable();
            });
        }

        // Assign existing organizations to admin user
        DB::table('organizations')
            ->whereNull('user_id')
            ->orWhere('user_id', '')
            ->update(['user_id' => '00000000-0000-0000-0000-000000000001']);

        // Make sure all organizations have a valid user_id
        $invalidOrgs = DB::table('organizations')
            ->whereNotIn('user_id', function($query) {
                $query->select('user_id')->from('users');
            })
            ->update(['user_id' => '00000000-0000-0000-0000-000000000001']);

        // Now add the foreign key constraint
        Schema::table('organizations', function (Blueprint $table) {
            try {
                $table->uuid('user_id')->nullable(false)->change();
                $table->foreign('user_id')
                      ->references('user_id')
                      ->on('users')
                      ->onDelete('cascade');
            } catch (\Exception $e) {
                // Foreign key might already exist
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            try {
                $table->dropForeign(['user_id']);
            } catch (\Exception $e) {
                // Foreign key might not exist
            }
            $table->dropColumn('user_id');
        });
    }
};
