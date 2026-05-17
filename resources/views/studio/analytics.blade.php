<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Playverse Author Studio - Analytics</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        .material-symbols-outlined{font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24;}
        .tactical-grid{
            background-image:
                linear-gradient(to right, rgba(68,71,72,.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(68,71,72,.05) 1px, transparent 1px);
            background-size: 24px 24px;
        }
        /* +++ NEW: bar animation helper +++ */
        .bar-animate {
            height: 0%;
            transition-property: height;
            transition-duration: 900ms;
            transition-timing-function: cubic-bezier(0.22, 1, 0.36, 1);
        }
    </style>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-background min-h-screen font-body-regular selection:bg-tertiary/30 flex">
    {{-- Sidebar: admin if admin route, otherwise studio --}}
    @if (request()->routeIs('admin.*'))
        @include('admin.partials.sidebar')
        @php
            $toForge  = route('admin.forge');
            $toComics = route('admin.comics');
            $toDash   = route('admin.dashboard');
        @endphp
    @else
        @include('studio.partials.sidebar')
        @php
            $toForge  = route('studio.forge', ['studio' => request()->route('studio')]);
            $toComics = route('studio.comics', ['studio' => request()->route('studio')]);
            $toDash   = route('studio.dashboard', ['studio' => request()->route('studio')]);
        @endphp
    @endif

    <main class="flex-1 md:ml-64 min-h-screen p-margin-mobile md:p-margin-desktop tactical-grid relative">
        <!-- Header -->
        <header class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-display-lg-mobile md:text-display-lg font-display-lg-mobile md:font-display-lg text-on-surface">Analytics</h2>
                <p class="text-data-value font-data-value text-on-surface-variant mt-2 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-tertiary inline-block animate-pulse"></span>
                    Realtime overview for your studio
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ $toDash }}" class="px-3 py-1.5 rounded border border-outline-variant/30 text-on-surface-variant hover:text-on-surface hover:bg-surface-variant transition text-sm">
                    Back to Dashboard
                </a>
                <a href="{{ $toForge }}" class="px-3 py-1.5 rounded border border-outline-variant/30 text-on-surface-variant hover:text-on-surface hover:bg-surface-variant transition text-sm">
                    Open Forge
                </a>
                <a href="{{ $toComics }}" class="px-3 py-1.5 rounded border border-outline-variant/30 text-on-surface-variant hover:text-on-surface hover:bg-surface-variant transition text-sm">
                    Open Composer
                </a>
            </div>
        </header>

        <!-- KPI Grid -->
        <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-gutter mb-gutter">
            <div class="bg-surface/70 border border-outline-variant/20 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <span class="text-data-label text-on-surface-variant uppercase tracking-widest">Total Projects</span>
                    <span class="material-symbols-outlined text-on-surface-variant">apps</span>
                </div>
                <div class="mt-3 text-3xl font-headline-md text-on-surface">{{ number_format($projectsTotal) }}</div>
                <div class="mt-1 text-xs text-on-surface-variant">Games {{ $gamesCount }} • Comics {{ $comicsCount }}</div>
            </div>

            <div class="bg-surface/70 border border-outline-variant/20 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <span class="text-data-label text-on-surface-variant uppercase tracking-widest">Comics Pages</span>
                    <span class="material-symbols-outlined text-on-surface-variant">auto_stories</span>
                </div>
                <div class="mt-3 text-3xl font-headline-md text-on-surface">{{ number_format($totalPages) }}</div>
                <div class="mt-1 text-xs text-on-surface-variant">Across {{ $comicsCount }} comic projects</div>
            </div>

            <div class="bg-surface/70 border border-outline-variant/20 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <span class="text-data-label text-on-surface-variant uppercase tracking-widest">Published</span>
                    <span class="material-symbols-outlined text-on-surface-variant">cloud_done</span>
                </div>
                <div class="mt-3 text-3xl font-headline-md text-on-surface">{{ number_format($publishedCount) }}</div>
                <div class="mt-1 text-xs text-on-surface-variant">Projects publicly available</div>
            </div>

            <div class="bg-surface-container-high border border-outline-variant/10 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <span class="text-data-label text-on-surface-variant uppercase tracking-widest">Server Load</span>
                    <span class="material-symbols-outlined text-error">memory</span>
                </div>
                <div class="mt-3 flex items-center justify-between">
                    <span class="text-3xl font-headline-md text-on-surface">{{ $serverLoad }}%</span>
                </div>
                <div class="mt-3 w-full bg-surface-container h-1.5 rounded-full overflow-hidden">
                    <div class="bg-error h-full rounded-full" style="width: {{ $serverLoad }}%"></div>
                </div>
            </div>
        </section>

        <!-- Charts + Logs -->
        <section class="grid grid-cols-1 md:grid-cols-12 gap-gutter">
            <!-- Chart: Projects Over Time -->
            <div class="col-span-1 md:col-span-8 bg-surface/70 border border-outline-variant/20 rounded-lg p-6 min-h-[360px] flex flex-col">
                <div class="flex justify-between items-center border-b border-outline-variant/10 pb-4 mb-6">
                    <h3 class="text-headline-md font-headline-md text-on-surface">Projects Over Time</h3>
                    <div class="flex gap-2">
                        <button class="px-3 py-1 text-data-label bg-tertiary/10 text-tertiary rounded-sm border border-tertiary/20">7D</button>
                        <button class="px-3 py-1 text-data-label text-on-surface-variant">30D</button>
                        <button class="px-3 py-1 text-data-label text-on-surface-variant">ALL</button>
                    </div>
                </div>

                <!-- +++ REPLACED: dynamic, animated bars bound to backend data +++ -->
                <div id="projects-chart" class="flex-1 relative flex items-end justify-between px-4 pb-8 pt-12 border-l border-b border-outline-variant/20 ml-8 mb-4">
                    <!-- Y Axis Labels from backend max -->
                    <div class="absolute -left-10 bottom-0 text-data-label text-on-surface-variant">0</div>
                    <div class="absolute -left-12 top-1/2 -translate-y-1/2 text-data-label text-on-surface-variant">
                        {{ number_format(ceil($projectTimelineMax / 2)) }}
                    </div>
                    <div class="absolute -left-12 top-0 text-data-label text-on-surface-variant">
                        {{ number_format($projectTimelineMax) }}
                    </div>

                    @foreach($projectTimeline as $idx => $pt)
                        <div class="group relative flex flex-col items-center justify-end"
                             style="width: 2rem;">
                            <div class="bar-animate w-8 bg-surface-variant border border-outline-variant/30 rounded-sm shadow-sm
                                        {{ $pt['count'] === $projectTimelineMax ? 'ring-1 ring-tertiary/40 bg-tertiary/20 border-tertiary/40' : '' }}"
                                 data-target="{{ $pt['percent'] }}"
                                 data-value="{{ $pt['count'] }}"
                                 style="height: 0%;">
                            </div>
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 text-data-label text-on-surface transition-opacity">
                                {{ number_format($pt['count']) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- X Axis labels from backend -->
                <div class="flex justify-between px-8 text-data-label text-on-surface-variant mt-2 ml-4">
                    @foreach($projectTimeline as $pt)
                        <span>{{ $pt['label'] }}</span>
                    @endforeach
                </div>
            </div>

            <!-- Deploy/Activity logs -->
            <div class="col-span-1 md:col-span-4 bg-surface-container-lowest border border-outline-variant/20 rounded-lg p-6 flex flex-col">
                <div class="flex justify-between items-center border-b border-outline-variant/10 pb-4 mb-4">
                    <h3 class="text-data-label text-on-surface-variant uppercase tracking-widest flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">terminal</span>
                        Activity Feed
                    </h3>
                </div>
                <div class="flex-1 overflow-y-auto space-y-3 font-data-value text-on-surface-variant">
                    @forelse($recentProjects as $p)
                        <div class="flex items-center gap-3 border border-outline-variant/20 rounded p-2 bg-surface/40">
                            <span class="material-symbols-outlined text-[16px] {{ $p->type === 'comic' ? 'text-tertiary' : 'text-primary' }}">
                                {{ $p->type === 'comic' ? 'menu_book' : 'smart_toy' }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <div class="text-on-surface text-sm truncate">{{ $p->title }}</div>
                                <div class="text-[11px] text-on-surface-variant">
                                    {{ ucfirst($p->type) }} • {{ $p->created_at?->format('Y-m-d H:i') ?? '—' }}
                                </div>
                            </div>
                            @if($p->type === 'comic')
                                <a href="{{ $toComics }}" class="text-tertiary text-xs underline">Open</a>
                            @else
                                <a href="{{ $toForge }}" class="text-primary text-xs underline">Open</a>
                            @endif
                        </div>
                    @empty
                        <div class="text-on-surface-variant text-sm">No recent activity.</div>
                    @endforelse
                </div>
            </div>
        </section>
    </main>

    <!-- +++ NEW: animate bars on first paint using backend percentages +++ -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const bars = document.querySelectorAll('#projects-chart .bar-animate');
            bars.forEach((bar, idx) => {
                const p = parseFloat(bar.getAttribute('data-target') || '0');
                // add stagger for a nice cascade effect
                bar.style.transitionDelay = (idx * 60) + 'ms';
                requestAnimationFrame(() => {
                    bar.style.height = (isFinite(p) ? p : 0) + '%';
                });
            });
        });
    </script>
</body>
</html>
