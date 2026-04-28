<div>
    <x-common.page-breadcrumb pageTitle="CTF Categories" />

    <div class="mb-4 flex items-center justify-between">
        <div class="flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300">
                Dashboard
            </a>
            <a href="{{ route('admin.ctf-competitions') }}" class="rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300">
                Competitions
            </a>
            <a href="{{ route('admin.ctf-categories') }}" class="rounded-md bg-emerald-500 px-3 py-2 text-sm font-semibold text-white">
                Categories
            </a>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700 dark:bg-green-900/20 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-700 dark:bg-red-900/20 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="pb-3 text-left font-medium text-gray-500">Order</th>
                    <th class="pb-3 text-left font-medium text-gray-500">Color</th>
                    <th class="pb-3 text-left font-medium text-gray-500">Name</th>
                    <th class="pb-3 text-left font-medium text-gray-500">Slug</th>
                    <th class="pb-3 text-left font-medium text-gray-500">Challenges</th>
                    <th class="pb-3 text-right font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <td class="py-3">
                            <button wire:click="up({{ $category->id }})" class="mr-1 text-gray-400 hover:text-gray-600">↑</button>
                            <button wire:click="down({{ $category->id }})" class="mr-1 text-gray-400 hover:text-gray-600">↓</button>
                        </td>
                        <td class="py-3">
                            <span class="inline-block h-6 w-6 rounded" style="background-color: {{ $category->color }}"></span>
                        </td>
                        <td class="py-3 font-medium text-gray-900 dark:text-white">
                            {{ $category->name }}
                        </td>
                        <td class="py-3 text-gray-600 dark:text-gray-400">
                            {{ $category->slug }}
                        </td>
                        <td class="py-3 text-gray-600 dark:text-gray-400">
                            {{ $category->challenges_count ?? $category->challenges()->count() }}
                        </td>
                        <td class="py-3 text-right">
                            @if(!$category->challenges()->exists())
                                <button wire:click="delete({{ $category->id }})" class="text-red-600 hover:text-red-700" onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            @else
                                <span class="text-gray-400">Has challenges</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500">
                            No categories found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>