<?php

namespace App\Http\Controllers\Admin;

use App\Models\Author;
use App\Models\Project;
use App\Support\Tenancy\TenantManager;

class DashboardController
{
    public function index()
    {
        $studioCounts = [
            'total' => Author::count(),
            'pending' => Author::where('verification_status', 'pending')->count(),
            'approved' => Author::where('verification_status', 'approved')->count(),
            'suspended' => Author::where('verification_status', 'suspended')->count(),
        ];

        $projectCounts = [
            'total' => Project::count(),
            'games' => Project::where('type', 'game')->count(),
            'comics' => Project::where('type', 'comic')->count(),
            'published' => Project::where('published', true)->count(),
        ];

        $pendingStudios = Author::where('verification_status', 'pending')->latest()->take(5)->get();
        $recentApproved = Author::where('verification_status', 'approved')->latest()->take(5)->get();
        $recentProjects = Project::with('author')->latest()->take(6)->get();

        $impersonated = app(TenantManager::class)->tenant();

        return view('admin.dashboard.index', compact(
            'studioCounts',
            'projectCounts',
            'pendingStudios',
            'recentApproved',
            'recentProjects',
            'impersonated'
        ));
    }
}
