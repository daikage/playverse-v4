<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Login — Playverse</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <style>.material-symbols-outlined{font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24;}</style>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-surface min-h-screen flex items-center justify-center">
    <div class="w-full max-w-sm bg-surface-container-low border border-outline-variant/20 rounded-lg p-6">
        <h1 class="text-headline-md font-headline-md mb-4">Admin Login</h1>

        @if ($errors->any())
            <div class="text-error text-sm mb-3">Invalid credentials.</div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
            @csrf
            <div>
                <label class="text-sm text-on-surface-variant">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="mt-2 w-full bg-surface border border-outline-variant/30 rounded px-3 py-2 text-sm" required>
            </div>

            <div>
                <label class="text-sm text-on-surface-variant">Password</label>
                <input type="password" name="password"
                       class="mt-2 w-full bg-surface border border-outline-variant/30 rounded px-3 py-2 text-sm" required>
            </div>

            <button type="submit" class="w-full px-4 py-2 border border-outline-variant/30 rounded text-on-surface-variant hover:text-on-surface hover:bg-surface-variant transition">
                Sign In
            </button>
        </form>
    </div>
</body>
</html>
