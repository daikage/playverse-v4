<?php

namespace App\Http\Controllers;

use App\Models\Project;

class StudioController
{
    public function dashboard()
    {
        // Projects auto-scoped to current tenant via BelongsToTenant
        $projects = Project::query()
            ->latest('id')
            ->take(10)
            ->get();

        return view('studio.dashboard', [
            'projects' => $projects,
        ]);
    }
}
