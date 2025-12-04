@php
    $links = [
        ['label' => 'Home', 'route' => 'home'],
        ['label' => 'About', 'route' => 'about'],
        ['label' => 'Events', 'route' => 'events'],
        ['label' => 'Team', 'route' => 'team'],
        ['label' => 'Contact', 'route' => 'contact'],
    ];
@endphp

<nav class="border-b border-gray-800/60 bg-black/40 backdrop-blur sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-lg bg-emerald-400/10 border border-emerald-400/40 flex items-center justify-center shadow-[0_0_25px_rgba(16,185,129,0.6)]">
                    <span class="text-emerald-400 font-semibold text-lg">C</span>
                </div>
                <div>
                    <div class="text-sm font-semibold tracking-wide text-emerald-400 uppercase">SLAU</div>
                    <div class="text-xs text-gray-400">Cybersecurity & Innovations Club</div>
                </div>
            </div>

            <div class="hidden md:flex items-center gap-8 text-sm">
                @foreach ($links as $link)
                    @php
                        $isActive = request()->routeIs($link['route']);
                    @endphp
                    <a href="{{ route($link['route']) }}"
                       class="nav-link text-gray-300 hover:text-emerald-400 {{ $isActive ? 'active text-emerald-400' : '' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>

            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-sm text-gray-300 hover:text-emerald-400">Sign in</a>
                <a href="{{ route('register') }}" class="cyber-button text-sm px-4 py-2">Join the Club</a>
            </div>

            {{-- Mobile menu button --}}
            <div x-data="{ open: false }" class="md:hidden flex items-center">
                <button @click="open = !open" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-300 hover:text-emerald-400 hover:bg-gray-900/60 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <span class="sr-only">Open main menu</span>
                    <svg x-show="!open" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="open" x-cloak class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div x-show="open" x-transition.origin.top.right class="absolute top-16 inset-x-0 mx-4 rounded-xl border border-gray-800 bg-black/95 shadow-2xl">
                    <div class="px-4 pt-4 pb-3 space-y-3">
                        @foreach ($links as $link)
                            @php
                                $isActive = request()->routeIs($link['route']);
                            @endphp
                            <a href="{{ route($link['route']) }}"
                               class="block text-sm px-2 py-1 rounded-md {{ $isActive ? 'text-emerald-400' : 'text-gray-300 hover:text-emerald-400' }}">
                                {{ $link['label'] }}
                            </a>
                        @endforeach

                        <div class="pt-2 border-t border-gray-800 flex items-center justify-between">
                            <a href="{{ route('login') }}" class="text-sm text-gray-300 hover:text-emerald-400">Sign in</a>
                            <a href="{{ route('register') }}" class="cyber-button text-xs px-3 py-1.5">Join the Club</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
