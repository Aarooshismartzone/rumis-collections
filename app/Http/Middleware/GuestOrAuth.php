<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class GuestOrAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in via Sanctum
        if (Auth::guard('sanctum')->check()) {
            return $next($request);
        }

        // Check if guest token is provided and valid
        $token = $request->bearerToken();
        if ($token && Cache::has('guest_token_' . $token)) {
            return $next($request);
        }
        return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
    }
}
