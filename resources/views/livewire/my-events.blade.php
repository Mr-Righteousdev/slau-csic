<div class="max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">My Events</h1>

    @if(auth()->check())
        <div class="space-y-8">
            <!-- Upcoming Events -->
            @if($upcomingEvents->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Upcoming Events</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($upcomingEvents as $event)
                            <div class="border rounded-lg p-4 hover:shadow-lg transition-shadow">
                                <h3 class="font-medium text-gray-900 mb-2">{{ $event->title }}</h3>
                                <p class="text-sm text-gray-600 mb-2">{{ $event->start_date->format('M j, Y g:i A') }}</p>
                                <p class="text-sm text-gray-600 mb-2">{{ $event->location }}</p>
                                <span class="inline-block px-2 py-1 text-xs font-medium rounded-full {{ $event->type === 'workshop' ? 'bg-blue-100 text-blue-800' : ($event->type === 'competition' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($event->type) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Past Events -->
            @if($pastEvents->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Past Events Attended</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($pastEvents as $event)
                            <div class="border rounded-lg p-4">
                                <h3 class="font-medium text-gray-900 mb-2">{{ $event->title }}</h3>
                                <p class="text-sm text-gray-600 mb-2">{{ $event->start_date->format('M j, Y g:i A') }}</p>
                                <p class="text-sm text-gray-600 mb-2">{{ $event->location }}</p>
                                <span class="inline-block px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                    Attended
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Events I'm Instructing -->
            @if($instructedEvents->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Events I'm Instructing</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($instructedEvents as $event)
                            <div class="border rounded-lg p-4">
                                <h3 class="font-medium text-gray-900 mb-2">{{ $event->title }}</h3>
                                <p class="text-sm text-gray-600 mb-2">{{ $event->start_date->format('M j, Y g:i A') }}</p>
                                <p class="text-sm text-gray-600 mb-2">{{ $event->location }}</p>
                                <div class="text-sm text-gray-600">
                                    {{ $event->registered_count }}/{{ $event->max_participants ?? '∞' }} registered
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Pending Feedback -->
            @if($pendingFeedback->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Events Needing Feedback</h2>
                    <div class="space-y-4">
                        @foreach($pendingFeedback as $event)
                            <div class="flex items-center justify-between border rounded-lg p-4">
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $event->title }}</h3>
                                    <p class="text-sm text-gray-600">{{ $event->start_date->format('M j, Y') }}</p>
                                </div>
                                <a href="{{ route('events.feedback', $event->slug) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                    Leave Feedback
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($upcomingEvents->count() === 0 && $pastEvents->count() === 0 && $instructedEvents->count() === 0)
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Events Yet</h3>
                    <p class="text-gray-600">You haven't registered for or instructed any events yet.</p>
                    <a href="{{ route('events') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        Browse Events →
                    </a>
                </div>
            @endif
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Login Required</h3>
            <p class="text-gray-600 mb-4">Please login to view your events.</p>
            <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors inline-block">
                Login
            </a>
        </div>
    @endif
</div>
