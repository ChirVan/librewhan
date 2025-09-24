<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Normalize allowed roles
        $allowed = [];
        foreach ($roles as $r) {
            $parts = preg_split('/[|,]/', $r);
            foreach ($parts as $p) {
                $p = trim($p);
                if ($p !== '') $allowed[] = strtolower($p);
            }
        }

        // 1) Check legacy session role first (this supports your old static auth)
        $sessionRole = strtolower(session('user_role', '') ?: '');
        if ($sessionRole && in_array($sessionRole, $allowed, true)) {
            return $next($request);
        }

        // 2) Then check authenticated user (Jetstream/Fortify)
        $user = Auth::user();
        if ($user) {
            $role = strtolower($user->usertype ?? $user->role ?? '');
            if ($role && in_array($role, $allowed, true)) {
                return $next($request);
            }
        }

        // default deny
        abort(403);
    }
}
