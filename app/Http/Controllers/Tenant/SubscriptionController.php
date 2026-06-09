<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Subscription;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function expired()
    {
        $tenant = tenant();
        $activeSubscription = $tenant->activeSubscription()->first();
        
        // Récupérer l'historique des abonnements
        $subscriptionHistory = $tenant->subscriptions()
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('tenant.subscription.expired', compact('tenant', 'activeSubscription', 'subscriptionHistory'));
    }
    
    public function info()
    {
        $tenant = tenant();
        $activeSubscription = $tenant->activeSubscription()->first();
        $subscriptionHistory = $tenant->subscriptions()
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('tenant.subscription.info', compact('tenant', 'activeSubscription', 'subscriptionHistory'));
    }
    
    public function checkStatus()
    {
        $tenant = tenant();
        $activeSubscription = $tenant->activeSubscription()->first();
        
        return response()->json([
            'has_active_subscription' => !is_null($activeSubscription),
            'subscription' => $activeSubscription ? [
                'plan' => $activeSubscription->plan,
                'ends_at' => $activeSubscription->ends_at->format('d/m/Y'),
                'days_remaining' => now()->diffInDays($activeSubscription->ends_at, false),
                'is_expiring_soon' => $activeSubscription->ends_at <= now()->addDays(30),
            ] : null,
        ]);
    }
}