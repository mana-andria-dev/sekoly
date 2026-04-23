<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnsureTenantDatabaseConnection
{
public function handle(Request $request, Closure $next)
{
    $tenant = tenant();
    
    \Log::info('Tenant DB Middleware', [
        'tenant_exists' => !is_null($tenant),
        'tenant_id' => $tenant ? $tenant->id : null,
        'current_database' => DB::connection()->getDatabaseName(),
        'request_url' => $request->url(),
        'request_method' => $request->method()
    ]);
    
    if ($tenant && $tenant->database) {
        $currentDatabase = DB::connection()->getDatabaseName();
        
        if ($currentDatabase !== $tenant->database) {
            config(['database.connections.tenant.database' => $tenant->database]);
            DB::purge('tenant');
            DB::reconnect('tenant');
            DB::setDefaultConnection('tenant');
            
            \Log::info('Database switched', [
                'from' => $currentDatabase,
                'to' => DB::connection()->getDatabaseName()
            ]);
        }
    }
    
    return $next($request);
}
}