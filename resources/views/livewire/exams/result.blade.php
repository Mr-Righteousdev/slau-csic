<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Exam Result</h1>
    </div>

    <!-- Score Display -->
    <div class="mb-6 rounded-lg {{ $passed ? 'bg-green-50 dark:bg-green-900/20' : 'bg-red-50 dark:bg-red-900/20' }} p-6">
        <div class="text-center">
            <p class="mb-2 text-sm font-medium {{ $passed ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }}">
                {{ $passed ? 'Congratulations!' : 'Keep Trying!' }}
            </p>
            <p class="text-5xl font-bold {{ $passed ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                {{ $score }}
            </p>
            <p class="mt-1 text-sm {{ $passed ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }}">
                {{ $passed ? 'Passed' : 'Failed' }} (Passing Score: {{ $attempt->exam->passing_score }}%)
            </p>
        </div>
    </div>

    <!-- Exam Details -->
    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Exam Details</h2>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Exam</p>
                <p class="font-medium text-gray-900 dark:text-white">{{ $attempt->exam->title }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Submitted At</p>
                <p class="font-medium text-gray-900 dark:text-white">{{ $attempt->submitted_at->format('Y-m-d H:i') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Duration Used</p>
                <p class="font-medium text-gray-900 dark:text-white">
                    {{ $attempt->exam->duration_minutes * 60 - $attempt->time_remaining_seconds }} seconds
                </p>
            </div>
        </div>
    </div>

    <!-- Question Breakdown -->
    <div class="mb-6">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Question Breakdown</h2>
            <button
                wire:click="toggleAnswers"
                class="text-sm text-blue-600 hover:underline dark:text-blue-400"
            >
                {{ $showAnswers ? 'Hide Answers' : 'Show Answers' }}
            </button>
        </div>

        <div class="space-y-4">
            @foreach($answers as $answer)
                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="mb-2 flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                            Question {{ $loop->iteration }}
                        </span>
                        <span class="rounded-full px-2 py-1 text-xs font-medium
                            {{ $answer->is_correct ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                            {{ $answer->is_correct ? 'Correct' : 'Incorrect' }}
                        </span>
                    </div>

                    @if($showAnswers)
                        <div class="mt-3 space-y-2 text-sm">
                            <div>
                                <p class="font-medium text-gray-700 dark:text-gray-300">Question:</p>
                                <p class="text-gray-900 dark:text-white">{!! $answer->examQuestion->question->question_text !!}</p>
                            </div>
                            <div>
                                <p class="font-medium text-gray-700 dark:text-gray-300">Your Answer:</p>
                                <p class="text-gray-900 dark:text-white">
                                    @if($answer->answer_text)
                                        {{ $answer->answer_text }}
                                    @elseif($answer->selectedOption)
                                        {{ $answer->selectedOption->option_text }}
                                    @else
                                        Not answered
                                    @endif
                                </p>
                            </div>
                            @if($answer->examQuestion->question->explanation)
                                <div>
                                    <p class="font-medium text-gray-700 dark:text-gray-300">Explanation:</p>
                                    <p class="text-gray-600 dark:text-gray-400">{{ $answer->examQuestion->question->explanation }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="mt-2 text-xs text-gray-600 dark:text-gray-400">
                        Marks Awarded: {{ $answer->marks_awarded }} / {{ $answer->examQuestion->effective_marks }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Actions -->
    <div class="flex gap-4">
        <a href="{{ route('exams.index') }}"
           class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
            Back to Exams
        </a>
        @if($passed)
            <a href="{{ route('exams.certificates') }}"
               class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                View Certificates
            </a>
        @endif
    </div>
</div>
