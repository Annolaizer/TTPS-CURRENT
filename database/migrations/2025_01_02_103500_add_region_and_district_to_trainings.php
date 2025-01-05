<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddRegionAndDistrictToTrainings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if foreign keys exist and drop them if they do
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_NAME = 'trainings'
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
            AND CONSTRAINT_NAME IN ('trainings_region_id_foreign', 'trainings_district_id_foreign')
        ");

        foreach ($foreignKeys as $key) {
            Schema::table('trainings', function (Blueprint $table) use ($key) {
                $table->dropForeign($key->CONSTRAINT_NAME);
            });
        }

        // Add foreign key constraints
        Schema::table('trainings', function (Blueprint $table) {
            // Add columns first
            $table->unsignedBigInteger('region_id')->after('training_id');
            $table->unsignedBigInteger('district_id')->after('region_id');

            // Make sure columns are not nullable
            $table->unsignedBigInteger('region_id')->nullable(false)->change();
            $table->unsignedBigInteger('district_id')->nullable(false)->change();

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
            $table->dropColumn('region_id');
            $table->dropColumn('district_id');
        });
    }
}
