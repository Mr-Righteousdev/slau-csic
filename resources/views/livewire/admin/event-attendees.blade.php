<div>
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $event->title }} - Attendees</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ $event->registrations->count() }} {{ $event->registrations->count() === 1 ? 'registration' : 'registrations' }}
                </p>
            </div>
            <a href="{{ route('admin.events') }}" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">
                ← Back to Events
            </a>
        </div>
    </div>

    {{ $this->table }}
</div>