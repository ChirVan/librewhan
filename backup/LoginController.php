<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        // If already authenticated, go straight to dashboard
        if (Session::has('authenticated')) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle login request with static credentials
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:barista,owner',
            'workspace' => 'required|in:sms,inventory',
        ]);

        // Static credentials
        $staticEmail = 'admin@gmail.com';
        $staticPassword = '123';

        if ($request->email === $staticEmail && $request->password === $staticPassword) {
            // Store authentication state in session
            Session::put('authenticated', true);
            Session::put('user_email', $staticEmail);
            Session::put('user_role', $request->role); // barista or owner
            Session::put('workspace_role', $request->workspace); // sms or inventory
            
            // Handle remember me functionality
            if ($request->has('remember')) {
                Session::put('remember_me', true);
            }

            // Log the access
            Log::info('User logged in', [
                'email' => $staticEmail,
                'role' => $request->role,
                'workspace' => $request->workspace
            ]);

            // Always redirect to main dashboard
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Session::flush();
        return redirect()->route('login');
    }
}
