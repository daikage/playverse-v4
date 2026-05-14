<?php

namespace App\Http\Controllers\Admin;

use App\Models\Author;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReviewController
{
    public function index()
    {
        // In a real app, protect via Gate::authorize('review-authors');
        $pending = Author::where('verification_status', 'pending')->latest()->get();
        $approved = Author::where('verification_status', 'approved')->latest()->take(10)->get();

        return view('admin.review.index', compact('pending', 'approved'));
    }

    public function approve(Author $author): RedirectResponse
    {
        $author->verification_status = 'approved';
        $author->playverse_key = $author->playverse_key ?? Str::uuid();
        $author->suspended_at = null;
        $author->save();

        return back()->with('status', 'Author approved and key issued.');
    }

    public function suspend(Author $author): RedirectResponse
    {
        $author->verification_status = 'suspended';
        $author->suspended_at = now();
        $author->save();

        return back()->with('status', 'Author suspended.');
    }
}
