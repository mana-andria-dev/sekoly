<?php
// app/Http/Middleware/EnsureTenantConnection.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class EnsureTenantConnection
{
    public function handle($request, Closure $next)
    {
        $tenant = tenant();
        
        if ($tenant && $tenant->database) {
            // Configurer la connexion tenant
            Config::set('database.connections.tenant.database', $tenant->database);
            DB::purge('tenant');
            DB::reconnect('tenant');
            
            // Définir la connexion par défaut sur tenant
            Config::set('database.default', 'tenant');
            DB::setDefaultConnection('tenant');
            
            \Log::info('Tenant connection ensured', [
                'database' => DB::connection()->getDatabaseName(),
                'tenant_id' => $tenant->id
            ]);
        }
        
        return $next($request);
    }
}