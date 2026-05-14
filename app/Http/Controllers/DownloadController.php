<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DownloadController
{
    public function __invoke(Request $request, Project $project, string $path): Response
    {
        // Authorize access to this project's asset if needed.
        // The Project model is tenant-scoped; ensure requested path belongs to it.
        if (! $project->asset_path || ! str_starts_with($path, trim($project->asset_path, '/'))) {
            abort(404);
        }

        $disk = config('filesystems.default', 's3'); // or your chosen disk
        $expires = now()->addMinutes(10);

        $tempUrl = Storage::disk($disk)->temporaryUrl($path, $expires);

        return redirect()->away($tempUrl);
    }
}
