<?php

namespace App\Http\Middleware;

use App\Models\Author;
use App\Support\Tenancy\TenantManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ImpersonateTenant
{
    public function __invoke(Request $request, Closure $next): Response
    {
        if (session()->has('impersonate_author_id')) {
            $author = Author::find(session('impersonate_author_id'));
            if ($author) {
                resolve(TenantManager::class)->setTenant($author);
                view()->share('tenant', $author);
            }
        }

        return $next($request);
    }
}
