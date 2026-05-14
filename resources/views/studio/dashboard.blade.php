<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $tenant?->name ?? 'Studio' }} — Studio</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0a0a0a] text-white antialiased min-h-screen">
    <div class="max-w-6xl mx-auto p-6 space-y-8">
        <header class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">{{ $tenant?->name ?? 'Studio' }}</h1>
                <p class="text-sm text-gray-400">Private Studio Command</p>
            </div>
            <nav class="text-sm space-x-4">
                <a href="{{ route('admin.impersonate.stop') }}" class="text-gray-400 hover:text-white">Stop Impersonation</a>
                <a href="/" class="text-gray-400 hover:text-white">Discovery</a>
            </nav>
        </header>

        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="border border-gray-800 rounded-md p-4">
                <h2 class="font-medium mb-2">Project Forge</h2>
                <p class="text-sm text-gray-400">Drag & drop upload (APK/IPA/EXE/DMG) — Livewire stepper placeholder.</p>
            </div>
            <div class="border border-gray-800 rounded-md p-4">
                <h2 class="font-medium mb-2">Comic Composer</h2>
                <p class="text-sm text-gray-400">Upload chapters, generate thumbnails — placeholder.</p>
            </div>
            <div class="border border-gray-800 rounded-md p-4">
                <h2 class="font-medium mb-2">Analytics</h2>
                <p class="text-sm text-gray-400">Downloads vs Views — minimalist charts placeholder.</p>
            </div>
        </section>

        <section>
            <h2 class="text-lg mb-3">Recent Projects</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($projects as $project)
                    <div class="border border-gray-800 rounded-md p-4">
                        <div class="text-sm text-gray-400 uppercase">{{ ucfirst($project->type) }}</div>
                        <div class="text-base font-medium">{{ $project->title }}</div>
                        <div class="text-xs text-gray-500">Platforms: {{ implode(', ', $project->platforms ?? []) ?: 'N/A' }}</div>
                        <div class="mt-2 text-xs text-gray-500">Published: {{ $project->published ? 'Yes' : 'No' }}</div>
                    </div>
                @empty
                    <div class="col-span-full border border-gray-800 rounded-md p-6 text-gray-400">
                        No projects yet.
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</body>
</html>
