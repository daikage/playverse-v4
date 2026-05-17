<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController
{

public function loginForm()
    {
        return $this->showLogin();
    }
    public function showLogin()
    {
        if (session()->has('admin_user_id')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:191'],
            'password' => ['required', 'string', 'max:191'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || $user->role !== 'admin' || ! Hash::check($data['password'], $user->password)) {
            return back()->withErrors(['email' => 'Invalid admin credentials'])->withInput();
        }

        session(['admin_user_id' => $user->id]);

        // Redirect to intended admin page (or dashboard)
        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout()
    {
        session()->forget('admin_user_id');

        return redirect()->route('admin.login');
    }
}
