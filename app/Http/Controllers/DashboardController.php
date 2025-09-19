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
        // Switch workspace if requested via query param
        $workspace = request('workspace');
        if ($workspace === 'sms' || $workspace === 'inventory') {
            session(['workspace_role' => $workspace]);
        }
        return view('dashboard.index');
    }
    
    /**
     * Display the SMS dashboard.
     */
    public function smsIndex()
    {
    // Always update workspace session when switching
    session(['workspace_role' => 'sms']);
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
    // Always update workspace session when switching
    session(['workspace_role' => 'inventory']);
    return view('dashboard.index');
    }
}
