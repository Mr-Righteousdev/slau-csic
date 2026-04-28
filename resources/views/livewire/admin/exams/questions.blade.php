<div>
    @if (session()->has('message'))
        <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-sm text-green-800 dark:bg-green-900 dark:text-green-200">
            {{ session('message') }}
        </div>
    @endif

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $exam->title }}</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Total Marks: <span class="font-semibold">{{ $totalMarks }}</span>
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.exams.add-question', $exam) }}"
               class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                + Add Questions
            </a>
            <a href="{{ route('admin.exams.index') }}"
               class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                Back to Exams
            </a>
        </div>
    </div>

    <!-- Questions Table -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <table class="w-full text-left text-sm text-gray-700 dark:text-gray-300">
            <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">Question</th>
                    <th class="px-4 py-3">Marks</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($questions as $question)
                    <tr class="border-t border-gray-200 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/50">
                        <td class="px-4 py-3 font-medium">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ ucfirst(str_replace('_', ' ', $question->question->type)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="max-w-md truncate">{{ strip_tags($question->question->question_text) }}</div>
                        </td>
                        <td class="px-4 py-3">{{ $question->effective_marks }}</td>
                        <td class="px-4 py-3">
                            <button
                                wire:click="removeQuestion({{ $question->id }})"
                                wire:confirm="Remove this question from the exam?"
                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                            >
                                Remove
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            No questions added yet.
                            <a href="{{ route('admin.exams.add-question', $exam) }}" class="text-blue-600 hover:underline">Add questions now</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
