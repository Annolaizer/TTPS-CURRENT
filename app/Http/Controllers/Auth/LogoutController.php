<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogoutController extends Controller
{
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        try {
            // Get user info before logout for logging
            $user = Auth::user();
            $role = $user ? $user->role : 'unknown';
            $email = $user ? $user->email : 'unknown';

            // Perform logout
            Auth::logout();
            
            // Clear and invalidate session
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Log the successful logout
            Log::info('User logged out successfully', [
                'email' => $email,
                'role' => $role,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // If it's an AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logged out successfully',
                    'redirect' => route('home')
                ]);
            }

            // For regular form submission
            return redirect()->route('home')->with('success', 'Logged out successfully');

        } catch (\Exception $e) {
            // Log the error
            Log::error('Logout failed', [
                'error' => $e->getMessage(),
                'user' => $email ?? 'unknown',
                'role' => $role ?? 'unknown'
            ]);

            // If it's an AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Logout failed. Please try again.'
                ], 500);
            }

            // For regular form submission
            return redirect()->back()->with('error', 'Logout failed. Please try again.');
        }
    }
}
