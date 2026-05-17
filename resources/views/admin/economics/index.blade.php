<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8" />
    <title>Admin Command: Economics</title>

    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <style>.material-symbols-outlined{font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24;}</style>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-background h-screen w-full overflow-hidden flex antialiased selection:bg-tertiary/30">
    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col md:ml-64 h-full relative overflow-hidden bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-surface-container/20 via-background to-background">
        <header class="w-full h-24 px-margin-mobile md:px-margin-desktop flex items-center justify-between border-b border-outline-variant/10 flex-shrink-0 z-10 bg-background/80 backdrop-blur-md">
            <div>
                <h1 class="text-headline-md font-headline-md text-on-surface tracking-tight">Economics</h1>
                <p class="text-data-label font-data-label text-on-surface-variant opacity-60 mt-1">Payouts & revenue</p>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-gutter">
            <div class="max-w-5xl mx-auto p-6 bg-surface-container-low/40 border border-outline-variant/20 rounded-lg">
                <p class="text-on-surface-variant">Placeholder for payouts, revenue shares, and financial reports.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.dashboard') }}" class="text-tertiary underline">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
