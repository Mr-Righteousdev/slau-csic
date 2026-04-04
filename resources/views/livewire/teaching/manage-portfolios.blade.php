<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Student Portfolios</h1>
        @can('portfolio.manage')
            <button wire:click="openModal" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Add Portfolio
            </button>
        @endcan
    </div>

    @if(count($portfolios) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($portfolios as $portfolio)
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-start justify-between mb-2">
                        <span class="px-2 py-1 text-xs font-medium rounded 
                            @switch($portfolio['category'])
                                @case('project') bg-blue-100 text-blue-800 @break
                                @case('achievement') bg-yellow-100 text-yellow-800 @break
                                @case('artwork') bg-purple-100 text-purple-800 @break
                                @case('research') bg-green-100 text-green-800 @break
                                @default bg-gray-100 text-gray-800
                            @endswitch">
                            {{ ucfirst($portfolio['category']) }}
                        </span>
                        <span class="px-2 py-1 text-xs rounded {{ $portfolio['is_published'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $portfolio['is_published'] ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                    
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ $portfolio['title'] }}</h3>
                    
                    @if($portfolio['description'])
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-2">{{ Str::limit($portfolio['description'], 100) }}</p>
                    @endif
                    
                    <p class="text-sm text-gray-500 mb-3">Student: {{ $portfolio['student']['name'] ?? 'Unknown' }}</p>
                    
                    <div class="flex items-center gap-2">
                        @if($portfolio['external_link'])
                            <a href="{{ $portfolio['external_link'] }}" target="_blank" class="p-2 text-blue-600 hover:bg-blue-50 rounded">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        @endif
                        
                        @can('portfolio.manage')
                            <button wire:click="togglePublish({{ $portfolio['id'] }})" 
                                class="p-2 text-gray-600 hover:bg-gray-50 rounded"
                                title="{{ $portfolio['is_published'] ? 'Unpublish' : 'Publish' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($portfolio['is_published'])
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    @endif
                                </svg>
                            </button>
                            
                            <button wire:click="editPortfolio({{ $portfolio['id'] }})" class="p-2 text-gray-600 hover:bg-gray-50 rounded">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            
                            <button wire:click="deletePortfolio({{ $portfolio['id'] }})" class="p-2 text-red-600 hover:bg-red-50 rounded">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        @endcan
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12 text-gray-500">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <p>No portfolios found.</p>
        </div>
    @endif

    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click.self="showModal = false">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md">
                <h2 class="text-xl font-bold mb-4">{{ $editingPortfolio ? 'Edit Portfolio' : 'Add Portfolio' }}</h2>
                
                <form wire:submit="savePortfolio">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Student</label>
                        <select wire:model="student_id" class="w-full px-3 py-2 border rounded-lg dark:border-gray-600 dark:bg-gray-700">
                            <option value="">Select Student</option>
                            @foreach($this->students as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('student_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Title</label>
                        <input type="text" wire:model="title" class="w-full px-3 py-2 border rounded-lg dark:border-gray-600 dark:bg-gray-700">
                        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea wire:model="description" rows="3" class="w-full px-3 py-2 border rounded-lg dark:border-gray-600 dark:bg-gray-700"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Category</label>
                        <select wire:model="category" class="w-full px-3 py-2 border rounded-lg dark:border-gray-600 dark:bg-gray-700">
                            @foreach(StudentPortfolio::categories() as $key => $label)
                                <option value="{{ $key }}">{{ ucfirst($label) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">External Link (optional)</label>
                        <input type="url" wire:model="external_link" class="w-full px-3 py-2 border rounded-lg dark:border-gray-600 dark:bg-gray-700">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">File (optional)</label>
                        <input type="file" wire:model="file" class="w-full px-3 py-2 border rounded-lg dark:border-gray-600 dark:bg-gray-700">
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" wire:model="is_published" class="rounded border-gray-300">
                            <span class="text-sm">Publish immediately</span>
                        </label>
                    </div>

                    <div class="flex gap-2 justify-end">
                        <button type="button" wire:click="showModal = false" class="px-4 py-2 border rounded-lg">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
