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
        $user = Auth::user();
        if (! $user) {
            abort(403);
        }

        // collect allowed roles, support both comma and pipe separators and multiple params
        $allowed = [];
        foreach ($roles as $r) {
            $parts = preg_split('/[|,]/', $r);
            foreach ($parts as $p) {
                $p = trim($p);
                if ($p !== '') $allowed[] = strtolower($p);
            }
        }

        // Determine user's role from model or legacy session
        $role = strtolower($user->usertype ?? $user->role ?? session('user_role') ?? '');

        if (! in_array($role, $allowed, true)) {
            abort(403);
        }

        return $next($request);
    }
}
