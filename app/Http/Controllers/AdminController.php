<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Get statistics for admin dashboard
        $totalUsers = \App\Models\User::count();
        $totalVendors = \App\Models\Vendor::count();
        $totalServices = \App\Models\Service::count();
        $totalOrders = \App\Models\Order::count();
        
        // Get recent orders
        $recentOrders = \App\Models\Order::with(['user', 'vendor'])
                                        ->orderBy('created_at', 'desc')
                                        ->take(5)
                                        ->get();
        
        // Get pending vendor approvals
        $pendingVendors = \App\Models\Vendor::where('is_approved', false)
                                          ->with('user')
                                          ->take(5)
                                          ->get();
        
        // Get pending service approvals
        $pendingServices = \App\Models\Service::where('is_approved', false)
                                            ->with('vendor')
                                            ->take(5)
                                            ->get();
        
        return view('pages.admin.dashboard', compact(
            'totalUsers', 
            'totalVendors', 
            'totalServices', 
            'totalOrders',
            'recentOrders',
            'pendingVendors',
            'pendingServices'
        ));
    }
    
    public function settings()
    {
        $settings = \App\Models\Setting::pluck('value', 'key')->all();
        return view('pages.admin.settings', compact('settings'));
    }
    
    public function updateSettings()
    {
        $validated = request()->validate([
            'site_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'free_quotes_limit' => 'required|integer|min:1',
            'commission_percentage' => 'required|numeric|min:0|max:100',
            'featured_vendors_limit' => 'required|integer|min:1',
            'maintenance_mode' => 'boolean',
        ]);
        
        foreach ($validated as $key => $value) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
        
        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully');
    }
}
