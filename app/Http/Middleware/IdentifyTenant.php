<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;

class IdentifyTenant
{
    public function handle($request, Closure $next)
    {
        $subdomain = explode('.', $request->getHost())[0];

        $tenant = Tenant::where('slug', $subdomain)->first();

        if (!$tenant) {
            abort(404, 'École introuvable');
        }

        app()->instance('tenant', $tenant);

        return $next($request);
    }
}
