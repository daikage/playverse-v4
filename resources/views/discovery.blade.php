<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Playverse Discovery Hub</title>

    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-surface font-body-regular antialiased min-h-screen selection:bg-tertiary/30 selection:text-tertiary">
    <!-- TopNavBar -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-surface/40 backdrop-blur-xl border-b border-outline-variant/20 flex justify-between items-center w-full px-margin-mobile md:px-margin-desktop h-16">
        <div class="flex items-center gap-8">
            <span class="text-headline-md font-headline-md font-bold tracking-tighter text-on-surface">PLAYVERSE</span>
            <div class="hidden md:flex items-center gap-6">
                <a class="text-on-surface-variant font-data-label text-data-label hover:text-primary transition-colors duration-150" href="/onboarding/register">Studio</a>
                <a class="text-on-surface-variant font-data-label text-data-label hover:text-primary transition-colors duration-150" href="{{ route('admin.login') }}">Admin</a>
                <a class="text-primary border-b-2 border-primary pb-1 font-data-label text-data-label opacity-80 scale-95 transition-all" href="{{ route('discovery') }}">Discovery</a>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <button class="text-on-surface-variant hover:text-primary transition-colors duration-150 flex items-center justify-center w-8 h-8 rounded-full" aria-label="Notifications">
                <span class="material-symbols-outlined text-[20px]">notifications</span>
            </button>
            <a href="{{ route('settings.show') }}" class="text-on-surface-variant hover:text-primary transition-colors duration-150 flex items-center justify-center w-8 h-8 rounded-full" aria-label="Settings">
                <span class="material-symbols-outlined text-[20px]">settings</span>
            </a>
            <a href="{{ route('profile.show') }}" class="w-8 h-8 rounded-full overflow-hidden border border-outline-variant/30 relative" aria-label="Profile">
                <img
                    class="w-full h-full object-cover"
                    alt="User Profile Avatar"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuBhXFp-d1TK5q2E05gxwbd2K8mxt5hF22rQdjzzlIOuPHspsmseFdzXoDHMFjzjXgPtc0FFmKxHloxUnd4tyI8w-3OF-c1g1sK3pF_R9s9Y5qE88oZf8bQ8YCAG642QCcFy4Mgt7zEgD0b4Z78YUqHHKEOdX7C2BWKHQRjtLTlVAkCPEFqDCjejzWXrwAx1OoJ3Qi0rJTsn2Hmj9iS7iwWr6SKdQKUnU55i2adYHrXXOp5eRvFW6TKinTJSCx5tQI6bQlqANTZwOcnv"
                />
            </a>
        </div>
    </nav>

    <!-- Main Content Canvas -->
    <main class="pt-16 pb-32">
        <!-- Hero Aurora Section -->
        <header class="relative w-full h-[614px] min-h-[500px] flex items-center overflow-hidden border-b border-outline-variant/10">
            <!-- Aurora Mesh Effects -->
            <div class="absolute top-[-20%] left-[-10%] w-[60%] h-[80%] rounded bg-on-tertiary-container/10 blur-[120px] mix-blend-screen pointer-events-none"></div>
            <div class="absolute bottom-[-20%] right-[-10%] w-[50%] h-[70%] rounded bg-tertiary/10 blur-[100px] mix-blend-screen pointer-events-none"></div>
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPjxyZWN0IHdpZHRoPSI0IiBoZWlnaHQ9IjQiIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wMiIvPjwvc3ZnPg==')] opacity-50 pointer-events-none"></div>
            <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop w-full relative z-10 flex flex-col items-start gap-gutter">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded border border-tertiary/30 bg-tertiary/5 backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-tertiary animate-pulse"></span>
                    <span class="font-data-label text-data-label text-tertiary tracking-wider uppercase">Live Network</span>
                </div>
                <h1 class="font-display-lg-mobile text-display-lg-mobile md:font-display-lg md:text-display-lg text-on-surface max-w-3xl leading-tight">
                    Discover Elite <br />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-tertiary to-on-surface">Digital Environments</span>
                </h1>
                <p class="font-body-regular text-body-regular text-on-surface-variant max-w-xl">
                    Access high-fidelity simulations, certified gaming experiences, and verified comic archives. Curated for the ultimate tactical immersion.
                </p>
                <div class="flex items-center gap-4 mt-4">
                    <a href="#catalog" class="px-6 py-3 bg-surface-variant text-on-surface font-data-label text-data-label rounded border border-outline-variant/30 hover:bg-surface-bright hover:text-white transition-all duration-150 flex items-center gap-2 group">
                        <span class="material-symbols-outlined text-[18px] group-hover:rotate-12 transition-transform">explore</span>
                        BROWSE CATALOG
                    </a>
                </div>
            </div>
        </header>

        <!-- Platform Smart Filtering -->
        <section class="sticky top-16 z-40 bg-background/80 backdrop-blur-2xl border-b border-outline-variant/20 py-4">
            <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop w-full flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-2 bg-surface-container-low p-1 rounded border border-outline-variant/10">
                    <button class="px-4 py-2 bg-surface-variant text-on-surface rounded font-data-label text-data-label flex items-center gap-2 border border-outline-variant/30 shadow-[0_0_10px_rgba(208,188,255,0.05)]">
                        <span class="material-symbols-outlined text-[16px]">desktop_windows</span>
                        Win
                    </button>
                    <button class="px-4 py-2 text-on-surface-variant hover:text-on-surface rounded font-data-label text-data-label flex items-center gap-2 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">desktop_mac</span>
                        Mac
                    </button>
                    <button class="px-4 py-2 text-on-surface-variant hover:text-on-surface rounded font-data-label text-data-label flex items-center gap-2 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">phone_iphone</span>
                        Mobile
                    </button>
                </div>
                <div class="flex items-center gap-3">
                    <span class="font-data-label text-data-label text-on-surface-variant">All Platforms</span>
                    <button class="w-10 h-5 bg-tertiary/20 rounded-full relative flex items-center px-1 border border-tertiary/30 transition-colors cursor-pointer" aria-label="Toggle All Platforms">
                        <div class="w-3 h-3 bg-tertiary rounded-full absolute right-1 shadow-[0_0_8px_rgba(208,188,255,0.6)]"></div>
                    </button>
                </div>
            </div>
        </section>

        <!-- Archive Grid -->
        <section id="catalog" class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop w-full mt-16">
            <div class="flex items-center justify-between mb-8">
                <h2 class="font-headline-md text-headline-md text-on-surface">Trending Archives</h2>
                <a href="{{ route('discovery') }}" class="text-on-surface-variant font-data-label text-data-label hover:text-tertiary flex items-center gap-1 transition-colors">
                    VIEW ALL <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>

            @php
                use Illuminate\Support\Facades\Storage;
                // Prefer controller-provided lists; otherwise default to empty.
                $items = isset($featured) && $featured ? $featured : ($projects ?? collect());
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
                @forelse(($items instanceof \Illuminate\Support\Collection ? $items : collect($items))->take(9) as $p)
                    @php
                        // Resolve cover image: comic thumbnail or first game screenshot; fallback placeholder
                        $cover = null;
                        $disk = config('filesystems.default', 'public');
                        if ($p->type === 'comic' && !empty($p->thumbnail_path)) {
                            $cover = $disk === 'public' ? Storage::disk('public')->url($p->thumbnail_path) : Storage::disk($disk)->temporaryUrl($p->thumbnail_path, now()->addMinutes(15));
                        } elseif (is_array($p->screenshots) && count($p->screenshots)) {
                            $first = $p->screenshots[0];
                            $cover = $disk === 'public' ? Storage::disk('public')->url($first) : Storage::disk($disk)->temporaryUrl($first, now()->addMinutes(15));
                        }
                        $cover = $cover ?: 'https://lh3.googleusercontent.com/aida-public/AB6AXuByTecouqnbnr25fTXR2hH34VUMIvfUnC7GEJuIVLmY35AZVcua_3pkvohHgvhqJi0gLasrnJv40oCgTKlwhzkXW8n5i8Vb9sWitgZ72dY0WigoAonr3sVzBAmCUxnU4K3hY8P4E22DVeYCcJOzUHg0Ey6rzrWeSkyqh8ceXqKCnlR8cWWnbdec6w_gkxskJlTFpkfQcVVx9H5i_WpbqA5nWtjqicSMOfUrQxPRY2JZ4C_bSwBgAlc1TASJ9FzzbMdSI8TTykQ52kEx';
                        $isVerified = (bool)($p->published);
                        $platforms = (array)($p->platforms ?? []);
                    @endphp

                    <article class="group relative bg-surface-container-low border border-outline-variant/20 rounded overflow-hidden hover:-translate-y-1 hover:border-tertiary/40 transition-all duration-300 shadow-[0_4px_24px_rgba(0,0,0,0.4)]">
                        <div class="aspect-video w-full relative overflow-hidden bg-surface-container-highest">
                            <img
                                alt="{{ $p->title }}"
                                class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500"
                                src="{{ $cover }}"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-background via-transparent to-transparent"></div>
                            <div class="absolute top-3 left-3 flex gap-2">
                                <span class="px-2 py-1 bg-surface/80 backdrop-blur-md border border-outline-variant/30 text-tertiary font-data-label text-data-label rounded text-[10px]">{{ strtoupper($p->type) }}</span>
                                @if($isVerified)
                                    <span class="px-2 py-1 bg-surface/80 backdrop-blur-md border border-outline-variant/30 text-on-surface font-data-label text-data-label rounded text-[10px]">VERIFIED</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-5 flex flex-col gap-3">
                            <h3 class="font-headline-md text-[18px] text-on-surface leading-tight">{{ $p->title }}</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-2">
                                {{ $p->author?->name ? 'By '.$p->author->name : '—' }}
                            </p>
                            <div class="flex items-center justify-between mt-2 pt-4 border-t border-outline-variant/10">
                                <div class="flex items-center gap-2 text-on-surface-variant">
                                    @if(in_array('windows', $platforms, true))
                                        <span class="material-symbols-outlined text-[16px]">desktop_windows</span>
                                    @endif
                                    @if(in_array('mac', $platforms, true))
                                        <span class="material-symbols-outlined text-[16px]">desktop_mac</span>
                                    @endif
                                    @if(in_array('android', $platforms, true) || in_array('ios', $platforms, true))
                                        <span class="material-symbols-outlined text-[16px]">phone_iphone</span>
                                    @endif
                                </div>
                                <span class="font-data-value text-data-value text-on-surface font-medium">
                                    {{ $p->type === 'comic' ? 'Included' : 'Included w/ Pass' }}
                                </span>
                            </div>
                        </div>
                    </article>
                @empty
                    <!-- Fallback (no projects): retain design by showing nothing or a subtle note -->
                    <div class="col-span-full text-on-surface-variant text-sm">No items available yet. Check back soon.</div>
                @endforelse
            </div>
        </section>
    </main>
</body>
</html>