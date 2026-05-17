<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8" />
    <title>Welcome to Playverse</title>
    @fonts
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-background text-on-surface min-h-screen flex items-center justify-center">
    <div class="text-center space-y-4">
        <h1 class="text-display-lg-mobile md:text-display-lg">Playverse</h1>
        <p class="text-on-surface-variant">Discover games and comics from verified studios.</p>
        <div class="flex items-center justify-center gap-3">
            <a href="{{ route('discovery') }}" class="px-4 py-2 rounded border border-outline-variant/30 hover:bg-surface-variant">Enter Discovery</a>
            <a href="{{ route('admin.login') }}" class="px-4 py-2 rounded border border-outline-variant/30 hover:bg-surface-variant">Admin</a>
        </div>
    </div>
</body>
</html>