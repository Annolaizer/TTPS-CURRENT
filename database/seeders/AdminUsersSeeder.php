<?php

namespace Database\Seeders;

use App\Models\PersonalInfo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Create super admin user if not exists
            $superAdmin = User::firstOrCreate(
                ['email' => 'superadmin@ttps.go.tz'],
                [
                    'name' => 'Super Administrator',
                    'password' => Hash::make('SuperAdmin@2024'),
                    'role' => 'super_administrator',
                    'status' => 'active'
                ]
            );

            // Create personal info for super admin if not exists
            PersonalInfo::firstOrCreate(
                ['user_id' => $superAdmin->user_id],
                [
                    'phone_number' => '0700000001',
                    'gender' => 'male',
                    'date_of_birth' => now(),
                    'disability_status' => false
                ]
            );

            // Create MoEST Admin User if not exists
            $moestAdmin = User::firstOrCreate(
                ['email' => 'moest@ttps.go.tz'],
                [
                    'name' => 'MoEST Administrator',
                    'password' => Hash::make('MoEST@2024'),
                    'role' => 'admin',
                    'status' => 'active'
                ]
            );

            // Create personal info for MoEST admin if not exists
            PersonalInfo::firstOrCreate(
                ['user_id' => $moestAdmin->user_id],
                [
                    'phone_number' => '0700000002',
                    'gender' => 'male',
                    'date_of_birth' => now(),
                    'disability_status' => false
                ]
            );
        });
    }
}