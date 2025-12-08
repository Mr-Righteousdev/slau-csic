<?php

namespace App\Livewire\Admin\Widgets;

use App\Models\User;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentActivityWidget extends TableWidget
{
    protected static ?string $heading = 'Recent Member Activity';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return User::with(['approvedByUser', 'suspendedByUser'])
            ->latest()
            ->limit(10);
    }

    protected function getTableColumns(): array
    {
        return [
            \Filament\Tables\Columns\TextColumn::make('name')
                ->label('Member')
                ->formatStateUsing(fn ($record) => $record->name),

            \Filament\Tables\Columns\TextColumn::make('description')
                ->label('Activity')
                ->formatStateUsing(function ($record) {
                    if ($record->membership_status === 'pending' && !$record->approved_at) {
                        return '📝 Pending Approval';
                    }
                    if ($record->approved_at && $record->membership_status === 'active') {
                        return '✅ Approved by ' . $record->approvedByUser?->name;
                    }
                    if ($record->membership_status === 'rejected') {
                        return '❌ Rejected by ' . $record->approvedByUser?->name;
                    }
                    if ($record->membership_status === 'suspended') {
                        return '⚠️ Suspended by ' . $record->suspendedByUser?->name;
                    }
                    if ($record->membership_type === 'alumni') {
                        return '🎓 Converted to Alumni';
                    }
                    return '📝 Profile Updated';
                }),

            \Filament\Tables\Columns\TextColumn::make('created_at')
                ->label('Time')
                ->dateTime('M d, H:i')
                ->sortable(),
        ];
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}