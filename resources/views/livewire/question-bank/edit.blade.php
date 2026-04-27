<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Question</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Update question details</p>
        </div>
        <a href="{{ route('question-bank.index') }}" wire:navigate
            class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </a>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Question Type -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Question Details</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Question Type</label>
                    <select wire:model="type" wire:change="updatedType($event.target.value)"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                        <option value="mcq">Multiple Choice (MCQ)</option>
                        <option value="true_false">True/False</option>
                        <option value="short_answer">Short Answer</option>
                        <option value="code_snippet">Code Snippet</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Marks</label>
                    <input type="number" wire:model="marks" min="1" 
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Question Text</label>
                <textarea wire:model="question_text" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                    placeholder="Enter your question..."></textarea>
            @error('question_text')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Explanation (Optional)</label>
                <textarea wire:model="explanation" rows="2"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                    placeholder="Explain the correct answer..."></textarea>
            </div>
        </div>

        <!-- Options (for MCQ, True/False, Code Snippet) -->
        @if(in_array($type, ['mcq', 'true_false', 'code_snippet']))
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Options</h2>
                @if($type === 'mcq' && count($options) < 6)
                    <button type="button" wire:click="addOption"
                        class="text-sm text-indigo-600 hover:text-indigo-800">
                        + Add Option
                    </button>
                @endif
            </div>

            @if($type === 'true_false')
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Select the correct answer:</p>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" wire:model="selectedCorrect" value="0" class="w-4 h-4 text-indigo-600">
                        <span class="text-gray-700 dark:text-gray-300">True</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" wire:model="selectedCorrect" value="1" class="w-4 h-4 text-indigo-600">
                        <span class="text-gray-700 dark:text-gray-300">False</span>
                    </label>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($options as $index => $option)
                        <div class="flex items-center gap-3">
                            <input type="checkbox" wire:model="options.{{ $index }}.is_correct"
                                class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500">
                            <input type="text" wire:model="options.{{ $index }}.option_text"
                                class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                                placeholder="Option {{ $index + 1 }}">
                            @if($type === 'mcq' && count($options) > 2)
                                <button type="button" wire:click="removeOption({{ $index }})"
                                    class="text-gray-400 hover:text-red-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        @endif

        <!-- Code Snippet Fields -->
        @if($type === 'code_snippet')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Code Snippet</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Programming Language</label>
                    <select wire:model="code_language"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select language</option>
                        <option value="python">Python</option>
                        <option value="javascript">JavaScript</option>
                        <option value="java">Java</option>
                        <option value="cpp">C++</option>
                        <option value="c">C</option>
                        <option value="php">PHP</option>
                        <option value="ruby">Ruby</option>
                        <option value="go">Go</option>
                        <option value="rust">Rust</option>
                        <option value="sql">SQL</option>
                        <option value="bash">Bash</option>
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Code Block</label>
                <textarea wire:model="code_block" rows="6"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-900 text-gray-100 font-mono text-sm focus:ring-2 focus:ring-indigo-500"
                    placeholder="// Enter code here..."></textarea>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="flex gap-3">
            <button type="submit"
                class="flex-1 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                Update Question
            </button>
            <a href="{{ route('question-bank.index') }}" wire:navigate
                class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>