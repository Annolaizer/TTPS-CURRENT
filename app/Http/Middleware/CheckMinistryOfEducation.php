<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckMinistryOfEducation
{
    public function handle(Request $request, Closure $next)
    {
        // During testing/development, allow all requests
        if (config('app.env') !== 'production') {
            return $next($request);
        }

        $user = $request->user();
        
        // Super admins can always access
        if ($user && $user->isSuperAdmin()) {
            return $next($request);
        }

        // Get user's organization with eager loading
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated'
            ], 401);
        }

        $organization = $user->organization()->first();
        
        // Check if user has an organization
        if (!$organization) {
            return response()->json([
                'status' => 'error',
                'message' => 'User does not belong to any organization'
            ], 403);
        }

        // Check if organization is Ministry of Education
        if (!$organization->isMinistryOfEducation()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Only Ministry of Education can perform this action'
            ], 403);
        }

        // Check if organization is active
        if ($organization->status !== 'active') {
            return response()->json([
                'status' => 'error',
                'message' => 'Organization account is not active'
            ], 403);
        }

        return $next($request);
    }
}
