<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Event;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Temporarily disable model events
        Event::fake();

        Organization::create([
            'name' => 'Ministry of Education',
            'type' => 'Government',
            'registration_number' => 'GOV001',
            'email' => 'info@moe.go.tz',
            'phone' => '+255123456789',
            'address' => 'Dodoma',
            'status' => 'active'
        ]);

        Organization::create([
            'name' => 'Tanzania Education Network',
            'type' => 'NGO',
            'registration_number' => 'NGO001',
            'email' => 'info@tenmet.org',
            'phone' => '+255987654321',
            'address' => 'Dar es Salaam',
            'status' => 'active'
        ]);

        Organization::create([
            'name' => 'Education Quality Improvers Ltd',
            'type' => 'Private',
            'registration_number' => 'PVT001',
            'email' => 'info@eqi.co.tz',
            'phone' => '+255111222333',
            'address' => 'Arusha',
            'status' => 'active'
        ]);

        Organization::create([
            'name' => 'Teachers Development Association',
            'type' => 'NGO',
            'registration_number' => 'NGO002',
            'email' => 'info@tda.or.tz',
            'phone' => '+255444555666',
            'address' => 'Mwanza',
            'status' => 'active'
        ]);
    }
}
