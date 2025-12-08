<?php

namespace App\Livewire\Admin\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class MemberStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected static ?string $heading = 'Member Statistics';

    protected function getStats(): array
    {
        $totalMembers = User::count();
        $activeMembers = User::where('membership_status', 'active')
            ->where('membership_type', 'active')
            ->count();
        $pendingApproval = User::where('membership_status', 'pending')->count();
        $newThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            Stat::make('Total Members', $totalMembers)
                ->description($totalMembers > 0 ? 'All registered members' : 'No members yet')
                ->descriptionIcon('heroicon-m-user-group')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('primary'),

            Stat::make('Active Members', $activeMembers)
                ->description($totalMembers > 0 ? round(($activeMembers / $totalMembers) * 100, 1) . '%' : '0%')
                ->descriptionIcon('heroicon-m-check-circle')
                ->descriptionColor('success')
                ->chart([5, 8, 12, 15, 18, 14, 20])
                ->color('success'),

            Stat::make('Pending Approval', $pendingApproval)
                ->description($pendingApproval > 0 ? 'Need attention' : 'All approved')
                ->descriptionIcon($pendingApproval > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->descriptionColor($pendingApproval > 0 ? 'warning' : 'success')
                ->chart([2, 3, 1, 4, 2, 5, 3])
                ->color($pendingApproval > 0 ? 'warning' : 'success'),

            Stat::make('New This Month', $newThisMonth)
                ->description('Joined this month')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->chart([1, 2, 3, 2, 4, 3, 5])
                ->color('info'),
        ];
    }

    protected function getColumns(): int
    {
        return 4;
    }
}