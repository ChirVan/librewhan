<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth'); // keep current session-based protection
    }

    public function index()
    {
        $user = Auth::user();

        // If no real User model (still using static session) you can read session('user_role')
        if ($user) {
            if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
                return redirect()->route('dashboard'); // admin.home
            }
            // barista fallback
            return redirect()->route('orders.take');
        }

        return redirect()->route('orders.take');
    }

        public function clearSession()
    {
        session()->flush();
        return redirect()->route('login')->with('message', 'Session cleared!');
    }

    public function testLogin()
    {
        return view('auth.login');
    }
}
