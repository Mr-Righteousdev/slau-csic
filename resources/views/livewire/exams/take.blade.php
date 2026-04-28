<div x-data="examTimer()" x-init="startTimer()">
    @if (session()->has('error'))
        <div class="mb-4 rounded-lg bg-red-100 px-4 py-3 text-sm text-red-800 dark:bg-red-900 dark:text-red-200">
            {{ session('error') }}
        </div>
    @endif

    <!-- Timer and Progress -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $exam->title }}</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Question {{ $currentQuestionIndex + 1 }} of {{ count($questions) }}
            </p>
        </div>

        <div class="flex items-center gap-4">
            <div class="rounded-lg bg-gray-100 px-4 py-2 dark:bg-gray-700">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Time Left:</span>
                <span class="ml-2 font-mono text-lg font-bold text-red-600 dark:text-red-400" x-text="formatTime(timeRemaining)"></span>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="mb-6">
        <div class="h-2 w-full rounded-full bg-gray-200 dark:bg-gray-700">
            <div class="h-2 rounded-full bg-blue-600 transition-all duration-300" style="width: {{ $this->progress }}%"></div>
        </div>
        <p class="mt-1 text-right text-xs text-gray-600 dark:text-gray-400">{{ $this->progress }}% complete</p>
    </div>

    <!-- Current Question -->
    @if($currentQuestion)
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="mb-4">
                <span class="mb-2 inline-block rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                    {{ ucfirst(str_replace('_', ' ', $currentQuestion->question->type)) }}
                </span>
                <p class="text-lg font-medium text-gray-900 dark:text-white">
                    {!! $currentQuestion->question->question_text !!}
                </p>
                @if($currentQuestion->question->code_block)
                    <pre class="mt-3 overflow-x-auto rounded-lg bg-gray-100 p-4 text-sm dark:bg-gray-900"><code>{{ $currentQuestion->question->code_block }}</code></pre>
                @endif
            </div>

            <!-- Multiple Choice -->
            @if($currentQuestion->question->type === 'multiple_choice')
                <div class="space-y-3">
                    @foreach($currentQuestion->question->options as $option)
                        <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-3 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/50">
                            <input type="radio"
                                   name="question_{{ $currentQuestion->id }}"
                                   value="{{ $option->id }}"
                                   wire:model="answers.{{ $currentQuestion->id }}.option_id"
                                   class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                            >
                            <span class="text-sm text-gray-900 dark:text-white">{{ $option->option_text }}</span>
                        </label>
                    @endforeach
                </div>

            <!-- True/False -->
            @elseif($currentQuestion->question->type === 'true_false')
                <div class="space-y-3">
                    <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-3 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/50">
                        <input type="radio"
                               name="question_{{ $currentQuestion->id }}"
                               value="1"
                               wire:model="answers.{{ $currentQuestion->id }}.option_id"
                               class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                        >
                        <span class="text-sm text-gray-900 dark:text-white">True</span>
                    </label>
                    <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-3 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/50">
                        <input type="radio"
                               name="question_{{ $currentQuestion->id }}"
                               value="2"
                               wire:model="answers.{{ $currentQuestion->id }}.option_id"
                               class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                        >
                        <span class="text-sm text-gray-900 dark:text-white">False</span>
                    </label>
                </div>

            <!-- Short Answer -->
            @elseif($currentQuestion->question->type === 'short_answer')
                <textarea
                    wire:model="answers.{{ $currentQuestion->id }}.text"
                    rows="4"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    placeholder="Enter your answer..."
                ></textarea>

            <!-- Code Snippet -->
            @elseif($currentQuestion->question->type === 'code_snippet')
                <textarea
                    wire:model="answers.{{ $currentQuestion->id }}.text"
                    rows="8"
                    class="w-full rounded-lg border border-gray-300 bg-gray-900 px-4 py-2 font-mono text-sm text-green-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600"
                    placeholder="Write your code here..."
                ></textarea>
                @if($currentQuestion->question->code_language)
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Language: {{ $currentQuestion->question->code_language }}</p>
                @endif
            @endif
        </div>
    @endif

    <!-- Navigation Buttons -->
    <div class="mt-6 flex items-center justify-between">
        <button
            wire:click="prevQuestion"
            @disabled($currentQuestionIndex === 0)
            class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
        >
            Previous
        </button>

        @if($currentQuestionIndex < count($questions) - 1)
            <button
                wire:click="nextQuestion"
                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600"
            >
                Next
            </button>
        @else
            <button
                wire:click="submitExam"
                wire:confirm="Are you sure you want to submit the exam?"
                class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600"
            >
                Submit Exam
            </button>
        @endif
    </div>

    <!-- JavaScript Timer -->
    <script>
        function examTimer() {
            return {
                timeRemaining: {{ $timeRemaining }},
                timerInterval: null,

                startTimer() {
                    this.timerInterval = setInterval(() => {
                        if (this.timeRemaining > 0) {
                            this.timeRemaining--;
                            @this.set('timeRemaining', this.timeRemaining);

                            // Update server every 10 seconds
                            if (this.timeRemaining % 10 === 0) {
                                @this.call('saveCurrentAnswer');
                            }
                        } else {
                            clearInterval(this.timerInterval);
                            @this.call('submitExam');
                        }
                    }, 1000);
                },

                formatTime(seconds) {
                    const mins = Math.floor(seconds / 60);
                    const secs = seconds % 60;
                    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                }
            }
        }
    </script>
</div>
