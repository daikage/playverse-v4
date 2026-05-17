<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Playverse Author Studio - Comic Composer</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        .material-symbols-outlined{font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24;}
        .progress-scanline{background-image:repeating-linear-gradient(-45deg,transparent,transparent 4px,rgba(255,255,255,.1) 4px,rgba(255,255,255,.1) 8px);}
        .bg-mesh-dark{background-image:
            radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%),
            radial-gradient(at 50% 0%, hsla(225,39%,30%,0.05) 0, transparent 50%),
            radial-gradient(at 100% 0%, hsla(339,49%,30%,0.05) 0, transparent 50%);
        }
    </style>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background bg-mesh-dark min-h-screen selection:bg-tertiary/30 selection:text-tertiary antialiased flex">
    {{-- Sidebar: admin if admin route, otherwise studio --}}
    @if (request()->routeIs('admin.*'))
        @include('admin.partials.sidebar')
        @php
            $uploadAction = route('admin.comics.store');
            $publishRoute = fn($p,$state) => route('admin.comics.publish', ['project'=>$p->id, 'state'=>$state]);
        @endphp
    @else
        @include('studio.partials.sidebar')
        @php
            $uploadAction = route('studio.comics.store', ['studio' => request()->route('studio')]);
            $publishRoute = fn($p,$state) => route('studio.comics.publish', ['studio'=>request()->route('studio'), 'project'=>$p->id, 'state'=>$state]);
        @endphp
    @endif

    <!-- Main Canvas -->
    <main class="flex-1 md:ml-64 pt-16 min-h-screen px-margin-mobile md:px-margin-desktop py-12 gap-8 max-w-[1440px] mx-auto">
        <!-- Header -->
        <header class="flex flex-col gap-2">
            <div class="text-data-label font-data-label text-on-surface-variant uppercase tracking-widest flex items-center gap-2 opacity-70">
                <span>WORKSPACE</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-tertiary">COMIC COMPOSER</span>
            </div>
            <div class="flex justify-between items-end">
                <h1 class="text-display-lg-mobile md:text-display-lg font-display-lg-mobile md:font-display-lg text-on-surface tracking-tighter">Comic Composer</h1>
                <div class="flex gap-4">
                    @if(!empty($current))
                        <form method="POST" action="{{ $publishRoute($current, $current->published ? 'unpublish' : 'publish') }}">
                            @csrf
                            <button class="px-6 py-2 border border-outline/30 text-on-surface text-data-label font-data-label hover:bg-white/5 transition-all duration-150 rounded-sm" type="submit">
                                {{ $current->published ? 'Set Draft' : 'Publish' }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @if(session('status'))
                <div class="text-xs text-tertiary mt-2">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="text-xs text-error mt-2">There were validation errors. Please review your upload.</div>
            @endif
        </header>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left Column: Uploader & Preview -->
            <div class="lg:col-span-8 flex flex-col gap-6">
                <!-- Uploader Zone -->
                <section class="rounded-xl p-6 border border-outline-variant/20 bg-surface/50 backdrop-blur-md">
                    <h3 class="text-headline-md font-headline-md text-on-surface mb-4">Asset Ingestion</h3>

                    <form method="POST" action="{{ $uploadAction }}" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        <div>
                            <label class="text-data-label text-on-surface-variant uppercase tracking-widest text-[11px]">Title</label>
                            <input name="title" class="mt-2 w-full bg-surface border border-outline-variant/30 rounded px-3 py-2 text-sm" required value="{{ old('title') }}">
                            @error('title')<div class="text-xs text-error mt-1">{{ $message }}</div>@enderror
                        </div>

                        <!-- NEW: Thumbnail (Cover) -->
                        <div>
                            <label class="text-data-label text-on-surface-variant uppercase tracking-widest text-[11px]">Thumbnail (Cover)</label>
                            <input type="file" name="thumbnail" accept="image/jpeg,image/png,image/webp" class="mt-2 w-full text-sm">
                            <p class="text-[11px] text-on-surface-variant mt-1">Shown in listings and detail pages. Optional, recommended 3:4.</p>
                            @error('thumbnail')<div class="text-xs text-error mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div class="border-2 border-dashed border-outline-variant/40 rounded-xl p-6 flex flex-col items-center justify-center bg-surface-container-low/50 hover:bg-tertiary/5 hover:border-tertiary/40 transition-all duration-300">
                                <span class="material-symbols-outlined text-[36px] text-on-surface-variant">archive</span>
                                <p class="text-sm text-on-surface mt-2">Upload CBZ/ZIP/PDF</p>
                                <input type="file" name="archive" accept=".cbz,.zip,.pdf" class="mt-3 w-full text-sm">
                                @error('archive')<div class="text-xs text-error mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="border-2 border-dashed border-outline-variant/40 rounded-xl p-6 flex flex-col items-center justify-center bg-surface-container-low/50 hover:bg-tertiary/5 hover:border-tertiary/40 transition-all duration-300">
                                <span class="material-symbols-outlined text-[36px] text-on-surface-variant">imagesmode</span>
                                <p class="text-sm text-on-surface mt-2">Or select images (multi)</p>
                                <input type="file" name="images[]" accept="image/*" multiple class="mt-3 w-full text-sm">
                                @error('images')<div class="text-xs text-error mt-1">{{ $message }}</div>@enderror
                                @error('images.*')<div class="text-xs text-error mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-8 py-2 bg-surface border border-tertiary/50 text-tertiary rounded hover:bg-tertiary/10 hover:border-tertiary transition-all text-[12px] flex items-center gap-2">
                                Initiate Upload
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </button>
                        </div>
                    </form>
                </section>

                <!-- Sequence Preview -->
                <section class="rounded-xl p-6 border border-outline-variant/20 bg-surface/50 backdrop-blur-md">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-data-label font-data-label text-on-surface-variant uppercase tracking-widest">Sequence Preview</h3>
                        @if($current)
                            <div class="text-xs text-on-surface-variant">Pages: {{ is_array($current->pages) ? count($current->pages) : 0 }}</div>
                        @endif
                    </div>

                    @if($current && count($pages))
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($pages as $i => $url)
                                <div class="aspect-[2/3] border border-outline-variant/20 rounded-lg overflow-hidden relative">
                                    <img src="{{ $url }}" alt="Page {{ $i + 1 }}" class="w-full h-full object-cover">
                                    <div class="absolute top-2 left-2 bg-surface-container/80 backdrop-blur-sm px-2 py-1 rounded text-[10px] font-data-label text-on-surface border border-white/10">PG {{ sprintf('%02d', $i + 1) }}</div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-on-surface-variant text-sm">
                            @if($current)
                                No pages yet. Upload a chapter to begin processing.
                            @else
                                No comic projects yet. Use the form above to create one.
                            @endif
                        </div>
                    @endif
                </section>
            </div>

            <!-- Right Column: Projects & Metadata -->
            <div class="lg:col-span-4">
                <aside class="rounded-xl p-6 border border-outline-variant/20 bg-surface/50 backdrop-blur-md h-full flex flex-col gap-6 sticky top-24">
                    <!-- NEW: Current cover preview -->
                    @if(!empty($coverUrl))
                        <div class="aspect-[3/4] rounded-lg overflow-hidden border border-outline-variant/20">
                            <img src="{{ $coverUrl }}" alt="Cover" class="w-full h-full object-cover">
                        </div>
                    @endif

                    <div class="flex items-center gap-2 mb-2 pb-4 border-b border-outline-variant/20">
                        <span class="material-symbols-outlined text-[18px] text-on-surface-variant">library_books</span>
                        <h3 class="text-data-label font-data-label text-on-surface uppercase tracking-widest">Your Comics</h3>
                    </div>

                    <div class="flex flex-col gap-2 max-h-[50vh] overflow-y-auto pr-1">
                        @forelse($projects as $p)
                            @php $active = isset($current) && $current && $current->id === $p->id; @endphp
                            <a href="{{ request()->routeIs('admin.*')
                                        ? route('admin.comics', ['project' => $p->id])
                                        : route('studio.comics', ['studio'=>request()->route('studio'), 'project' => $p->id]) }}"
                               class="flex items-center gap-3 px-3 py-2 rounded border {{ $active ? 'border-tertiary/50 bg-tertiary/10 text-tertiary' : 'border-outline-variant/20 hover:bg-white/5 text-on-surface-variant' }}">
                                @if(!empty($p->thumbnail_path))
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($p->thumbnail_path) }}"
                                         alt="thumb"
                                         class="w-10 h-10 rounded object-cover border border-outline-variant/20">
                                @else
                                    <div class="w-10 h-10 rounded bg-surface-variant border border-outline-variant/20 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[18px]">image</span>
                                    </div>
                                @endif
                                <div class="truncate">
                                    <div class="text-sm truncate">{{ $p->title }}</div>
                                    <div class="text-[10px] opacity-70">{{ $p->published ? 'PUBLISHED' : 'DRAFT' }}</div>
                                </div>
                                <span class="material-symbols-outlined text-[16px] ml-auto">arrow_forward</span>
                            </a>
                        @empty
                            <div class="text-on-surface-variant text-sm">No comics yet.</div>
                        @endforelse
                    </div>

                    <div class="mt-auto pt-4 border-t border-outline-variant/20 text-xs text-on-surface-variant">
                        Processing uses queued jobs; ensure your queue worker is running.
                    </div>
                </aside>
            </div>
        </div>
    </main>
</body>
</html>
