<div>
    <x-common.page-breadcrumb pageTitle="CTF Challenges: {{ $competition->title }}" />

    <div class="mb-4 flex items-center justify-between">
        <div class="flex gap-2">
            <a href="{{ route('admin.ctf-competitions') }}" class="rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300">
                Competitions
            </a>
            <a href="{{ route('admin.ctf-challenges', $competition) }}" class="rounded-md bg-emerald-500 px-3 py-2 text-sm font-semibold text-white">
                Challenges
            </a>
            <a href="{{ route('admin.ctf-categories') }}" class="rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300">
                Categories
            </a>
        </div>
        <a href="{{ route('admin.ctf-challenges.create', $competition) }}" class="rounded-md bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
            + Add Challenge
        </a>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700 dark:bg-green-900/20 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="mb-4 flex gap-4">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Search challenges..."
                class="rounded-md border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800"
            />
        </div>

        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="pb-3 text-left font-medium text-gray-500">Title</th>
                    <th class="pb-3 text-left font-medium text-gray-500">Category</th>
                    <th class="pb-3 text-left font-medium text-gray-500">Difficulty</th>
                    <th class="pb-3 text-left font-medium text-gray-500">Points</th>
                    <th class="pb-3 text-left font-medium text-gray-500">Active</th>
                    <th class="pb-3 text-left font-medium text-gray-500">Solves</th>
                    <th class="pb-3 text-right font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($challenges as $challenge)
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <td class="py-3 font-medium text-gray-900 dark:text-white">
                            {{ $challenge->title }}
                        </td>
                        <td class="py-3">
                            <span class="inline-flex rounded-md px-2 py-1 text-xs font-medium" style="background-color: {{ $challenge->category?->color ?? '#6b7280' }}20; color: {{ $challenge->category?->color ?? '#6b7280' }}">
                                {{ $challenge->category?->name ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td class="py-3">
                            <span class="inline-flex rounded-md px-2 py-1 text-xs font-semibold
                                @if($challenge->difficulty === 'easy') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                @elseif($challenge->difficulty === 'medium') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                                @elseif($challenge->difficulty === 'hard') bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400
                                @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 @endif">
                                {{ $challenge->difficulty }}
                            </span>
                        </td>
                        <td class="py-3 text-gray-600 dark:text-gray-400">
                            {{ $challenge->points }}
                        </td>
                        <td class="py-3">
                            <button wire:click="toggleActive({{ $challenge->id }})" class="text-sm">
                                @if($challenge->is_active)
                                    <span class="text-green-600">Yes</span>
                                @else
                                    <span class="text-gray-400">No</span>
                                @endif
                            </button>
                        </td>
                        <td class="py-3 text-gray-600 dark:text-gray-400">
                            {{ $challenge->submissions()->where('is_correct', true)->count() }}
                        </td>
                        <td class="py-3 text-right">
                            <a href="{{ route('admin.ctf-challenges.edit', ['competition' => $competition, 'challenge' => $challenge]) }}" class="mr-2 text-blue-600 hover:text-blue-700">
                                Edit
                            </a>
                            <button wire:click="delete({{ $challenge->id }})" class="text-red-600 hover:text-red-700" onclick="return confirm('Are you sure?')">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-500">
                            No challenges yet. Add your first one!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $challenges->links() }}
        </div>
    </div>
</div>