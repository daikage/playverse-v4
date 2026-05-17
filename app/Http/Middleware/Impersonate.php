<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Impersonate
{
    public function handle(Request $request, Closure $next)
    {
        // Implement your impersonation logic here if needed.
        // For now this is a pass-through middleware to satisfy Kernel references.
        return $next($request);
    }
}
