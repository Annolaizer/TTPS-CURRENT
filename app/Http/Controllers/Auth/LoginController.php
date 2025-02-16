<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm($role)
    {
        $roles = ['teacher', 'organization', 'cpd_facilitator', 'admin', 'super_administrator'];

        if (!in_array($role, $roles)) {
            abort(404);
        }

        $displayRole = ucfirst(str_replace('_', ' ', $role));
        
        if ($role === 'admin' || $role === 'super_administrator') {
            return view('auth.login.admin.index');
        }

        return view('auth.login.index', ['role' => $displayRole]);
    }

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
            if (!Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'password' => ['Invalid password.'],
                ]);
            }

            // For admin login page, check if user is either admin or super_administrator
            if ($credentials['role'] === 'admin' && !in_array($user->role, ['admin', 'super_administrator'])) {
                throw ValidationException::withMessages([
                    'email' => ['This account does not have administrative access.'],
                ]);
            }

            // For other roles, check exact match
            if ($credentials['role'] !== 'admin' && $user->role !== $credentials['role']) {
                throw ValidationException::withMessages([
                    'role' => ['The provided credentials do not match the selected role.'],
                ]);
            }

            // Log in the user
            Auth::login($user);
            $request->session()->regenerate();

            // Update last login timestamp
            $user->update(['last_login' => now()]);

            // Redirect to admin dashboard for both admin and super_administrator
            $redirectPath = match($user->role) {
                'admin', 'super_administrator' => route('admin.dashboard'),
                'teacher' => route('teacher.dashboard'),
                'cpd_facilitator' => route('cpd_facilitator.dashboard'),
                'organization' => route('organization.dashboard'),
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
                ]);
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
        $userRole = Auth::user()->role;
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect admin users back to admin login page
        if (in_array($userRole, ['admin', 'super_administrator'])) {
            return redirect()->route('login.role', 'admin');
        }

        return redirect('/');
    }
}
