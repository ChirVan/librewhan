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
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Actions\Fortify\UpdateUserPassword;

class FortifyServiceProvider extends ServiceProvider
{
    public function register() 
    { 
        // Replace Fortify's default LoginResponse with our role-aware response
        $this->app->singleton(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );

        $this->app->singleton(
            \Laravel\Fortify\Contracts\UpdatesUserProfileInformation::class,
            \App\Actions\Fortify\UpdateUserProfileInformation::class
        );

        $this->app->singleton(
            \Laravel\Fortify\Contracts\UpdatesUserPasswords::class,
            \App\Actions\Fortify\UpdateUserPassword::class
        );

        $this->app->singleton(
            \Laravel\Fortify\Contracts\ResetsUserPasswords::class,
            \App\Actions\Fortify\ResetUserPassword::class
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

        RateLimiter::for('two-factor', function (Request $request) {
            // Fortify sometimes stores a temporary session key 'login.id' during the
            // login flow. If it's not present (or you're using different auth flows),
            // fall back to the request IP so the limiter doesn't resolve to null.
            
            $request->session()->all(); // FOR TESTING, REMOVE IF NOT NEEDED
            
            $key = $request->session()->get('login.id') ?: $request->ip();

            return Limit::perMinute(5)->by($key);
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
