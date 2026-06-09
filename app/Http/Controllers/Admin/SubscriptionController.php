<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with('tenant')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.subscriptions.index', compact('subscriptions'));
    }
    
    public function create($tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);
        return view('admin.subscriptions.create', compact('tenant'));
    }
    
    public function store(Request $request, $tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);
        
        $validated = $request->validate([
            'plan' => 'required|in:basic,premium,enterprise',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);
        
        $subscription = Subscription::create([
            'tenant_id' => $tenant->id,
            'plan' => $validated['plan'],
            'amount' => $validated['amount'],
            'status' => 'active',
            'starts_at' => $validated['start_date'],
            'ends_at' => $validated['end_date'],
        ]);
        
        return redirect()->route('admin.schools.show', $tenant->id)
            ->with('success', 'Abonnement créé avec succès');
    }
    
    public function cancel($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->update(['status' => 'cancelled']);
        
        return redirect()->back()->with('success', 'Abonnement annulé');
    }
}