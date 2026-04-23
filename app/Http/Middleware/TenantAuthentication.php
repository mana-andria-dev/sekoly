<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantAuthentication
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur a accès à ce tenant
        if (Auth::check() && Auth::user()->tenant_id !== tenancy()->tenant->id) {
            Auth::logout();
            return redirect('https://site.test')->with('error', 'Accès non autorisé');
        }
        
        return $next($request);
    }
}