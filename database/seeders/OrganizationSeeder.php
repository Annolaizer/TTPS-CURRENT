<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Temporarily disable model events
        Event::fake();
        
        $now = Carbon::now();
        
        $organizations = [
            [
                'name' => 'Ministry of Education',
                'type' => 'Government',
                'registration_number' => 'GOV001',
                'email' => 'info@moe.go.tz',
                'phone' => '+255123456789',
                'address' => 'Dodoma',
                'status' => 'active'
            ],
            [
                'name' => 'Tanzania Education Network',
                'type' => 'NGO',
                'registration_number' => 'NGO001',
                'email' => 'info@tenmet.org',
                'phone' => '+255987654321',
                'address' => 'Dar es Salaam',
                'status' => 'active'
            ],
            [
                'name' => 'Education Quality Improvers Ltd',
                'type' => 'Private',
                'registration_number' => 'PVT001',
                'email' => 'info@eqi.co.tz',
                'phone' => '+255111222333',
                'address' => 'Arusha',
                'status' => 'active'
            ],
            [
                'name' => 'Teachers Development Association',
                'type' => 'NGO',
                'registration_number' => 'NGO002',
                'email' => 'info@tda.or.tz',
                'phone' => '+255444555666',
                'address' => 'Mwanza',
                'status' => 'active'
            ]
        ];

        foreach ($organizations as $org) {
            // Create user first
            $userId = (string) Str::uuid();
            $title = 'Mr.';
            $firstName = explode(' ', $org['name'])[0];
            $lastName = 'Admin';
            
            // Insert into users table
            DB::table('users')->insert([
                'user_id' => $userId,
                'email' => $org['email'],
                'name' => "$title $firstName $lastName",
                'title' => $title,
                'first_name' => $firstName,
                'middle_name' => null,
                'last_name' => $lastName,
                'password' => Hash::make('Organization@2024'),
                'role' => 'organization',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Insert into personal_info table
            DB::table('personal_info')->insert([
                'user_id' => $userId,
                'gender' => 'male',
                'date_of_birth' => '1980-01-01',
                'phone_number' => $org['phone'],
                'disability_status' => false,
                'disability_type' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Create organization
            Organization::create(array_merge($org, [
                'user_id' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }
}
