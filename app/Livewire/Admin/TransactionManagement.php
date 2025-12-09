<?php

namespace App\Livewire\Admin;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TransactionManagement extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Transaction::query()->with(['creator', 'approver']))
            ->columns([
                TextColumn::make('date')
                    ->date('M d, Y')
                    ->sortable()
                    ->label('Date'),

                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'income' => 'success',
                        'expense' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable(),

                TextColumn::make('category')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->tooltip(fn ($record): string => $record->category),

                TextColumn::make('amount')
                    ->money('USD')
                    ->sortable()
                    ->weight('bold')
                    ->alignRight(),

                TextColumn::make('paid_to_from')
                    ->label('Paid To/From')
                    ->searchable()
                    ->limit(25)
                    ->tooltip(fn ($record): string => $record->paid_to_from),

                TextColumn::make('payment_method')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                IconColumn::make('requires_approval')
                    ->label('Requires Approval')
                    ->boolean()
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('warning')
                    ->falseColor('success'),

                TextColumn::make('creator.name')
                    ->label('Created By')
                    ->sortable()
                    ->limit(20)
                    ->tooltip(fn ($record): string => $record->creator?->name ?? 'System'),

                TextColumn::make('created_at')
                    ->dateTime('M d, Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'income' => 'Income',
                        'expense' => 'Expense',
                    ])
                    ->label('Transaction Type'),

                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->label('Status'),

                SelectFilter::make('category')
                    ->options(function () {
                        $incomeCategories = [
                            'Membership Dues' => 'Membership Dues',
                            'Donations' => 'Donations',
                            'Sponsorships' => 'Sponsorships',
                            'Fundraising' => 'Fundraising',
                            'Other Income' => 'Other Income',
                        ];
                        $expenseCategories = [
                            'Events' => 'Events',
                            'Equipment' => 'Equipment',
                            'Prizes' => 'Prizes',
                            'Refreshments' => 'Refreshments',
                            'Printing' => 'Printing',
                            'Travel' => 'Travel',
                            'Other Expense' => 'Other Expense',
                        ];

                        return array_merge($incomeCategories, $expenseCategories);
                    })
                    ->searchable()
                    ->label('Category'),

                SelectFilter::make('payment_method')
                    ->options([
                        'cash' => 'Cash',
                        'check' => 'Check',
                        'card' => 'Card',
                        'transfer' => 'Bank Transfer',
                        'other' => 'Other',
                    ])
                    ->label('Payment Method'),

                Filter::make('requires_approval')
                    ->label('Requires Approval')
                    ->query(fn (Builder $query): Builder => $query->where('requires_approval', true)),

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
                                fn (Builder $query) => $query->whereDate('date', '>=', $data['from_date'])
                            )
                            ->when(
                                $data['to_date'],
                                fn (Builder $query) => $query->whereDate('date', '<=', $data['to_date'])
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['from_date'] ?? null) {
                            $indicators[] = Indicator::make('From: '.Carbon::parse($data['from_date'])->format('M d, Y'))
                                ->removeField('from_date');
                        }

                        if ($data['to_date'] ?? null) {
                            $indicators[] = Indicator::make('To: '.Carbon::parse($data['to_date'])->format('M d, Y'))
                                ->removeField('to_date');
                        }

                        return $indicators;
                    }),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Transaction')
                    ->modalHeading('Add New Transaction')
                    ->slideOver()
                    ->schema([
                        Section::make('Transaction Details')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Select::make('type')
                                            ->label('Transaction Type')
                                            ->options([
                                                'income' => 'Income',
                                                'expense' => 'Expense',
                                            ])
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(fn (callable $set) => $set('category', null)),

                                        Select::make('category')
                                            ->label('Category')
                                            ->options(function (callable $get) {
                                                $type = $get('type');
                                                if ($type === 'income') {
                                                    return [
                                                        'Membership Dues' => 'Membership Dues',
                                                        'Donations' => 'Donations',
                                                        'Sponsorships' => 'Sponsorships',
                                                        'Fundraising' => 'Fundraising',
                                                        'Other Income' => 'Other Income',
                                                    ];
                                                } elseif ($type === 'expense') {
                                                    return [
                                                        'Events' => 'Events',
                                                        'Equipment' => 'Equipment',
                                                        'Prizes' => 'Prizes',
                                                        'Refreshments' => 'Refreshments',
                                                        'Printing' => 'Printing',
                                                        'Travel' => 'Travel',
                                                        'Other Expense' => 'Other Expense',
                                                    ];
                                                }

                                                return [];
                                            })
                                            ->required()
                                            ->disabled(fn (callable $get) => ! $get('type')),

                                        TextInput::make('amount')
                                            ->label('Amount')
                                            ->numeric()
                                            ->prefix('$')
                                            ->step(0.01)
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                $set('requires_approval', $state > 100);
                                            }),

                                        DatePicker::make('date')
                                            ->label('Transaction Date')
                                            ->required()
                                            ->default(now()),
                                    ]),

                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('paid_to_from')
                                            ->label(fn (callable $get) => $get('type') === 'income' ? 'Received From' : 'Paid To')
                                            ->required(),

                                        Select::make('payment_method')
                                            ->label('Payment Method')
                                            ->options([
                                                'cash' => 'Cash',
                                                'check' => 'Check',
                                                'card' => 'Card',
                                                'transfer' => 'Bank Transfer',
                                                'other' => 'Other',
                                            ])
                                            ->default('cash')
                                            ->required(),
                                    ]),

                                Textarea::make('description')
                                    ->label('Description')
                                    ->rows(3)
                                    ->maxLength(500),

                                FileUpload::make('receipt_path')
                                    ->label('Receipt')
                                    ->directory('receipts')
                                    ->acceptedFileTypes(['pdf', 'jpg', 'jpeg', 'png'])
                                    ->maxSize(5120) // 5MB
                                    ->helperText('Upload receipt or proof of transaction'),

                                Toggle::make('requires_approval')
                                    ->label('Requires Approval (amount > $100)')
                                    ->disabled()
                                    ->helperText('Automatically set for amounts over $100'),
                            ]),
                    ])
                    ->using(function (array $data) {
                        $data['created_by'] = Auth::user()->id;
                        $transaction = Transaction::create($data);

                        Notification::make()
                            ->title('Transaction created successfully')
                            ->success()
                            ->send();

                        return $transaction;
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->extraAttributes(['class' => 'z-9999999'])
                        ->schema([
                            Section::make('Transaction Information')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('type')->disabled(),
                                            TextInput::make('category')->disabled(),
                                            TextInput::make('amount')->prefix('$')->disabled(),
                                            TextInput::make('date')->disabled(),
                                        ]),

                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('paid_to_from')->label('Paid To/From')->disabled(),
                                            TextInput::make('payment_method')->disabled(),
                                            TextInput::make('status')->disabled(),
                                            TextInput::make('requires_approval')->disabled(),
                                        ]),

                                    Textarea::make('description')->disabled(),

                                    FileUpload::make('receipt_path')
                                        ->label('Receipt')
                                        ->disabled()
                                        ->acceptedFileTypes(['pdf', 'jpg', 'jpeg', 'png']),
                                ]),

                            Section::make('Approval Information')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('creator.name')
                                                ->label('Created By')
                                                ->default(fn ($record) => $record?->creator?->name ?? 'System')
                                                ->disabled(),

                                            TextInput::make('approver.name')
                                                ->label('Approved By')
                                                ->default(fn ($record) => $record?->approver?->name ?? 'Not approved')
                                                ->disabled(),
                                        ]),

                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('created_at')->disabled(),
                                            TextInput::make('approved_at')->disabled(),
                                        ]),
                                ]),
                        ]),

                    EditAction::make()
                        ->extraAttributes(['class' => 'z-9999999'])
                        ->visible(fn (Transaction $record): bool => $record->status !== 'approved' || Auth::user()->hasAnyRole(['treasurer', 'president', 'super-admin']))
                        ->schema([
                            Section::make('Transaction Details')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            Select::make('type')
                                                ->options([
                                                    'income' => 'Income',
                                                    'expense' => 'Expense',
                                                ])
                                                ->required()
                                                ->reactive()
                                                ->afterStateUpdated(fn (callable $set) => $set('category', null)),

                                            Select::make('category')
                                                ->options(function (callable $get) {
                                                    $type = $get('type');
                                                    if ($type === 'income') {
                                                        return [
                                                            'Membership Dues' => 'Membership Dues',
                                                            'Donations' => 'Donations',
                                                            'Sponsorships' => 'Sponsorships',
                                                            'Fundraising' => 'Fundraising',
                                                            'Other Income' => 'Other Income',
                                                        ];
                                                    } elseif ($type === 'expense') {
                                                        return [
                                                            'Events' => 'Events',
                                                            'Equipment' => 'Equipment',
                                                            'Prizes' => 'Prizes',
                                                            'Refreshments' => 'Refreshments',
                                                            'Printing' => 'Printing',
                                                            'Travel' => 'Travel',
                                                            'Other Expense' => 'Other Expense',
                                                        ];
                                                    }

                                                    return [];
                                                })
                                                ->required()
                                                ->disabled(fn (callable $get) => ! $get('type')),

                                            TextInput::make('amount')
                                                ->numeric()
                                                ->prefix('$')
                                                ->step(0.01)
                                                ->required()
                                                ->reactive()
                                                ->afterStateUpdated(function ($state, callable $set) {
                                                    $set('requires_approval', $state > 100);
                                                }),

                                            DatePicker::make('date')->required(),
                                        ]),

                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('paid_to_from')
                                                ->label(fn (callable $get) => $get('type') === 'income' ? 'Received From' : 'Paid To')
                                                ->required(),

                                            Select::make('payment_method')
                                                ->options([
                                                    'cash' => 'Cash',
                                                    'check' => 'Check',
                                                    'card' => 'Card',
                                                    'transfer' => 'Bank Transfer',
                                                    'other' => 'Other',
                                                ])
                                                ->required(),
                                        ]),

                                    Textarea::make('description')
                                        ->rows(3)
                                        ->maxLength(500),

                                    FileUpload::make('receipt_path')
                                        ->label('Receipt')
                                        ->directory('receipts')
                                        ->acceptedFileTypes(['pdf', 'jpg', 'jpeg', 'png'])
                                        ->maxSize(5120),

                                    Toggle::make('requires_approval')
                                        ->label('Requires Approval (amount > $100)')
                                        ->disabled(),

                                    Select::make('status')
                                        ->options([
                                            'pending' => 'Pending',
                                            'approved' => 'Approved',
                                            'rejected' => 'Rejected',
                                        ])
                                        ->visible(fn () => Auth::user()->hasAnyRole(['treasurer', 'president', 'super-admin']))
                                        ->required(),
                                ]),
                        ])
                        ->using(function (Transaction $record, array $data) {
                            $oldValues = $record->toArray();

                            // Handle approval status change
                            if (isset($data['status']) && $data['status'] !== $record->status) {
                                if ($data['status'] === 'approved') {
                                    $data['approved_by'] = Auth::id();
                                    $data['approved_at'] = now();
                                } elseif ($data['status'] === 'rejected') {
                                    $data['approved_by'] = Auth::id();
                                    $data['approved_at'] = now();
                                }
                            }

                            $record->update($data);

                            Notification::make()
                                ->title('Transaction updated successfully')
                                ->success()
                                ->send();

                            return $record;
                        }),

                    Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (Transaction $record): bool => $record->status === 'pending' &&
                            Auth::user()->hasAnyRole(['treasurer', 'president', 'super-admin'])
                        )
                        ->action(function (Transaction $record) {
                            $record->update([
                                'status' => 'approved',
                                'approved_by' => Auth::id(),
                                'approved_at' => now(),
                            ]);

                            Notification::make()
                                ->title('Transaction approved')
                                ->success()
                                ->send();
                        }),

                    Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (Transaction $record): bool => $record->status === 'pending' &&
                            Auth::user()->hasAnyRole(['treasurer', 'president', 'super-admin'])
                        )
                        ->action(function (Transaction $record) {
                            $record->update([
                                'status' => 'rejected',
                                'approved_by' => Auth::id(),
                                'approved_at' => now(),
                            ]);

                            Notification::make()
                                ->title('Transaction rejected')
                                ->danger()
                                ->send();
                        }),

                    DeleteAction::make()
                        ->requiresConfirmation()
                        ->visible(fn (Transaction $record): bool => Auth::user()->hasAnyRole(['treasurer', 'president', 'super-admin']))
                        ->action(function (Transaction $record) {
                            $record->delete();

                            Notification::make()
                                ->title('Transaction deleted')
                                ->success()
                                ->send();
                        }),
                ])
                    ->dropdownPlacement('bottom-end')
                    ->label('Actions')
                    ->icon('heroicon-o-ellipsis-vertical')
                    ->button(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('approve')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn () => Auth::user()->hasAnyRole(['treasurer', 'president', 'super-admin']))
                        ->action(function (Collection $records) {
                            $records->each(function (Transaction $record) {
                                if ($record->status === 'pending') {
                                    $record->update([
                                        'status' => 'approved',
                                        'approved_by' => Auth::id(),
                                        'approved_at' => now(),
                                    ]);
                                }
                            });

                            Notification::make()
                                ->title('Transactions approved')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('reject')
                        ->label('Reject Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn () => Auth::user()->hasAnyRole(['treasurer', 'president', 'super-admin']))
                        ->action(function (Collection $records) {
                            $records->each(function (Transaction $record) {
                                if ($record->status === 'pending') {
                                    $record->update([
                                        'status' => 'rejected',
                                        'approved_by' => Auth::id(),
                                        'approved_at' => now(),
                                    ]);
                                }
                            });

                            Notification::make()
                                ->title('Transactions rejected')
                                ->danger()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->visible(fn () => Auth::user()->hasAnyRole(['treasurer', 'president', 'super-admin'])),
                ]),
            ])
            ->emptyStateHeading('No transactions found')
            ->emptyStateDescription('Once you add transactions, they will appear here.')
            ->emptyStateIcon('heroicon-o-banknotes')
            ->emptyStateActions([
                CreateAction::make()
                    ->label('Add Transaction')
                    ->button(),
            ])
            ->striped()
            ->deferLoading();
    }

    public function render(): View
    {
        return view('livewire.admin.transaction-management');
    }
}
