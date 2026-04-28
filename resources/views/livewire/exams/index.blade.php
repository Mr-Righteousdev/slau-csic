<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Available Exams</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Browse and take published exams. Pass an exam to become eligible for certificates.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($exams as $exam)
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $exam->title ?? 'Untitled Exam' }}
                </h3>

                @if($exam->description)
                    <p class="mb-4 line-clamp-2 text-sm text-gray-600 dark:text-gray-400">
                        {{ Str::limit(strip_tags($exam->description), 100) }}
                    </p>
                @endif

                <div class="mb-4 space-y-2 text-sm text-gray-600 dark:text-gray-400">
                    <div class="flex items-center gap-2">
                        <span class="font-medium">Duration:</span>
                        <span>{{ $exam->duration_minutes }} minutes</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-medium">Passing Score:</span>
                        <span>{{ $exam->passing_score }}%</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-medium">Questions:</span>
                        <span>{{ $exam->questions->count() }}</span>
                    </div>
                </div>

                @if($exam->attempts->isNotEmpty() && $exam->attempts->where('user_id', auth()->id())->whereNotNull('submitted_at')->first())
                    @php
                        $attempt = $exam->attempts->where('user_id', auth()->id())->whereNotNull('submitted_at')->first();
                    @endphp
                    <div class="mb-4 rounded-lg bg-green-50 p-3 dark:bg-green-900/20">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">
                            Already Attempted
                        </p>
                        <p class="text-xs text-green-600 dark:text-green-300">
                            Score: {{ $attempt->total_score }} | {{ $attempt->passed ? 'Passed' : 'Failed' }}
                        </p>
                        <a href="{{ route('exams.result', $attempt) }}"
                           class="mt-2 inline-block text-xs text-blue-600 hover:underline dark:text-blue-400">
                            View Result
                        </a>
                    </div>
                    <a href="{{ route('exams.result', $attempt) }}"
                       class="inline-flex w-full items-center justify-center rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        View Result
                    </a>
                @else
                    <a href="{{ route('exams.take', $exam) }}"
                       class="inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                        Start Exam
                    </a>
                @endif
            </div>
        @empty
            <div class="col-span-full rounded-lg border border-gray-200 bg-white p-8 text-center dark:border-gray-700 dark:bg-gray-800">
                <p class="text-gray-500 dark:text-gray-400">No published exams available yet.</p>
            </div>
        @endforelse
    </div>
</div>
