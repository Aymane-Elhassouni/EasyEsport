<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        $user->loadMissing('role.permissions');

        if (!$user->hasPermission($permission)) {
            abort(403, 'Forbidden. Missing permission: ' . $permission);
        }

        return $next($request);
    }
}
