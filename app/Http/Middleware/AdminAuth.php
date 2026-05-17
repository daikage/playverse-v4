<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $id = session('admin_user_id');
        if (! $id) {
            return redirect()->route('admin.login');
        }

        $user = User::find($id);
        if (! $user || $user->role !== 'admin') {
            session()->forget('admin_user_id');
            return redirect()->route('admin.login')->withErrors(['email' => 'Unauthorized']);
        }

        // Share admin user in views (optional)
        view()->share('adminUser', $user);

        return $next($request);
    }
}
