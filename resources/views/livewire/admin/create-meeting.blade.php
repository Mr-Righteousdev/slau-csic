<div>
    {{-- Success Message --}}
    @if($showSuccessMessage && $createdMeeting)
    <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-green-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-green-800">Meeting created successfully!</h3>
                <p class="mt-1 text-sm text-green-700">
                    Meeting code: <span class="font-mono font-bold">{{ $createdMeeting->meeting_code }}</span>
                </p>
                <a href="{{ route('admin.meetings.show', $createdMeeting->id) }}" class="mt-2 inline-flex items-center text-sm font-medium text-green-600 hover:text-green-500">
                    View meeting details
                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <button wire:click="closeSuccessMessage" class="ml-3 text-green-600 hover:text-green-500">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    </div>
    @endif

    {{-- Form --}}
    <form wire:submit="submit" class="space-y-6">
        {{-- Meeting Title --}}
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                Meeting Title <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                id="title"
                wire:model="title"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                placeholder="e.g., Weekly General Meeting"
            >
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Meeting Type --}}
        <div>
            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                Meeting Type <span class="text-red-500">*</span>
            </label>
            <select
                id="type"
                wire:model="type"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
                <option value="general">General Meeting</option>
                <option value="executive">Executive Board Meeting</option>
                <option value="special">Special Meeting</option>
                <option value="training">Training Session</option>
                <option value="workshop">Workshop</option>
            </select>
            @error('type')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Date and Time --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="scheduled_date" class="block text-sm font-medium text-gray-700 mb-1">
                    Date <span class="text-red-500">*</span>
                </label>
                <input
                    type="date"
                    id="scheduled_date"
                    wire:model="scheduled_date"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('scheduled_date') border-red-500 @enderror"
                >
                @error('scheduled_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="scheduled_time" class="block text-sm font-medium text-gray-700 mb-1">
                    Time <span class="text-red-500">*</span>
                </label>
                <input
                    type="time"
                    id="scheduled_time"
                    wire:model="scheduled_time"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('scheduled_time') border-red-500 @enderror"
                >
                @error('scheduled_time')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Location --}}
        <div>
            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">
                Location <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                id="location"
                wire:model="location"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('location') border-red-500 @enderror"
                placeholder="e.g., Room 301, IT Building"
            >
            @error('location')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Duration and Expected Attendees --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-1">
                    Duration (minutes) <span class="text-red-500">*</span>
                </label>
                <input
                    type="number"
                    id="duration_minutes"
                    wire:model="duration_minutes"
                    min="15"
                    max="480"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('duration_minutes') border-red-500 @enderror"
                >
                @error('duration_minutes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="expected_attendees" class="block text-sm font-medium text-gray-700 mb-1">
                    Expected Attendees
                </label>
                <input
                    type="number"
                    id="expected_attendees"
                    wire:model="expected_attendees"
                    min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Optional"
                >
            </div>
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                Description
            </label>
            <textarea
                id="description"
                wire:model="description"
                rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Brief description of the meeting..."
            ></textarea>
        </div>

        {{-- Agenda --}}
        <div>
            <label for="agenda" class="block text-sm font-medium text-gray-700 mb-1">
                Agenda
            </label>
            <textarea
                id="agenda"
                wire:model="agenda"
                rows="4"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Meeting agenda items..."
            ></textarea>
            <p class="mt-1 text-sm text-gray-500">You can format this with bullet points or numbered lists</p>
        </div>

        {{-- Submit Button --}}
        <div class="flex items-center justify-end space-x-3 pt-4 border-t">
            <button
                type="button"
                wire:click="resetFormLater"
                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition"
            >
                Reset
            </button>
            <button
                type="submit"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center"
                wire:loading.attr="disabled"
                wire:target="submit"
            >
                <span wire:loading.remove wire:target="submit">Create Meeting</span>
                <span wire:loading wire:target="submit" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Creating...
                </span>
            </button>
        </div>
    </form>
</div>
