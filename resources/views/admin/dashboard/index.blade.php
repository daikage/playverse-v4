<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8" />
    <title>Admin Command: Dashboard</title>

    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <style>.material-symbols-outlined{font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24;}</style>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-background h-screen w-full overflow-hidden flex antialiased selection:bg-tertiary/30 selection:text-tertiary">
    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col md:ml-64 h-full relative overflow-hidden bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-surface-container/20 via-background to-background">
        <header class="w-full h-24 px-margin-mobile md:px-margin-desktop flex items-center justify-between border-b border-outline-variant/10 flex-shrink-0 z-10 bg-background/80 backdrop-blur-md">
            <div>
                <h1 class="text-headline-md font-headline-md text-on-surface tracking-tight">Admin Dashboard</h1>
                <p class="text-data-label font-data-label text-on-surface-variant opacity-60 mt-1">System overview and quick links</p>
            </div>

            <div class="flex items-center gap-3">
                @if($impersonated)
                    <a href="{{ route('studio.dashboard', ['studio' => $impersonated->slug]) }}"
                       class="px-3 py-1.5 rounded border border-outline-variant/30 text-on-surface-variant hover:text-on-surface hover:bg-surface-variant transition">
                        <span class="material-symbols-outlined text-[18px]">person</span>
                        <span class="ml-1 text-sm">{{ $impersonated->name }}</span>
                    </a>
                    <a href="{{ route('admin.impersonate.stop') }}"
                       class="px-3 py-1.5 rounded border border-outline-variant/30 text-on-surface-variant hover:text-on-surface hover:bg-surface-variant transition text-sm">
                        Stop Impersonation
                    </a>
                @else
                    <a href="{{ route('admin.review.index') }}"
                       class="px-3 py-1.5 rounded border border-outline-variant/30 text-on-surface-variant hover:text-on-surface hover:bg-surface-variant transition text-sm">
                        Review Queue
                    </a>
                @endif
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-gutter">
            <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop space-y-8">

                <!-- KPI Cards -->
                <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-gutter">
                    <div class="bg-surface-container-low/40 border border-outline-variant/20 rounded-lg p-5">
                        <div class="flex items-center justify-between">
                            <span class="text-data-label text-on-surface-variant uppercase tracking-widest">Studios</span>
                            <span class="material-symbols-outlined text-on-surface-variant">business</span>
                        </div>
                        <div class="mt-3 text-3xl font-headline-md text-on-surface">{{ number_format($studioCounts['total']) }}</div>
                        <div class="mt-1 text-xs text-on-surface-variant">Approved {{ $studioCounts['approved'] }} • Pending {{ $studioCounts['pending'] }} • Suspended {{ $studioCounts['suspended'] }}</div>
                    </div>

                    <div class="bg-surface-container-low/40 border border-outline-variant/20 rounded-lg p-5">
                        <div class="flex items-center justify-between">
                            <span class="text-data-label text-on-surface-variant uppercase tracking-widest">Projects</span>
                            <span class="material-symbols-outlined text-on-surface-variant">apps</span>
                        </div>
                        <div class="mt-3 text-3xl font-headline-md text-on-surface">{{ number_format($projectCounts['total']) }}</div>
                        <div class="mt-1 text-xs text-on-surface-variant">Games {{ $projectCounts['games'] }} • Comics {{ $projectCounts['comics'] }} • Published {{ $projectCounts['published'] }}</div>
                    </div>

                    <div class="bg-surface-container-low/40 border border-outline-variant/20 rounded-lg p-5">
                        <div class="flex items-center justify-between">
                            <span class="text-data-label text-on-surface-variant uppercase tracking-widest">Review Queue</span>
                            <span class="material-symbols-outlined text-on-surface-variant">fact_check</span>
                        </div>
                        <div class="mt-3 text-3xl font-headline-md text-on-surface">{{ number_format($studioCounts['pending']) }}</div>
                        <div class="mt-3">
                            <a href="{{ route('admin.review.index') }}" class="text-tertiary text-sm underline">Open Verifications</a>
                        </div>
                    </div>

                    <div class="bg-surface-container-low/40 border border-outline-variant/20 rounded-lg p-5">
                        <div class="flex items-center justify-between">
                            <span class="text-data-label text-on-surface-variant uppercase tracking-widest">Deployment</span>
                            <span class="material-symbols-outlined text-on-surface-variant">cloud_upload</span>
                        </div>
                        <div class="mt-3 text-3xl font-headline-md text-on-surface">Ready</div>
                        <div class="mt-1 text-xs text-on-surface-variant">Forge & Composer operational</div>
                        <div class="mt-3 flex gap-3">
                            <a href="{{ route('admin.forge') }}" class="px-3 py-1.5 rounded border border-outline-variant/30 text-on-surface-variant hover:text-on-surface hover:bg-surface-variant transition text-sm">Open Forge</a>
                            <a href="{{ route('admin.comics') }}" class="px-3 py-1.5 rounded border border-outline-variant/30 text-on-surface-variant hover:text-on-surface hover:bg-surface-variant transition text-sm">Open Composer</a>
                        </div>
                    </div>
                </section>

                <!-- Two-column Panels -->
                <section class="grid grid-cols-1 lg:grid-cols-2 gap-gutter">
                    <!-- Pending Studios -->
                    <div class="bg-surface-container-low/40 border border-outline-variant/20 rounded-lg p-5">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-headline-md text-on-surface">Verification Queue (Preview)</h2>
                            <a href="{{ route('admin.review.index') }}" class="text-tertiary text-sm underline">View All</a>
                        </div>
                        <div class="space-y-3">
                            @forelse($pendingStudios as $s)
                                <div class="flex items-center justify-between border border-outline-variant/20 rounded p-3 bg-surface/40">
                                    <div class="text-sm">
                                        <div class="text-on-surface font-medium">{{ $s->name }}</div>
                                        <div class="text-on-surface-variant">Slug: {{ $s->slug }}</div>
                                    </div>
                                    <form action="{{ route('admin.review.approve', $s) }}" method="POST">
                                        @csrf
                                        <button class="px-3 py-1 text-sm rounded border border-tertiary/50 text-tertiary hover:bg-tertiary/10 transition">Approve</button>
                                    </form>
                                </div>
                            @empty
                                <div class="text-on-surface-variant text-sm">Queue is empty.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Recently Approved -->
                    <div class="bg-surface-container-low/40 border border-outline-variant/20 rounded-lg p-5">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-headline-md text-on-surface">Recently Approved Studios</h2>
                        </div>
                        <div class="space-y-3">
                            @forelse($recentApproved as $a)
                                <div class="flex items-center justify-between border border-outline-variant/20 rounded p-3 bg-surface/40">
                                    <div class="text-sm">
                                        <div class="text-on-surface font-medium">{{ $a->name }}</div>
                                        <div class="text-on-surface-variant">Key: <span class="text-on-surface">{{ $a->playverse_key ?? '—' }}</span></div>
                                    </div>
                                    <a href="{{ route('admin.impersonate.start', $a) }}"
                                       class="px-3 py-1 text-sm rounded border border-outline-variant/30 text-on-surface-variant hover:text-on-surface hover:bg-surface-variant transition">
                                        Impersonate
                                    </a>
                                </div>
                            @empty
                                <div class="text-on-surface-variant text-sm">No approvals yet.</div>
                            @endforelse
                        </div>
                    </div>
                </section>

                <!-- Recent Projects -->
                <section>
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-headline-md text-on-surface">Recent Projects</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-gutter">
                        @forelse($recentProjects as $p)
                            <article class="bg-surface-container-low/40 border border-outline-variant/20 rounded-lg p-5 flex flex-col gap-3">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm">
                                        <div class="text-on-surface font-medium truncate">{{ $p->title }}</div>
                                        <div class="text-on-surface-variant text-xs">
                                            {{ ucfirst($p->type) }} • {{ $p->author?->name ?? 'Unknown Studio' }}
                                        </div>
                                    </div>
                                    <span class="text-[10px] px-2 py-0.5 rounded border
                                        {{ $p->published ? 'text-tertiary border-tertiary/50' : 'text-on-surface-variant border-outline-variant/30' }}">
                                        {{ $p->published ? 'PUBLISHED' : 'DRAFT' }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between pt-2 border-t border-outline-variant/10">
                                    @if($p->type === 'comic')
                                        <span class="text-on-surface-variant text-xs">Pages: {{ is_array($p->pages) ? count($p->pages) : 0 }}</span>
                                    @else
                                        <span class="text-on-surface-variant text-xs">Platforms: {{ implode(', ', $p->platforms ?? []) ?: 'N/A' }}</span>
                                    @endif

                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.impersonate.start', $p->author) }}"
                                           class="text-tertiary text-xs underline">Studio</a>
                                        @if($p->type === 'game')
                                            <a href="{{ route('admin.forge') }}" class="text-on-surface-variant text-xs underline hover:text-on-surface">Forge</a>
                                        @else
                                            <a href="{{ route('admin.comics') }}" class="text-on-surface-variant text-xs underline hover:text-on-surface">Composer</a>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="col-span-full text-on-surface-variant text-sm">No projects yet.</div>
                        @endforelse
                    </div>
                </section>

            </div>
        </div>
    </main>
</body>
</html>
