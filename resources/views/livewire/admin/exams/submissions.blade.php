<div>
    @if (session()->has('message'))
        <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-sm text-green-800 dark:bg-green-900 dark:text-green-200">
            {{ session('message') }}
        </div>
    @endif

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Exam Submissions</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Review and grade exam attempts from members.
        </p>
    </div>

    <!-- Filters -->
    <div class="mb-4 flex flex-col gap-4 sm:flex-row">
        <input
            wire:model.live.debounce.300ms="search"
            type="text"
            placeholder="Search by user name or email..."
            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400"
        >

        <select
            wire:model.live="examFilter"
            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white sm:w-48"
        >
            <option value="">All Exams</option>
            @foreach($exams as $exam)
                <option value="{{ $exam->id }}">{{ $exam->title }}</option>
            @endforeach
        </select>

        <select
            wire:model.live="statusFilter"
            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white sm:w-40"
        >
            <option value="">All Status</option>
            <option value="passed">Passed</option>
            <option value="failed">Failed</option>
            <option value="pending">Pending Grading</option>
        </select>
    </div>

    <!-- Submissions Table -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <table class="w-full text-left text-sm text-gray-700 dark:text-gray-300">
            <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">User</th>
                    <th class="px-4 py-3">Exam</th>
                    <th class="px-4 py-3">Started</th>
                    <th class="px-4 py-3">Submitted</th>
                    <th class="px-4 py-3">Score</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($submissions as $submission)
                    <tr class="border-t border-gray-200 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/50">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $submission->user->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $submission->user->email }}</p>
                        </td>
                        <td class="px-4 py-3">{{ $submission->exam->title }}</td>
                        <td class="px-4 py-3 text-xs">{{ $submission->started_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3 text-xs">{{ $submission->submitted_at?->format('Y-m-d H:i') ?? '—' }}</td>
                        <td class="px-4 py-3">
                            {{ $submission->total_score }}
                        </td>
                        <td class="px-4 py-3">
                            @if($submission->passed === true)
                                <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">Passed</span>
                            @elseif($submission->passed === false)
                                <span class="rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-200">Failed</span>
                            @else
                                <span class="rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Pending</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.exams.grading', $submission) }}"
                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                Grade
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            No submissions found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $submissions->links() }}
    </div>
</div>