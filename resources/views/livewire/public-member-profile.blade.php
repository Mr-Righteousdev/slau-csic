<div>
    <!-- Profile Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-32"></div>
        
        <div class="px-6 pb-6">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between -mt-16">
                <!-- Profile Photo -->
                <div class="flex flex-col sm:flex-row sm:items-end space-y-4 sm:space-y-0 sm:space-x-6">
                    <img src="{{ $member->avatar_url }}" 
                         alt="{{ $member->name }}" 
                         class="w-32 h-32 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-lg">
                    
                    <div class="mb-4 sm:mb-6">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $member->name }}</h1>
                        
                        @if($member->show_program && $member->program)
                            <p class="text-gray-600 dark:text-gray-400">{{ $member->program }}</p>
                        @endif
                        
                        @if($member->show_year && $member->year_of_study)
                            <p class="text-sm text-gray-500 dark:text-gray-500">Year {{ $member->year_of_study }}</p>
                        @endif
                        
                        <!-- Roles -->
                        @if($member->roles->count() > 0)
                            <div class="flex flex-wrap gap-2 mt-2">
                                @foreach($member->roles as $role)
                                    <span class="px-3 py-1 text-sm bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-300 rounded-full">
                                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Contact Actions -->
                <div class="flex space-x-3">
                    @if($member->can_be_contacted)
                        @if($member->show_email)
                            <a href="mailto:{{ $member->email }}" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Email
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bio Section -->
    @if($member->bio)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mt-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">About</h2>
            <p class="text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $member->bio }}</p>
        </div>
    @endif

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_attendance'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Meetings Attended</div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['attendance_rate'] }}%</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Attendance Rate</div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['projects_led'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Projects Led</div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['trainings_completed'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Trainings Completed</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mt-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contact Information</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if($member->show_email)
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Email</div>
                        <a href="mailto:{{ $member->email }}" class="text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400">
                            {{ $member->email }}
                        </a>
                    </div>
                </div>
            @endif

            @if($member->show_phone && $member->phone)
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Phone</div>
                        <a href="tel:{{ $member->phone }}" class="text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400">
                            {{ $member->phone }}
                        </a>
                    </div>
                </div>
            @endif

            @if($member->show_discord && $member->discord_username)
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515a.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0a12.64 12.64 0 0 0-.617-1.25a.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057a19.9 19.9 0 0 0 5.993 3.03a.078.078 0 0 0 .084-.028a14.09 14.09 0 0 0 1.226-1.994a.076.076 0 0 0-.041-.106a13.107 13.107 0 0 1-1.872-.892a.077.077 0 0 1-.008-.128a10.2 10.2 0 0 0 .372-.292a.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127a12.299 12.299 0 0 1-1.873.892a.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028a19.839 19.839 0 0 0 6.002-3.03a.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419c0-1.333.956-2.419 2.157-2.419c1.21 0 2.176 1.096 2.157 2.42c0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419c0-1.333.955-2.419 2.157-2.419c1.21 0 2.176 1.096 2.157 2.42c0 1.333-.946 2.418-2.157 2.418z"/>
                    </svg>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Discord</div>
                        <div class="text-gray-900 dark:text-white">{{ $member->discord_username }}</div>
                    </div>
                </div>
            @endif

            @if($member->joined_at)
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Member Since</div>
                        <div class="text-gray-900 dark:text-white">{{ $member->joined_at->format('F Y') }}</div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Social Links -->
        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Social Profiles</h3>
            <div class="flex space-x-4">
                @if($member->github_username)
                    <a href="https://github.com/{{ $member->github_username }}" target="_blank" 
                       class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                        </svg>
                    </a>
                @endif
                
                @if($member->linkedin_url)
                    <a href="{{ $member->linkedin_url }}" target="_blank" 
                       class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Back to Directory -->
    <div class="mt-6">
        <a href="{{ route('members.directory') }}" 
           class="inline-flex items-center text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Member Directory
        </a>
    </div>
</div>