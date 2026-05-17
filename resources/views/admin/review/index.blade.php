<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Command: Verification Queue</title>

    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-background h-screen w-full overflow-hidden flex antialiased selection:bg-tertiary/30 selection:text-tertiary">
    @php
        // Safely resolve the impersonated tenant (if any) for sidebar routing
        $tm = app()->bound(\App\Support\Tenancy\TenantManager::class) ? app(\App\Support\Tenancy\TenantManager::class) : null;
        $impersonated = $tm?->tenant();
        $studioLink = $impersonated ? route('studio.dashboard', ['studio' => $impersonated->slug]) : null;
    @endphp

    @include('admin.partials.sidebar')

    <!-- Main Canvas -->
    <main class="flex-1 flex flex-col md:ml-64 h-full relative overflow-hidden bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-surface-container/20 via-background to-background">
        <!-- Contextual Header -->
        <header class="w-full h-24 px-margin-mobile md:px-margin-desktop flex items-center justify-between border-b border-outline-variant/10 flex-shrink-0 z-10 bg-background/80 backdrop-blur-md">
            <div class="flex flex-col gap-1">
                <div class="flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-tertiary animate-pulse shadow-[0_0_8px_rgba(208,188,255,0.6)]"></span>
                    <h2 class="text-headline-md font-headline-md text-on-surface tracking-tight">Queue.Active</h2>
                </div>
                <p class="text-data-label font-data-label text-on-surface-variant opacity-60">
                    SYSTEM.VERIFICATION // {{ number_format($pending->count()) }} PENDING
                </p>
            </div>

            <!-- Impersonation Mode Toggle (visual only) -->
            <div class="flex items-center gap-4 p-3 rounded-lg border border-outline-variant/20 bg-surface-container-low/50">
                <span class="material-symbols-outlined text-on-surface-variant text-[18px]">admin_panel_settings</span>
                <span class="text-data-label font-data-label text-on-surface hidden md:block">Impersonation Mode</span>
                <button class="w-10 h-5 bg-surface-variant rounded-full relative transition-colors hover:bg-surface-container-highest cursor-pointer border border-outline-variant/30 flex items-center px-[2px]">
                    <div class="w-3.5 h-3.5 bg-outline rounded-full transition-all"></div>
                </button>
            </div>
        </header>

        <!-- Tinder-style Verification Area -->
        <div class="flex-1 overflow-y-auto flex flex-col items-center justify-start p-gutter relative w-full">
            <!-- Background Decorative Elements -->
            <div class="absolute inset-0 pointer-events-none overflow-hidden flex items-center justify-center opacity-10">
                <div class="w-[800px] h-[800px] rounded-full border border-tertiary/20"></div>
                <div class="w-[600px] h-[600px] rounded-full border border-tertiary/10 absolute"></div>
            </div>

            <!-- Card Container -->
            <div class="w-full max-w-[520px] flex flex-col items-center z-10 relative mt-10">
                @php($current = $pending->first())
                @if($current)
                    <!-- Preview Card (current pending studio) -->
                    <div class="w-full aspect-[3/4] bg-surface-container-lowest rounded-xl border border-tertiary/30 shadow-[0_0_20px_rgba(208,188,255,0.1)] overflow-hidden flex flex-col relative group transition-transform duration-300 transform hover:-translate-y-2">
                        <!-- Image Area (70%) -->
                        <div class="h-[70%] w-full relative bg-surface-variant overflow-hidden">
                            @if($current->logo_path)
                                <img class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity duration-500 group-hover:scale-105"
                                     src="{{ asset('storage/'.$current->logo_path) }}" alt="{{ $current->name }} Logo" />
                            @else
                                <img class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity duration-500 group-hover:scale-105"
                                     src="https://lh3.googleusercontent.com/aida-public/AB6AXuAHAxsZPRncX9F7bF6gdXheW6SIIhMjdiHzZfh2Cy4GWTChMHcZhSP-3af8ftusKbdP5fQSkunEYH7En32Sa1rHIgfJqbkdzC85Xv0e2J5jDnVN8llqy5JRoGEPfhilYFKd-An-5V11uoif6IE__wPB2qc9580LAKYggJNy5o_3qvnoL4f2v9Q15ZzJedykj0kjIR1b5kt_nta3LL8w2VSNxTpu3rpx6HlPbXhhQhf5bQ6jVCM6pXNirVk15aF9TFDmz3dKHTiJMhN-"
                                     alt="Studio Preview Placeholder" />
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-surface-container-lowest via-transparent to-transparent opacity-90"></div>

                            <!-- Status Chip -->
                            <div class="absolute top-4 left-4 px-3 py-1.5 bg-surface-container/90 backdrop-blur-md border border-outline-variant/30 rounded-full flex items-center gap-2">
                                <span class="text-data-label font-data-label text-tertiary uppercase">PENDING</span>
                            </div>
                        </div>

                        <!-- Metadata Area (30%) -->
                        <div class="h-[30%] w-full p-6 flex flex-col justify-between bg-surface-container-lowest relative z-10">
                            <div class="flex justify-between items-start w-full">
                                <div>
                                    <h3 class="text-display-lg-mobile font-display-lg-mobile text-on-surface leading-none mb-1 tracking-tight group-hover:text-tertiary transition-colors">
                                        {{ $current->name }}
                                    </h3>
                                    <div class="flex items-center gap-2 text-on-surface-variant mt-2">
                                        <span class="material-symbols-outlined text-[14px]">link</span>
                                        <span class="text-body-sm font-body-sm">Slug: <span class="text-on-surface">{{ $current->slug }}</span></span>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-data-value font-data-value text-on-surface bg-surface-container py-1 px-2 rounded text-xs border border-outline-variant/20">
                                        Studio UUID
                                    </span>
                                    <span class="text-data-label font-data-label text-on-surface-variant text-[10px] mt-1">{{ $current->studio_uuid }}</span>
                                </div>
                            </div>

                            <!-- Links / dossier -->
                            <div class="grid grid-cols-3 gap-4 border-t border-outline-variant/10 pt-4 w-full">
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-data-label font-data-label text-on-surface-variant opacity-50 text-[10px]">GITHUB</span>
                                    <span class="text-data-value font-data-value text-on-surface truncate">
                                        @if(!empty($current->links['github'] ?? null))
                                            <a class="hover:text-tertiary" href="{{ $current->links['github'] }}" target="_blank" rel="noreferrer">Open</a>
                                        @else
                                            —
                                        @endif
                                    </span>
                                </div>
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-data-label font-data-label text-on-surface-variant opacity-50 text-[10px]">ARTSTATION</span>
                                    <span class="text-data-value font-data-value text-on-surface truncate">
                                        @if(!empty($current->links['artstation'] ?? null))
                                            <a class="hover:text-tertiary" href="{{ $current->links['artstation'] }}" target="_blank" rel="noreferrer">Open</a>
                                        @else
                                            —
                                        @endif
                                    </span>
                                </div>
                                <div class="flex flex-col gap-0.5 items-end">
                                    <span class="text-data-label font-data-label text-on-surface-variant opacity-50 text-[10px]">ITCH.IO</span>
                                    <span class="text-data-value font-data-value text-on-surface truncate">
                                        @if(!empty($current->links['itch'] ?? null))
                                            <a class="hover:text-tertiary" href="{{ $current->links['itch'] }}" target="_blank" rel="noreferrer">Open</a>
                                        @else
                                            —
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tactical Action Buttons -->
                    <div class="flex items-center justify-center gap-8 mt-10 w-full px-4">
                        <!-- Reject -->
                        <form action="{{ route('admin.review.suspend', $current) }}" method="POST">
                            @csrf
                            <button class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-surface-container border border-outline-variant/30 flex items-center justify-center text-on-surface-variant hover:bg-surface-variant hover:text-error hover:border-error/40 transition-all duration-150 group shadow-lg" title="Suspend">
                                <span class="material-symbols-outlined text-3xl md:text-4xl group-hover:scale-110 transition-transform">close</span>
                            </button>
                        </form>

                        <!-- Approve -->
                        <form action="{{ route('admin.review.approve', $current) }}" method="POST">
                            @csrf
                            <button class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-surface border border-tertiary/50 flex items-center justify-center text-tertiary shadow-[0_0_20px_rgba(208,188,255,0.15)] hover:bg-tertiary/10 hover:shadow-[0_0_30px_rgba(208,188,255,0.3)] transition-all duration-150 group relative overflow-hidden" title="Approve">
                                <div class="absolute inset-0 bg-tertiary/5 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <span class="material-symbols-outlined text-4xl md:text-5xl group-hover:scale-110 transition-transform relative z-10" style="font-variation-settings: 'FILL' 1;">check</span>
                            </button>
                        </form>
                    </div>
                @else
                    <!-- No pending studios -->
                    <div class="w-full max-w-xl mt-16 text-center border border-outline-variant/20 rounded-xl p-8 bg-surface-container-low/40 backdrop-blur-md">
                        <div class="text-tertiary mb-2">Queue Empty</div>
                        <p class="text-on-surface-variant">No pending studios. You can review recently approved below.</p>
                    </div>
                @endif
            </div>

            <!-- Recently Approved -->
            <section class="w-full max-w-5xl z-10 relative mt-16 px-margin-mobile md:px-margin-desktop">
                <h2 class="text-headline-md font-headline-md text-on-surface tracking-tight mb-4">Recently Approved</h2>
                <div class="grid grid-cols-1 gap-3">
                    @forelse($approved as $a)
                        <div class="border border-outline-variant/20 rounded-md p-4 flex items-center justify-between bg-surface-container/40">
                            <div class="text-sm">
                                <div class="font-medium text-on-surface">{{ $a->name }}</div>
                                <div class="text-on-surface-variant">Key: <span class="text-on-surface">{{ $a->playverse_key }}</span></div>
                            </div>
                            <a href="{{ route('admin.impersonate.start', $a) }}" class="px-3 py-1 text-sm bg-white/5 border border-gray-700 rounded hover:bg-white/10">Impersonate</a>
                        </div>
                    @empty
                        <div class="text-on-surface-variant text-sm">No approved studios yet.</div>
                    @endforelse
                </div>
            </section>

            <div class="h-10"></div>
        </div>
    </main>
</body>
</html>
