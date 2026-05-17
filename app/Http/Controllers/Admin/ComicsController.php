<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\ProcessComicUpload;
use App\Models\Project;
use App\Support\Tenancy\TenantManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ComicsController
{
    public function index(Request $request, TenantManager $tenancy)
    {
        // Reuse studio screen; ensure impersonation for tenant scope
        $tenant = $tenancy->tenant();

        $projects = Project::query()
            ->where('type','comic')
            ->latest('id')
            ->take(12)
            ->get();

        $current = null;
        if ($request->filled('project')) {
            $current = Project::query()->where('type','comic')->find($request->integer('project'));
        }
        if (! $current) {
            $current = $projects->first();
        }

        $pages = [];
        $coverUrl = null; // +++ ADD +++
        if ($current) {
            $disk = config('filesystems.default', 'public');
            $pages = array_map(function ($path) use ($disk) {
                if ($disk === 'public') {
                    return Storage::disk('public')->url($path);
                }
                return Storage::disk($disk)->temporaryUrl($path, now()->addMinutes(15));
            }, $current->pages ?? []);

            // +++ ADD: cover URL for thumbnail +++
            if (! empty($current->thumbnail_path)) {
                $coverUrl = $disk === 'public'
                    ? Storage::disk('public')->url($current->thumbnail_path)
                    : Storage::disk($disk)->temporaryUrl($current->thumbnail_path, now()->addMinutes(15));
            }
            // +++ END ADD +++
        }

        return view('studio.comics', [
            'projects' => $projects,
            'current' => $current,
            'pages' => $pages,
            // +++ ADD +++
            'coverUrl' => $coverUrl,
            // +++ END ADD +++
        ]);
    }

    public function store(Request $request, TenantManager $tenancy)
    {
        $tenant = $tenancy->tenant();
        abort_unless($tenant, 400, 'Impersonate a studio to upload.');

        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'archive' => ['nullable', 'file', 'max:512000', 'mimetypes:application/zip,application/x-zip-compressed,application/pdf,application/x-cbz'],
            'images' => ['nullable', 'array'],
            'images.*' => ['file', 'mimetypes:image/jpeg,image/png,image/webp', 'max:20480'],
            // +++ ADD +++
            'thumbnail' => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/webp', 'max:15360'],
            // +++ END ADD +++
        ]);

        if (! $request->hasFile('archive') && ! $request->hasFile('images')) {
            return back()->withErrors(['archive' => 'Upload a CBZ/ZIP/PDF or select image files.'])->withInput();
        }

        $baseSlug = Str::slug($data['title']);
        $slug = $baseSlug;
        $i = 1;
        while (Project::query()->forTenant($tenant->id)->where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$i++;
        }

        $project = Project::create([
            'author_id' => $tenant->id,
            'title' => $data['title'],
            'slug' => $slug,
            'type' => 'comic',
            'published' => false,
        ]);

        $disk = 'public';
        $sourceForJob = null;

        if ($request->hasFile('archive')) {
            $dir = "comics/{$project->id}/src";
            $original = $request->file('archive')->getClientOriginalName();
            $storedPath = $request->file('archive')->storeAs($dir, $original, $disk);
            $project->asset_path = $storedPath;
            $sourceForJob = $storedPath;
        } else {
            $stackDir = "comics/{$project->id}/stack";
            foreach ($request->file('images', []) as $file) {
                $name = $file->getClientOriginalName();
                Storage::disk($disk)->putFileAs($stackDir, $file, $name, ['visibility' => 'public']);
            }
            $project->asset_path = $stackDir.'/';
            $sourceForJob = $stackDir;
        }

        // +++ ADD: store thumbnail cover +++
        $disk = 'public';
        if ($request->hasFile('thumbnail')) {
            $coverDir = "comics/{$project->id}/cover";
            $name = $request->file('thumbnail')->getClientOriginalName();
            $thumbPath = $request->file('thumbnail')->storeAs($coverDir, $name, $disk);
            $project->thumbnail_path = $thumbPath;
        }
        // +++ END ADD +++

        $project->save();

        ProcessComicUpload::dispatch($project, $sourceForJob, $disk);

        return redirect()
            ->route('admin.comics', ['project' => $project->id])
            ->with('status', 'Upload queued. We are generating pages.');
    }

    public function publish(Project $project, Request $request)
    {
        $project->published = $request->input('state', 'publish') === 'publish';
        $project->save();

        return back()->with('status', $project->published ? 'Project published.' : 'Project set to draft.');
    }
}
