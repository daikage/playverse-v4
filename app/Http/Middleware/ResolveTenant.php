<?php

namespace App\Http\Middleware;

use App\Models\Author;
use App\Support\Tenancy\TenantManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    public function __invoke(Request $request, Closure $next): Response
    {
        /** First path segment is treated as the studio slug: /{studio}/... */
        $slug = $request->route('studio');

        $author = null;
        if ($slug) {
            $author = Author::query()->where('slug', $slug)->first();
        }

        // Optional: Block suspended studios
        if ($author && $author->suspended_at) {
            abort(403, 'Studio suspended');
        }

        resolve(TenantManager::class)->setTenant($author);

        // Share to views for convenience
        view()->share('tenant', $author);

        return $next($request);
    }
}
