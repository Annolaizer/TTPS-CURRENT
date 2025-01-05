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
            // Create super admin user
            $superAdmin = User::create([
                'name' => 'Super Administrator',
                'email' => 'superadmin@ttps.go.tz',
                'password' => Hash::make('SuperAdmin@2024'),
                'role' => 'super_administrator',
                'status' => 'active'
            ]);

            // Create personal info for super admin
            PersonalInfo::create([
                'user_id' => $superAdmin->user_id,
                'title' => 'Mr',
                'first_name' => 'Super',
                'last_name' => 'Administrator',
                'phone_number' => '0700000001',
                'gender' => 'male',
                'date_of_birth' => now()
            ]);

            // Create Admin User
            $admin = User::create([
                'name' => 'System Administrator',
                'email' => 'admin@ttps.go.tz',
                'password' => Hash::make('Admin@2024'),
                'role' => 'admin',
                'status' => 'active'
            ]);

            // Create personal info for admin
            PersonalInfo::create([
                'user_id' => $admin->user_id,
                'title' => 'Mr',
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'phone_number' => '0700000002',
                'gender' => 'male',
                'date_of_birth' => now()
            ]);
        });
    }
}
