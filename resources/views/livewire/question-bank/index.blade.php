<div>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Questions</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage question bank questions</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('question-bank.create') }}" wire:navigate
                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Question
            </a>
            <a href="{{ route('question-bank.export') }}" wire:navigate
                class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export
            </a>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" wire:model.live="search" placeholder="Search questions..."
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <select wire:model.live="typeFilter"
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <option value="">All Types</option>
                <option value="mcq">MCQ</option>
                <option value="true_false">True/False</option>
                <option value="short_answer">Short Answer</option>
                <option value="code_snippet">Code Snippet</option>
            </select>
        </div>
    </div>

    <!-- Questions List -->
    <div class="space-y-4">
        @forelse ($questions as $question)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-1 text-xs font-medium rounded
                                @switch($question->type)
                                    @case('mcq') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @break
                                    @case('true_false') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @break
                                    @case('short_answer') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @break
                                    @case('code_snippet') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 @break
                                @endswitch">
                                {{ strtoupper($question->type) }}
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $question->marks }} mark{{ $question->marks > 1 ? 's' : '' }}</span>
                            @if($question->trashed())
                                <span class="px-2 py-1 text-xs font-medium rounded bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Deleted</span>
                            @endif
                        </div>
                        <p class="text-gray-900 dark:text-white mb-2">{{ Str::limit($question->question_text, 200) }}</p>
                        @if($question->options->count() > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($question->options as $option)
                                    <span class="px-2 py-1 text-xs rounded border
                                        {{ $option->is_correct ? 'bg-green-100 border-green-300 text-green-800 dark:bg-green-900 dark:border-green-700 dark:text-green-200' : 'bg-gray-100 border-gray-300 text-gray-700 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300' }}">
                                        {{ $option->option_text }}
                                        @if($option->is_correct)
                                            <span class="ml-1 text-green-600">✓</span>
                                        @endif
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="flex gap-2 ml-4">
                        @if($question->trashed())
                            <button wire:click="restore({{ $question->id }})" wire:loading.attr="disabled"
                                class="text-green-600 hover:text-green-800 p-2" title="Restore">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </button>
                            <button wire:click="delete({{ $question->id }})" wire:loading.attr="disabled"
                                class="text-red-600 hover:text-red-800 p-2" title="Permanently Delete">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        @else
                            <a href="{{ route('question-bank.edit', $question->id) }}" wire:navigate
                                class="text-indigo-600 hover:text-indigo-800 p-2" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L13.828 15H9.5v.5a2 2 0 11-4 0v-2a2 2 0 01.5-1.5l1.5-1.5L10.5 10.5z"/>
                                </svg>
                            </a>
                            <button wire:click="delete({{ $question->id }})" wire:loading.attr="disabled"
                                class="text-gray-600 hover:text-gray-800 p-2" title="Delete">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.007 2.817-1.543.218-2.225.877-2.225 1.628v.842c0-1.164 1.024-2.146 2.363-2.584.77-.253 1.467-.577 1.467-1.577v-.058c.928-.53 1.607-1.64 1.607-2.905 0-1.657-1.343-3-3-3-1.164 0-2.225.89-2.225 2.009v.021c0 .752.532 1.395 1.314 1.705.488.193.864.48.864 1.025v.061c0 .752-.532 1.395-1.314 1.705-.488.193-.864.48-.864 1.025v.056c0 1.164-1.024 2.146-2.363 2.584-.77.253-1.467.577-1.467 1.577v.058c0 .752.532 1.395 1.314 1.705.488.193.864.48.864 1.025v.061c0 .752-.532 1.395-1.314 1.705-.488.193-.864.48-.864 1.025v.061c0 .752.532 1.395 1.314 1.705.488.193.864.48.864 1.025 0 .545-.376 1.332-.864 1.73"/>
                </svg>
                <p class="text-gray-500 dark:text-gray-400">No questions found.</p>
                <a href="{{ route('question-bank.create') }}" wire:navigate class="text-indigo-600 hover:text-indigo-800 text-sm">Create your first question</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($questions->hasPages())
        <div class="mt-6">
            {{ $questions->links() }}
        </div>
    @endif

    @if(session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="fixed bottom-4 right-4 bg-gray-800 text-white px-4 py-2 rounded-lg shadow-lg">
            {{ session('message') }}
        </div>
    @endif
</div>