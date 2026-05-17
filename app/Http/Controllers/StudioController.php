<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Support\Tenancy\TenantManager;
use App\Support\Telemetry\TelemetryReporter;
use App\Jobs\ProcessComicUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudioController
{
    public function dashboard()
    {
        $projects = Project::query()->latest('id')->take(10)->get();

        return view('studio.dashboard', [
            'projects' => $projects,
        ]);
    }

    public function forge(TenantManager $tenancy, TelemetryReporter $telemetry)
    {
        $tenant = $tenancy->tenant();
        abort_unless($tenant, 400);

        $recent = Project::query()->where('type', 'game')->latest()->first();
        $snapshot = $telemetry->snapshot($recent);

        $log = [];
        if ($recent) {
            $log = Cache::get("project:{$recent->id}:build_log", []);
        }

        return view('studio.forge', [
            'telemetry' => $snapshot,
            'buildLog' => $log,
            'recentProject' => $recent,
        ]);
    }

    public function forgeStore(Request $request, TenantManager $tenancy, TelemetryReporter $telemetry)
    {
        $tenant = $tenancy->tenant();
        abort_unless($tenant, 400, 'Tenant not resolved. Are you impersonating a studio?');

        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'platforms' => ['nullable', 'array'],
            'platforms.*' => ['in:windows,mac,android,ios'],
            'binary' => ['required', 'file', 'max:512000'], // ~500MB
            // +++ NEW MEDIA +++
            'screenshots' => ['nullable', 'array'],
            'screenshots.*' => ['file', 'mimetypes:image/jpeg,image/png,image/webp', 'max:20480'],
            'videos' => ['nullable', 'array'],
            'videos.*' => ['file', 'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-matroska', 'max:512000'],
            // +++ END MEDIA +++
        ]);

        $ext = strtolower($request->file('binary')->getClientOriginalExtension());
        $allowed = ['exe', 'dmg', 'pkg', 'apk', 'zip'];
        if (! in_array($ext, $allowed, true)) {
            return back()
                ->withErrors(['binary' => 'Unsupported file type. Allowed: .exe, .dmg, .pkg, .apk, .zip'])
                ->withInput();
        }

        // Unique slug per tenant
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
            'type' => 'game',
            'platforms' => $data['platforms'] ?? [],
            'published' => false,
        ]);

        $disk = 'public';

        // Store binary
        $binaryDir = "games/{$project->id}/binary";
        $binaryName = $request->file('binary')->getClientOriginalName();
        $binaryPath = $request->file('binary')->storeAs($binaryDir, $binaryName, $disk);
        $project->asset_path = $binaryPath;

        // Store screenshots
        $screens = [];
        if ($request->hasFile('screenshots')) {
            $shotDir = "games/{$project->id}/screenshots";
            foreach ($request->file('screenshots') as $file) {
                $name = $file->getClientOriginalName();
                $path = $file->storeAs($shotDir, $name, $disk);
                $screens[] = $path;
            }
        }
        $project->screenshots = $screens;

        // Store videos
        $vids = [];
        if ($request->hasFile('videos')) {
            $vidDir = "games/{$project->id}/videos";
            foreach ($request->file('videos') as $file) {
                $name = $file->getClientOriginalName();
                $path = $file->storeAs($vidDir, $name, $disk);
                $vids[] = $path;
            }
        }
        $project->videos = $vids;

        $project->save();

        // Cache an "active" build log snapshot for this project
        $buildLog = $telemetry->buildLog(['file' => $binaryName]);
        Cache::put("project:{$project->id}:build_log", $buildLog, now()->addMinutes(10));

        return redirect()
            ->route('studio.forge', ['studio' => $tenant->slug])
            ->with('status', 'Upload received. Project created: '.$project->title);
    }

    public function comics(Request $request, TenantManager $tenancy)
    {
        $tenant = $tenancy->tenant();
        abort_unless($tenant, 400);

        $projects = Project::query()
            ->where('type', 'comic')
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

    public function comicsStore(Request $request, TenantManager $tenancy)
    {
        $tenant = $tenancy->tenant();
        abort_unless($tenant, 400, 'Tenant not resolved.');

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
            $sourceForJob = $storedPath; // file path
        } else {
            $stackDir = "comics/{$project->id}/stack";
            foreach ($request->file('images', []) as $i => $file) {
                $name = $file->getClientOriginalName();
                Storage::disk($disk)->putFileAs($stackDir, $file, $name, ['visibility' => 'public']);
            }
            $project->asset_path = $stackDir.'/';
            $sourceForJob = $stackDir; // directory path
        }

        // +++ ADD: store thumbnail cover +++
        if ($request->hasFile('thumbnail')) {
            $coverDir = "comics/{$project->id}/cover";
            $name = $request->file('thumbnail')->getClientOriginalName();
            $thumbPath = $request->file('thumbnail')->storeAs($coverDir, $name, $disk);
            $project->thumbnail_path = $thumbPath;
        }
        // +++ END ADD +++

        $project->save();

        // Dispatch page processing
        ProcessComicUpload::dispatch($project, $sourceForJob, $disk);

        return redirect()
            ->route('studio.comics', ['studio' => $tenant->slug, 'project' => $project->id])
            ->with('status', 'Upload queued. We are generating pages.');
    }

    public function comicsPublish(Project $project, Request $request)
    {
        // Project is already tenant-scoped via global scope
        $state = $request->input('state', 'publish');
        $project->published = $state === 'publish';
        $project->save();

        return back()->with('status', $project->published ? 'Project published.' : 'Project set to draft.');
    }

    public function analytics(TenantManager $tenancy)
    {
        $tenant = $tenancy->tenant();
        abort_unless($tenant, 400, 'Tenant not resolved.');

        // Existing aggregates
        $projectsTotal   = Project::query()->count();
        $gamesCount      = Project::query()->where('type', 'game')->count();
        $comicsCount     = Project::query()->where('type', 'comic')->count();
        $publishedCount  = Project::query()->where('published', true)->count();

        $comicProjects = Project::query()->where('type', 'comic')->get(['pages']);
        $totalPages = $comicProjects->sum(function ($p) {
            return is_array($p->pages) ? count($p->pages) : 0;
        });

        $recentProjects = Project::query()->latest()->take(8)->get();

        // +++ NEW: Build "projects over time" timeline for the last 7 days +++
        $start = now()->subDays(6)->startOfDay();

        // Get counts per day (already tenant-scoped via global scope)
        $raw = Project::query()
            ->where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->groupBy('d')
            ->pluck('c', 'd');

        $projectTimeline = [];
        $projectTimelineMax = 0;

        for ($i = 0; $i < 7; $i++) {
            $day = $start->copy()->addDays($i);
            $key = $day->toDateString();
            $count = (int) ($raw[$key] ?? 0);
            $projectTimeline[] = [
                'label' => strtoupper($day->format('D')),
                'count' => $count,
            ];
            $projectTimelineMax = max($projectTimelineMax, $count);
        }

        $projectTimelineMax = max(1, $projectTimelineMax); // avoid div-by-zero
        // Compute height percentages per bar
        foreach ($projectTimeline as &$pt) {
            $pt['percent'] = round(($pt['count'] / $projectTimelineMax) * 100, 2);
        }
        unset($pt);
        // +++ END NEW +++

        $serverLoad = min(100, max(0, ($projectsTotal * 7) % 100));

        return view('studio.analytics', compact(
            'projectsTotal',
            'gamesCount',
            'comicsCount',
            'publishedCount',
            'totalPages',
            'recentProjects',
            'serverLoad',
            // +++ pass to view +++
            'projectTimeline',
            'projectTimelineMax'
        ));
    }
}
