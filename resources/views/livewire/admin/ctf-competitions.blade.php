<div>
    <x-common.page-breadcrumb pageTitle="CTF Competitions" />

    <div class="mb-4 flex items-center justify-between">
        <div class="flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300">
                Dashboard
            </a>
            <a href="{{ route('admin.ctf-competitions') }}" class="rounded-md bg-emerald-500 px-3 py-2 text-sm font-semibold text-white">
                Competitions
            </a>
            <a href="{{ route('admin.ctf-categories') }}" class="rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300">
                Categories
            </a>
            <a href="{{ route('admin.ctf-submissions') }}" class="rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300">
                Submissions
            </a>
        </div>
        <a href="{{ route('admin.ctf-competitions.create') }}" class="rounded-md bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
            + Create Competition
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
                placeholder="Search competitions..."
                class="rounded-md border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800"
            />
            <select wire:model.live="statusFilter" class="rounded-md border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800">
                <option value="all">All Status</option>
                <option value="draft">Draft</option>
                <option value="published">Published</option>
                <option value="archived">Archived</option>
            </select>
        </div>

        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="pb-3 text-left font-medium text-gray-500">Title</th>
                    <th class="pb-3 text-left font-medium text-gray-500">Status</th>
                    <th class="pb-3 text-left font-medium text-gray-500">Public</th>
                    <th class="pb-3 text-left font-medium text-gray-500">Challenges</th>
                    <th class="pb-3 text-left font-medium text-gray-500">Dates</th>
                    <th class="pb-3 text-right font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($competitions as $competition)
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <td class="py-3 font-medium text-gray-900 dark:text-white">
                            {{ $competition->title }}
                        </td>
                        <td class="py-3">
                            <span class="inline-flex rounded-md px-2 py-1 text-xs font-semibold
                                @if($competition->status === 'published') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                @elseif($competition->status === 'draft') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                                @else bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400 @endif">
                                {{ $competition->status }}
                            </span>
                        </td>
                        <td class="py-3">
                            {{ $competition->is_public ? 'Yes' : 'No' }}
                        </td>
                        <td class="py-3 text-gray-600 dark:text-gray-400">
                            {{ $competition->challenges_count ?? $competition->challenges()->count() }}
                        </td>
                        <td class="py-3 text-gray-600 dark:text-gray-400">
                            @if($competition->start_date)
                                {{ $competition->start_date->format('M d') }}
                                @if($competition->end_date)
                                    - {{ $competition->end_date->format('M d') }}
                                @endif
                            @else
                                <span class="text-gray-400">TBD</span>
                            @endif
                        </td>
                        <td class="py-3 text-right">
                            <a href="{{ route('admin.ctf-challenges', $competition) }}" class="mr-2 text-emerald-600 hover:text-emerald-700">
                                Challenges
                            </a>
                            <a href="{{ route('admin.ctf-competitions.edit', $competition) }}" class="mr-2 text-blue-600 hover:text-blue-700">
                                Edit
                            </a>
                            <button wire:click="delete({{ $competition->id }})" class="text-red-600 hover:text-red-700" onclick="return confirm('Are you sure?')">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500">
                            No competitions found. Create your first one!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $competitions->links() }}
        </div>
    </div>
</div>