<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // Admin role == 2
            if (Auth::user()->role == '2') {
                return $next($request);
            } else {
                return response()->json([
                    'message' => 'Access denied',
                ], 403);
            }
        } else {
            return response()->json([
                'message' => 'Unauthenticated',
            ], 401);
        }
    }
}
