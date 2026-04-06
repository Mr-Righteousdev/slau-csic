<div class="py-6">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Pending Approvals</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Review and approve or reject member applications</p>
            </div>
            <a href="{{ route('admin.users') }}" wire:navigate class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                View All Users
            </a>
        </div>

        <div class="mt-6">
            {{ $this->table }}
        </div>
    </div>
</div>