<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                // Determine redirect path based on role
                $redirectPath = match($user->role) {
                    'admin', 'super_administrator' => route('admin.dashboard'),
                    'teacher', 'organization', 'cpd_facilitator' => '/dashboard',
                    default => '/'
                };

                // Return JSON response for AJAX request
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Already authenticated',
                        'redirect' => $redirectPath
                    ]);
                }

                return redirect($redirectPath);
            }
        }

        return $next($request);
    }
}
