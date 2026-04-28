<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Certificate Eligibility</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Members eligible for certificates based on exam passes.
        </p>
    </div>

    <!-- Filters -->
    <div class="mb-4">
        <select
            wire:model.live="examFilter"
            class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
        >
            <option value="">All Exams</option>
            @foreach($exams as $exam)
                <option value="{{ $exam->id }}">{{ $exam->title }}</option>
            @endforeach
        </select>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <table class="w-full text-left text-sm text-gray-700 dark:text-gray-300">
            <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">User</th>
                    <th class="px-4 py-3">Exam</th>
                    <th class="px-4 py-3">Date Eligible</th>
                    <th class="px-4 py-3">Score</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($eligibilities as $eligibility)
                    <tr class="border-t border-gray-200 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/50">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $eligibility->user->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $eligibility->user->email }}</p>
                        </td>
                        <td class="px-4 py-3">{{ $eligibility->exam->title }}</td>
                        <td class="px-4 py-3 text-xs">{{ $eligibility->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-3">{{ $eligibility->examAttempt?->total_score ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if($eligibility->eligible)
                                <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">Eligible</span>
                            @else
                                <span class="rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-200">Revoked</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($eligibility->eligible)
                                <button
                                    wire:click="revoke({{ $eligibility->id }})"
                                    wire:confirm="Revoke certificate eligibility for {{ $eligibility->user->name }}?"
                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                >
                                    Revoke
                                </button>
                            @else
                                <span class="text-xs text-gray-500 dark:text-gray-400">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            No certificate eligibilities found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $eligibilities->links() }}
    </div>
</div>