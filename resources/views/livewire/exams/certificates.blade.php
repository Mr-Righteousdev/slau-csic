<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Your Certificates</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Exams you've passed and are eligible for certificates.
        </p>
    </div>

    @if($eligibilities->isEmpty())
        <div class="rounded-lg border border-gray-200 bg-white p-8 text-center shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <svg class="mx-auto mb-4 h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-lg font-medium text-gray-900 dark:text-white">No certificates yet</p>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Pass an exam to become eligible for a certificate.
            </p>
            <a href="{{ route('exams.index') }}"
               class="mt-4 inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                Browse Exams
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($eligibilities as $eligibility)
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $eligibility->exam->title }}
                            </h3>
                            <div class="mt-2 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                                <p><span class="font-medium">Passed on:</span> {{ $eligibility->created_at->format('Y-m-d') }}</p>
                                <p><span class="font-medium">Score:</span>
                                    {{ $eligibility->examAttempt->total_score }}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">
                                Eligible
                            </span>
                            <button disabled
                                    class="rounded-lg border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-400 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-500"
                                    title="Certificate download coming in Phase 11">
                                Download (Coming Soon)
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>