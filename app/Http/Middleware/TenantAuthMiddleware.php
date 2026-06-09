<?php
// app/Http/Middleware/TenantAuthMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class TenantAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        // Vérifier si l'utilisateur est connecté sur le tenant
        if (!Auth::guard('tenant')->check()) {
            // Rediriger vers le login du tenant
            return redirect()->route('tenant.login');
        }
        
        // Vérifier si l'utilisateur est actif
        $user = Auth::guard('tenant')->user();
        if (!$user->is_active) {
            Auth::guard('tenant')->logout();
            return redirect()->route('tenant.login')->withErrors([
                'email' => 'Votre compte est désactivé.'
            ]);
        }
        
        return $next($request);
    }
}