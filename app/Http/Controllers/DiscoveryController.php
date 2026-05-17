<?php

namespace App\Http\Controllers;

use App\Models\Project;

class DiscoveryController
{
    public function index()
    {
        // Pull published projects for discovery (both comics and games)
        $projects = Project::with('author')
            ->where('published', true)
            ->latest()
            ->take(24)
            ->get();

        // Separate featured subsets if the design needs it
        $featured = $projects->take(6);
        $comics = $projects->where('type', 'comic')->take(12);
        $games = $projects->where('type', 'game')->take(12);

        return view('discovery', compact('projects', 'featured', 'comics', 'games'));
    }
}
