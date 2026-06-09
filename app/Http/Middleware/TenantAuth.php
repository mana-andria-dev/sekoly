<?php
// app/Http/Middleware/TenantAuth.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class TenantAuth
{
    public function handle($request, Closure $next)
    {
        // Forcer l'utilisation du guard 'tenant'
        if (Auth::guard('tenant')->check()) {
            // L'utilisateur est authentifié sur le tenant
            return $next($request);
        }
        
        // Rediriger vers la page de login du tenant
        return redirect()->route('tenant.login');
    }
}