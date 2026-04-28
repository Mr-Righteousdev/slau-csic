<div>
    @if (session()->has('message'))
        <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-sm text-green-800 dark:bg-green-900 dark:text-green-200">
            {{ session('message') }}
        </div>
    @endif

    <!-- Header -->
    <div class="mb-6 flex items-start justify-between">
        <div>
            <div class="mb-1 text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('admin.exams.index') }}" class="hover:underline">Exams</a>
                / Submissions
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $attempt->exam->title }}</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Grading attempt by <strong>{{ $attempt->user->name }}</strong>
                ({{ $attempt->user->email }})
            </p>
        </div>
        <div class="text-right">
            <div class="text-3xl font-bold {{ $attempt->passed ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                {{ $attempt->total_score }}
            </div>
            <p class="text-sm {{ $attempt->passed ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }}">
                {{ $attempt->passed ? 'Passed' : 'Failed' }}
            </p>
        </div>
    </div>

    <!-- Summary -->
    <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
            <p class="text-xs text-gray-500 dark:text-gray-400">Started</p>
            <p class="font-medium text-gray-900 dark:text-white">{{ $attempt->started_at->format('Y-m-d H:i') }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
            <p class="text-xs text-gray-500 dark:text-gray-400">Submitted</p>
            <p class="font-medium text-gray-900 dark:text-white">{{ $attempt->submitted_at?->format('Y-m-d H:i') ?? '—' }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
            <p class="text-xs text-gray-500 dark:text-gray-400">Passing Score</p>
            <p class="font-medium text-gray-900 dark:text-white">{{ $attempt->exam->passing_score }}%</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
            <p class="text-xs text-gray-500 dark:text-gray-400">AI Grading</p>
            <p class="font-medium text-gray-900 dark:text-white">
                {{ config('exam.grading.ai_enabled') ? 'Enabled' : 'Disabled' }}
            </p>
        </div>
    </div>

    <!-- Question-by-Question Grading -->
    <div class="mb-6 space-y-4">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Question Breakdown</h2>

        @foreach($attempt->answers as $answer)
            @php
                $question = $answer->examQuestion?->question;
                $maxMarks = $answer->examQuestion?->effective_marks ?? 0;
            @endphp
            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="mb-3 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                            Q{{ $loop->iteration }}
                        </span>
                        @if($question)
                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                {{ str_replace('_', ' ', $question->type) }}
                            </span>
                        @endif
                    </div>
                    <span class="text-sm font-medium">
                        {{ $answer->marks_awarded ?? 0 }} / {{ $maxMarks }} pts
                    </span>
                </div>

                @if($question)
                    <p class="mb-3 text-sm text-gray-800 dark:text-gray-200">
                        {!! $question->question_text !!}
                    </p>
                @endif

                <!-- Student's Answer -->
                <div class="mb-3 rounded bg-gray-50 p-3 dark:bg-gray-700/50">
                    <p class="mb-1 text-xs font-medium text-gray-600 dark:text-gray-400">Student's Answer:</p>
                    @if($answer->selected_option_id && $answer->selectedOption)
                        <p class="text-sm text-gray-900 dark:text-white">{{ $answer->selectedOption->option_text }}</p>
                    @elseif($answer->answer_text)
                        <p class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $answer->answer_text }}</p>
                    @else
                        <p class="text-sm italic text-gray-500">Not answered</p>
                    @endif
                </div>

                <!-- Correct Answer -->
                @if($question && $question->options->where('is_correct', true)->first())
                    <div class="mb-3 rounded bg-green-50 p-3 dark:bg-green-900/20">
                        <p class="mb-1 text-xs font-medium text-green-700 dark:text-green-300">Correct Answer:</p>
                        <p class="text-sm text-green-900 dark:text-green-200">
                            {{ $question->options->where('is_correct', true)->first()->option_text }}
                        </p>
                    </div>
                @endif

                <!-- Manual Override -->
                <div class="flex items-end gap-3 border-t border-gray-200 pt-3 dark:border-gray-700">
                    <div>
                        <label class="mb-1 block text-xs text-gray-600 dark:text-gray-400">Marks</label>
                        <input
                            type="number"
                            min="0"
                            max="{{ $maxMarks }}"
                            wire:change="updateManualGrade({{ $answer->id }}, $event.target.value, {{ $answer->is_correct ? 'true' : 'false' }})"
                            value="{{ $this->manualGrades[$answer->id]['marks_awarded'] ?? 0 }}"
                            class="w-20 rounded-lg border border-gray-300 bg-white px-2 py-1 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        >
                    </div>
                    <div>
                        <label class="mb-1 block text-xs text-gray-600 dark:text-gray-400">Correct</label>
                        <select
                            wire:change="updateManualGrade({{ $answer->id }}, {{ $this->manualGrades[$answer->id]['marks_awarded'] ?? 0 }}, $event.target.value === '1')"
                            class="rounded-lg border border-gray-300 bg-white px-2 py-1 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="1" {{ ($answer->is_correct === true) ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ ($answer->is_correct === false) ? 'selected' : '' }}>No</option>
                            <option value="" {{ is_null($answer->is_correct) ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <span class="ml-auto text-xs">
                        @if($answer->is_correct === true)
                            <span class="rounded bg-green-100 px-2 py-0.5 text-green-800 dark:bg-green-900 dark:text-green-200">Correct</span>
                        @elseif($answer->is_correct === false)
                            <span class="rounded bg-red-100 px-2 py-0.5 text-red-800 dark:bg-red-900 dark:text-red-200">Incorrect</span>
                        @else
                            <span class="rounded bg-yellow-100 px-2 py-0.5 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Pending</span>
                        @endif
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Admin Notes -->
    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
        <h3 class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">Admin Notes</h3>
        <textarea
            wire:model.live="adminNotes"
            rows="3"
            class="mb-3 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white"
            placeholder="Add notes about this submission..."
        ></textarea>
        <button
            wire:click="saveNotes"
            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600"
        >
            Save Notes
        </button>
    </div>

    <!-- Actions -->
    <div class="flex gap-3">
        <a href="{{ route('admin.exams.submissions') }}"
           class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
            Back to Submissions
        </a>
        @if(config('exam.grading.ai_enabled'))
            <button
                wire:click="regrade"
                class="rounded-lg bg-purple-600 px-4 py-2 text-sm font-medium text-white hover:bg-purple-700 dark:bg-purple-500 dark:hover:bg-purple-600">
                Re-grade All (AI)
            </button>
        @endif
    </div>
</div>