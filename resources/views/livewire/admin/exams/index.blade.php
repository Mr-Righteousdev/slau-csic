<div>
    @if (session()->has('message'))
        <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-sm text-green-800 dark:bg-green-900 dark:text-green-200">
            {{ session('message') }}
        </div>
    @endif

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Exams</h1>
        <a href="{{ route('admin.exams.create') }}"
           class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">
            + New Exam
        </a>
    </div>

    <!-- Filters -->
    <div class="mb-4 flex flex-col gap-4 sm:flex-row">
        <input
            wire:model.live="search"
            type="text"
            placeholder="Search exams..."
            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400"
        >

        <select
            wire:model.live="statusFilter"
            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white sm:w-48"
        >
            <option value="">All Status</option>
            <option value="draft">Draft</option>
            <option value="published">Published</option>
            <option value="archived">Archived</option>
        </select>
    </div>

    <!-- Exams Table -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <table class="w-full text-left text-sm text-gray-700 dark:text-gray-300">
            <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Duration</th>
                    <th class="px-4 py-3">Passing Score</th>
                    <th class="px-4 py-3">Questions</th>
                    <th class="px-4 py-3">Total Marks</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($exams as $exam)
                    <tr class="border-t border-gray-200 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/50">
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                            {{ $exam->title ?? 'Untitled' }}
                        </td>
                        <td class="px-4 py-3">
                            @switch($exam->status)
                                @case('published')
                                    <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">Published</span>
                                    @break
                                @case('draft')
                                    <span class="rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Draft</span>
                                    @break
                                @case('archived')
                                    <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-800 dark:bg-gray-700 dark:text-gray-300">Archived</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-4 py-3">{{ $exam->duration_minutes }} min</td>
                        <td class="px-4 py-3">{{ $exam->passing_score }}%</td>
                        <td class="px-4 py-3">{{ $exam->questions_count }}</td>
                        <td class="px-4 py-3">{{ $exam->total_marks }}</td>
                        <td class="space-x-2 px-4 py-3">
                            <button
                                wire:click="toggleStatus({{ $exam->id }})"
                                wire:confirm="Toggle status for this exam?"
                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                            >
                                Toggle Status
                            </button>
                            <a href="{{ route('admin.exams.edit', $exam) }}"
                               class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300"
                            >
                                Edit
                            </a>
                            <button
                                wire:click="delete({{ $exam->id }})"
                                wire:confirm="Are you sure you want to delete this exam?"
                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            No exams found. <a href="{{ route('admin.exams.create') }}" class="text-blue-600 hover:underline">Create one now</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $exams->links() }}
    </div>
</div>
