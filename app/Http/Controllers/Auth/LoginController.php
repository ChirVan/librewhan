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
        // Debug: Force clear session for testing
        Session::forget('authenticated');
        Session::forget('user_email');
        Session::forget('remember_me');
        
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
        ]);

        // Static credentials
        $staticEmail = 'admin@gmail.com';
        $staticPassword = '123';

        if ($request->email === $staticEmail && $request->password === $staticPassword) {
            // Store authentication state in session
            Session::put('authenticated', true);
            Session::put('user_email', $staticEmail);
            
            // Handle remember me functionality
            if ($request->has('remember')) {
                Session::put('remember_me', true);
            }

            return redirect()->intended(route('dashboard'));
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