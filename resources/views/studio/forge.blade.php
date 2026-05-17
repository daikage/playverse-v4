<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <title>Playverse OS - Project Forge</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <style>
        .material-symbols-outlined{font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24;}
        .bg-tactical-grid{
            background-size:40px 40px;
            background-image:
                linear-gradient(to right, rgba(142,145,146,0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(142,145,146,0.03) 1px, transparent 1px);
        }
    </style>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-surface font-body-regular min-h-screen overflow-hidden selection:bg-tertiary/30 selection:text-tertiary flex relative">
    @if (request()->routeIs('admin.*'))
        @include('admin.partials.sidebar')
        @php
            $submitAction = route('admin.forge.store');
        @endphp
    @else
        @include('studio.partials.sidebar')
        @php
            $submitAction = route('studio.forge.store', ['studio' => request()->route('studio')]);
        @endphp
    @endif

    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] bg-tertiary/5 blur-[120px] rounded-full"></div>
        <div class="absolute top-[40%] -right-[10%] w-[40%] h-[60%] bg-primary/5 blur-[150px] rounded-full"></div>
        <div class="absolute inset-0 bg-tactical-grid opacity-50"></div>
    </div>

    <main class="flex-1 md:ml-64 mt-0 md:mt-0 flex flex-col h-screen relative z-10">
        <header class="w-full h-16 px-margin-mobile md:px-margin-desktop flex items-center justify-between border-b border-outline-variant/20 flex-shrink-0 z-10 bg-surface/40 backdrop-blur-xl">
            <div class="flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-tertiary animate-pulse"></span>
                <h1 class="text-headline-md font-headline-md tracking-tight">Project Forge</h1>
            </div>
            @php
                $backHref = request()->routeIs('admin.*')
                    ? route('admin.dashboard')
                    : route('studio.dashboard', ['studio' => request()->route('studio')]);
            @endphp
            <a href="{{ $backHref }}" class="text-on-surface-variant hover:text-on-surface transition-colors text-sm">Back</a>
        </header>

        <div class="flex-1 p-margin-desktop overflow-y-auto relative">
            <div class="w-full max-w-container-max mx-auto flex flex-col xl:flex-row gap-gutter">
                <!-- Left Primary Panel -->
                <div class="flex-1 bg-surface-container/80 backdrop-blur-3xl border border-outline-variant/20 rounded-xl p-8 flex flex-col relative overflow-hidden shadow-2xl">
                    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-tertiary/30 to-transparent"></div>

                    <header class="mb-6">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="w-2 h-2 rounded-full bg-tertiary animate-pulse"></span>
                            <span class="font-data-label text-tertiary text-[10px] tracking-widest uppercase">Upload Pipeline</span>
                        </div>
                        <h2 class="font-display-lg text-on-surface mb-2">PROJECT FORGE</h2>
                        <p class="font-body-sm text-on-surface-variant max-w-xl">Upload compiled binaries, screenshots, and optional videos. Register a new project.</p>

                        @if (session('status'))
                            <div class="mt-3 text-xs text-tertiary">{{ session('status') }}</div>
                        @endif
                        @if ($errors->any())
                            <div class="mt-3 text-xs text-error">There were validation errors. Please review below.</div>
                        @endif
                    </header>

                    <!-- Form -->
                    <form method="POST" enctype="multipart/form-data" action="{{ $submitAction }}" class="flex-1 flex flex-col gap-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="font-data-label text-on-surface-variant text-[11px]">Title</label>
                                <input name="title"
                                       class="mt-2 w-full bg-surface border border-outline-variant/30 rounded px-3 py-2 text-sm focus:outline-none focus:border-tertiary/50"
                                       value="{{ old('title') }}"
                                       required>
                                @error('title')<div class="text-xs text-error mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div>
                                <label class="font-data-label text-on-surface-variant text-[11px]">Platforms</label>
                                <div class="mt-2 grid grid-cols-2 gap-2">
                                    @php $opts = ['windows' => 'Windows', 'mac' => 'Mac', 'android' => 'Android', 'ios' => 'iOS']; @endphp
                                    @foreach ($opts as $val => $label)
                                        <label class="flex items-center gap-2 text-sm text-on-surface-variant">
                                            <input type="checkbox" name="platforms[]" value="{{ $val }}" class="rounded border-outline-variant/40 bg-surface" {{ in_array($val, (array) old('platforms', []), true) ? 'checked' : '' }}>
                                            <span>{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('platforms')<div class="text-xs text-error mt-1">{{ $message }}</div>@enderror
                                @error('platforms.*')<div class="text-xs text-error mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div>
                            <label class="font-data-label text-on-surface-variant text-[11px]">Binary Upload (.exe, .dmg, .pkg, .apk, .zip)</label>
                            <input type="file" name="binary" class="mt-2 w-full text-sm" required>
                            @error('binary')<div class="text-xs text-error mt-1">{{ $message }}</div>@enderror
                        </div>

                        <!-- NEW: Screenshots uploader -->
                        <div>
                            <label class="font-data-label text-on-surface-variant text-[11px]">Screenshots (JPEG/PNG/WEBP)</label>
                            <input type="file" name="screenshots[]" accept="image/jpeg,image/png,image/webp" class="mt-2 w-full text-sm" multiple>
                            <p class="text-[11px] text-on-surface-variant mt-1">You can upload multiple screenshots.</p>
                            @error('screenshots')<div class="text-xs text-error mt-1">{{ $message }}</div>@enderror
                            @error('screenshots.*')<div class="text-xs text-error mt-1">{{ $message }}</div>@enderror
                        </div>

                        <!-- NEW: Videos uploader (optional) -->
                        <div>
                            <label class="font-data-label text-on-surface-variant text-[11px]">Product Videos (optional)</label>
                            <input type="file" name="videos[]" accept="video/mp4,video/quicktime,video/x-msvideo,video/x-matroska" class="mt-2 w-full text-sm" multiple>
                            <p class="text-[11px] text-on-surface-variant mt-1">MP4, MOV, AVI, MKV up to ~500MB per file.</p>
                            @error('videos')<div class="text-xs text-error mt-1">{{ $message }}</div>@enderror
                            @error('videos.*')<div class="text-xs text-error mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mt-2 flex justify-between items-center border-t border-outline-variant/20 pt-6">
                            <a href="{{ $backHref }}"
                               class="px-6 py-2 rounded-lg font-data-label text-on-surface border border-outline-variant hover:bg-surface-variant transition-colors text-[12px]">
                                ABORT SEQUENCE
                            </a>

                            <button type="submit"
                                    class="px-8 py-2 rounded-lg font-data-label text-tertiary bg-surface border border-tertiary/50 hover:bg-tertiary/10 hover:border-tertiary hover:shadow-[0_0_20px_rgba(208,188,255,0.15)] transition-all text-[12px] flex items-center gap-2">
                                INITIATE UPLOAD
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Context Panel -->
                <aside class="w-full xl:w-80 flex-col gap-gutter shrink-0 hidden lg:flex">
                    <!-- NETWORK TELEMETRY (dynamic) -->
                    <div class="bg-surface-container-low/60 backdrop-blur-xl border border-outline-variant/10 rounded-xl p-6 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10">
                            <span class="material-symbols-outlined text-[80px]">sensors</span>
                        </div>
                        <h3 class="font-data-label text-on-surface mb-6 border-b border-outline-variant/20 pb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-primary">memory</span>
                            NETWORK TELEMETRY
                        </h3>
                        <div class="flex flex-col gap-4 font-data-value text-[12px]">
                            <div class="flex justify-between items-center">
                                <span class="text-on-surface-variant">Uplink Node</span>
                                <span class="text-on-surface">{{ $telemetry['node'] ?? 'US-EAST-1' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-on-surface-variant">Latency</span>
                                <span class="text-primary flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                                    {{ $telemetry['latency_ms'] ?? 20 }}ms
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-on-surface-variant">Encryption</span>
                                <span class="text-tertiary">{{ $telemetry['encryption'] ?? 'AES-256-GCM' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-on-surface-variant">Available Bandwidth</span>
                                <span class="text-on-surface">{{ $telemetry['bandwidth'] ?? '2.0 Gbps' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- ACTIVE BUILD LOG (dynamic) -->
                    <div class="bg-surface-container-lowest border border-outline-variant/20 rounded-xl p-0 flex flex-col flex-1 relative overflow-hidden">
                        <div class="bg-surface-variant/40 border-b border-outline-variant/20 p-3 px-4 flex justify-between items-center">
                            <h3 class="font-data-label text-on-surface text-[11px]">ACTIVE BUILD LOG</h3>
                            <span class="material-symbols-outlined text-[14px] text-on-surface-variant">receipt_long</span>
                        </div>
                        <div class="p-4 font-data-value text-[10px] text-on-surface-variant/80 leading-relaxed overflow-y-auto flex-1">
                            @forelse($buildLog as $line)
                                <div class="flex gap-3 {{ $line['level'] === 'BUILD' ? 'bg-surface-variant/30 p-2 rounded' : '' }} mb-1">
                                    <span class="text-outline">{{ $line['time'] }}</span>
                                    <span class="{{ $line['level'] === 'BUILD' ? 'text-tertiary font-bold' : ($line['level'] === 'SYNC' ? 'text-primary' : 'text-on-surface') }}">
                                        [{{ $line['level'] }}]
                                    </span>
                                    <span class="text-on-surface">{{ $line['message'] }}</span>
                                </div>
                            @empty
                                <p class="text-on-surface-variant">No recent build activity. Upload a binary to start logging.</p>
                            @endforelse
                            <span class="inline-block w-2 h-3 bg-tertiary/60 animate-pulse mt-2"></span>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>
</body>
</html>