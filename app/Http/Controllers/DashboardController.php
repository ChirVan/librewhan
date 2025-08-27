<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard.
     */
    public function index()
    {
        // Always show the main dashboard design
        return view('dashboard.index');
    }
    
    /**
     * Display the SMS dashboard.
     */
    public function smsIndex()
    {
        // Update workspace session for owners who switch
        if (session('user_role') === 'owner') {
            session(['workspace_role' => 'sms']);
        }
        
        // Use the same main dashboard design
        return view('dashboard.index');
    }
    
    /**
     * Display the Inventory dashboard.
     */
    /**
     * Display the inventory dashboard.
     */
    public function inventoryIndex()
    {
        // Update workspace session for owners who switch
        if (session('user_role') === 'owner') {
            session(['workspace_role' => 'inventory']);
        }
        
        // Use the same main dashboard design
        return view('dashboard.index');
    }
}
