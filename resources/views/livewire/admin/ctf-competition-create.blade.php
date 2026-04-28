<div>
    <x-common.page-breadcrumb pageTitle="Create CTF Competition" />

    <div class="mb-4 flex items-center justify-between">
        <a href="{{ route('admin.ctf-competitions') }}" class="flex items-center text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            ← Back to Competitions
        </a>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <form wire:submit="save" class="space-y-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Title <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    wire:model="title"
                    wire:input="updatedTitle($event.target.value)"
                    id="title"
                    class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-700 dark:bg-gray-800"
                />
                @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Slug <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    wire:model="slug"
                    id="slug"
                    class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-700 dark:bg-gray-800"
                />
                @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Description
                </label>
                <textarea
                    wire:model="description"
                    id="description"
                    rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-700 dark:bg-gray-800"
                ></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Start Date
                    </label>
                    <input
                        type="datetime-local"
                        wire:model="start_date"
                        id="start_date"
                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-700 dark:bg-gray-800"
                    />
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        End Date
                    </label>
                    <input
                        type="datetime-local"
                        wire:model="end_date"
                        id="end_date"
                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-700 dark:bg-gray-800"
                    />
                </div>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Status <span class="text-red-500">*</span>
                </label>
                <select
                    wire:model="status"
                    id="status"
                    class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-700 dark:bg-gray-800"
                >
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                    <option value="archived">Archived</option>
                </select>
            </div>

            <div class="flex items-center">
                <input
                    type="checkbox"
                    wire:model="is_public"
                    id="is_public"
                    class="h-4 w-4 rounded border-gray-300 text-emerald-500 focus:ring-emerald-500"
                />
                <label for="is_public" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                    Public (visible to all members)
                </label>
            </div>

            <div>
                <label for="max_score" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Max Score
                </label>
                <input
                    type="number"
                    wire:model="max_score"
                    id="max_score"
                    min="0"
                    class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-700 dark:bg-gray-800"
                />
            </div>

            <div class="flex justify-end">
                <a href="{{ route('admin.ctf-competitions') }}" class="mr-3 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                    Cancel
                </a>
                <button type="submit" class="rounded-md bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                    Create Competition
                </button>
            </div>
        </form>
    </div>
</div>