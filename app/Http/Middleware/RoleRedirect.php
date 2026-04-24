<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleRedirect
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('web')->user();

        if ($user && ($request->is('/') || $request->is('login') || $request->is('register'))) {
            $user->loadMissing('role');

            if ($user->hasRole('admin') || $user->hasRole('super_admin')) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('player.dashboard');
        }

        return $next($request);
    }
}
