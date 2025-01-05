<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixTrainingLocationColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop existing foreign key constraints first
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
            $table->dropForeign(['district_id']);
        });

        // Drop existing columns if they exist
        Schema::table('trainings', function (Blueprint $table) {
            if (Schema::hasColumn('trainings', 'region_id')) {
                $table->dropColumn('region_id');
            }
            if (Schema::hasColumn('trainings', 'district_id')) {
                $table->dropColumn('district_id');
            }
        });

        // Add columns back with proper structure
        Schema::table('trainings', function (Blueprint $table) {
            $table->unsignedBigInteger('region_id')->after('organization_id');
            $table->unsignedBigInteger('district_id')->after('region_id');

            // Add foreign key constraints
            $table->foreign('region_id')
                  ->references('region_id')
                  ->on('regions')
                  ->onDelete('restrict');

            $table->foreign('district_id')
                  ->references('district_id')
                  ->on('districts')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
            $table->dropForeign(['district_id']);
            $table->dropColumn(['region_id', 'district_id']);
        });
    }
}
