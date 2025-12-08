<div>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Financial Transactions</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Manage club income and expenses</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Summary Stats -->
                    <div class="text-right">
                        <div class="text-sm text-gray-600 dark:text-gray-400">Total Income</div>
                        <div class="text-lg font-bold text-green-600">
                            ${{ number_format(\App\Models\Transaction::where('type', 'income')->where('status', 'approved')->sum('amount'), 2) }}
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-600 dark:text-gray-400">Total Expenses</div>
                        <div class="text-lg font-bold text-red-600">
                            ${{ number_format(\App\Models\Transaction::where('type', 'expense')->where('status', 'approved')->sum('amount'), 2) }}
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-600 dark:text-gray-400">Balance</div>
                        <div class="text-lg font-bold text-blue-600">
                            ${{ number_format(
                                \App\Models\Transaction::where('type', 'income')->where('status', 'approved')->sum('amount') -
                                \App\Models\Transaction::where('type', 'expense')->where('status', 'approved')->sum('amount'), 
                                2
                            ) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
            {{ $this->table }}
        </div>
    </div>
</div>