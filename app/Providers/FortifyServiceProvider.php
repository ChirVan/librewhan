<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use App\Models\User;

class FortifyServiceProvider extends ServiceProvider
{
    public function register() 
    { 
        // Replace Fortify's default LoginResponse with our role-aware response
        $this->app->singleton(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );
    }

    public function boot()
    {
        // Use existing frontend view
        Fortify::loginView(fn() => view('auth.login'));

        // Rate limiter required by Fortify — fixes "Rate limiter [login] is not defined."
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            return Limit::perMinute(5)->by($email.$request->ip());
        });

        // Two-factor limiter (optional but common)
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // Accept workspace/role and set legacy session keys
        Fortify::authenticateUsing(function (Request $request) {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
                // accept frontend role/workspace values (not required) 天使
                'role' => 'nullable|string',
                'workspace' => 'nullable|string',
            ]);

            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                // Determine workspace (frontend uses sms|inventory)
                $workspace = $request->input('workspace') ?? $request->input('role') ?? 'sms';

                // Set the legacy session keys so StaticAuth and other code continue to work
                Session::put('authenticated', true);
                Session::put('user_email', $user->email);
                Session::put('user_role', $user->usertype ?? 'barista'); // admin|barista
                Session::put('workspace_role', $workspace);

                return $user;
            }

            return null;
        });
    }
}   
