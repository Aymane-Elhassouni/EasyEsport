<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class ExtractJwtFromCookie
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('token');

        if ($token) {
            try {
                $user = JWTAuth::setToken($token)->authenticate();

                if ($user && (!Auth::check() || Auth::id() !== $user->id)) {
                    Auth::guard('web')->login($user);
                    View::share('authUser', $user);
                    Cache::put('user_online_' . $user->id, true, now()->addMinutes(2));
                }
            } catch (JWTException $e) {
                \Illuminate\Support\Facades\Log::error('JWT Error: ' . $e->getMessage());
            }
        } else {
            \Illuminate\Support\Facades\Log::warning('No token cookie found');
        }

        $response = $next($request);

        // Track after auth is resolved
        if (Auth::check()) {
            Cache::put('user_online_' . Auth::id(), true, now()->addMinutes(2));
        }

        return $response;
    }
}
