<div>
    @if (session()->has('message'))
        <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-sm text-green-800 dark:bg-green-900 dark:text-green-200">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 rounded-lg bg-red-100 px-4 py-3 text-sm text-red-800 dark:bg-red-900 dark:text-red-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add Questions to {{ $exam->title }}</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Select questions from the Question Bank to add to this exam.
            </p>
        </div>
        <a href="{{ route('admin.exams.questions', $exam) }}"
           class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
            Back to Questions
        </a>
    </div>

    <!-- Filters -->
    <div class="mb-4 flex flex-col gap-4 sm:flex-row">
        <input
            wire:model.live="search"
            type="text"
            placeholder="Search questions..."
            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400"
        >

        <select
            wire:model.live="typeFilter"
            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white sm:w-48"
        >
            <option value="">All Types</option>
            <option value="multiple_choice">Multiple Choice</option>
            <option value="true_false">True/False</option>
            <option value="short_answer">Short Answer</option>
            <option value="code_snippet">Code Snippet</option>
        </select>

        <button
            wire:click="addSelected"
            class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50 dark:bg-blue-500 dark:hover:bg-blue-600"
            @disabled(empty($selectedQuestions))
        >
            Add Selected ({{ count($selectedQuestions) }})
        </button>
    </div>

    <!-- Questions List -->
    <div class="space-y-4">
        @forelse ($questions as $question)
            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800
                 @if(in_array($question->id, $selectedQuestions)) ring-2 ring-blue-500 @endif"
            >
                <div class="flex items-start gap-4">
                    <input
                        type="checkbox"
                        wire:click="toggleSelect({{ $question->id }})"
                        @checked(in_array($question->id, $selectedQuestions))
                        class="mt-1 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                    >

                    <div class="flex-1">
                        <div class="mb-2 flex items-center gap-2">
                            <span class="rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $question->marks }} marks</span>
                        </div>
                        <p class="text-sm text-gray-900 dark:text-white">{!! $question->question_text !!}</p>

                        @if(in_array($question->id, $selectedQuestions))
                            <div class="mt-3">
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                    Custom Marks (optional, default: {{ $question->marks }})
                                </label>
                                <input
                                    wire:model="customMarks.{{ $question->id }}"
                                    type="number"
                                    min="0"
                                    placeholder="Default: {{ $question->marks }}"
                                    class="mt-1 w-32 rounded-lg border border-gray-300 bg-white px-3 py-1 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                >
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-lg border border-gray-200 bg-white p-8 text-center text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                No available questions found. All questions from the Question Bank have been added to this exam.
            </div>
        @endforelse
    </div>
</div>
