<!— new file —>
@php
    // Resolve impersonated tenant (if any) for studio routes
    $tm = app()->bound(\App\Support\Tenancy\TenantManager::class) ? app(\App\Support\Tenancy\TenantManager::class) : null;
    $impersonated = $tm?->tenant();
    $forgeLink = $impersonated ? route('studio.forge', ['studio' => $impersonated->slug]) : null;
    $composerLink = $impersonated ? route('studio.comics', ['studio' => $impersonated->slug]) : null;

    $activeClasses = 'flex items-center gap-4 bg-tertiary/10 text-tertiary border-r-2 border-tertiary py-3 px-6 scale-[0.98] transition-transform';
    $linkClasses = 'flex items-center gap-4 text-on-surface-variant py-3 px-6 hover:bg-surface-variant/30 hover:text-on-surface transition-all duration-150';
@endphp

<nav class="hidden md:flex flex-col bg-surface-container/40 backdrop-blur-2xl border-r border-outline-variant/10 fixed left-0 top-0 h-full w-64 pt-20 pb-8 z-50">
    <!-- Header Anchor -->
    <div class="px-6 mb-12">
        <h1 class="text-headline-md font-headline-md font-black text-on-surface tracking-tighter">PLAYVERSE OS</h1>
        <p class="text-data-label font-data-label text-on-surface-variant mt-1 text-[10px]">Tactical Command</p>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 flex flex-col gap-1 w-full">
        <a href="{{ route('admin.dashboard') }}"
           class="{{ request()->routeIs('admin.dashboard') ? $activeClasses : $linkClasses }}">
            <span class="material-symbols-outlined text-[20px]">dashboard</span>
            <span class="text-data-label font-data-label uppercase tracking-widest">Dashboard</span>
        </a>

        <a href="{{ route('admin.forge') }}"
           class="{{ request()->routeIs('admin.forge') ? $activeClasses : $linkClasses }}">
            <span class="material-symbols-outlined text-[20px]">token</span>
            <span class="text-data-label font-data-label uppercase tracking-widest">Project Forge</span>
        </a>

        <a href="{{ route('admin.comics') }}"
           class="{{ request()->routeIs('admin.comics') ? $activeClasses : $linkClasses }}">
            <span class="material-symbols-outlined text-[20px]">menu_book</span>
            <span class="text-data-label font-data-label uppercase tracking-widest">Comic Composer</span>
        </a>

        <a href="{{ route('admin.command') }}"
           class="{{ request()->routeIs('admin.command') ? $activeClasses : $linkClasses }}">
            <span class="material-symbols-outlined text-[20px]">security</span>
            <span class="text-data-label font-data-label uppercase tracking-widest">Command Desk</span>
        </a>

        <a href="{{ route('admin.review.index') }}"
           class="{{ request()->routeIs('admin.review.index') ? $activeClasses : $linkClasses }}">
            <span class="material-symbols-outlined text-[20px]" style="{{ request()->routeIs('admin.review.index') ? "font-variation-settings: 'FILL' 1;" : '' }}">fact_check</span>
            <span class="text-data-label font-data-label uppercase tracking-widest">Verifications</span>
        </a>

        <a href="{{ route('admin.economics') }}"
           class="{{ request()->routeIs('admin.economics') ? $activeClasses : $linkClasses }}">
            <span class="material-symbols-outlined text-[20px]">payments</span>
            <span class="text-data-label font-data-label uppercase tracking-widest">Economics</span>
        </a>
    </div>

    <!-- Footer Actions -->
    <div class="mt-auto flex flex-col gap-1 w-full border-t border-outline-variant/10 pt-4">
        <a href="mailto:support@playverse.local"
           class="flex items-center gap-4 text-on-surface-variant py-3 px-6 hover:bg-surface-variant/30 hover:text-on-surface transition-all duration-150">
            <span class="material-symbols-outlined text-[20px]">help</span>
            <span class="text-data-label font-data-label uppercase tracking-widest">Support</span>
        </a>
        {{-- CHANGED: Sign out goes to admin logout --}}
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit"
               class="w-full flex items-center gap-4 text-on-surface-variant py-3 px-6 hover:bg-surface-variant/30 hover:text-on-surface transition-all duration-150 text-left">
                <span class="material-symbols-outlined text-[20px]">logout</span>
                <span class="text-data-label font-data-label uppercase tracking-widest">Sign Out</span>
            </button>
        </form>
    </div>
</nav>
