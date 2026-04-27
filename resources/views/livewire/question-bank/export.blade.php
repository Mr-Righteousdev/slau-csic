<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Export Questions</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Download question bank in ExamShield format</p>
        </div>
        <a href="{{ route('question-bank.index') }}" wire:navigate
            class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="text-center py-8">
            <svg class="w-16 h-16 mx-auto text-indigo-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            <h2 class="text-xl font-medium text-gray-900 dark:text-white mb-2">Export Question Bank</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-6">
                Export all {{ $count }} questions to JSON format compatible with ExamShield.
            </p>
            <button wire:click="exportAll"
                class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download JSON
            </button>
        </div>
    </div>

    <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
        <h3 class="font-medium text-blue-900 dark:text-blue-200 mb-2">Export Format</h3>
        <p class="text-sm text-blue-700 dark:text-blue-300">
            The exported JSON follows the ExamShield import format, including question type, text, marks, code blocks, and options with correct answers.
        </p>
    </div>
</div>