<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Storage;

class ComicsController
{
    public function show(Project $project)
    {
        abort_unless($project->type === 'comic', 404);

        // Only show if published or if tenant/admin (basic gate for demo)
        if (! $project->published) {
            // Allow if impersonating this tenant
            $tenant = app(\App\Support\Tenancy\TenantManager::class)->tenant();
            abort_unless($tenant && $tenant->id === $project->author_id, 403);
        }

        $pages = $project->pages ?? [];

        // Convert to temporary URLs for the viewer
        $disk = config('filesystems.default', 'public');
        $urls = array_map(function ($path) use ($disk) {
            if ($disk === 'public') {
                return Storage::disk('public')->url($path);
            }
            return Storage::disk($disk)->temporaryUrl($path, now()->addMinutes(15));
        }, $pages);

        return view('comics.show', [
            'project' => $project,
            'pages' => $urls,
        ]);
    }
}
