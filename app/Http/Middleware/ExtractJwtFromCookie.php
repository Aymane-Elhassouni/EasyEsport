<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExtractJwtFromCookie
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('token');
        \Illuminate\Support\Facades\Log::info('JwtBridge: Cookie token is: ' . ($token ? 'Present' : 'Missing'));

        if ($token) {
            $request->headers->set('Authorization', 'Bearer ' . $token);
            
            try {
                \PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth::setToken($token);
                if ($user = \PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth::authenticate()) {
                    \Illuminate\Support\Facades\Log::info('JwtBridge: User authenticated: ' . $user->email);
                    \Illuminate\Support\Facades\Auth::login($user);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('JwtBridge: Exception - ' . $e->getMessage());
            }
        }

        // If not logged in, bounce them to login
        if (!\Illuminate\Support\Facades\Auth::check()) {
            \Illuminate\Support\Facades\Log::info('JwtBridge: Unauthorized access. Redirecting to login.');
            return redirect()->route('login');
        }


        return $next($request);
    }
}
