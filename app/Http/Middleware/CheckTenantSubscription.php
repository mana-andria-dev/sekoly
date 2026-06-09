<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckTenantSubscription
{
    public function handle(Request $request, Closure $next)
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return redirect()->route('tenant.login');
        }
        
        // Récupérer l'abonnement actif
        $activeSubscription = $tenant->activeSubscription()->first();
        
        // Vérifier si l'abonnement existe et est actif
        if (!$activeSubscription) {
            return redirect()->route('tenant.subscription.expired')
                ->with('error', 'Votre école n\'a pas d\'abonnement actif. Veuillez contacter l\'administration.');
        }
        
        // Vérifier si l'abonnement est expiré
        if ($activeSubscription->ends_at <= now()) {
            $activeSubscription->update(['status' => 'expired']);
            return redirect()->route('tenant.subscription.expired')
                ->with('error', 'Votre abonnement a expiré depuis le ' . $activeSubscription->ends_at->format('d/m/Y'));
        }
        
        // Vérifier si l'abonnement expire bientôt (dans 30 jours)
        if ($activeSubscription->ends_at <= now()->addDays(30)) {
            session()->flash('subscription_warning', 'Votre abonnement expire le ' . $activeSubscription->ends_at->format('d/m/Y'));
        }
        
        // Stocker les infos d'abonnement dans la vue
        view()->share('activeSubscription', $activeSubscription);
        
        return $next($request);
    }
}