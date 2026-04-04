@php
    $links = [
        ['label' => 'Home', 'route' => 'home', 'match' => ['home']],
        ['label' => 'About', 'route' => 'about', 'match' => ['about']],
        ['label' => 'Projects', 'route' => 'projects', 'match' => ['projects']],
        ['label' => 'Events', 'route' => 'events-out', 'match' => ['events-out']],
        ['label' => 'Team', 'route' => 'team', 'match' => ['team']],
        ['label' => 'Members', 'route' => 'members.public', 'match' => ['members.public', 'members.public.show']],
        ['label' => 'Contact', 'route' => 'contact', 'match' => ['contact']],
    ];
@endphp

<nav class="fixed inset-x-0 top-0 z-50">
    <div class="mx-auto max-w-6xl px-4 pt-4 sm:px-6 lg:px-8">
        <div class="site-header-shell rounded-md px-4 sm:px-6">
            <div class="flex h-16 items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center overflow-hidden rounded-md border border-white/70 bg-white shadow-[0_12px_30px_rgba(2,8,23,0.12)]">
                        <img src="{{ asset('images/club/logo1.jpg') }}" alt="SLAU Cybersecurity Club logo" class="h-9 w-9 object-contain">
                    </div>
                    <div>
                        <div class="text-sm font-semibold uppercase tracking-[0.2em]" style="color: var(--page-primary);">SLAU</div>
                        <div class="text-xs" style="color: var(--page-text-soft);">Cybersecurity &amp; Innovations Club</div>
                    </div>
                </a>

                <div class="hidden items-center gap-8 text-sm md:flex">
                    @foreach ($links as $link)
                        @php
                            $isActive = request()->routeIs(...$link['match']);
                        @endphp
                        <a href="{{ route($link['route']) }}" class="nav-link {{ $isActive ? 'active' : '' }}">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>

                <div class="hidden items-center gap-3 md:flex">
                    <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle theme">
                        <span class="theme-toggle-indicator" data-theme-icon>☾</span>
                        <span class="text-xs font-medium uppercase tracking-[0.18em]" data-theme-label>Dark</span>
                    </button>
                    <a href="{{ route('login') }}" class="text-sm" style="color: var(--page-text-soft);">Sign in</a>
                    <a href="{{ route('register') }}" class="cyber-button px-4 py-2 text-sm">Join the Club</a>
                </div>

                <div x-data="{ open: false }" class="flex items-center gap-2 md:hidden">
                    <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle theme">
                        <span class="theme-toggle-indicator" data-theme-icon>☾</span>
                    </button>
                    <button
                        @click="open = !open"
                        type="button"
                        class="inline-flex items-center justify-center rounded-sm p-2"
                        style="color: var(--page-text-soft);"
                    >
                        <span class="sr-only">Open main menu</span>
                        <svg x-show="!open" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="open" x-cloak class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div
                        x-show="open"
                        x-transition.origin.top.right
                        class="absolute inset-x-0 top-20 mx-4 rounded-md p-4 shadow-2xl"
                        style="border: 1px solid var(--page-border); background: var(--page-surface-elevated);"
                    >
                        <div class="space-y-3">
                            @foreach ($links as $link)
                                @php
                                    $isActive = request()->routeIs(...$link['match']);
                                @endphp
                                <a href="{{ route($link['route']) }}" class="block rounded-sm px-3 py-2 text-sm {{ $isActive ? 'font-semibold' : '' }}" style="color: var(--page-text-soft);">
                                    {{ $link['label'] }}
                                </a>
                            @endforeach

                            <div class="flex items-center justify-between border-t pt-3" style="border-color: var(--page-border);">
                                <a href="{{ route('login') }}" class="text-sm" style="color: var(--page-text-soft);">Sign in</a>
                                <a href="{{ route('register') }}" class="cyber-button px-3 py-2 text-xs">Join the Club</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
