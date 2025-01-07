<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Organization;
use App\Models\PersonalInfo;
use App\Models\TeacherProfile;
use App\Models\Facilitator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use App\Models\Notification; // Add this line

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        // Convert role to proper format for validation
        Log::info($request->all());
        $role = strtolower($request->role);
        $request->merge(['role' => $role]);

        Log::info('Starting teacher registration process', ['email' => $request->email, 'role' => $role]);

        $validated = $request->validate([
            'firstname' => ['required', 'string', 'max:50'],
            'lastname' => ['required', 'string', 'max:50'],
            'othername' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'telephone' => ['required', 'string', 'max:15'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:teacher,cpd_facilitator'],
        ]);

        try {
            $result = DB::transaction(function () use ($request) {
                Log::info('Generating user data for creation');
                
                $userData = [
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => $request->role,
                    'status' => 'pending',
                    'user_id' => (string) Str::uuid(),
                    'name' => $request->firstname . ' ' . $request->lastname,
                ];
                
                Log::info('Attempting to create user', ['email' => $userData['email'], 'role' => $userData['role']]);
                
                $user = User::create($userData);
                
                Log::info('User created successfully', ['user_id' => $user->user_id]);

                Log::info('Creating personal info for user');
                
                PersonalInfo::create([
                    'user_id' => $user->user_id,
                    'title' => 'Mr', // Default, can be updated in profile
                    'first_name' => $request->firstname,
                    'middle_name' => $request->othername,
                    'last_name' => $request->lastname,
                    'phone_number' => $request->telephone,
                    'gender' => 'male', // Default to male, can be updated in profile
                    'date_of_birth' => now(), // Can be updated in profile
                    'disability_status' => false // Default value
                ]);

                Log::info('Personal info created successfully');

                // Create role-specific profile
                if ($request->role === 'teacher') {
                    TeacherProfile::create([
                        'teacher_id' => (string) Str::uuid(),
                        'user_id' => $user->user_id,
                        'registration_number' => 'T' . Str::random(8),
                        'status' => 'pending'
                    ]);
                    
                    try {
                        // Create notification for new teacher registration
                        Notification::createSystemNotification(
                            "A new teacher has registered with ID: {$user->user_id}"
                        );
                        Log::info('Teacher profile created successfully');
                    } catch (\Exception $e) {
                        Log::error('Error creating notification: ' . $e->getMessage());
                        // Continue with registration even if notification fails
                    }
                } elseif ($request->role === 'cpd_facilitator') {
                    Facilitator::create([
                        'facilitator_id' => (string) Str::uuid(),
                        'user_id' => $user->user_id,
                        'status' => 'pending',
                        'specialization' => $request->specialization ?? 'General Education',  // Default value
                        'qualifications' => $request->qualifications ?? 'Pending verification',  // Default value
                        'registration_number' => 'F' . Str::random(8)  // Generate a random registration number
                    ]);
                    Log::info('Facilitator profile created successfully');
                }

                return $user;
            });

            if ($result) {
                Log::info('Registration completed successfully', ['user_id' => $result->user_id]);
                return response()->json([
                    'success' => true,
                    'message' => 'Registration successful! Please login to continue.',
                    'redirect' => route('login.role', ['role' => $request->role])
                ]);
            }

            Log::error('Registration failed: Result was null');
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again later.'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again later.'
            ], 500);
        }
    }

    public function storeOrganization(Request $request)
    {
        $validated = $request->validate([
            'orgType' => ['required', 'in:government,private,ngo'],
            'orgName' => ['required', 'string', 'max:255'],
            'firstname' => ['required', 'string', 'max:50'],
            'lastname' => ['required', 'string', 'max:50'],
            'othername' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'telephone' => ['required', 'string', 'max:15'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        try {
            $result = DB::transaction(function () use ($request) {
                // First create the user
                $userData = [
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'organization',
                    'status' => 'pending',
                    'user_id' => (string) Str::uuid(),
                    'name' => $request->firstname . ' ' . $request->lastname,
                ];

                $user = User::create($userData);

                Log::info('User created with ID: ' . $user->user_id);

                // Then create the organization with the user's ID
                Organization::create([
                    'user_id' => $user->user_id,  // Add the user_id to link organization to user
                    'name' => $request->orgName,
                    'type' => strtoupper($request->orgType),  // Convert to uppercase to match enum values
                    'email' => $request->email,
                    'phone' => $request->telephone,
                    'status' => 'pending'
                ]);

                Log::info('Organization created for user: ' . $user->user_id);

                // Finally create the personal info
                PersonalInfo::create([
                    'user_id' => $user->user_id,
                    'title' => 'Mr', // Default, can be updated in profile
                    'first_name' => $request->firstname,
                    'middle_name' => $request->othername,
                    'last_name' => $request->lastname,
                    'phone_number' => $request->telephone,
                    'gender' => 'male', // Default to male, can be updated in profile
                    'date_of_birth' => now(), // Can be updated in profile
                    'disability_status' => false // Default value
                ]);

                Log::info('Personal info created for user: ' . $user->user_id);

                return $user;
            });

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Organization registration successful! Please login to continue.',
                    'redirect' => route('login.role', ['role' => 'organization'])
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Organization registration error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again later.'
            ], 500);
        }
    }
}
