<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Playverse — Tactical Dossier</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0a0a0a] text-white antialiased min-h-screen flex items-center justify-center">
    <div class="max-w-lg w-full p-6 border border-gray-800 rounded-md">
        <h1 class="text-xl font-semibold mb-1">Tactical Dossier</h1>
        <p class="text-xs text-gray-400 mb-6">Define your Studio Identity.</p>

        <form action="{{ route('onboarding.studio') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="text-sm text-gray-300">Logo</label>
                <input name="logo" type="file" accept="image/*" class="w-full mt-1 text-sm">
                @error('logo')<div class="text-xs text-red-400 mt-1">{{ $message }}</div>@enderror
            </div>

            <div>
                <label class="text-sm text-gray-300">Mission Statement</label>
                <textarea name="mission_statement" rows="4" class="w-full mt-1 bg-[#0a0a0a] border border-gray-700 rounded-md p-2 text-sm"></textarea>
                @error('mission_statement')<div class="text-xs text-red-400 mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-300">GitHub</label>
                    <input name="links[github]" type="url" class="w-full mt-1 bg-[#0a0a0a] border border-gray-700 rounded-md p-2 text-sm">
                    @error('links.github')<div class="text-xs text-red-400 mt-1">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="text-sm text-gray-300">ArtStation</label>
                    <input name="links[artstation]" type="url" class="w-full mt-1 bg-[#0a0a0a] border border-gray-700 rounded-md p-2 text-sm">
                    @error('links.artstation')<div class="text-xs text-red-400 mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="text-sm text-gray-300">itch.io</label>
                    <input name="links[itch]" type="url" class="w-full mt-1 bg-[#0a0a0a] border border-gray-700 rounded-md p-2 text-sm">
                    @error('links.itch')<div class="text-xs text-red-400 mt-1">{{ $message }}</div>@enderror
                </div>
            </div>

            <button class="w-full bg-white/5 hover:bg-white/10 border border-gray-700 rounded-md py-2 text-sm">
                Submit for Review
            </button>
        </form>
    </div>
</body>
</html>
