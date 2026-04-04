<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Course Materials</h1>
        @can('content.create')
            <button wire:click="openUploadModal" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Upload Material
            </button>
        @endcan
    </div>

    <div class="mb-4 flex gap-4">
        <input type="text" wire:model.live="search" placeholder="Search materials..." 
            class="px-4 py-2 border border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-800 dark:text-white">
        
        <select wire:model.live="filterType" 
            class="px-4 py-2 border border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-800 dark:text-white">
            <option value="">All Types</option>
            <option value="video">Video</option>
            <option value="pdf">PDF</option>
            <option value="note">Note</option>
            <option value="link">Link</option>
        </select>
    </div>

    @if(count($materials) > 0)
        <div class="grid gap-4">
            @foreach($materials as $material)
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $material['title'] }}</h3>
                            @if($material['description'])
                                <p class="text-gray-600 dark:text-gray-300 mt-1">{{ $material['description'] }}</p>
                            @endif
                            <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                                <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded">
                                    {{ ucfirst($material['type']) }}
                                </span>
                                <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded">
                                    {{ ucfirst($material['visibility'] ?? 'all') }}
                                </span>
                                <span>{{ \Carbon\Carbon::parse($material['created_at'])->format('M j, Y') }}</span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            @if($material['url'])
                                <a href="{{ $material['url'] }}" target="_blank" class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900 rounded">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            @endif
                            @can('content.edit')
                                <button wire:click="editMaterial({{ $material['id'] }})" class="p-2 text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 rounded">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                            @endcan
                            @can('content.delete')
                                <button wire:click="deleteMaterial({{ $material['id'] }})" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900 rounded">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12 text-gray-500">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p>No course materials found.</p>
        </div>
    @endif

    @if($showUploadModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click.self="showUploadModal = false">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md">
                <h2 class="text-xl font-bold mb-4">{{ $editingMaterial ? 'Edit Material' : 'Upload Material' }}</h2>
                
                <form wire:submit="saveMaterial">
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
                        <label class="block text-sm font-medium mb-1">Type</label>
                        <select wire:model="type" class="w-full px-3 py-2 border rounded-lg dark:border-gray-600 dark:bg-gray-700">
                            <option value="note">Note</option>
                            <option value="video">Video</option>
                            <option value="pdf">PDF</option>
                            <option value="link">External Link</option>
                        </select>
                    </div>

                    @if($type === 'link')
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">External Link</label>
                            <input type="url" wire:model="external_link" class="w-full px-3 py-2 border rounded-lg dark:border-gray-600 dark:bg-gray-700">
                        </div>
                    @else
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">File</label>
                            <input type="file" wire:model="file" class="w-full px-3 py-2 border rounded-lg dark:border-gray-600 dark:bg-gray-700">
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Visibility</label>
                        <select wire:model="visibility" class="w-full px-3 py-2 border rounded-lg dark:border-gray-600 dark:bg-gray-700">
                            <option value="all">Everyone</option>
                            <option value="teachers">Teachers Only</option>
                            <option value="students">Students Only</option>
                        </select>
                    </div>

                    <div class="flex gap-2 justify-end">
                        <button type="button" wire:click="showUploadModal = false" class="px-4 py-2 border rounded-lg">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
