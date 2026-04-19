<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Get token from cookie if not in Header
        $token = $request->cookie('token');

        if ($token) {
            try {
                // 2. Set the token and authenticate
                JWTAuth::setToken($token);
                $user = JWTAuth::authenticate();

                if ($user) {
                    // 3. Log the user into the web guard (session-less but populates Auth::user())
                    Auth::login($user);
                }
            } catch (Exception $e) {
                // Token invalid or expired - ignore and let guest proceed or 'auth' middleware catch it
            }
        }

        return $next($request);
    }
}
