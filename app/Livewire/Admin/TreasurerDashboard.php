<?php

namespace App\Livewire\Admin;

use App\Models\BudgetCategory;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TreasurerDashboard extends Component
{
    public function render()
    {
        // Get financial statistics
        $totalIncome = Transaction::where('type', 'income')->where('status', 'approved')->sum('amount');
        $totalExpenses = Transaction::where('type', 'expense')->where('status', 'approved')->sum('amount');
        $currentBalance = $totalIncome - $totalExpenses;

        $thisMonthIncome = Transaction::where('type', 'income')
            ->where('status', 'approved')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        $thisMonthExpenses = Transaction::where('type', 'expense')
            ->where('status', 'approved')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        $pendingApprovals = Transaction::where('status', 'pending')->count();

        // Budget vs Actual data
        $budgetCategories = BudgetCategory::where('is_active', true)
            ->with(['allocations' => function ($query) {
                $query->where('academic_year', '2025-2026');
            }])
            ->get();

        $budgetData = [];
        foreach ($budgetCategories as $category) {
            $spent = Transaction::where('category', $category->name)
                ->where('status', 'approved')
                ->where('type', $category->type)
                ->whereYear('date', now()->year)
                ->sum('amount');

            $allocated = $category->allocated_amount;

            $budgetData[] = [
                'category' => $category->name,
                'allocated' => $allocated,
                'spent' => $spent,
                'remaining' => $allocated - $spent,
                'percentage' => $allocated > 0 ? ($spent / $allocated) * 100 : 0,
            ];
        }

        // Recent transactions
        $recentTransactions = Transaction::with(['creator'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Spending trend (last 6 months)
        $spendingTrend = Transaction::select(
            DB::raw('MONTH(date) as month'),
            DB::raw('YEAR(date) as year'),
            DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income'),
            DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as expenses')
        )
            ->where('status', 'approved')
            ->where('date', '>=', now()->subMonths(6))
            ->groupBy(DB::raw('YEAR(date), MONTH(date)'))
            ->orderBy(DB::raw('YEAR(date), MONTH(date)'))
            ->get();

        // Budget status for alerts
        $overBudgetCategories = [];
        foreach ($budgetData as $budget) {
            if ($budget['percentage'] > 80) {
                $overBudgetCategories[] = $budget;
            }
        }

        return view('livewire.admin.treasurer-dashboard', [
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'currentBalance' => $currentBalance,
            'thisMonthIncome' => $thisMonthIncome,
            'thisMonthExpenses' => $thisMonthExpenses,
            'pendingApprovals' => $pendingApprovals,
            'budgetData' => $budgetData,
            'recentTransactions' => $recentTransactions,
            'spendingTrend' => $spendingTrend,
            'overBudgetCategories' => $overBudgetCategories,
        ]);
    }
}
