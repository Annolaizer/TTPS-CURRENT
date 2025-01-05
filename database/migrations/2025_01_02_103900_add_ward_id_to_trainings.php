<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddWardIdToTrainings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add ward_id column first
        Schema::table('trainings', function (Blueprint $table) {
            $table->unsignedBigInteger('ward_id')->after('district_id')->nullable();
        });

        // Add foreign key constraint
        Schema::table('trainings', function (Blueprint $table) {
            $table->foreign('ward_id')
                  ->references('ward_id')
                  ->on('wards')
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
            $table->dropForeign(['ward_id']);
            $table->dropColumn('ward_id');
        });
    }
}
