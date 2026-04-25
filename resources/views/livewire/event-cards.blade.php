<div>
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Events</h1>
    
    <!-- Search and Filter Header -->
    <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
        <div class="relative w-full sm:w-64">
            <input 
                type="text" 
                wire:model.debounce.500ms="search" 
                placeholder="Search events..." 
                class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
    </div>
    
    <!-- Category Filter Pills -->
    <div class="mb-6 flex flex-wrap gap-2">
        <button 
            wire:click="$set('filter', 'all')"
            class="px-3 py-1.5 rounded-full text-sm font-medium transition-colors {{ $filter === 'all' ? 'bg-gray-800 dark:bg-white text-white dark:text-gray-800' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}"
        >
            All
        </button>
        @php
            $categories = [
                ['key' => 'workshop', 'label' => 'Workshop', 'color' => 'green'],
                ['key' => 'competition', 'label' => 'Competition', 'color' => 'red'],
                ['key' => 'ctf', 'label' => 'CTF', 'color' => 'red'],
                ['key' => 'bootcamp', 'label' => 'Bootcamp', 'color' => 'purple'],
                ['key' => 'awareness_campaign', 'label' => 'Awareness', 'color' => 'yellow'],
                ['key' => 'talk', 'label' => 'Talk', 'color' => 'blue'],
                ['key' => 'social', 'label' => 'Social', 'color' => 'indigo'],
                ['key' => 'hackathon', 'label' => 'Hackathon', 'color' => 'orange'],
            ];
        @endphp
        @foreach($categories as $cat)
            <button 
                wire:click="$set('filter', '{{ $cat['key'] }}')"
                class="px-3 py-1.5 rounded-full text-sm font-medium transition-colors 
                    @if($filter === '{{ $cat['key'] }}')
                        bg-{{ $cat['color'] }}-600 text-white
                    @else
                        bg-{{ $cat['color'] }}-100 dark:bg-{{ $cat['color'] }}-900/30 text-{{ $cat['color'] }}-700 dark:text-{{ $cat['color'] }}-300 border border-{{ $cat['color'] }}-300 dark:border-{{ $cat['color'] }}-700
                    @endif
                hover:opacity-80"
            >
                {{ $cat['label'] }}
            </button>
        @endforeach
    </div>
    
    @if(isset($events) && $events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
                @php
                    $badgeColor = match($event->type) {
                        'workshop' => 'green',
                        'competition' => 'red',
                        'ctf' => 'red',
                        'bootcamp' => 'purple',
                        'awareness_campaign' => 'yellow',
                        'talk' => 'blue',
                        'social' => 'indigo',
                        'hackathon' => 'orange',
                        default => 'gray'
                    };
                    $label = match($event->type) {
                        'awareness_campaign' => 'Awareness',
                        default => ucfirst($event->type)
                    };
                @endphp
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $event->title }}</h3>
                        <div class="flex flex-col items-end gap-1">
                            @if($event->is_recurring)
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Recurring weekly
                                </span>
                            @endif
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-{{ $badgeColor }}-100 text-{{ $badgeColor }}-800 dark:bg-{{ $badgeColor }}-900/40 dark:text-{{ $badgeColor }}-300">
                                {{ $label }}
                            </span>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">{{ Str::limit($event->description, 150) }}</p>
                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-4">
                        <span>{{ $event->start_date->format('M j, Y') }}</span>
                        @if($event->location)
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $event->location }}
                            </span>
                        @endif
                    </div>

                    <!-- Capacity Progress Bar -->
                    @if($event->max_participants)
                        <div class="mt-3">
                            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                                <span>{{ $event->registered_count }}/{{ $event->max_participants }} spots filled</span>
                                <span>{{ $this->getRemainingSpots($event) }} remaining</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="h-2 rounded-full {{ $event->is_full ? 'bg-red-500' : 'bg-green-500' }}"
                                     style="width: {{ $event->max_participants ? ($event->registered_count / $event->max_participants * 100) : 0 }}%">
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- RSVP Button -->
                    <div class="mt-4 flex items-center gap-2">
                        @if($this->isUserAttending($event))
                            <button disabled class="px-4 py-2 bg-green-500 text-white rounded-lg cursor-not-allowed">
                                Going
                            </button>
                            <button
                                wire:click="cancelRsvp({{ $event->id }})"
                                class="px-3 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 text-sm"
                            >
                                Can't Go
                            </button>
                        @elseif($event->is_full)
                            <button disabled class="px-4 py-2 bg-gray-300 text-gray-600 rounded-lg cursor-not-allowed">
                                Event Full
                            </button>
                        @else
                            <button
                                wire:click="rsvpForEvent({{ $event->id }})"
                                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors"
                            >
                                RSVP
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($events->hasPages())
            <div class="mt-8">
                {{ $events->links() }}
            </div>
        @endif
    @else
        <p class="text-gray-600 dark:text-gray-400">No events found.</p>
    @endif
</div>