<div>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Treasurer Dashboard</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Financial overview and management</p>
                </div>
                <div class="text-sm text-gray-500">
                    {{ now()->format('F j, Y') }}
                </div>
            </div>
        </div>

        <!-- Financial Overview Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3-2-1.343-2-3-2m0 8c1.11 0 2.08.402 2.599-1M12 8V7m0 1v8m0 0h.01M12 15h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Current Balance</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            ${{ number_format($currentBalance, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Income This Month</p>
                        <p class="text-2xl font-bold text-green-600">
                            ${{ number_format($thisMonthIncome, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9m-4 0V5a2 2 0 012-2h4a2 2 0 012 2v2"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Expenses This Month</p>
                        <p class="text-2xl font-bold text-red-600">
                            ${{ number_format($thisMonthExpenses, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-lg relative">
                        @if($pendingApprovals > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                {{ $pendingApprovals }}
                            </span>
                        @endif
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Approvals</p>
                        <p class="text-2xl font-bold text-yellow-600">
                            {{ $pendingApprovals }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Budget Alerts -->
        @if(count($overBudgetCategories) > 0)
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.932-3.254L13.068 4.846A2.25 2.25 0 0011.035 3.5H8.25a2.25 2.25 0 00-2.033 1.346L6.056 8.746c-.467 1.587.408 3.254 1.932 3.254z"></path>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Budget Alert</h3>
                        <p class="text-sm text-red-700 dark:text-red-300">
                            {{ count($overBudgetCategories) }} budget categor{{ count($overBudgetCategories) == 1 ? 'y' : 'ies' }} have exceeded 80% of allocated budget
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Budget vs Actual Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Budget vs Actual</h3>
                <div class="space-y-3">
                    @foreach($budgetData as $budget)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $budget['category'] }}</span>
                                <span class="text-sm text-gray-500">
                                    ${{ number_format($budget['spent'], 2) }} / ${{ number_format($budget['allocated'], 2) }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="h-2 rounded-full {{ $budget['percentage'] > 80 ? 'bg-red-500' : ($budget['percentage'] > 60 ? 'bg-yellow-500' : 'bg-green-500') }}"
                                     style="width: {{ min($budget['percentage'], 100) }}%">
                                </div>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ number_format($budget['percentage'], 1) }}% used • ${{ number_format($budget['remaining'], 2) }} remaining
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Transactions</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($recentTransactions as $transaction)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $transaction->date->format('M d, Y') }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">
                                        {{ Str::limit($transaction->description, 30) }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm font-medium {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction->type === 'income' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $transaction->status === 'approved' ? 'bg-green-100 text-green-800' : ($transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Spending Trend Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Spending Trend (6 Months)</h3>
            <div class="h-64">
                <canvas id="spendingChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('spendingChart');
            if (ctx) {
                const spendingData = @json($spendingTrend->toArray());
                
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: spendingData.map(item => item.month + ' ' + item.year),
                        datasets: [{
                            label: 'Income',
                            data: spendingData.map(item => parseFloat(item.income)),
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            tension: 0.4
                        }, {
                            label: 'Expenses',
                            data: spendingData.map(item => parseFloat(item.expenses)),
                            borderColor: 'rgb(239, 68, 68)',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                }
            });
            }
        });
    </script>
</div>