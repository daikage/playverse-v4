{{-- new file --}}
@php
    $studio = request()->route('studio');
    $active = 'flex items-center gap-4 bg-tertiary/10 text-tertiary border-r-2 border-tertiary py-3 px-6 scale-[0.98] transition-transform';
    $link = 'flex items-center gap-4 text-on-surface-variant py-3 px-6 hover:bg-surface-variant/30 hover:text-on-surface transition-all duration-150';
@endphp

<nav class="hidden md:flex flex-col bg-surface-container/40 backdrop-blur-2xl border-r border-outline-variant/10 fixed left-0 top-0 h-full w-64 pt-20 pb-8 z-50">
    <div class="px-6 mb-12">
        <h1 class="text-headline-md font-headline-md font-black text-on-surface tracking-tighter">STUDIO</h1>
        <p class="text-data-label font-data-label text-on-surface-variant mt-1 text-[10px]">{{ $studio }}</p>
    </div>

    <div class="flex-1 flex flex-col gap-1 w-full">
        <a href="{{ route('studio.dashboard', ['studio' => $studio]) }}"
           class="{{ request()->routeIs('studio.dashboard') ? $active : $link }}">
            <span class="material-symbols-outlined text-[20px]">space_dashboard</span>
            <span class="text-data-label font-data-label uppercase tracking-widest">Dashboard</span>
        </a>

        <a href="{{ route('studio.forge', ['studio' => $studio]) }}"
           class="{{ request()->routeIs('studio.forge') ? $active : $link }}">
            <span class="material-symbols-outlined text-[20px]">token</span>
            <span class="text-data-label font-data-label uppercase tracking-widest">Project Forge</span>
        </a>

        <a href="{{ route('studio.comics', ['studio' => $studio]) }}"
           class="{{ request()->routeIs('studio.comics') ? $active : $link }}">
            <span class="material-symbols-outlined text-[20px]">menu_book</span>
            <span class="text-data-label font-data-label uppercase tracking-widest">Comic Composer</span>
        </a>

        <a href="{{ route('studio.analytics', ['studio' => $studio]) }}"
           class="{{ request()->routeIs('studio.analytics') ? $active : $link }}">
            <span class="material-symbols-outlined text-[20px]">monitoring</span>
            <span class="text-data-label font-data-label uppercase tracking-widest">Analytics</span>
        </a>
    </div>

    <div class="mt-auto flex flex-col gap-1 w-full border-t border-outline-variant/10 pt-4">
        <a href="/"
           class="flex items-center gap-4 text-on-surface-variant py-3 px-6 hover:bg-surface-variant/30 hover:text-on-surface transition-all duration-150">
            <span class="material-symbols-outlined text-[20px]">home</span>
            <span class="text-data-label font-data-label uppercase tracking-widest">Discovery</span>
        </a>
    </div>
</nav>
