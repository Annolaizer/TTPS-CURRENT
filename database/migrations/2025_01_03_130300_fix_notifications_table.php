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
        Schema::table('notifications', function (Blueprint $table) {
            // Drop the existing notification_id column if it exists
            if (Schema::hasColumn('notifications', 'notification_id')) {
                $table->dropColumn('notification_id');
            }
            
            // Add the notification_id column as auto-incrementing primary key
            $table->id('notification_id')->first();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('notification_id');
        });
    }
};
