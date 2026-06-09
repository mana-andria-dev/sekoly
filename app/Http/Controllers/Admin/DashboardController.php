<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Subscription;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSchools = Tenant::count();
        $activeSchools = Tenant::whereHas('subscriptions', function($query) {
            $query->where('status', 'active')
                  ->where('ends_at', '>', Carbon::now());
        })->count();
        
        $expiringSoon = Tenant::whereHas('subscriptions', function($query) {
            $query->where('status', 'active')
                  ->where('ends_at', '<=', Carbon::now()->addDays(30))
                  ->where('ends_at', '>', Carbon::now());
        })->count();
        
        $recentSchools = Tenant::orderBy('created_at', 'desc')->take(5)->get();
        
        $monthlyRevenue = Subscription::where('status', 'active')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('amount');
        
        return view('admin.dashboard.index', compact(
            'totalSchools', 'activeSchools', 'expiringSoon', 
            'recentSchools', 'monthlyRevenue'
        ));
    }
}