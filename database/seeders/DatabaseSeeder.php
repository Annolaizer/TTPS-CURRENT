<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\TanzaniaLocationsSeeder;
use Database\Seeders\AdminUsersSeeder;
use Database\Seeders\TeacherSeeder;
use Database\Seeders\CpdFacilitatorSeeder;
use Database\Seeders\SubjectSeeder;
use Database\Seeders\OrganizationSeeder;
use Database\Seeders\TrainingSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TanzaniaLocationsSeeder::class,
            AdminUsersSeeder::class,
            TeacherSeeder::class,
            CpdFacilitatorSeeder::class,
            SubjectSeeder::class,
            OrganizationSeeder::class,
            TrainingSeeder::class,
        ]);

        // User::factory(10)->create();

        // Commented out default user creation as we'll handle users differently
        /*
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        */
    }
}
