<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create New Exam</h1>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-sm text-green-800 dark:bg-green-900 dark:text-green-200">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit="save" class="max-w-2xl space-y-6">
        <div>
            <label for="title" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                Title
            </label>
            <input
                wire:model="title"
                type="text"
                id="title"
                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                placeholder="Enter exam title"
            >
            @error('title')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="description" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                Description
            </label>
            <textarea
                wire:model="description"
                id="description"
                rows="4"
                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                placeholder="Enter exam description (optional)"
            ></textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <label for="duration_minutes" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                    Duration (minutes)
                </label>
                <input
                    wire:model="duration_minutes"
                    type="number"
                    id="duration_minutes"
                    min="1"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                >
                @error('duration_minutes')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="passing_score" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                    Passing Score (%)
                </label>
                <input
                    wire:model="passing_score"
                    type="number"
                    id="passing_score"
                    min="0"
                    max="100"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                >
                @error('passing_score')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button
                type="submit"
                class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800"
            >
                Save
            </button>
            <button
                type="button"
                wire:click="saveAndContinue"
                class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
            >
                Save & Create Another
            </button>
            <a
                href="{{ route('admin.exams.index') }}"
                class="text-sm text-gray-600 hover:underline dark:text-gray-400"
            >
                Cancel
            </a>
        </div>
    </form>
</div>
