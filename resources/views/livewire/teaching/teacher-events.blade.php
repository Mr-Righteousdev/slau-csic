<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Events</h1>
        
        <div class="flex gap-2">
            <button wire:click="viewMode = 'list'" 
                class="p-2 rounded {{ $viewMode === 'list' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
            </button>
            <button wire:click="viewMode = 'calendar'" 
                class="p-2 rounded {{ $viewMode === 'calendar' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="mb-4 flex gap-4">
        <select wire:model.live="filter" 
            class="px-4 py-2 border border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-800 dark:text-white">
            <option value="upcoming">Upcoming</option>
            <option value="past">Past</option>
            <option value="all">All Events</option>
        </select>
    </div>

    @if(count($events) > 0)
        <div class="grid gap-4">
            @foreach($events as $event)
                @php
                    $rsvpStatus = $this->getUserRsvpStatus($event);
                @endphp
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $event->title }}</h3>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $event->status === 'scheduled' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </div>
                            
                            @if($event->description)
                                <p class="text-gray-600 dark:text-gray-300 text-sm mb-2">{{ Str::limit($event->description, 200) }}</p>
                            @endif
                            
                            <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $event->start_date->format('M j, Y g:i A') }}
                                </span>
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
                        </div>
                        
                        <div class="flex flex-col items-end gap-2">
                            @if($event->registration_required)
                                <span class="text-xs text-gray-500">Registration Required</span>
                            @endif
                            
                            @can('teacher.events.rsvp')
                                <div class="flex gap-2">
                                    <button wire:click="rsvp({{ $event->id }}, 'going')" 
                                        class="px-3 py-1 text-sm rounded {{ $rsvpStatus === 'going' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-green-50' }}">
                                        Accept
                                    </button>
                                    <button wire:click="rsvp({{ $event->id }}, 'not_going')" 
                                        class="px-3 py-1 text-sm rounded {{ $rsvpStatus === 'not_going' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-red-50' }}">
                                        Decline
                                    </button>
                                </div>
                            @endcan
                            
                            <a href="{{ route('events.show', $event->slug) }}" class="text-blue-600 hover:underline text-sm">
                                View Details →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12 text-gray-500">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p>No events found.</p>
        </div>
    @endif
</div>
