<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtAuthenticateMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            // Handle exceptions (e.g., token expired, invalid token)
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Unauthorize'
            ], 401);
        }

        if (!$user) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Unauthorize'
            ], 401);
        }

        if (
            count($roles) > 0
            && (!$user->role || !in_array($user->role, $roles))
        ) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Unauthorize, invalid user'
            ], 401);
        }


        // You now have the currently logged-in user in the $user variable
        // You can do something with $user here

        return $next($request);
    }
}
