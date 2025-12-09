@php
    $currentPath = request()->path();
@endphp

<aside id="sidebar"
    class="fixed flex flex-col mt-0 top-0 px-5 left-0 bg-white dark:bg-gray-900 dark:border-gray-800 text-gray-900 h-screen transition-all duration-300 ease-in-out z-99999 border-r border-gray-200"
    x-data="{
        openSubmenus: {},
        init() {
            this.initializeActiveMenus();
        },
        initializeActiveMenus() {
            // Keep your existing initialization logic
            const currentPath = '{{ $currentPath }}';

            // Add your route-specific active menu initialization here
            // Example:
            if (currentPath.startsWith('admin/dashboard')) {
                this.openSubmenus['dashboard'] = true;
            }
            if (currentPath.startsWith('admin/users')) {
                this.openSubmenus['admin'] = true;
            }
            if (currentPath.startsWith('admin/fines')) {
                this.openSubmenus['admin'] = true;
            }
            if (currentPath.startsWith('admin/fine-types')) {
                this.openSubmenus['admin'] = true;
            }
            if (currentPath.startsWith('/fines')) {
                this.openSubmenus['main'] = true;
            }
            // Add more as needed
        },
        toggleSubmenu(menuKey) {
            const newState = !this.openSubmenus[menuKey];

            // Optional: Close other submenus when opening a new one
            if (newState) {
                this.openSubmenus = {};
            }

            this.openSubmenus[menuKey] = newState;
        },
        isSubmenuOpen(menuKey) {
            return this.openSubmenus[menuKey] || false;
        },
        isActive(path) {
            return window.location.pathname === path ||
                   '{{ $currentPath }}' === path.replace(/^\//, '');
        }
    }"
    :class="{
        'w-[290px]': $store.sidebar.isExpanded || $store.sidebar.isMobileOpen || $store.sidebar.isHovered,
        'w-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
        'translate-x-0': $store.sidebar.isMobileOpen,
        '-translate-x-full xl:translate-x-0': !$store.sidebar.isMobileOpen
    }"
    @mouseenter="if (!$store.sidebar.isExpanded) $store.sidebar.setHovered(true)"
    @mouseleave="$store.sidebar.setHovered(false)">

    <!-- Logo Section -->
    <div class="pt-8 pb-7 flex"
        :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
        'xl:justify-center' :
        'justify-start'">
        <a href="/">
            <img x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                class="dark:hidden" src="/images/logo/logo.svg" alt="Logo" width="150" height="40" />
            <img x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                class="hidden dark:block" src="/images/logo/logo-dark.svg" alt="Logo" width="150"
                height="40" />
            <img x-show="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen"
                src="/images/logo/logo-icon.svg" alt="Logo" width="32" height="32" />
        </a>
    </div>

    <!-- Navigation Menu -->
    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
        <nav class="mb-6">
            <div class="flex flex-col gap-4">

                <!-- Menu Group: Main Menu -->
                <div>
                    <h2 class="mb-4 text-xs uppercase flex leading-[20px] text-gray-400"
                        :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                        'lg:justify-center' : 'justify-start'">
                        <template
                            x-if="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">
                            <span>Menu</span>
                        </template>
                        <template x-if="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z" fill="currentColor"/>
                            </svg>
                        </template>
                    </h2>

                    <ul class="flex flex-col gap-1">
                        <!-- Dashboard - Available to all authenticated users -->
                        <li>
                            <a href="{{ route('admin.dashboard') }}" wire:navigate class="menu-item group"
                                :class="[
                                    isActive('/admin') ? 'menu-item-active' : 'menu-item-inactive',
                                    (!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                                    'xl:justify-center' : 'justify-start'
                                ]">
                                <span :class="isActive('/admin') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V8.99998C14.25 9.41419 14.5858 9.74998 15 9.74998H18.5C18.9142 9.74998 19.25 9.41419 19.25 8.99998V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15ZM14.25 15C14.25 14.5858 14.5858 14.25 15 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z" fill="currentColor"></path></svg>
                                </span>
                                <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                    class="menu-item-text flex items-center gap-2">
                                    Dashboard
                                </span>
                            </a>
                        </li>

                        <!-- User Profile - Available to all authenticated users -->
                        <li>
                            <a href="{{ route('user-profile') }}" wire:navigate class="menu-item group"
                                :class="[
                                    isActive('/user-profile') ? 'menu-item-active' : 'menu-item-inactive',
                                    (!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                                    'xl:justify-center' : 'justify-start'
                                ]">
                                <span :class="isActive('/user-profile') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2.25C9.37665 2.25 7.25 4.37665 7.25 7C7.25 9.62335 9.37665 11.75 12 11.75C14.6234 11.75 16.75 9.62335 16.75 7C16.75 4.37665 14.6234 2.25 12 2.25ZM8.75 7C8.75 5.20507 10.2051 3.75 12 3.75C13.7949 3.75 15.25 5.20507 15.25 7C15.25 8.79493 13.7949 10.25 12 10.25C10.2051 10.25 8.75 8.79493 8.75 7ZM5.75 14.25C5.75 13.4216 6.42157 12.75 7.25 12.75H16.75C17.5784 12.75 18.25 13.4216 18.25 14.25V19.25C18.25 20.0784 17.5784 20.75 16.75 20.75H7.25C6.42157 20.75 5.75 20.0784 5.75 19.25V14.25ZM7.25 14.25V19.25H16.75V14.25H7.25Z" fill="currentColor"></path></svg>
                                </span>
                                <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                    class="menu-item-text flex items-center gap-2">
                                    Profile
                                </span>
                            </a>
                        </li>

                        <!-- Members Directory - Available to all authenticated users -->
                        <li>
                            <a href="{{ route('members.directory') }}" wire:navigate class="menu-item group"
                                :class="[
                                    isActive('/members') ? 'menu-item-active' : 'menu-item-inactive',
                                    (!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                                    'xl:justify-center' : 'justify-start'
                                ]">
                                <span :class="isActive('/members') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.25 13C3.25 12.5858 3.58579 12.25 4 12.25H14C14.4142 12.25 14.75 12.5858 14.75 13C14.75 13.4142 14.4142 13.75 14 13.75H4C3.58579 13.75 3.25 13.4142 3.25 13ZM3.25 16C3.25 15.5858 3.58579 15.25 4 15.25H14C14.4142 15.25 14.75 15.5858 14.75 16C14.75 16.4142 14.4142 16.75 14 16.75H4C3.58579 16.75 3.25 16.4142 3.25 16ZM3.25 19C3.25 18.5858 3.58579 18.25 4 18.25H14C14.4142 18.25 14.75 18.5858 14.75 19C14.75 19.4142 14.4142 19.75 14 19.75H4C3.58579 19.75 3.25 19.4142 3.25 19ZM17 12.25C16.5858 12.25 16.25 12.5858 16.25 13C16.25 13.4142 16.5858 13.75 17 13.75H20C20.4142 13.75 20.75 13.4142 20.75 13C20.75 12.5858 20.4142 12.25 20 12.25H17ZM17 15.25C16.5858 15.25 16.25 15.5858 16.25 16C16.25 16.4142 16.5858 16.75 17 16.75H20C20.4142 16.75 20.75 16.4142 20.75 16C20.75 15.5858 20.4142 15.25 20 15.25H17ZM17 18.25C16.5858 18.25 16.25 18.5858 16.25 19C16.25 19.4142 16.5858 19.75 17 19.75H20C20.4142 19.75 20.75 19.4142 20.75 19C20.75 18.5858 20.4142 18.25 20 18.25H17Z" fill="currentColor"></path></svg>
                                </span>
                                <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                    class="menu-item-text flex items-center gap-2">
                                    Members
                                </span>
                            </a>
                        </li>

                        <!-- Events - Available to all authenticated users -->
                        <li>
                            <a href="{{ route('events') }}" wire:navigate class="menu-item group"
                                :class="[
                                    isActive('/events') ? 'menu-item-active' : 'menu-item-inactive',
                                    (!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                                    'xl:justify-center' : 'justify-start'
                                ]">
                                <span :class="isActive('/events') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z" fill="currentColor"></path></svg>
                                </span>
                                <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                    class="menu-item-text flex items-center gap-2">
                                    Events
                                </span>
                            </a>
                        </li>

                        <!-- My Events - Available to all authenticated users -->
                        <li>
                            <a href="{{ route('my-events') }}" wire:navigate class="menu-item group"
                                :class="[
                                    isActive('/my-events') ? 'menu-item-active' : 'menu-item-inactive',
                                    (!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                                    'xl:justify-center' : 'justify-start'
                                ]">
                                <span :class="isActive('/my-events') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z" fill="currentColor"></path></svg>
                                </span>
                                <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                    class="menu-item-text flex items-center gap-2">
                                    My Events
                                </span>
                            </a>
                        </li>

                        <!-- Financial Management - Available to treasurer, president, super-admin -->
                        @hasanyrole('treasurer|president|super-admin')
                            <li>
                                <a href="{{ route('admin.treasurer-dashboard') }}" wire:navigate class="menu-item group"
                                    :class="[
                                        isActive('/admin/treasurer-dashboard') ? 'menu-item-active' : 'menu-item-inactive',
                                        (!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                                        'xl:justify-center' : 'justify-start'
                                    ]">
                                    <span :class="isActive('/admin/treasurer-dashboard') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V3h-8v18zm0-8h8v-6h-8v6z" fill="currentColor"></path></svg>
                                    </span>
                                    <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                        class="menu-item-text flex items-center gap-2">
                                        Treasurer Dashboard
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.transactions') }}" wire:navigate class="menu-item group"
                                    :class="[
                                        isActive('/admin/transactions') ? 'menu-item-active' : 'menu-item-inactive',
                                        (!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                                        'xl:justify-center' : 'justify-start'
                                    ]">
                                    <span :class="isActive('/admin/transactions') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V8.99998C14.25 9.41419 14.5858 9.74998 15 9.74998H18.5C18.9142 9.74998 19.25 9.41419 19.25 8.99998V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15ZM14.25 15C14.25 14.5858 14.5858 14.25 15 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z" fill="currentColor"></path></svg>
                                    </span>
                                    <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                        class="menu-item-text flex items-center gap-2">
                                        Transactions
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.budget-categories') }}" wire:navigate class="menu-item group"
                                    :class="[
                                        isActive('/admin/budget-categories') ? 'menu-item-active' : 'menu-item-inactive',
                                        (!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                                        'xl:justify-center' : 'justify-start'
                                    ]">
                                    <span :class="isActive('/admin/budget-categories') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V8.99998C14.25 9.41419 14.5858 9.74998 15 9.74998H18.5C18.9142 9.74998 19.25 9.41419 19.25 8.99998V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15ZM14.25 15C14.25 14.5858 14.5858 14.25 15 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z" fill="currentColor"></path></svg>
                                    </span>
                                    <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                        class="menu-item-text flex items-center gap-2">
                                        Budget Categories
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.fines') }}" wire:navigate class="menu-item group"
                                    :class="[
                                        isActive('/admin/fines') ? 'menu-item-active' : 'menu-item-inactive',
                                        (!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                                        'xl:justify-center' : 'justify-start'
                                    ]">
                                    <span :class="isActive('/admin/fines') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" fill="currentColor"></path></svg>
                                    </span>
                                    <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                        class="menu-item-text flex items-center gap-2">
                                        Fines Management
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.fine-types') }}" wire:navigate class="menu-item group"
                                    :class="[
                                        isActive('/admin/fine-types') ? 'menu-item-active' : 'menu-item-inactive',
                                        (!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                                        'xl:justify-center' : 'justify-start'
                                    ]">
                                    <span :class="isActive('/admin/fine-types') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M7 7h10v3H7V7zm0 4h10v3H7v-3zm0 4h10v3H7v-3z" fill="currentColor"></path></svg>
                                    </span>
                                    <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                        class="menu-item-text flex items-center gap-2">
                                        Fine Types
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.financial-reports') }}" wire:navigate class="menu-item group"
                                    :class="[
                                        isActive('/admin/financial-reports') ? 'menu-item-active' : 'menu-item-inactive',
                                        (!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                                        'xl:justify-center' : 'justify-start'
                                    ]">
                                    <span :class="isActive('/admin/financial-reports') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M9 17h6m0 0v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2H2a2 2 0 002-2zm0 0V9a2 2 0 012-2h-2a2 2 0 00-2 2v6a2 2 0 002 2H2A2 2 0 002-2zm0 0H8v-2a2 2 0 012-2h-2a2 2 0 00-2 2V2z" fill="currentColor"></path></svg>
                                    </span>
                                    <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                        class="menu-item-text flex items-center gap-2">
                                        Financial Reports
                                    </span>
                                </a>
                            </li>
                        @endhasanyrole



                        <!-- ADMIN ROLE ITEMS -->
                        @hasrole('admin|super-admin')


                            <li>
                                <a href="{{ route('admin.meetings') }}" wire:navigate class="menu-item group"
                                    :class="[
                                        isActive('/admin/meetings') ? 'menu-item-active' : 'menu-item-inactive',
                                        (!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                                        'xl:justify-center' : 'justify-start'
                                    ]"
                                    wire:navigate>
                                    <span :class="isActive('/admin/create-meeting') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V8.99998C14.25 9.41419 14.5858 9.74998 15 9.74998H18.5C18.9142 9.74998 19.25 9.41419 19.25 8.99998V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15ZM14.25 15C14.25 14.5858 14.5858 14.25 15 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z" fill="currentColor"></path></svg>
                                    </span>
                                    <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                        class="menu-item-text flex items-center gap-2">
                                        Meetings
                                    </span>
                                </a>
                            </li>


                        @endhasrole

                        @hasrole('super-admin')
                            <li>
                                <a href="{{ route('admin.users') }}" wire:navigate class="menu-item group"
                                    :class="[
                                        isActive('/admin/users') ? 'menu-item-active' : 'menu-item-inactive',
                                        (!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                                        'xl:justify-center' : 'justify-start'
                                    ]">
                                    <span :class="isActive('/admin/users') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V8.99998C14.25 9.41419 14.5858 9.74998 15 9.74998H18.5C18.9142 9.74998 19.25 9.41419 19.25 8.99998V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15ZM14.25 15C14.25 14.5858 14.5858 14.25 15 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z" fill="currentColor"></path></svg>
                                    </span>
                                    <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                        class="menu-item-text flex items-center gap-2">
                                        User Management
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.roles-permissions') }}" wire:navigate class="menu-item group"
                                    :class="[
                                        isActive('/admin/roles-permissions') ? 'menu-item-active' : 'menu-item-inactive',
                                        (!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                                        'xl:justify-center' : 'justify-start'
                                    ]">
                                    <span :class="isActive('/admin/roles-permissions') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V8.99998C14.25 9.41419 14.5858 9.74998 15 9.74998H18.5C18.9142 9.74998 19.25 9.41419 19.25 8.99998V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15ZM14.25 15C14.25 14.5858 14.5858 14.25 15 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z" fill="currentColor"></path></svg>
                                    </span>
                                    <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                        class="menu-item-text flex items-center gap-2">
                                        Roles & Permissions
                                    </span>
                                </a>
                            </li>
                        @endhasrole



                    </ul>
                </div>

                <!-- Menu Group: Tools -->
                <div>
                    <h2 class="mb-4 text-xs uppercase flex leading-[20px] text-gray-400"
                        :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                        'lg:justify-center' : 'justify-start'">
                        <template
                            x-if="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">
                            <span>Tools</span>
                        </template>
                        <template x-if="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z" fill="currentColor"/>
                            </svg>
                        </template>
                    </h2>

                    <ul class="flex flex-col gap-1">

                    </ul>
                </div>
            </div>
        </nav>


        @impersonating($guard = null)
            <a href="{{ route('impersonate.leave') }}">Leave impersonation</a>
        @endImpersonating
        <!-- Sidebar Widget -->

    </div>
    {{-- <div x-data x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-transition class="mt-auto">
        @include('layouts.sidebar-widget')
    </div> --}}
</aside>

<!-- Mobile Overlay -->
<div x-show="$store.sidebar.isMobileOpen" @click="$store.sidebar.setMobileOpen(false)"
    class="fixed z-50 h-screen w-full bg-gray-900/50"></div>

