<div>
    <x-common.page-breadcrumb pageTitle="CTF Submissions" />

    <div class="mb-4 flex items-center justify-between">
        <div class="flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300">
                Dashboard
            </a>
            <a href="{{ route('admin.ctf-competitions') }}" class="rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300">
                Competitions
            </a>
            <a href="{{ route('admin.ctf-submissions') }}" class="rounded-md bg-emerald-500 px-3 py-2 text-sm font-semibold text-white">
                Submissions
            </a>
        </div>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="mb-4 flex gap-4">
            <input
                type="text"
                wire:model.live="userFilter"
                placeholder="Search by user name or student ID..."
                class="rounded-md border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800"
            />
            <select wire:model.live="competitionFilter" class="rounded-md border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800">
                <option value="">All Competitions</option>
                @foreach($competitions as $competition)
                    <option value="{{ $competition->id }}">{{ $competition->title }}</option>
                @endforeach
            </select>
            <select wire:model.live="correctFilter" class="rounded-md border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800">
                <option value="all">All</option>
                <option value="correct">Correct Only</option>
                <option value="incorrect">Incorrect Only</option>
            </select>
        </div>

        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="pb-3 text-left font-medium text-gray-500">#</th>
                    <th class="pb-3 text-left font-medium text-gray-500">User</th>
                    <th class="pb-3 text-left font-medium text-gray-500">Challenge</th>
                    <th class="pb-3 text-left font-medium text-gray-500">Flag Submitted</th>
                    <th class="pb-3 text-left font-medium text-gray-500">Status</th>
                    <th class="pb-3 text-left font-medium text-gray-500">Points</th>
                    <th class="pb-3 text-left font-medium text-gray-500">Attempt</th>
                    <th class="pb-3 text-left font-medium text-gray-500">IP Address</th>
                    <th class="pb-3 text-left font-medium text-gray-500">Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($submissions as $submission)
                    <tr class="border-b border-gray-100 dark:border-gray-800 {{ $submission->is_correct ? 'bg-green-50 dark:bg-green-900/10' : 'bg-red-50 dark:bg-red-900/10' }}">
                        <td class="py-3 text-gray-600 dark:text-gray-400">
                            {{ $loop->iteration }}
                        </td>
                        <td class="py-3 font-medium text-gray-900 dark:text-white">
                            {{ $submission->user?->name }}
                            <div class="text-xs text-gray-500">{{ $submission->user?->student_id }}</div>
                        </td>
                        <td class="py-3 text-gray-600 dark:text-gray-400">
                            {{ $submission->challenge?->title }}
                        </td>
                        <td class="py-3 font-mono text-gray-600 dark:text-gray-400">
                            {{ Str::limit($submission->submitted_flag, 20) }}
                        </td>
                        <td class="py-3">
                            <span class="inline-flex rounded-md px-2 py-1 text-xs font-semibold
                                @if($submission->is_correct) bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 @endif">
                                {{ $submission->is_correct ? 'Correct' : 'Incorrect' }}
                            </span>
                        </td>
                        <td class="py-3 text-gray-600 dark:text-gray-400">
                            {{ $submission->points_awarded }}
                        </td>
                        <td class="py-3 text-gray-600 dark:text-gray-400">
                            {{ $submission->attempt_number }}
                        </td>
                        <td class="py-3 text-gray-600 dark:text-gray-400">
                            {{ $submission->ip_address ?? '-' }}
                        </td>
                        <td class="py-3 text-gray-600 dark:text-gray-400">
                            {{ $submission->submitted_at?->format('M d H:i:s') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-8 text-center text-gray-500">
                            No submissions found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $submissions->links() }}
        </div>
    </div>
</div>