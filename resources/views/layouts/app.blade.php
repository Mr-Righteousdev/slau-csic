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
        $authUser = null;
        $profilePhotoUrl = null;
        $topNavItems = [];
        $sidebarSections = [];

        if (auth()->check() && $user = auth()->user()) {
            $authUser = $user;
            $canCreateEvents = $user->hasAnyRole(['admin', 'super-admin', 'member']);
            $userId = $user->id;
            $userName = e($user->name); // Escape for safety
            $isAdmin = $user->hasRole('admin');
            $profilePhotoUrl = $user->profile_photo
                ? \Illuminate\Support\Facades\Storage::url($user->profile_photo)
                : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=FFFFFF&background=0f766e';

            $topNavItems = [
                ['label' => 'Club Portal', 'route' => 'dashboard', 'active' => request()->routeIs('dashboard', 'portal.*')],
                ['label' => 'Profile', 'route' => 'user-profile', 'active' => request()->routeIs('user-profile', 'settings.*')],
            ];

            if ($user->hasAnyRole(['admin', 'super-admin', 'president', 'head_discipline'])) {
                $topNavItems[] = ['label' => 'Members', 'route' => 'members.directory', 'active' => request()->routeIs('members.*')];
            }

            if ($user->hasAnyRole(['admin', 'super-admin', 'president'])) {
                $topNavItems[] = ['label' => 'Events', 'route' => 'events-out', 'active' => request()->routeIs('events-out', 'events.*', 'my-events')];
            }

            $sidebarSections[] = [
                'title' => 'Home',
                'items' => [
                    ['label' => 'Club Portal', 'route' => 'dashboard', 'active' => request()->routeIs('dashboard', 'portal.*')],
                    ['label' => 'Profile', 'route' => 'user-profile', 'active' => request()->routeIs('user-profile', 'settings.*')],
                ],
            ];

            if ($user->hasAnyRole(['admin', 'super-admin', 'president', 'head_discipline'])) {
                $sidebarSections[0]['items'][] = ['label' => 'Members', 'route' => 'members.directory', 'active' => request()->routeIs('members.*')];
            }

            if ($user->hasAnyRole(['admin', 'super-admin', 'president'])) {
                $sidebarSections[0]['items'][] = ['label' => 'Events', 'route' => 'events-out', 'active' => request()->routeIs('events-out', 'events.*')];
                $sidebarSections[0]['items'][] = ['label' => 'My Events', 'route' => 'my-events', 'active' => request()->routeIs('my-events')];
            }

            if ($user->hasAnyRole(['admin', 'super-admin', 'president', 'secretary'])) {
                $sidebarSections[] = [
                    'title' => 'Portal',
                    'items' => [
                        ['label' => 'Competitions', 'route' => 'portal.competitions', 'active' => request()->routeIs('portal.competitions')],
                        ['label' => 'Cabinet Voting', 'route' => 'portal.voting', 'active' => request()->routeIs('portal.voting')],
                        ['label' => 'CTF Arena', 'route' => 'portal.ctf', 'active' => request()->routeIs('portal.ctf')],
                        ['label' => 'Online Classes', 'route' => 'portal.classes', 'active' => request()->routeIs('portal.classes')],
                    ],
                ];
            }

            $teachingItems = [];

            if ($user->can('content.view')) {
                $teachingItems[] = ['label' => 'Course Materials', 'route' => 'teacher.content', 'active' => request()->routeIs('teacher.content')];
            }

            if ($user->can('create teaching session')) {
                $teachingItems[] = ['label' => 'Sessions Attendance', 'route' => 'admin.teaching-sessions', 'active' => request()->routeIs('admin.teaching-sessions*')];
            }

            if ($user->can('teacher.events.view')) {
                $teachingItems[] = ['label' => 'Teaching Events', 'route' => 'teacher.events', 'active' => request()->routeIs('teacher.events')];
            }

            if ($user->can('teacher.reports.view')) {
                $teachingItems[] = ['label' => 'Analytics', 'route' => 'teacher.analytics', 'active' => request()->routeIs('teacher.analytics')];
            }

            if ($user->can('portfolio.view')) {
                $teachingItems[] = ['label' => 'Portfolios', 'route' => 'teacher.portfolios', 'active' => request()->routeIs('teacher.portfolios')];
            }

            if ($teachingItems !== []) {
                $sidebarSections[] = ['title' => 'Teaching', 'items' => $teachingItems];
            }

            if ($user->hasAnyRole(['treasurer', 'president', 'super-admin'])) {
                $sidebarSections[] = [
                    'title' => 'Finance',
                    'items' => [
                        ['label' => 'Treasurer Dashboard', 'route' => 'admin.treasurer-dashboard', 'active' => request()->routeIs('admin.treasurer-dashboard')],
                        ['label' => 'Transactions', 'route' => 'admin.transactions', 'active' => request()->routeIs('admin.transactions')],
                        ['label' => 'Budget Categories', 'route' => 'admin.budget-categories', 'active' => request()->routeIs('admin.budget-categories')],
                        ['label' => 'Fines Management', 'route' => 'admin.fines', 'active' => request()->routeIs('admin.fines')],
                        ['label' => 'Fine Types', 'route' => 'admin.fine-types', 'active' => request()->routeIs('admin.fine-types')],
                        ['label' => 'Financial Reports', 'route' => 'admin.financial-reports', 'active' => request()->routeIs('admin.financial-reports')],
                    ],
                ];
            }

            if ($user->hasAnyRole(['admin', 'super-admin'])) {
                $sidebarSections[] = [
                    'title' => 'Admin',
                    'items' => [
                        ['label' => 'Meetings', 'route' => 'admin.meetings', 'active' => request()->routeIs('admin.meetings', 'admin.meeting.details')],
                        ['label' => 'User Management', 'route' => 'admin.users', 'active' => request()->routeIs('admin.users')],
                        ['label' => 'Pending Approvals', 'route' => 'admin.pending-members', 'active' => request()->routeIs('admin.pending-members')],
                        ['label' => 'Roles & Permissions', 'route' => 'admin.roles-permissions', 'active' => request()->routeIs('admin.roles-permissions')],
                    ],
                ];
            }
        }
    @endphp

    <script>
        (function() {
            const html = document.documentElement;
            const storedTheme = localStorage.getItem('hs_theme');
            const resolvedTheme = storedTheme === 'auto'
                ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
                : (storedTheme === 'dark' ? 'dark' : 'light');

            html.classList.remove('light', 'dark', 'default', 'auto');

            if (storedTheme === 'auto') {
                html.classList.add('auto', resolvedTheme);
            } else {
                html.classList.add(resolvedTheme);
            }
        })();
    </script>





