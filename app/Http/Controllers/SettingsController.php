<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingsController
{
    public function show()
    {
        $user = session()->has('admin_user_id')
            ? User::find(session('admin_user_id'))
            : null;

        return view('settings.show', compact('user'));
    }

    public function update(Request $request)
    {
        $id = session('admin_user_id');
        if (! $id) {
            return redirect()->route('admin.login');
        }

        $user = User::findOrFail($id);

        $data = $request->validate([
            'password' => ['nullable', 'string', 'min:8', 'max:191'],
        ]);

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
            $user->save();
        }

        return back()->with('status', 'Settings saved.');
    }
}
