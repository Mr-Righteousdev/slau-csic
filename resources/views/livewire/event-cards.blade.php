<div>
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Events</h1>
    
    @if(isset($events) && $events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $event->title }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">{{ Str::limit($event->description, 150) }}</p>
                    <p class="text-sm text-gray-500">{{ $event->start_date->format('M j, Y') }}</p>
                    <a href="{{ route('events.show', $event->slug) }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded">View Details</a>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-600">No events found.</p>
    @endif
</div>