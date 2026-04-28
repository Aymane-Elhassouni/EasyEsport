<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TrackLastSeen
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (Auth::guard('web')->check()) {
            Cache::put('user_online_' . Auth::guard('web')->id(), true, now()->addMinutes(2));
        }

        return $response;
    }
}
