<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $project->title }} — Reader</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .ghost-ui { opacity: 0; transition: opacity .2s; }
        .viewer:hover .ghost-ui { opacity: 1; }
    </style>
</head>
<body class="bg-[#0a0a0a] text-white antialiased min-h-screen">
    <div class="max-w-3xl mx-auto">
        <header class="p-4 flex items-center justify-between">
            <div>
                <div class="text-sm text-gray-400">Playverse</div>
                <h1 class="text-xl font-semibold">{{ $project->title }}</h1>
            </div>
            <div class="ghost-ui">
                <a href="{{ route('projects.download', ['project' => $project->id, 'path' => $project->asset_path]) }}"
                   class="text-sm text-gray-300 hover:text-white border border-gray-700 px-3 py-1 rounded">
                    Download Vault
                </a>
            </div>
        </header>

        <main class="viewer space-y-4 p-4">
            @forelse($pages as $src)
                <img src="{{ $src }}" loading="lazy" class="w-full rounded" alt="Page">
            @empty
                <div class="text-gray-500 text-sm border border-gray-800 rounded p-4">
                    No pages available. Processing may still be underway.
                </div>
            @endforelse
        </main>
    </div>
</body>
</html>
