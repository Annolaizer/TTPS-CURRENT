<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
                'role' => ['required', 'in:teacher,organization,cpd_facilitator,admin,super_administrator'],
            ]);

            // Find the user by email
            $user = User::where('email', $credentials['email'])->first();
            
            if (!$user) {
                throw ValidationException::withMessages([
                    'email' => ['User not found with this email.'],
                ]);
            }

            // Check password
            if (!Hash::check($request->password, $user->password_hash)) {
                throw ValidationException::withMessages([
                    'password' => ['Invalid password.'],
                ]);
            }

            // Check role
            if ($user->role !== $credentials['role']) {
                throw ValidationException::withMessages([
                    'role' => ['The provided credentials do not match the selected role.'],
                ]);
            }

            // Log in the user
            Auth::login($user);
            $request->session()->regenerate();

            // Update last login timestamp
            $user->update(['last_login' => now()]);

            // Using test route for admin dashboard
            $redirectPath = match($user->role) {
                'admin', 'super_administrator' => route('test.admin.dashboard'),
                'teacher', 'organization', 'cpd_facilitator' => '/dashboard',
                default => '/'
            };

            // Return JSON response for AJAX request
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful',
                    'redirect' => $redirectPath,
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role
                    ]
                ]);
            }

            // Return redirect for non-AJAX request
            return redirect()->intended($redirectPath);

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred during login.',
                    'error' => $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
