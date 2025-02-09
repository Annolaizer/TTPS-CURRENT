<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ClearAndFixTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create backup of trainings and training_subjects tables
        DB::statement('CREATE TABLE trainings_backup LIKE trainings');
        DB::statement('INSERT INTO trainings_backup SELECT * FROM trainings');
        DB::statement('CREATE TABLE training_subjects_backup LIKE training_subjects');
        DB::statement('INSERT INTO training_subjects_backup SELECT * FROM training_subjects');

        // Clear training_subjects table first (due to foreign key constraint)
        DB::table('training_subjects')->delete();
        
        // Clear trainings table
        DB::table('trainings')->delete();

        // Drop existing columns if they exist
        Schema::table('trainings', function (Blueprint $table) {
            // Drop foreign keys if they exist
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.TABLE_CONSTRAINTS
                WHERE TABLE_NAME = 'trainings'
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'
                AND CONSTRAINT_NAME IN ('trainings_region_id_foreign', 'trainings_district_id_foreign')
            ");

            foreach ($foreignKeys as $key) {
                $table->dropForeign($key->CONSTRAINT_NAME);
            }

            if (Schema::hasColumn('trainings', 'region_id')) {
                if (DB::getSchemaBuilder()->getColumnType('trainings', 'region_id') !== 'bigint') {
                    $table->dropColumn('region_id');
                }
            }
            if (Schema::hasColumn('trainings', 'district_id')) {
                if (DB::getSchemaBuilder()->getColumnType('trainings', 'district_id') !== 'bigint') {
                    $table->dropColumn('district_id');
                }
            }
        });

        // Add columns with proper structure if they don't exist
        Schema::table('trainings', function (Blueprint $table) {
            if (!Schema::hasColumn('trainings', 'region_id')) {
                $table->unsignedBigInteger('region_id')->after('organization_id');
            }
            if (!Schema::hasColumn('trainings', 'district_id')) {
                $table->unsignedBigInteger('district_id')->after('region_id');
            }

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
        // Clear existing data
        DB::table('training_subjects')->delete();
        DB::table('trainings')->delete();

        // Restore backups with explicit column mapping
        DB::statement('
            INSERT INTO trainings (
                id, organization_id, training_type, title, description, 
                start_date, end_date, status, venue, venue_name, 
                ward_id, created_at, updated_at, verified_at, verified_by
            )
            SELECT 
                id, organization_id, training_type, title, description, 
                start_date, end_date, status, venue, venue_name, 
                ward_id, created_at, updated_at, verified_at, verified_by
            FROM trainings_backup
        ');
        
        DB::statement('
            INSERT INTO training_subjects (
                training_id, subject_id, created_at, updated_at
            )
            SELECT 
                training_id, subject_id, created_at, updated_at
            FROM training_subjects_backup
        ');
        
        // Drop backup tables
        DB::statement('DROP TABLE trainings_backup');
        DB::statement('DROP TABLE training_subjects_backup');

        Schema::table('trainings', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
            $table->dropForeign(['district_id']);
            $table->dropColumn(['region_id', 'district_id']);
        });
    }
}
