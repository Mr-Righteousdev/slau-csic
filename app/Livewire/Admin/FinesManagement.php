<?php

namespace App\Livewire\Admin;

use App\Models\Fine;
use App\Models\FinePayment;
use App\Models\FineType;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FinesManagement extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Fine::query()->with(['user', 'fineType', 'issuedBy', 'waivedBy']))
            ->columns([
                TextColumn::make('user.name')
                    ->label('Member')
                    ->searchable()
                    ->sortable()
                    ->limit(25)
                    ->tooltip(fn ($record): string => $record->user?->name ?? 'Unknown'),

                TextColumn::make('fineType.name')
                    ->label('Fine Type')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('amount')
                    ->money('USD')
                    ->sortable()
                    ->weight('bold')
                    ->alignRight(),

                TextColumn::make('reason')
                    ->searchable()
                    ->limit(40)
                    ->tooltip(fn ($record): string => $record->reason),

                TextColumn::make('issue_date')
                    ->date('M d, Y')
                    ->sortable()
                    ->label('Issued'),

                TextColumn::make('due_date')
                    ->date('M d, Y')
                    ->sortable()
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
                    })
                    ->sortable(),

                TextColumn::make('balance')
                    ->money('USD')
                    ->sortable()
                    ->weight('bold')
                    ->alignRight()
                    ->color(fn ($record): string => $record->balance > 0 ? 'danger' : 'success'),

                TextColumn::make('issuedBy.name')
                    ->label('Issued By')
                    ->sortable()
                    ->limit(20)
                    ->tooltip(fn ($record): string => $record->issuedBy?->name ?? 'System')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'partially_paid' => 'Partially Paid',
                        'waived' => 'Waived',
                        'overdue' => 'Overdue',
                    ])
                    ->label('Status'),

                SelectFilter::make('fine_type_id')
                    ->options(FineType::active()->pluck('name', 'id'))
                    ->label('Fine Type')
                    ->searchable(),

                Filter::make('overdue')
                    ->label('Overdue Only')
                    ->query(fn (Builder $query): Builder => $query->overdue()),

                Filter::make('due_soon')
                    ->label('Due Soon (3 days)')
                    ->query(fn (Builder $query): Builder => $query->dueSoon()),

                Filter::make('date_range')
                    ->schema([
                        DatePicker::make('from_date')
                            ->label('From Date'),
                        DatePicker::make('to_date')
                            ->label('To Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from_date'],
                                fn (Builder $query) => $query->whereDate('issue_date', '>=', $data['from_date'])
                            )
                            ->when(
                                $data['to_date'],
                                fn (Builder $query) => $query->whereDate('issue_date', '<=', $data['to_date'])
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from_date'] ?? null) {
                            $indicators[] = "From: {$data['from_date']}";
                        }
                        if ($data['to_date'] ?? null) {
                            $indicators[] = "To: {$data['to_date']}";
                        }

                        return $indicators;
                    }),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Issue Fine')
                    ->modalHeading('Issue New Fine')
                    ->slideOver()
                    ->schema([
                        Section::make('Fine Details')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Select::make('user_id')
                                            ->label('Member')
                                            ->options(User::approved()->pluck('name', 'id'))
                                            ->searchable()
                                            ->required()
                                            ->placeholder('Select member to fine'),

                                        Select::make('fine_type_id')
                                            ->label('Fine Type')
                                            ->options(FineType::active()->pluck('name', 'id'))
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                $fineType = FineType::find($state);
                                                if ($fineType) {
                                                    $set('amount', $fineType->default_amount);
                                                }
                                            }),

                                        TextInput::make('amount')
                                            ->label('Amount')
                                            ->numeric()
                                            ->prefix('$')
                                            ->step(0.01)
                                            ->required()
                                            ->minValue(0)
                                            ->maxValue(9999.99),

                                        DatePicker::make('due_date')
                                            ->label('Due Date')
                                            ->required()
                                            ->default(now()->addDays(14)),
                                    ]),

                                Textarea::make('reason')
                                    ->label('Reason')
                                    ->rows(3)
                                    ->required()
                                    ->maxLength(500)
                                    ->placeholder('Explain why this fine is being issued'),

                                Checkbox::make('send_notification')
                                    ->label('Send notification to member')
                                    ->default(true),
                            ]),
                    ])
                    ->using(function (array $data) {
                        $data['issue_date'] = now();
                        $data['issued_by'] = Auth::id();
                        $data['status'] = 'pending';
                        $data['amount_paid'] = 0;
                        $data['balance'] = $data['amount'];

                        $fine = Fine::create($data);

                        // TODO: Send notification if checked
                        if ($data['send_notification'] ?? false) {
                            // Send fine notification
                        }

                        Notification::make()
                            ->title('Fine issued successfully')
                            ->success()
                            ->send();

                        return $fine;
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->extraAttributes(['class' => 'z-9999999'])
                        ->schema([
                            Section::make('Fine Information')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('user.name')->label('Member')->disabled(),
                                            TextInput::make('fineType.name')->label('Fine Type')->disabled(),
                                            TextInput::make('amount')->prefix('$')->disabled(),
                                            TextInput::make('balance')->prefix('$')->disabled(),
                                        ]),

                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('issue_date')->disabled(),
                                            TextInput::make('due_date')->disabled(),
                                            TextInput::make('status')->disabled(),
                                            TextInput::make('amount_paid')->prefix('$')->disabled(),
                                        ]),

                                    Textarea::make('reason')->disabled(),

                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('issuedBy.name')
                                                ->label('Issued By')
                                                ->default(fn ($record) => $record?->issuedBy?->name ?? 'System')
                                                ->disabled(),
                                            TextInput::make('waivedBy.name')
                                                ->label('Waived By')
                                                ->default(fn ($record) => $record?->waivedBy?->name ?? 'Not waived')
                                                ->disabled(),
                                        ]),

                                    Textarea::make('waived_reason')
                                        ->label('Waiver Reason')
                                        ->disabled(),
                                ]),
                        ]),

                    Action::make('record_payment')
                        ->label('Record Payment')
                        ->icon('heroicon-o-banknotes')
                        ->color('success')
                        ->visible(fn (Fine $record): bool => in_array($record->status, ['pending', 'partially_paid']))
                        ->form([
                            Grid::make(2)
                                ->schema([
                                    TextInput::make('amount')
                                        ->label('Payment Amount')
                                        ->numeric()
                                        ->prefix('$')
                                        ->step(0.01)
                                        ->required()
                                        ->minValue(0.01)
                                        ->maxValue(fn ($record) => $record->balance),

                                    DatePicker::make('payment_date')
                                        ->label('Payment Date')
                                        ->required()
                                        ->default(now()),
                                ]),

                            Select::make('payment_method')
                                ->label('Payment Method')
                                ->options(FinePayment::getPaymentMethods())
                                ->required(),

                            TextInput::make('receipt_number')
                                ->label('Receipt Number')
                                ->placeholder('Optional'),

                            Textarea::make('notes')
                                ->label('Notes')
                                ->rows(2)
                                ->maxLength(500),
                        ])
                        ->action(function (Fine $record, array $data) {
                            $record->recordPayment(
                                $data['amount'],
                                $data['payment_method'],
                                $data['receipt_number'] ?? null,
                                $data['notes'] ?? null
                            );

                            Notification::make()
                                ->title('Payment recorded successfully')
                                ->success()
                                ->send();
                        }),

                    Action::make('waive')
                        ->label('Waive Fine')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->visible(fn (Fine $record): bool => $record->status === 'pending' &&
                            Auth::user()->hasAnyRole(['treasurer', 'president', 'super-admin']))
                        ->schema([
                            Select::make('waiver_reason')
                                ->label('Waiver Reason')
                                ->options([
                                    'first_offense' => 'First Offense',
                                    'special_circumstances' => 'Special Circumstances',
                                    'error' => 'Error',
                                    'other' => 'Other',
                                ])
                                ->required(),

                            Textarea::make('waiver_explanation')
                                ->label('Explanation')
                                ->rows(3)
                                ->required()
                                ->maxLength(500),

                            TextInput::make('partial_waiver_amount')
                                ->label('Partial Waiver Amount (optional)')
                                ->numeric()
                                ->prefix('$')
                                ->step(0.01)
                                ->minValue(0)
                                ->maxValue(fn ($record) => $record->balance)
                                ->placeholder('Leave empty for full waiver'),
                        ])
                        ->action(function (Fine $record, array $data) {
                            $waiverAmount = $data['partial_waiver_amount'] ?? $record->balance;

                            if ($waiverAmount >= $record->balance) {
                                // Full waiver
                                $record->waive(
                                    Auth::user(),
                                    $data['waiver_reason'].': '.$data['waiver_explanation']
                                );
                            } else {
                                // Partial waiver - reduce balance
                                $record->update([
                                    'balance' => $record->balance - $waiverAmount,
                                    'waived_by' => Auth::id(),
                                    'waived_reason' => $data['waiver_reason'].': '.$data['waiver_explanation'],
                                ]);
                            }

                            Notification::make()
                                ->title('Fine waived successfully')
                                ->success()
                                ->send();
                        }),

                    DeleteAction::make()
                        ->requiresConfirmation()
                        ->visible(fn (Fine $record): bool => Auth::user()->hasAnyRole(['treasurer', 'president', 'super-admin']))
                        ->action(function (Fine $record) {
                            $record->delete();

                            Notification::make()
                                ->title('Fine deleted successfully')
                                ->success()
                                ->send();
                        }),
                ])
                    ->dropdownPlacement('bottom-end')
                    ->label('Actions')
                    ->icon('heroicon-o-ellipsis-vertical')
                    ->button(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('send_reminder')
                        ->label('Send Reminder')
                        ->icon('heroicon-o-bell')
                        ->color('info')
                        ->action(function (Collection $records) {
                            $count = $records->filter(fn ($record) => $record->status === 'pending')->count();

                            // TODO: Send reminders

                            Notification::make()
                                ->title("Reminders sent to {$count} members")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('bulk_waive')
                        ->label('Waive Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->visible(fn () => Auth::user()->hasAnyRole(['treasurer', 'president', 'super-admin']))
                        ->form([
                            Select::make('waiver_reason')
                                ->label('Waiver Reason')
                                ->options([
                                    'first_offense' => 'First Offense',
                                    'special_circumstances' => 'Special Circumstances',
                                    'error' => 'Error',
                                    'other' => 'Other',
                                ])
                                ->required(),

                            Textarea::make('waiver_explanation')
                                ->label('Explanation')
                                ->rows(3)
                                ->required()
                                ->maxLength(500),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $count = 0;
                            $records->each(function (Fine $record) use ($data, &$count) {
                                if ($record->status === 'pending') {
                                    $record->waive(
                                        Auth::user(),
                                        $data['waiver_reason'].': '.$data['waiver_explanation']
                                    );
                                    $count++;
                                }
                            });

                            Notification::make()
                                ->title("{$count} fines waived successfully")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->visible(fn () => Auth::user()->hasAnyRole(['treasurer', 'president', 'super-admin'])),
                ]),
            ])
            ->emptyStateHeading('No fines found')
            ->emptyStateDescription('Once you issue fines, they will appear here.')
            ->emptyStateIcon('heroicon-o-currency-dollar')
            ->emptyStateActions([
                CreateAction::make()
                    ->label('Issue Fine')
                    ->button(),
            ])
            ->striped()
            ->deferLoading();
    }

    public function render(): View
    {
        return view('livewire.admin.fines-management');
    }
}
