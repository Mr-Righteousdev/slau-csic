<?php

namespace App\Livewire\Admin\Widgets;

use App\Models\Fine;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FinesOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalOutstanding = Fine::whereIn('status', ['pending', 'partially_paid'])->sum('balance');
        $overdueCount = Fine::overdue()->count();
        $collectedThisMonth = Fine::whereHas('payments', function ($query) {
            $query->whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year);
        })->sum('amount_paid');

        $totalIssued = Fine::sum('amount');
        $totalPaid = Fine::sum('amount_paid');
        $collectionRate = $totalIssued > 0 ? ($totalPaid / $totalIssued) * 100 : 0;

        return [
            Stat::make('Outstanding Fines', '$'.number_format($totalOutstanding, 2))
                ->description('Total unpaid balance')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning'),

            Stat::make('Overdue Fines', $overdueCount)
                ->description('Fines past due date')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Collected This Month', '$'.number_format($collectedThisMonth, 2))
                ->description('Total payments received')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Collection Rate', number_format($collectionRate, 1).'%')
                ->description('Overall collection efficiency')
                ->descriptionIcon('heroicon-m-chart-pie')
                ->color('primary'),
        ];
    }
}
