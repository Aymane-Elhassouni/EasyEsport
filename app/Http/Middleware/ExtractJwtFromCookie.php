<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

                if ($user) {
                    Auth::guard('web')->login($user);
                    View::share('authUser', $user);
                }
            } catch (JWTException) {
                // Token invalide — on laisse passer sans user
            }
        }

        return $next($request);
    }
}
