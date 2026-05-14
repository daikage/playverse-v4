<?php

namespace App\Http\Controllers\Admin;

use App\Models\Author;
use Illuminate\Http\RedirectResponse;

class ImpersonationController
{
    public function start(Author $author): RedirectResponse
    {
        // NOTE: Gate this behind proper admin auth in a real app
        session(['impersonate_author_id' => $author->id]);

        return redirect()->route('studio.dashboard', ['studio' => $author->slug]);
    }

    public function stop(): RedirectResponse
    {
        session()->forget('impersonate_author_id');

        return redirect('/');
    }
}
