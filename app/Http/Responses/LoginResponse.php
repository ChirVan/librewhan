<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;

class LoginResponse implements LoginResponseContract
{
    /**
     * Redirect users after Fortify login based on role.
     */
    public function toResponse($request)
    {
        $user = Auth::user();
        $role = strtolower($user->usertype ?? $user->role ?? session('user_role') ?? '');

        if ($role === 'admin' || $role === 'owner') {
            return redirect()->intended(route('admin.home') ?? route('dashboard'));
        }

        if ($role === 'barista') {
            return redirect()->intended(route('pos.take') ?? route('dashboard'));
        }

        return redirect()->intended(route('dashboard'));
    }
}