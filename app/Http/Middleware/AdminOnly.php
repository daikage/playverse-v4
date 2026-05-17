<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user || $user->role !== 'admin') {
            return redirect()->route('admin.login.form')->with('error', 'Please sign in as admin.');
        }

        return $next($request);
    }
}
