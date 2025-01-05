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

        $organizations = [
            [
                'name' => 'Ministry of Education',
                'type' => 'government',
                'registration_number' => 'GOV001',
                'email' => 'info@moe.go.tz',
                'phone' => '+255123456789',
                'address' => 'Dodoma, Tanzania',
                'status' => 'active'
            ],
            [
                'name' => 'Tanzania Education Network',
                'type' => 'ngo',
                'registration_number' => 'NGO001',
                'email' => 'info@tenmet.org',
                'phone' => '+255987654321',
                'address' => 'Dar es Salaam, Tanzania',
                'status' => 'active'
            ],
            [
                'name' => 'Education Quality Improvers Ltd',
                'type' => 'private',
                'registration_number' => 'PVT001',
                'email' => 'info@eqi.co.tz',
                'phone' => '+255111222333',
                'address' => 'Arusha, Tanzania',
                'status' => 'active'
            ],
            [
                'name' => 'Teachers Development Association',
                'type' => 'ngo',
                'registration_number' => 'NGO002',
                'email' => 'info@tda.or.tz',
                'phone' => '+255444555666',
                'address' => 'Mwanza, Tanzania',
                'status' => 'active'
            ],
            [
                'name' => 'Regional Education Office - Dodoma',
                'type' => 'government',
                'registration_number' => 'GOV002',
                'email' => 'reo.dodoma@gov.tz',
                'phone' => '+255777888999',
                'address' => 'Dodoma, Tanzania',
                'status' => 'active'
            ]
        ];

        foreach ($organizations as $org) {
            Organization::firstOrCreate(
                ['email' => $org['email']],
                $org
            );
        }
    }
}
