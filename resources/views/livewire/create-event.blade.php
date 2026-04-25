<div>
    <form wire:submit.prevent="saveEvent" class="space-y-6">
        <!-- Basic Info -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Event Title</label>
            <input wire:model="title" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @error
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
            <textarea wire:model="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Event Type</label>
                <select wire:model="type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm">
                    <option value="workshop">Workshop</option>
                    <option value="competition">Competition</option>
                    <option value="ctf">CTF</option>
                    <option value="bootcamp">Bootcamp</option>
                    <option value="awareness_campaign">Awareness Campaign</option>
                    <option value="talk">Talk</option>
                    <option value="social">Social</option>
                    <option value="hackathon">Hackathon</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                <input wire:model="location" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm" />
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date & Time</label>
                <input wire:model="start_date" type="datetime-local" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm" />
                @error('start_date') <span class="text-red-500 text-sm">{{ $message }}</span> @error
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Date & Time</label>
                <input wire:model="end_date" type="datetime-local" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm" />
                @error('end_date') <span class="text-red-500 text-sm">{{ $message }}</span> @error
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Participants</label>
                <input wire:model="max_participants" type="number" min="1" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Registration Fee</label>
                <input wire:model="registration_fee" type="number" min="0" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm" />
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Registration Deadline</label>
                <input wire:model="registration_deadline" type="datetime-local" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">External Link</label>
                <input wire:model="external_link" type="url" placeholder="https://" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm" />
            </div>
        </div>

        <div class="flex gap-6">
            <label class="flex items-center">
                <input wire:model="registration_required" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" />
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Registration Required</span>
            </label>
            <label class="flex items-center">
                <input wire:model="is_public" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" />
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Public Event</span>
            </label>
        </div>

        <!-- Recurrence Section -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Recurrence Settings</h3>
            <label class="flex items-center mb-4">
                <input wire:model="recurrence_enabled" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" />
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Enable Weekly Recurrence</span>
            </label>

            @if($recurrence_enabled)
                <div class="grid grid-cols-2 gap-4 ml-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Recurrence Pattern</label>
                        <select wire:model="recurrence_pattern" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm">
                            <option value="weekly">Weekly</option>
                            <option value="biweekly">Bi-weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ends At</label>
                        <input wire:model="recurrence_ends_at" type="date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm" />
                    </div>
                </div>
            @endif
        </div>

        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Create Event
        </button>
    </form>
</div>