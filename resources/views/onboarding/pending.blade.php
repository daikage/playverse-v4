<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Playverse — Pending Deployment</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0a0a0a] text-white antialiased min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full p-6 border border-gray-800 rounded-md text-center">
        <div class="text-yellow-400 mb-2">Live Signal: Pending Review</div>
        <h1 class="text-xl font-semibold mb-1">{{ $author->name }}</h1>
        <p class="text-xs text-gray-400">Your dossier is under review. Sandbox Mode is available; publishing is locked until Activation.</p>
        <div class="mt-6 text-sm text-gray-500">
            Studio Key will appear after approval.
        </div>
    </div>
</body>
</html>
