<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $educationLevels = [
            'Pre Primary Education' => 40,  // 40% of teachers
            'Primary Education' => 60,      // 60% of teachers
            'Lower Secondary Education' => 0,
            'Upper Secondary Education' => 0
        ];

        $subjects = [
            'Mathematics', 'English', 'Kiswahili', 'Science', 'Social Studies',
            'Geography', 'History', 'Biology', 'Chemistry', 'Physics'
        ];

        $now = Carbon::now();
        $titles = ['Mr.', 'Mrs.', 'Ms.', 'Dr.'];

        // Get a random ward_id from the wards table
        $wardIds = DB::table('wards')->pluck('ward_id')->toArray();
        if (empty($wardIds)) {
            throw new \Exception('No wards found in the database. Please run the TanzaniaLocationsSeeder first.');
        }

        // Create 100 teachers
        for ($i = 0; $i < 100; $i++) {
            // Create user first
            $userId = (string) Str::uuid();
            
            DB::table('users')->insert([
                'user_id' => $userId,
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('Teacher@2024'),
                'role' => 'teacher',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Determine gender
            $gender = fake()->randomElement(['male', 'female']);
            
            // Determine disability status and type
            $hasDisability = fake()->boolean(10); // 10% chance of having a disability
            $disabilityType = $hasDisability ? fake()->randomElement(['Visual', 'Hearing', 'Physical', 'Other']) : null;

            // Create personal info
            DB::table('personal_info')->insert([
                'user_id' => $userId,
                'title' => fake()->randomElement($titles),
                'first_name' => fake()->firstName($gender),
                'middle_name' => fake()->optional()->firstName($gender),
                'last_name' => fake()->lastName(),
                'gender' => $gender,
                'date_of_birth' => fake()->dateTimeBetween('-60 years', '-25 years')->format('Y-m-d'),
                'phone_number' => fake()->numerify('255#########'),
                'disability_status' => $hasDisability,
                'disability_type' => $disabilityType,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Determine education level based on distribution
            $random = rand(1, 100);
            $level = '';
            $sum = 0;
            foreach ($educationLevels as $educationLevel => $percentage) {
                $sum += $percentage;
                if ($random <= $sum) {
                    $level = $educationLevel;
                    break;
                }
            }

            // Create teacher profile
            DB::table('teacher_profiles')->insert([
                'teacher_id' => (string) Str::uuid(),
                'user_id' => $userId,
                'registration_number' => 'TTP' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'education_level' => $level,
                'teaching_subject' => fake()->randomElement($subjects),
                'years_of_experience' => fake()->numberBetween(1, 30),
                'current_school' => fake()->company() . ' School',
                'ward_id' => fake()->randomElement($wardIds),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
