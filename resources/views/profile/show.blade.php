<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8">
    <title>Profile — Playverse</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-surface min-h-screen p-6">
    <div class="max-w-xl mx-auto bg-surface-container-low border border-outline-variant/20 rounded-lg p-6">
        <h1 class="text-headline-md font-headline-md mb-4">Profile</h1>

        @if (session('status'))
            <div class="text-tertiary text-sm mb-3">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            <div>
                <label class="text-sm text-on-surface-variant">Name</label>
                <input type="text" name="name" value="{{ old('name', $user?->name) }}"
                       class="mt-2 w-full bg-surface border border-outline-variant/30 rounded px-3 py-2 text-sm" required>
            </div>

            <button type="submit" class="px-4 py-2 border border-outline-variant/30 rounded text-on-surface-variant hover:text-on-surface hover:bg-surface-variant transition">
                Save
            </button>
        </form>
    </div>
</body>
</html>
