<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginResponse implements LoginResponseContract
{
    /**
     * Return the response after the user is authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = $request->user();

        // API / AJAX requests expect JSON
        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        // Keep legacy session keys consistent
        if ($user) {
            $role = strtolower($user->usertype ?? $user->role ?? session('user_role') ?? '');

            Session::put('authenticated', true);
            Session::put('user_email', $user->email);
            Session::put('user_role', $role);

            // Default workspace for baristas
            if ($role === 'barista') {
                Session::put('workspace_role', 'sms');
            } elseif ($role === 'admin') {
                // admin default workspace (pick one you prefer)
                Session::put('workspace_role', session('workspace_role', 'inventory'));
            }
        }

        // Role-based default redirects (use intended where possible)
        if ($user && ($user->usertype ?? '') === 'admin') {
            return redirect()->intended(route('dashboard'));
        }

        if ($user && ($user->usertype ?? '') === 'barista') {
            return redirect()->intended(route('orders.take'));
        }

        // fallback
        return redirect()->intended(route('dashboard'));
    }
}
