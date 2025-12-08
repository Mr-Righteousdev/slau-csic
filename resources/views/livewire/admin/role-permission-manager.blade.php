<div x-data="{ activeTab: 'roles' }" class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Roles & Permissions</h2>
            <p class="text-gray-600 dark:text-gray-400">Manage user roles and permissions</p>
        </div>

        <div class="flex items-center space-x-3">
            <!-- Quick Actions -->
            <button @click="activeTab = 'roles'"
                    :class="activeTab === 'roles' ? 'bg-brand-600 text-white' : 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300'"
                    class="px-4 py-2 rounded-lg font-medium transition-colors">
                Roles
            </button>
            <button @click="activeTab = 'permissions'"
                    :class="activeTab === 'permissions' ? 'bg-brand-600 text-white' : 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300'"
                    class="px-4 py-2 rounded-lg font-medium transition-colors">
                Permissions
            </button>
            <button @click="activeTab = 'assignments'"
                    :class="activeTab === 'assignments' ? 'bg-brand-600 text-white' : 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300'"
                    class="px-4 py-2 rounded-lg font-medium transition-colors">
                Assignments
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-green-800 dark:text-green-300">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-red-800 dark:text-red-300">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Roles Tab -->
    <div x-show="activeTab === 'roles'" x-transition class="space-y-6">
        <!-- Roles Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex-1">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                    <div class="relative flex-1">
                        <input type="text"
                               wire:model.live.debounce.300ms="search"
                               placeholder="Search roles..."
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-colors">
                        <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <select wire:model.live="filter"
                            class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-colors">
                        <option value="all">All Roles</option>
                        <option value="with_users">With Users</option>
                        <option value="without_users">Without Users</option>
                    </select>
                </div>
            </div>

            <button wire:click="createRole"
                    class="inline-flex items-center px-4 py-2.5 bg-brand-600 text-white font-medium rounded-lg hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create Role
            </button>
        </div>

        <!-- Roles Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Role
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Permissions
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Users
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Created
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($roles as $role)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-brand-100 dark:bg-brand-900/30 text-brand-600 dark:text-brand-400 rounded-lg">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $role->name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $role->guard_name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($role->permissions->take(3) as $permission)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                                {{ $permission->name }}
                                            </span>
                                        @empty
                                            <span class="text-sm text-gray-500 dark:text-gray-400">No permissions</span>
                                        @endforelse
                                        @if($role->permissions->count() > 3)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300">
                                                +{{ $role->permissions->count() - 3 }} more
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex -space-x-2">
                                            @forelse($role->users->take(3) as $user)
                                                <img class="h-8 w-8 rounded-full border-2 border-white dark:border-gray-800"
                                                     src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&color=fff' }}"
                                                     alt="{{ $user->name }}">
                                            @empty
                                            @endforelse
                                        </div>
                                        <span class="ml-2 text-sm text-gray-900 dark:text-white">
                                            {{ $role->users_count ?? $role->users->count() }} users
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $role->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <button wire:click="editRole({{ $role->id }})"
                                                class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDeleteRole({{ $role->id }})"
                                                class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-gray-500 dark:text-gray-400">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No roles found</h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new role.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($roles->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $roles->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Permissions Tab -->
    <div x-show="activeTab === 'permissions'" x-transition class="space-y-6" style="display: none;">
        <!-- Permissions Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex-1">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                    <div class="relative flex-1">
                        <input type="text"
                               wire:model.live.debounce.300ms="search"
                               placeholder="Search permissions..."
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-colors">
                        <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <select wire:model.live="filter"
                            class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-colors">
                        <option value="all">All Permissions</option>
                        @foreach($permissionGroups as $key => $group)
                            <option value="{{ $key }}">{{ $group }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button wire:click="createPermission"
                    class="inline-flex items-center px-4 py-2.5 bg-brand-600 text-white font-medium rounded-lg hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create Permission
            </button>
        </div>

        <!-- Permissions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($permissionGroups as $groupKey => $groupName)
                @php
                    $groupPermissions = $permissions->get($groupKey, []);
                @endphp

                @if(count($groupPermissions) > 0 || $filter === 'all' || $filter === $groupKey)
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $groupName }}
                                </h3>
                                <span class="px-2 py-1 text-xs font-medium bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-300 rounded-full">
                                    {{ count($groupPermissions) }}
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Permissions for {{ strtolower($groupName) }}
                            </p>
                        </div>

                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($groupPermissions as $permission)
                                <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-900/30 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $permission->name }}
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                Assigned to {{ $permission->roles_count ?? $permission->roles->count() }} roles
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <button wire:click="editPermission({{ $permission->id }})"
                                                    class="p-1 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button wire:click="confirmDeletePermission({{ $permission->id }})"
                                                    class="p-1 text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                @if($filter === 'all' || $filter === $groupKey)
                                    <div class="px-6 py-8 text-center">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">No permissions in this group</p>
                                    </div>
                                @endif
                            @endforelse
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <!-- User Assignments Tab -->
    <div x-show="activeTab === 'assignments'" x-transition class="space-y-6" style="display: none;">
        <!-- Assignments Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3">
                    <div class="relative flex-1">
                        <input type="text"
                               wire:model.live.debounce.300ms="userSearch"
                               placeholder="Search users..."
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-colors">
                        <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <button wire:click="openUserAssignmentModal"
                    class="inline-flex items-center px-4 py-2.5 bg-brand-600 text-white font-medium rounded-lg hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Assign Roles
            </button>
        </div>

        <!-- Users List -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                User
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Roles
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full"
                                                 src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&color=fff' }}"
                                                 alt="{{ $user->name }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $user->name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                ID: {{ $user->id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($user->roles as $role)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-brand-100 text-brand-800 dark:bg-brand-900/30 dark:text-brand-400">
                                                {{ $role->name }}
                                            </span>
                                        @empty
                                            <span class="text-sm text-gray-500 dark:text-gray-400">No roles assigned</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button wire:click="openUserAssignmentModal({{ $user->id }})"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-lg text-brand-700 bg-brand-100 hover:bg-brand-200 dark:text-brand-400 dark:bg-brand-900/30 dark:hover:bg-brand-900/50 transition-colors">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Manage Roles
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="text-gray-500 dark:text-gray-400">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No users found</h3>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Role Modal -->
        <x-filament::modal width="2xl" id="role-modal">
            <x-slot name="heading">
                {{ $roleModalTitle }}
            </x-slot>

            <form wire:submit.prevent="saveRole" class="space-y-6">
                <div>
                    <label for="roleName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Role Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        id="roleName"
                        wire:model="roleName"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('roleName') border-red-500 @enderror"
                        placeholder="e.g., admin, editor, viewer">
                    @error('roleName')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Permissions
                    </label>
                    <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 max-h-64 overflow-y-auto">
                        <div class="space-y-2">
                            @foreach($allPermissions as $permission)
                                <label class="flex items-center">
                                    <input type="checkbox"
                                        wire:model="selectedPermissions"
                                        value="{{ $permission->id }}"
                                        class="h-4 w-4 text-brand-600 rounded border-gray-300 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $permission->name }}
                                        @if($permission->group)
                                            <span class="text-xs text-gray-500 dark:text-gray-400">({{ $permissionGroups[$permission->group] ?? $permission->group }})</span>
                                        @endif
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                    <button type="button"
                            wire:click="$dispatch('close-modal', { id: 'role-modal' })"
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Save Role
                    </button>
                </div>
            </form>
        </x-filament::modal>


    <!-- Delete Role Confirmation Modal -->
        <x-filament::modal width="2xl" id="delete-role-modal">
            <x-slot name="heading">
                Delete Role
            </x-slot>

            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.698-.833-2.464 0L4.338 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            Are you sure?
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Are you sure you want to delete this role? This action cannot be undone.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                <button type="button"
                        wire:click="$dispatch('close-modal', { id: 'delete-role-modal' })"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="button"
                        wire:click="deleteRole"
                        class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Delete Role
                </button>
            </div>
        </x-filament::modal>


    <!-- Permission Modal -->
        <x-filament::modal width="2xl" id="permission-modal">
            <x-slot name="heading">
                {{ $permissionModalTitle }}
            </x-slot>

            <form wire:submit.prevent="savePermission" class="space-y-6">
                <div>
                    <label for="permissionName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Permission Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        id="permissionName"
                        wire:model="permissionName"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('permissionName') border-red-500 @enderror"
                        placeholder="e.g., create user, edit post, delete file">
                    @error('permissionName')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="permissionGroup" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Permission Group
                    </label>
                    <select id="permissionGroup"
                            wire:model="permissionGroup"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('permissionGroup') border-red-500 @enderror">
                        <option value="">Select Group</option>
                        @foreach($permissionGroups as $key => $group)
                            <option value="{{ $key }}">{{ $group }}</option>
                        @endforeach
                        <option value="custom">Custom Group</option>
                    </select>
                    @if($permissionGroup === 'custom')
                        <input type="text"
                            wire:model="permissionGroup"
                            class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter custom group name">
                    @endif
                    @error('permissionGroup')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                    <button type="button"
                            wire:click="$dispatch('close-modal', { id: 'permission-modal' })"
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Save Permission
                    </button>
                </div>
            </form>
        </x-filament::modal>

    <!-- Delete Permission Confirmation Modal -->
        <x-filament::modal width="2xl" id="delete-permission-modal">
            <x-slot name="heading">
                Delete Permission
            </x-slot>

            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.698-.833-2.464 0L4.338 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            Are you sure?
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Are you sure you want to delete this permission? This action cannot be undone.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                <button type="button"
                        wire:click="$dispatch('close-modal', { id: 'delete-permission-modal' })"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="button"
                        wire:click="deletePermission"
                        class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Delete Permission
                </button>
            </div>
        </x-filament::modal>

    <!-- User Assignment Modal -->

        <x-filament::modal width="2xl" id="user-assignment-modal">
            <x-slot name="heading">
                Assign Roles to Users
            </x-slot>
            <form wire:submit.prevent="assignRolesToUser">
                <div class="px-6 pt-5 pb-4 sm:p-6 sm:pb-4">


                    <div class="space-y-4">
                        <div>
                            <label for="userSearch" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Select User *
                            </label>
                            <select id="selectedUser"
                                    wire:model.live="selectedUser"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-brand-500 focus:border-brand-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Select a user</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            @error('selectedUser') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        @if($selectedUser)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Select Roles
                                </label>
                                <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 max-h-64 overflow-y-auto">
                                    <div class="space-y-2">
                                        @foreach($allRoles as $role)
                                            <label class="flex items-center">
                                                <input type="checkbox"
                                                    wire:model="assignedRoles"
                                                    value="{{ $role->id }}"
                                                    class="h-4 w-4 text-brand-600 rounded border-gray-300 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700">
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                                    {{ $role->name }}
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">({{ $role->permissions->count() }} permissions)</span>
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 flex justify-end space-x-3">
                    <button type="button"
                            wire:click="$set('showUserAssignmentModal', false)"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-brand-600 border border-transparent rounded-md hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                        Assign Roles
                    </button>
                </div>
            </form>

        </x-filament::modal>

</div>

