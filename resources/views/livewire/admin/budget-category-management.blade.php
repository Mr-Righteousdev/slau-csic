<div>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Budget Categories</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Manage income and expense categories</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Summary Stats -->
                    <div class="text-right">
                        <div class="text-sm text-gray-600 dark:text-gray-400">Total Categories</div>
                        <div class="text-lg font-bold text-blue-600">
                            {{ \App\Models\BudgetCategory::count() }}
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-600 dark:text-gray-400">Active Categories</div>
                        <div class="text-lg font-bold text-green-600">
                            {{ \App\Models\BudgetCategory::where('is_active', true)->count() }}
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-600 dark:text-gray-400">Total Budget</div>
                        <div class="text-lg font-bold text-purple-600">
                            ${{ number_format(\App\Models\BudgetCategory::where('is_active', true)->sum('allocated_amount'), 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Budget Categories Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
            {{ $this->table }}
        </div>
    </div>
</div>