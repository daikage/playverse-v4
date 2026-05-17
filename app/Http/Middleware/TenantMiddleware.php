<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // If you have a TenantManager, you can resolve tenant here using route param 'studio'
        // Example:
        // if ($slug = $request->route('studio')) {
        //     app(\App\Support\Tenancy\TenantManager::class)->setBySlug($slug);
        // }

        return $next($request);
    }
}
