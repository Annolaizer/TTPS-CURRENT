<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Faker\Generator as Faker;

class CpdFacilitatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specializations = [
            'Mathematics Education',
            'Science Education',
            'Language Arts',
            'Early Childhood Education',
            'Special Education',
            'Educational Technology',
            'Curriculum Development',
            'Educational Leadership',
            'STEM Education',
            'Inclusive Education'
        ];

        $qualifications = [
            'Ph.D. in Education',
            'Master of Education (M.Ed.)',
            'Master of Arts in Teaching',
            'Bachelor of Education with 10+ years experience',
            'Master of Science in Educational Technology',
            'Doctor of Educational Leadership'
        ];

        // Create 20 CPD Facilitators
        for ($i = 0; $i < 20; $i++) {
            $userId = (string) Str::uuid();
            $facilitatorId = (string) Str::uuid();
            $title = fake()->randomElement(['Mr.', 'Mrs.', 'Ms.', 'Dr.', 'Prof.']);
            $firstName = fake()->firstName();
            $middleName = fake()->optional(0.7)->firstName();
            $lastName = fake()->lastName();
            $now = Carbon::now();

            // Insert into users table
            DB::table('users')->insert([
                'user_id' => $userId,
                'email' => fake()->unique()->safeEmail(),
                'name' => "$title $firstName $lastName",
                'title' => $title,
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'last_name' => $lastName,
                'password' => Hash::make('password123'), // Default password
                'role' => 'cpd_facilitator',
                'status' => fake()->randomElement(['active', 'inactive', 'pending']),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Insert into personal_info table
            DB::table('personal_info')->insert([
                'user_id' => $userId,
                'gender' => fake()->randomElement(['male', 'female']),
                'date_of_birth' => fake()->dateTimeBetween('-60 years', '-25 years')->format('Y-m-d'),
                'phone_number' => fake()->numerify('+255-###-###-###'),
                'disability_status' => fake()->boolean(10), // 10% chance of having a disability
                'disability_type' => fake()->boolean(10) ? fake()->randomElement([
                    'Visual Impairment',
                    'Hearing Impairment',
                    'Physical Disability',
                    'Other'
                ]) : null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Insert into facilitators table
            DB::table('facilitators')->insert([
                'facilitator_id' => $facilitatorId,
                'user_id' => $userId,
                'specialization' => fake()->randomElement($specializations),
                'qualifications' => json_encode([
                    'degree' => fake()->randomElement($qualifications),
                    'certifications' => [
                        fake()->randomElement([
                            'Professional Teacher Certification',
                            'Educational Leadership Certificate',
                            'Digital Learning Specialist Certificate',
                            'Special Education Certification'
                        ]),
                        fake()->randomElement([
                            'Advanced Pedagogical Methods Certificate',
                            'Curriculum Design Excellence Certificate',
                            'Educational Assessment Specialist',
                            'Teaching Excellence Award'
                        ])
                    ],
                    'experience_years' => fake()->numberBetween(5, 25)
                ]),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
