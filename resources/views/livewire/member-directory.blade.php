<div clas="mb-8">
    <!-- Header with Stats -->
    <x-common.page-breadcrumb pageTitle="Members" />
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Member Directory</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Browse and connect with our club members</p>
            </div>

            <!-- View Mode Toggle -->
            <div class="flex items-center space-x-2 mt-4 lg:mt-0">
                <button wire:click="$set('viewMode', 'grid')"
                        class="p-2 rounded-lg {{ $viewMode === 'grid' ? 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-300' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                </button>
                <button wire:click="$set('viewMode', 'list')"
                        class="p-2 rounded-lg {{ $viewMode === 'list' ? 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-300' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Total Members</div>
            </div>
            <div class="bg-green-50 dark:bg-green-900 rounded-lg p-4">
                <div class="text-2xl font-bold text-green-600 dark:text-green-300">{{ $stats['active'] }}</div>
                <div class="text-sm text-green-600 dark:text-green-400">Active Members</div>
            </div>
            <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-4">
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-300">{{ $stats['alumni'] }}</div>
                <div class="text-sm text-blue-600 dark:text-blue-400">Alumni</div>
            </div>
            <div class="bg-purple-50 dark:bg-purple-900 rounded-lg p-4">
                <div class="text-2xl font-bold text-purple-600 dark:text-purple-300">{{ $stats['active_percentage'] }}%</div>
                <div class="text-sm text-purple-600 dark:text-purple-400">Active Rate</div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="lg:col-span-2">
                <input wire:model.live.debounce.300ms="search"
                       type="text"
                       placeholder="Search members by name, email, or program..."
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
            </div>

            <!-- Filter -->
            <div>
                <select wire:model.live="filter" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <option value="all">All Members</option>
                    <option value="active">Active Members</option>
                    <option value="alumni">Alumni</option>
                    <option value="executive">Executive Board</option>
                </select>
            </div>

            <!-- Year Filter -->
            <div>
                <select wire:model.live="yearFilter" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <option value="">All Years</option>
                    @foreach($years as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <!-- Program Filter -->
            <div>
                <select wire:model.live="programFilter" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <option value="">All Programs</option>
                    @foreach($programs as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Role Filter -->
            <div>
                <select wire:model.live="roleFilter" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <option value="">All Roles</option>
                    @foreach($roles as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Members Display -->
    <div>
        @if($members->count() > 0)
            @if($viewMode === 'grid')
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($members as $member)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md transition-shadow p-6">
                            <!-- Profile Photo -->
                            <div class="flex flex-col items-center">
                                <img src="{{ $member->avatar_url }}"
                                     alt="{{ $member->name }}"
                                     class="w-20 h-20 rounded-full object-cover mb-4">

                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center">{{ $member->name }}</h3>

                                @if($member->show_program && $member->program)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 text-center">{{ $member->program }}</p>
                                @endif

                                @if($member->show_year && $member->year_of_study)
                                    <p class="text-sm text-gray-500 dark:text-gray-500 text-center">Year {{ $member->year_of_study }}</p>
                                @endif

                                <!-- Roles -->
                                @if($member->roles->count() > 0)
                                    <div class="flex flex-wrap gap-1 mt-2 justify-center">
                                        @foreach($member->roles->take(2) as $role)
                                            <span class="px-2 py-1 text-xs bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-300 rounded-full">
                                                {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Contact Options -->
                            <div class="mt-4 space-y-2">
                                @if($member->can_be_contacted)
                                    @if($member->show_email)
                                        <a href="mailto:{{ $member->email }}" class="flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            Email
                                        </a>
                                    @endif

                                    @if($member->show_discord && $member->discord_username)
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515a.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0a12.64 12.64 0 0 0-.617-1.25a.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057a19.9 19.9 0 0 0 5.993 3.03a.078.078 0 0 0 .084-.028a14.09 14.09 0 0 0 1.226-1.994a.076.076 0 0 0-.041-.106a13.107 13.107 0 0 1-1.872-.892a.077.077 0 0 1-.008-.128a10.2 10.2 0 0 0 .372-.292a.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127a12.299 12.299 0 0 1-1.873.892a.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028a19.839 19.839 0 0 0 6.002-3.03a.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419c0-1.333.956-2.419 2.157-2.419c1.21 0 2.176 1.096 2.157 2.42c0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419c0-1.333.955-2.419 2.157-2.419c1.21 0 2.176 1.096 2.157 2.42c0 1.333-.946 2.418-2.157 2.418z"/>
                                            </svg>
                                            {{ $member->discord_username }}
                                        </div>
                                    @endif
                                @endif

                                <!-- Social Links -->
                                <div class="flex space-x-3">
                                    @if($member->github_username)
                                        <a href="https://github.com/{{ $member->github_username }}" target="_blank" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                            </svg>
                                        </a>
                                    @endif

                                    @if($member->linkedin_url)
                                        <a href="{{ $member->linkedin_url }}" target="_blank" class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- List View -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Member</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Program</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Year</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Roles</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contact</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($members as $member)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="w-10 h-10 rounded-full object-cover mr-3">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $member->name }}</div>
                                                    @if($member->membership_type === 'alumni')
                                                        <span class="text-xs text-blue-600 dark:text-blue-400">Alumni</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($member->show_program && $member->program)
                                                <span class="text-sm text-gray-900 dark:text-white">{{ $member->program }}</span>
                                            @else
                                                <span class="text-sm text-gray-400 dark:text-gray-600">Private</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($member->show_year && $member->year_of_study)
                                                <span class="text-sm text-gray-900 dark:text-white">Year {{ $member->year_of_study }}</span>
                                            @else
                                                <span class="text-sm text-gray-400 dark:text-gray-600">Private</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($member->roles->take(2) as $role)
                                                    <span class="px-2 py-1 text-xs bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-300 rounded-full">
                                                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex space-x-3">
                                                @if($member->can_be_contacted && $member->show_email)
                                                    <a href="mailto:{{ $member->email }}" class="text-gray-400 hover:text-gray-600">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </a>
                                                @endif

                                                @if($member->github_username)
                                                    <a href="https://github.com/{{ $member->github_username }}" target="_blank" class="text-gray-400 hover:text-gray-600">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                                        </svg>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Pagination -->
            <div class="mt-6">
                {{ $members->links() }}
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No members found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Try adjusting your search or filter criteria.</p>
            </div>
        @endif
    </div>
</div>
