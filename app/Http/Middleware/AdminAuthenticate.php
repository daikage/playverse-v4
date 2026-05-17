<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        // Allow auth endpoints through to prevent redirect loops
        if (
            $request->routeIs('admin.login') ||
            $request->routeIs('admin.login.submit') ||
            $request->routeIs('admin.logout') ||
            $request->is('admin/login')
        ) {
            return $next($request);
        }

        $id = session('admin_user_id');

        if (! $id) {
            return redirect()->route('admin.login');
        }

        $user = User::find($id);

        if (! $user || $user->role !== 'admin') {
            session()->forget('admin_user_id');

            return redirect()
                ->route('admin.login')
                ->withErrors(['email' => 'Unauthorized']);
        }

        view()->share('adminUser', $user);

        return $next($request);
    }
}
