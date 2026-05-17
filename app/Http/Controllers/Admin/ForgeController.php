<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Support\Tenancy\TenantManager;
use App\Support\Telemetry\TelemetryReporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ForgeController
{
    public function index(TelemetryReporter $telemetry)
    {
        $recent = Project::query()->where('type', 'game')->latest()->first();
        $snapshot = $telemetry->snapshot($recent);
        $log = $recent ? Cache::get("project:{$recent->id}:build_log", []) : [];

        return view('studio.forge', [
            'telemetry' => $snapshot,
            'buildLog' => $log,
            'recentProject' => $recent,
        ]);
    }

    public function store(Request $request, TenantManager $tenancy, TelemetryReporter $telemetry)
    {
        $tenant = $tenancy->tenant();
        abort_unless($tenant, 400, 'Impersonate a studio before uploading to Project Forge.');

        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'platforms' => ['nullable', 'array'],
            'platforms.*' => ['in:windows,mac,android,ios'],
            'binary' => ['required', 'file', 'max:512000'],
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
            return back()->withErrors(['binary' => 'Unsupported file type. Allowed: .exe, .dmg, .pkg, .apk, .zip'])->withInput();
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
            'type' => 'game',
            'platforms' => $data['platforms'] ?? [],
            'published' => false,
        ]);

        $disk = 'public';

        $binaryDir = "games/{$project->id}/binary";
        $binaryName = $request->file('binary')->getClientOriginalName();
        $binaryPath = $request->file('binary')->storeAs($binaryDir, $binaryName, $disk);
        $project->asset_path = $binaryPath;

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

        $buildLog = $telemetry->buildLog(['file' => $binaryName]);
        Cache::put("project:{$project->id}:build_log", $buildLog, now()->addMinutes(10));

        return redirect()->route('admin.forge')->with('status', 'Upload received. Project created: '.$project->title);
    }
}
