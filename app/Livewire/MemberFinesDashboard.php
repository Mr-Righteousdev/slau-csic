<?php

namespace App\Livewire;

use App\Models\Fine;
use App\Models\FineAppeal;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MemberFinesDashboard extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Fine::query()->where('user_id', Auth::id())->with(['fineType', 'payments']))
            ->columns([
                TextColumn::make('fineType.name')
                    ->label('Fine Type')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('amount')
                    ->money('USD')
                    ->weight('bold')
                    ->alignRight(),

                TextColumn::make('reason')
                    ->limit(50)
                    ->tooltip(fn ($record): string => $record->reason),

                TextColumn::make('issue_date')
                    ->date('M d, Y')
                    ->label('Issued'),

                TextColumn::make('due_date')
                    ->date('M d, Y')
                    ->label('Due Date'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'partially_paid' => 'info',
                        'waived' => 'gray',
                        'overdue' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'partially_paid' => 'Partially Paid',
                        'waived' => 'Waived',
                        'overdue' => 'Overdue',
                    }),

                TextColumn::make('balance')
                    ->money('USD')
                    ->weight('bold')
                    ->alignRight()
                    ->color(fn ($record): string => $record->balance > 0 ? 'danger' : 'success'),

                TextColumn::make('payments_count')
                    ->label('Payments')
                    ->formatStateUsing(fn ($record): int => $record->payments->count())
                    ->alignRight(),
            ])
            ->recordActions([
                Action::make('appeal')
                    ->label('Appeal')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('warning')
                    ->visible(fn (Fine $record): bool => $record->canBeAppealed())
                    ->schema([
                        Select::make('appeal_reason')
                            ->label('Appeal Reason')
                            ->options(FineAppeal::getAppealReasons())
                            ->required(),

                        Textarea::make('explanation')
                            ->label('Explanation')
                            ->rows(4)
                            ->required()
                            ->maxLength(1000)
                            ->placeholder('Please explain why you believe this fine should be waived or reduced...'),
                    ])
                    ->action(function (Fine $record, array $data) {
                        $record->appeals()->create([
                            'appeal_reason' => $data['appeal_reason'],
                            'explanation' => $data['explanation'],
                            'status' => 'pending',
                            'submitted_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Appeal submitted successfully')
                            ->body('Your appeal has been submitted and will be reviewed by the club leadership.')
                            ->success()
                            ->send();
                    }),

                Action::make('view_payments')
                    ->label('View Payments')
                    ->icon('heroicon-o-banknotes')
                    ->color('info')
                    ->visible(fn (Fine $record): bool => $record->payments->count() > 0)
                    ->modalContent(fn (Fine $record): string => view('livewire.partials.fine-payments', [
                        'payments' => $record->payments()->with('recordedBy')->get(),
                    ])->render()),
            ])
            ->emptyStateHeading('No fines found')
            ->emptyStateDescription('Great! You have no fines on your record.')
            ->emptyStateIcon('heroicon-o-check-circle')
            ->striped()
            ->deferLoading();
    }

    public function getTotalOutstanding(): float
    {
        return Fine::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'partially_paid'])
            ->sum('balance');
    }

    public function getTotalPaid(): float
    {
        return Fine::where('user_id', Auth::id())
            ->sum('amount_paid');
    }

    public function getOverdueCount(): int
    {
        return Fine::where('user_id', Auth::id())
            ->overdue()
            ->count();
    }

    public function render(): View
    {
        $totalOutstanding = $this->getTotalOutstanding();
        $totalPaid = $this->getTotalPaid();
        $overdueCount = $this->getOverdueCount();

        return view('livewire.member-fines-dashboard', compact(
            'totalOutstanding',
            'totalPaid',
            'overdueCount'
        ));
    }
}