</head>

<body class="bg-background text-foreground">

    <!-- ========== HEADER ========== -->
<header class="fixed top-0 inset-x-0 z-70 flex w-full flex-wrap border-b border-gray-200 bg-white text-sm dark:border-gray-800 dark:bg-gray-950 md:flex-nowrap md:justify-start">
  <nav class="mx-auto flex w-full basis-full items-center px-4 py-2.5 sm:px-5.5">
    <div class="w-full flex items-center gap-x-1.5">
      <ul class="flex items-center gap-1.5">
        <li class="relative inline-flex items-center gap-1 pe-1.5 last:pe-0 last:after:hidden after:absolute after:end-0 after:top-1/2 after:inline-block after:h-3.5 after:w-px after:-translate-y-1/2 after:rotate-12 after:rounded-full after:bg-gray-200 dark:after:bg-gray-700">
          <a class="shrink-0 inline-flex justify-center items-center bg-primary size-8 rounded-md text-xl inline-block font-semibold focus:outline-hidden focus:opacity-80" href="{{ route('dashboard') }}" wire:navigate aria-label="SLAU Club Portal">
          	<svg class="shrink-0 size-5" width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
          		<path fill-rule="evenodd" clip-rule="evenodd" d="M18.0835 3.23358C9.88316 3.23358 3.23548 9.8771 3.23548 18.0723V35.5832H0.583496V18.0723C0.583496 8.41337 8.41851 0.583252 18.0835 0.583252C27.7485 0.583252 35.5835 8.41337 35.5835 18.0723C35.5835 27.7312 27.7485 35.5614 18.0835 35.5614H16.7357V32.911H18.0835C26.2838 32.911 32.9315 26.2675 32.9315 18.0723C32.9315 9.8771 26.2838 3.23358 18.0835 3.23358Z" class="fill-primary-foreground" fill="currentColor"/>
          		<path fill-rule="evenodd" clip-rule="evenodd" d="M18.0833 8.62162C12.8852 8.62162 8.62666 12.9245 8.62666 18.2879V35.5833H5.97468V18.2879C5.97468 11.5105 11.3713 5.97129 18.0833 5.97129C24.7954 5.97129 30.192 11.5105 30.192 18.2879C30.192 25.0653 24.7954 30.6045 18.0833 30.6045H16.7355V27.9542H18.0833C23.2815 27.9542 27.54 23.6513 27.54 18.2879C27.54 12.9245 23.2815 8.62162 18.0833 8.62162Z" class="fill-primary-foreground" fill="currentColor"/>
          		<path d="M24.8225 18.1012C24.8225 21.8208 21.8053 24.8361 18.0833 24.8361C14.3614 24.8361 11.3442 21.8208 11.3442 18.1012C11.3442 14.3815 14.3614 11.3662 18.0833 11.3662C21.8053 11.3662 24.8225 14.3815 24.8225 18.1012Z" class="fill-primary-foreground" fill="currentColor"/>
          	</svg>
          </a>

          <div class="hidden sm:block">

          </div>

          <!-- Sidebar Toggle -->
          <button type="button" class="inline-flex size-7.5 items-center gap-x-1 rounded-md border border-transparent p-1.5 text-xs text-gray-600 hover:bg-gray-100 disabled:pointer-events-none disabled:opacity-50 dark:text-gray-300 dark:hover:bg-white/5" aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-pro-sidebar" data-hs-overlay="#hs-pro-sidebar">
            <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M15 3v18"/><path d="m10 15-3-3 3-3"/></svg>
            <span class="sr-only">Sidebar Toggle</span>
          </button>
          <!-- End Sidebar Toggle -->
        </li>



        <li class="relative inline-flex items-center pe-1.5 last:pe-0 last:after:hidden after:absolute after:end-0 after:top-1/2 after:inline-block after:h-3.5 after:w-px after:-translate-y-1/2 after:rotate-12 after:rounded-full after:bg-gray-200 dark:after:bg-gray-700">
          <!-- Teams Dropdown -->
          <div class="inline-flex justify-center w-full">
            <div class="hs-dropdown relative [--strategy:absolute] [--placement:bottom-left] inline-flex w-full">
              <!-- Teams Button -->
              <button id="hs-pro-antmd" type="button" class="flex min-h-8 items-center gap-x-1 rounded-lg px-2 py-1 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-hidden dark:text-gray-200 dark:hover:bg-white/5" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                SLAU - Cybersecurity and Innovations Club
                {{-- <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7 15 5 5 5-5"/><path d="m7 9 5-5 5 5"/></svg> --}}
              </button>
              <!-- End Teams Button -->

              <!-- Dropdown -->
              {{-- <div class="hs-dropdown-menu hs-dropdown-open:opacity-100 w-65 transition-[opacity,margin] duration opacity-0 hidden z-20 bg-dropdown border border-dropdown-line rounded-xl shadow-xl" role="menu" aria-orientation="vertical" aria-labelledby="hs-pro-antmd">
                <div class="p-1">
                  <span class="block pt-2 pb-2 ps-2.5 text-sm text-muted-foreground-1">
                    Teams (1)
                  </span>

                  <div class="flex flex-col gap-y-1">
                    <!-- Item -->
                    <label for="hs-pro-antmdi1" class="py-2 px-2.5 group flex justify-start items-center gap-x-3 rounded-lg cursor-pointer text-[13px] text-dropdown-item-foreground hover:bg-dropdown-item-hover focus:outline-hidden focus:bg-dropdown-item-focus">
                      <input type="radio" class="hidden" id="hs-pro-antmdi1" name="hs-pro-antmdi" checked>
                      <svg class="shrink-0 size-4 opacity-0 group-has-checked:opacity-100" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                      <span class="grow">
                        <span class="block text-sm font-medium text-foreground">
                          Marketing
                        </span>
                      </span>
                    </label>
                    <!-- End Item -->
                  </div>
                </div> --}}

                {{-- <div class="p-1 border-t border-dropdown-divider">
                  <button type="button" class="w-full flex items-center gap-x-3 py-2 px-2.5 rounded-lg text-sm text-dropdown-item-foreground hover:bg-dropdown-item-hover disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-dropdown-item-focus">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
                    Add team
                  </button>
                </div>
              </div> --}}
              <!-- End Dropdown -->
            </div>
          </div>
          <!-- End Teams Dropdown -->
        </li>
      </ul>

      <ul class="flex flex-row items-center gap-x-3 ms-auto">

        <li class="relative hidden items-center gap-1.5 pe-3 last:pe-0 last:after:hidden after:absolute after:end-0 after:top-1/2 after:inline-block after:h-3.5 after:w-px after:-translate-y-1/2 after:rotate-12 after:rounded-full after:bg-gray-200 dark:after:bg-gray-700 lg:inline-flex">

            <button type="button" class="rounded-full font-medium text-gray-700 hover:bg-gray-100 focus:outline-hidden dark:hidden dark:text-gray-200 dark:hover:bg-white/5" data-hs-theme-click-value="dark">
  <span class="group inline-flex size-9 shrink-0 items-center justify-center">
    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
  </span>
</button>
<button type="button" class="hidden rounded-full font-medium text-gray-700 hover:bg-gray-100 focus:outline-hidden dark:block dark:text-gray-200 dark:hover:bg-white/5" data-hs-theme-click-value="default">
  <span class="group inline-flex size-9 shrink-0 items-center justify-center">
    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
  </span>
</button>

          @foreach ($topNavItems as $navItem)
            <a
              class="{{ $navItem['active'] ? 'bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400' : 'text-gray-700 dark:text-gray-300' }} flex items-center gap-x-1.5 rounded-lg px-2.5 py-1.5 text-sm hover:bg-gray-100 focus:outline-hidden dark:hover:bg-white/5"
              href="{{ route($navItem['route']) }}"
              wire:navigate
            >
              {{ $navItem['label'] }}
            </a>
          @endforeach
        </li>

        <li class="relative inline-flex items-center gap-1.5 pe-3 last:pe-0 last:after:hidden after:absolute after:end-0 after:top-1/2 after:inline-block after:h-3.5 after:w-px after:-translate-y-1/2 after:rotate-12 after:rounded-full after:bg-gray-200 dark:after:bg-gray-700">
          <button type="button" class="relative hidden size-8 items-center justify-center gap-x-1.5 rounded-full text-sm text-gray-600 hover:bg-gray-100 focus:outline-hidden dark:text-gray-300 dark:hover:bg-white/5 lg:flex">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 7v14"/><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/></svg>
            <span class="sr-only">Knowledge Base</span>
          </button>

          <div class="h-8">
            <!-- Account Dropdown -->
            <div class="hs-dropdown inline-flex [--strategy:absolute] [--auto-close:inside] [--placement:bottom-right] relative text-start">
              <button id="hs-dnad" type="button" class="inline-flex shrink-0 items-center gap-x-3 rounded-full p-0.5 text-start text-gray-700 hover:bg-gray-100 focus:outline-hidden dark:text-gray-200 dark:hover:bg-white/5" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                <img class="shrink-0 size-7 rounded-full object-cover" src="{{ $profilePhotoUrl }}" alt="{{ $authUser?->name ?? 'User avatar' }}" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($authUser?->name ?? 'User') }}&color=FFFFFF&background=0f766e';">
              </button>

              <!-- Account Dropdown -->
              <div class="hs-dropdown-menu hs-dropdown-open:opacity-100 z-20 hidden w-60 rounded-xl border border-gray-200 bg-white opacity-0 shadow-xl transition-[opacity,margin] duration dark:border-gray-800 dark:bg-gray-dark" role="menu" aria-orientation="vertical" aria-labelledby="hs-dnad">
                <div class="py-2 px-3.5">
                  <span class="font-medium text-gray-900 dark:text-white">
                    {{ $authUser?->name }}
                  </span>
                  <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $authUser?->email }}
                  </p>
                  <div class="mt-1.5">
                    <a class="flex items-center justify-center gap-x-1.5 rounded-lg bg-brand-500 px-2.5 py-2 text-[13px] font-medium text-white hover:bg-brand-600 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50" href="{{ route('user-profile') }}" wire:navigate>
                      View profile
                    </a>
                  </div>
                </div>
                <div class="border-t border-gray-200 px-4 py-2 dark:border-gray-800">
                  <!-- Switch/Toggle -->
                  <div class="flex flex-wrap justify-between items-center gap-2">
                    <span class="flex-1 cursor-pointer text-sm text-gray-900 dark:text-white">Theme</span>
                    <div class="p-0.5 inline-flex cursor-pointer rounded-full border border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800">
                      <button type="button" class="flex size-7 items-center justify-center rounded-full bg-white text-gray-700 shadow-sm hs-auto-mode-active:bg-transparent hs-auto-mode-active:shadow-none hs-dark-mode-active:bg-transparent hs-dark-mode-active:shadow-none dark:bg-gray-950 dark:text-gray-200" data-hs-theme-click-value="default">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 3v1"/><path d="M12 20v1"/><path d="M3 12h1"/><path d="M20 12h1"/><path d="m18.364 5.636-.707.707"/><path d="m6.343 17.657-.707.707"/><path d="m5.636 5.636.707.707"/><path d="m17.657 17.657.707.707"/></svg>
                        <span class="sr-only">Default (Light)</span>
                      </button>
                      <button type="button" class="flex size-7 items-center justify-center rounded-full text-gray-700 hs-dark-mode-active:bg-gray-900 hs-dark-mode-active:text-white hs-dark-mode-active:shadow-sm dark:text-gray-200 dark:hs-dark-mode-active:bg-white dark:hs-dark-mode-active:text-gray-900" data-hs-theme-click-value="dark">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                        <span class="sr-only">Dark</span>
                      </button>
                      <button type="button" class="flex size-7 items-center justify-center rounded-full text-gray-700 hs-auto-light-mode-active:bg-white hs-auto-mode-active:shadow-sm dark:text-gray-200 dark:hs-auto-light-mode-active:bg-gray-900" data-hs-theme-click-value="auto">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/></svg>
                        <span class="sr-only">Auto</span>
                      </button>
                    </div>
                  </div>
                  <!-- End Switch/Toggle -->
                </div>
                <div class="border-t border-gray-200 p-1 dark:border-gray-800">
                  <a class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 disabled:pointer-events-none disabled:opacity-50 focus:outline-hidden dark:text-gray-300 dark:hover:bg-white/5" href="{{ route('user-profile') }}" wire:navigate>
                    <svg class="shrink-0 mt-0.5 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Profile
                  </a>
                  <a class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 disabled:pointer-events-none disabled:opacity-50 focus:outline-hidden dark:text-gray-300 dark:hover:bg-white/5" href="{{ route('settings.profile.edit') }}" wire:navigate>
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" ><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                    Settings
                  </a>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-x-3 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 disabled:pointer-events-none disabled:opacity-50 focus:outline-hidden dark:text-gray-300 dark:hover:bg-white/5">
                      <svg class="shrink-0 mt-0.5 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg>
                      Log out
                    </button>
                  </form>
                </div>
              </div>
              <!-- End Account Dropdown -->
            </div>
            <!-- End Account Dropdown -->
          </div>
        </li>
      </ul>
    </div>
  </nav>
