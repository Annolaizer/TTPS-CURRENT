<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        // Insert default region
        DB::table('regions')->insert([
            'region_id' => 1,
            'name' => 'Default Region',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Insert default district
        DB::table('districts')->insert([
            'district_id' => 1,
            'region_id' => 1,
            'name' => 'Default District',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
