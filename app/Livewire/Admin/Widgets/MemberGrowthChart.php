<?php

namespace App\Livewire\Admin\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MemberGrowthChart extends ChartWidget
{
    protected static ?string $heading = 'Member Growth (6 Months)';

    protected static ?int $sort = 2;

    protected static string $color = 'info';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = [];
        $values = [];

        // Fill in missing months with 0
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('Y-m');
            $count = $data->where('month', $month)->first();
            $labels[] = now()->subMonths($i)->format('M');
            $values[] = $count ? $count->count : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'New Members',
                    'data' => $values,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}