<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    @livewireStyles
    @filamentStyles
    <title>{{ $title ?? 'Dashboard' }} | SLAU Cybersecurity & Innovations Club</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- User Permissions for JavaScript -->
    @php
        // Calculate permissions safely
        $canCreateEvents = false;
        $userId = null;
        $userName = null;
        $isAdmin = false;

        if (auth()->check() && $user = auth()->user()) {
            $canCreateEvents = $user->hasAnyRole(['admin', 'super-admin', 'member']);
            $userId = $user->id;
            $userName = e($user->name); // Escape for safety
            $isAdmin = $user->hasRole('admin');
        }
    @endphp

    <script>
        window.userPermissions = {
            canCreateEvents: {{ $canCreateEvents ? 'true' : 'false' }},
            userId: {{ $userId ?: 'null' }},
            userName: {{ $userName ? "'" . $userName . "'" : 'null' }},
            isAdmin: {{ $isAdmin ? 'true' : 'false' }}
        };
    </script>
    <!-- Theme Store -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                init() {
                    const savedTheme = localStorage.getItem('slau-theme');
                    const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' :
                        'light';
                    this.theme = savedTheme || systemTheme;
                    this.updateTheme();
                },
                theme: 'light',
                toggle() {
                    this.theme = this.theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('slau-theme', this.theme);
                    this.updateTheme();
                },
                updateTheme() {
                    const html = document.documentElement;
                    const body = document.body;
                    if (this.theme === 'dark') {
                        html.classList.add('dark');
                        html.dataset.theme = 'dark';
                        body.classList.add('dark', 'bg-gray-900');
                    } else {
                        html.classList.remove('dark');
                        html.dataset.theme = 'light';
                        body.classList.remove('dark', 'bg-gray-900');
                    }
                }
            });

            Alpine.store('sidebar', {
                // Initialize based on screen size
                isExpanded: window.innerWidth >= 1280, // true for desktop, false for mobile
                isMobileOpen: false,
                isHovered: false,

                toggleExpanded() {
                    this.isExpanded = !this.isExpanded;
                    // When toggling desktop sidebar, ensure mobile menu is closed
                    this.isMobileOpen = false;
                },

                toggleMobileOpen() {
                    this.isMobileOpen = !this.isMobileOpen;
                    // Don't modify isExpanded when toggling mobile menu
                },

                setMobileOpen(val) {
                    this.isMobileOpen = val;
                },

                setHovered(val) {
                    // Only allow hover effects on desktop when sidebar is collapsed
                    if (window.innerWidth >= 1280 && !this.isExpanded) {
                        this.isHovered = val;
                    }
                }
            });
        });
    </script>

    <!-- Apply dark mode immediately to prevent flash -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('slau-theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const theme = savedTheme || systemTheme;

            // Apply to <html> immediately (always available)
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                document.documentElement.dataset.theme = 'dark';
            } else {
                document.documentElement.classList.remove('dark');
                document.documentElement.dataset.theme = 'light';
            }

            // Apply body classes once <body> exists
            const applyBodyTheme = () => {
                if (!document.body) return;

                if (theme === 'dark') {
                    document.body.classList.add('dark', 'bg-gray-900');
                } else {
                    document.body.classList.remove('dark', 'bg-gray-900');
                }
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', applyBodyTheme);
            } else {
                applyBodyTheme();
            }
        })();

        // Re-apply theme during Livewire navigation (before content swaps to prevent flash)
        document.addEventListener('livewire:navigating', () => {
            const savedTheme = localStorage.getItem('slau-theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const theme = savedTheme || systemTheme;

            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                document.documentElement.dataset.theme = 'dark';
                document.body.classList.add('dark', 'bg-gray-900');
            } else {
                document.documentElement.classList.remove('dark');
                document.documentElement.dataset.theme = 'light';
                document.body.classList.remove('dark', 'bg-gray-900');
            }
        });

        // Also handle navigating for extra safety
        document.addEventListener('livewire:navigated', () => {
            const savedTheme = localStorage.getItem('slau-theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const theme = savedTheme || systemTheme;

            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                document.documentElement.dataset.theme = 'dark';
                document.body.classList.add('dark', 'bg-gray-900');
            } else {
                document.documentElement.classList.remove('dark');
                document.documentElement.dataset.theme = 'light';
                document.body.classList.remove('dark', 'bg-gray-900');
            }
        });
    </script>

</head>

<body
    x-data="{ 'loaded': true}"
    x-init="$store.sidebar.isExpanded = window.innerWidth >= 1280;
    const checkMobile = () => {
        if (window.innerWidth < 1280) {
            $store.sidebar.setMobileOpen(false);
            $store.sidebar.isExpanded = false;
        } else {
            $store.sidebar.isMobileOpen = false;
            $store.sidebar.isExpanded = true;
        }
    };
    window.addEventListener('resize', checkMobile);">

    {{-- preloader --}}
    <x-common.preloader/>
    {{-- preloader end --}}

    <div class="min-h-screen xl:flex">
        @include('layouts.backdrop')
        @include('layouts.sidebar')

        <div class="flex-1"
            :class="{
                'xl:ml-[290px]': $store.sidebar.isExpanded || $store.sidebar.isHovered,
                'xl:ml-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
                'ml-0': $store.sidebar.isMobileOpen
            }">
            <!-- app header start -->
            @include('layouts.app-header')
            <!-- app header end -->
            <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
                @yield('content')
                {{ $slot ?? '' }}
            </div>
        </div>

    </div>
    @livewireScripts
    @filamentScripts
    @livewire('notifications')
    @stack('scripts')
</body>


</html>
