<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CpdFacilitatorMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || $request->user()->role !== 'cpd_facilitator') {
            abort(403, 'Unauthorized action. Only CPD Facilitators can access this area.');
        }

        return $next($request);
    }
}
