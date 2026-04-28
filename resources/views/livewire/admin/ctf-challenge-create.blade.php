<div>
    <x-common.page-breadcrumb pageTitle="Add CTF Challenge" />

    <div class="mb-4 flex items-center justify-between">
        <a href="{{ route('admin.ctf-challenges', $competition) }}" class="flex items-center text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            ← Back to Challenges
        </a>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-2 gap-4">
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
                </div>
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

            <div>
                <label for="flag" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Flag <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    wire:model="flag"
                    id="flag"
                    placeholder="e.g., CTF{flag_here}"
                    class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-700 dark:bg-gray-800"
                />
                <p class="mt-1 text-xs text-gray-500">Will be stored as SHA256 hash</p>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="points" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Points <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        wire:model="points"
                        id="points"
                        min="1"
                        max="10000"
                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-700 dark:bg-gray-800"
                    />
                </div>
                <div>
                    <label for="difficulty" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Difficulty <span class="text-red-500">*</span>
                    </label>
                    <select
                        wire:model="difficulty"
                        id="difficulty"
                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-700 dark:bg-gray-800"
                    >
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="hard">Hard</option>
                        <option value="insane">Insane</option>
                    </select>
                </div>
                <div>
                    <label for="ctf_category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select
                        wire:model="ctf_category_id"
                        id="ctf_category_id"
                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-700 dark:bg-gray-800"
                    >
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center">
                <input
                    type="checkbox"
                    wire:model="is_active"
                    id="is_active"
                    class="h-4 w-4 rounded border-gray-300 text-emerald-500 focus:ring-emerald-500"
                />
                <label for="is_active" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                    Active
                </label>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="hint" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Hint
                    </label>
                    <input
                        type="text"
                        wire:model="hint"
                        id="hint"
                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-700 dark:bg-gray-800"
                    />
                </div>
                <div>
                    <label for="hint_cost" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Hint Cost (points)
                    </label>
                    <input
                        type="number"
                        wire:model="hint_cost"
                        id="hint_cost"
                        min="0"
                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-700 dark:bg-gray-800"
                    />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="max_attempts" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Max Attempts (0 = unlimited)
                    </label>
                    <input
                        type="number"
                        wire:model="max_attempts"
                        id="max_attempts"
                        min="0"
                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-700 dark:bg-gray-800"
                    />
                </div>
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Sort Order
                    </label>
                    <input
                        type="number"
                        wire:model="sort_order"
                        id="sort_order"
                        min="0"
                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-700 dark:bg-gray-800"
                    />
                </div>
            </div>

            <div>
                <label for="tags" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Tags (comma-separated)
                </label>
                <input
                    type="text"
                    wire:model="tags"
                    id="tags"
                    placeholder="sql,xss,auth"
                    class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-700 dark:bg-gray-800"
                />
            </div>

            <div class="flex justify-end">
                <a href="{{ route('admin.ctf-challenges', $competition) }}" class="mr-3 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                    Cancel
                </a>
                <button type="submit" class="rounded-md bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                    Create Challenge
                </button>
            </div>
        </form>
    </div>
</div>