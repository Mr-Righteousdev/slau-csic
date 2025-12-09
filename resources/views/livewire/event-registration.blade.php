<div class="bg-white rounded-lg shadow-md p-6">
    @if($event->registration_required)
        @if(auth()->check())
            @if($registered)
                <div class="text-center">
                    <div class="mb-4">
                        <svg class="w-16 h-16 mx-auto text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L10 11.586l-1.293-1.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">You're Registered!</h3>
                    <p class="text-gray-600 mb-4">You have successfully registered for this event.</p>
                    <button wire:click="unregister" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        Cancel Registration
                    </button>
                </div>
            @else
                <div class="text-center">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Register for {{ $event->title }}</h3>
                    <div class="mb-4 text-gray-600">
                        @if($event->max_participants)
                            <p>{{ $remainingSpots }} spots remaining out of {{ $event->max_participants }}</p>
                        @else
                            <p>Unlimited spots available</p>
                        @endif
                    </div>
                    @hasrole('admin|super-admin|member')
                        @if($remainingSpots > 0)
                            <button wire:click="register" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                Register Now
                            </button>
                        @else
                            <p class="text-red-600 font-medium">This event is full</p>
                        @endif
                    @else
                        <p class="text-gray-600 font-medium">Registration requires member privileges</p>
                    @endhasrole
                </div>
            @endif
        @else
            <div class="text-center">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Login Required</h3>
                <p class="text-gray-600 mb-4">Please login to register for this event.</p>
                <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors inline-block">
                    Login
                </a>
            </div>
        @endif
    @else
        <div class="text-center">
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Registration Required</h3>
            <p class="text-gray-600">This event does not require registration. Just show up!</p>
        </div>
    @endif
</div>