</header>
<!-- ========== END HEADER ========== -->

<!-- ========== MAIN SIDEBAR ========== -->
<!-- Sidebar -->
<div id="hs-pro-sidebar" class="hs-overlay [--body-scroll:true] lg:[--overlay-backdrop:false] [--is-layout-affect:true] [--opened:lg] [--auto-close:lg]
hs-overlay-open:translate-x-0 lg:hs-overlay-layout-open:translate-x-0
-translate-x-full
w-60
hidden
fixed top-0 bottom-0 z-60 start-0 lg:top-13
bg-white dark:bg-gray-950
lg:block lg:-translate-x-full lg:end-auto lg:bottom-0" role="dialog" tabindex="-1" aria-label="Sidebar">
  <div class="relative flex h-full max-h-full flex-col">
    <!-- Body -->
    <nav class="p-3 py-5 size-full flex flex-col overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-none [&::-webkit-scrollbar-track]:bg-scrollbar-track [&::-webkit-scrollbar-thumb]:bg-scrollbar-thumb">
      <div class="lg:hidden mb-2 flex items-center justify-between">
        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-x-1.5 py-2 px-2.5 font-medium text-xs bg-secondary text-secondary-foreground rounded-lg focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none">
          <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.73 1.73 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.73 1.73 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.73 1.73 0 0 0 3.407 2.31zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z"/></svg>
          Club Portal
        </a>

        <!-- Sidebar Toggle -->
        <button type="button" class="inline-flex size-7.5 items-center gap-x-1 rounded-md p-1.5 text-xs text-gray-500 disabled:pointer-events-none disabled:opacity-50 focus:outline-hidden dark:text-gray-400" aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-pro-sidebar" data-hs-overlay="#hs-pro-sidebar">
          <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
          <span class="sr-only">Sidebar Toggle</span>
        </button>
        <!-- End Sidebar Toggle -->
      </div>

      <button type="button" class="inline-flex w-full items-center gap-x-2 rounded-lg border border-gray-200 bg-gray-50 p-1.5 ps-2.5 text-sm text-gray-500 shadow-xs focus:outline-hidden disabled:pointer-events-none disabled:opacity-50 dark:border-gray-800 dark:bg-gray-800 dark:text-gray-400" aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-pro-cmsssm" data-hs-overlay="#hs-pro-cmsssm">
        Search
        <span class="ms-auto flex items-center gap-x-1 py-px px-1.5 border border-line-2 rounded-md">
          <svg class="shrink-0 size-2.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 6v12a3 3 0 1 0 3-3H6a3 3 0 1 0 3 3V6a3 3 0 1 0-3 3h12a3 3 0 1 0-3-3"></path></svg>
          <span class="text-[11px] uppercase">k</span>
        </span>
      </button>

      @foreach ($sidebarSections as $section)
        <div class="mt-3 flex flex-col border-t border-gray-200 pt-3 first:mt-0 first:border-t-0 first:pt-0 dark:border-gray-800">
          <span class="mb-2 block ps-2.5 text-xs font-medium uppercase text-gray-500 dark:text-gray-400">
            {{ $section['title'] }}
          </span>

          <ul class="flex flex-col gap-y-1">
            @foreach ($section['items'] as $item)
              <li>
                <a
                  class="{{ $item['active'] ? 'bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400' : 'text-gray-700 dark:text-gray-300' }} flex w-full items-center gap-x-2 rounded-lg px-2.5 py-2 text-sm hover:bg-gray-100 focus:outline-hidden dark:hover:bg-white/5"
                  href="{{ route($item['route']) }}"
                  wire:navigate
                >
                  {{ $item['label'] }}
                </a>
              </li>
            @endforeach
          </ul>
        </div>
      @endforeach
    </nav>
    <!-- End Body -->

    <!-- Footer -->
    <footer class="mt-auto p-3 flex flex-col">
      <!-- List -->
      <ul class="flex flex-col gap-y-1">
        <li>
          <a class="flex w-full items-center gap-x-2 rounded-lg px-2.5 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-hidden dark:text-gray-300 dark:hover:bg-white/5" href="{{ route('events-out') }}" wire:navigate>
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>
            Events
          </a>
        </li>
        <li>
          <a class="flex w-full items-center gap-x-2 rounded-lg px-2.5 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-hidden dark:text-gray-300 dark:hover:bg-white/5" href="{{ route('contact') }}" wire:navigate>
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
            Contact
          </a>
        </li>
        <li class="lg:hidden">
          <a class="flex w-full items-center gap-x-2 rounded-lg px-2.5 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-hidden dark:text-gray-300 dark:hover:bg-white/5" href="{{ route('about') }}" wire:navigate>
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 7v14"/><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/></svg>
            About Club
          </a>
        </li>
      </ul>
      <!-- End List -->
    </footer>
    <!-- End Footer -->
  </div>
</div>
<!-- End Sidebar -->
<!-- ========== END MAIN SIDEBAR ========== -->

<!-- ========== MAIN CONTENT ========== -->
<main class="bg-gray-50 px-3 pb-3 pt-13 dark:bg-gray-950 lg:fixed lg:inset-0 lg:hs-overlay-layout-open:ps-60">
  <div class="flex h-[calc(100dvh-62px)] flex-col overflow-hidden rounded-lg border border-gray-200 bg-white shadow-xs dark:border-gray-800 dark:bg-gray-dark lg:h-full">
    <!-- Body -->
    <div class="flex-1 flex flex-col overflow-y-auto [&::-webkit-scrollbar]:w-0">
      <div class="flex-1 flex flex-col lg:flex-row">
        <div class="flex-1 min-w-0 flex flex-col border-e border-line-2 p-4">
          @yield('content')
            {{ $slot ?? '' }}
        </div>
        <!-- End Col -->


        <!-- End Col -->
      </div>
    </div>
    <!-- End Body -->
  </div>
</main>
<!-- ========== END MAIN CONTENT ========== -->
    @livewireScripts
    @filamentScripts
    @livewire('notifications')
    @stack('scripts')
</body>


</html>
