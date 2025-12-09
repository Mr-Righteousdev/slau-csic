<div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
    @if($event->banner_image)
        <div class="h-64 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $event->banner_image) }}')"></div>
    @endif
    
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-3xl font-bold text-gray-900">{{ $event->title }}</h1>
            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $event->status === 'scheduled' ? 'bg-blue-100 text-blue-800' : ($event->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                {{ ucfirst($event->status) }}
            </span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="md:col-span-2">
                <div class="prose max-w-none">
                    <h2 class="text-xl font-semibold mb-3">About This Event</h2>
                    <div class="text-gray-700">{!! $event->description !!}</div>
                </div>
                
                @if($event->requirements)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-2">Requirements</h3>
                        <p class="text-gray-700">{{ $event->requirements }}</p>
                    </div>
                @endif
            </div>
            
            <div class="space-y-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold mb-2">Event Details</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v1a2 2 0 002 2h1v1a1 1 0 102 0v-1h1a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H4a2 2 0 00-2 2v1a2 2 0 002 2h1v1a1 1 0 102 0v-1h1a2 2 0 002-2V6a2 2 0 00-2-2h-1V3z" clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ $event->type }}</span>
                        </div>
                        
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 102 0V6zm0 6a1 1 0 10-2 0v2a1 1 0 102 0v-2z" clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ $event->start_date->format('M j, Y g:i A') }}</span>
                        </div>
                        
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l4.95-4.95a7 7 0 10-9.9-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ $event->location }}</span>
                        </div>
                        
                        @if($event->max_participants)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.525-4.314l-3.376 3.376a.75.75 0 01-1.06 0l-3.376-3.376A6.97 6.97 0 004 11.93c-.01.34-.025.673-.07 1z"></path>
                                </svg>
                                <span>{{ $event->registered_count }}/{{ $event->max_participants }} registered</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                @if($event->registration_required)
                    <livewire:event-registration :event="$event" />
                @endif
            </div>
        </div>
        
        @if($event->instructors->count() > 0)
            <div class="mt-6">
                <h3 class="text-xl font-semibold mb-4">Instructors</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($event->instructors as $instructor)
                        <div class="flex items-center space-x-3 bg-gray-50 rounded-lg p-4">
                            <div class="w-12 h-12 bg-gray-300 rounded-full flex-shrink-0"></div>
                            <div>
                                <h4 class="font-medium">{{ $instructor->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $instructor->pivot->role }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        
        @if($event->resources->count() > 0)
            <div class="mt-6">
                <h3 class="text-xl font-semibold mb-4">Resources</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($event->resources as $resource)
                        <div class="border rounded-lg p-4">
                            <h4 class="font-medium mb-2">{{ $resource->title }}</h4>
                            <p class="text-sm text-gray-600 mb-2">{{ ucfirst($resource->type) }}</p>
                            @if($resource->display_url)
                                <a href="{{ $resource->display_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                    View Resource →
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>