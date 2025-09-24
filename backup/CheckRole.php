<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    public function handle($request, Closure $next, $roles)
    {
        $roles = explode('|', $roles);
        $user = auth()->user();
        $sessionRole = session('user_role');

        if ($user && in_array(($user->usertype ?? ''), $roles)) {
            return $next($request);
        }

        if ($sessionRole && in_array($sessionRole, $roles)) {
            return $next($request);
        }

        abort(403);
    }
}
