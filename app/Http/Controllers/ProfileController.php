<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController
{
    public function show()
    {
        $user = session()->has('admin_user_id')
            ? User::find(session('admin_user_id'))
            : null;

        return view('profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $id = session('admin_user_id');
        if (! $id) {
            return redirect()->route('admin.login');
        }

        $user = User::findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
        ]);

        $user->name = $data['name'];
        $user->save();

        return back()->with('status', 'Profile updated.');
    }
}
