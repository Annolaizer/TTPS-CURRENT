<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyTrainingsTableStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop existing foreign keys if they exist
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
        });
    }
}
