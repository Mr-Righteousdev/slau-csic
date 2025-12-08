<?php

namespace App\Livewire\Admin;

use App\Models\BudgetCategory;
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
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BudgetCategoryManagement extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(BudgetCategory::query())
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'income' => 'success',
                        'expense' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable(),

                TextColumn::make('allocated_amount')
                    ->label('Allocated Amount')
                    ->money('USD')
                    ->sortable()
                    ->alignRight()
                    ->weight('bold'),

                TextColumn::make('semester')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('academic_year')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                TextColumn::make('description')
                    ->limit(50)
                    ->tooltip(fn ($record): string => $record->description ?? 'No description')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'income' => 'Income Categories',
                        'expense' => 'Expense Categories',
                    ])
                    ->label('Category Type'),

                SelectFilter::make('semester')
                    ->options([
                        'Fall' => 'Fall Semester',
                        'Spring' => 'Spring Semester',
                        'Summer' => 'Summer Semester',
                    ])
                    ->label('Semester'),

                SelectFilter::make('academic_year')
                    ->options([
                        '2024-2025' => '2024-2025',
                        '2025-2026' => '2025-2026',
                        '2026-2027' => '2026-2027',
                    ])
                    ->label('Academic Year'),

                SelectFilter::make('is_active')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ])
                    ->label('Status'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Budget Category')
                    ->modalHeading('Add New Budget Category')
                    ->slideOver()
                    ->schema([
                        Section::make('Category Information')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Category Name')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(BudgetCategory::class, 'name', fn ($query) => $query->where('type', request('type'))),

                                        Select::make('type')
                                            ->label('Category Type')
                                            ->options([
                                                'income' => 'Income',
                                                'expense' => 'Expense',
                                            ])
                                            ->required()
                                            ->reactive(),

                                        TextInput::make('allocated_amount')
                                            ->label('Allocated Amount')
                                            ->numeric()
                                            ->prefix('$')
                                            ->step(0.01)
                                            ->required()
                                            ->default(0),

                                        Select::make('semester')
                                            ->label('Semester')
                                            ->options([
                                                'Fall' => 'Fall',
                                                'Spring' => 'Spring',
                                                'Summer' => 'Summer',
                                            ])
                                            ->required(),

                                        Select::make('academic_year')
                                            ->label('Academic Year')
                                            ->options([
                                                '2024-2025' => '2024-2025',
                                                '2025-2026' => '2025-2026',
                                                '2026-2027' => '2026-2027',
                                            ])
                                            ->required()
                                            ->default('2025-2026'),

                                        Toggle::make('is_active')
                                            ->label('Active')
                                            ->default(true),
                                    ]),

                                Textarea::make('description')
                                    ->label('Description')
                                    ->rows(3)
                                    ->maxLength(500)
                                    ->helperText('Optional description for this budget category'),
                            ]),
                    ])
                    ->using(function (array $data) {
                        $category = BudgetCategory::create($data);
                        
                        Notification::make()
                            ->title('Budget category created successfully')
                            ->success()
                            ->send();
                        
                        return $category;
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->extraAttributes(['class' => 'z-9999999'])
                        ->schema([
                            Section::make('Category Information')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('name')->disabled(),
                                            TextInput::make('type')->disabled(),
                                            TextInput::make('allocated_amount')->prefix('$')->disabled(),
                                            TextInput::make('semester')->disabled(),
                                        ]),

                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('academic_year')->disabled(),
                                            TextInput::make('is_active')->disabled(),
                                            TextInput::make('created_at')->disabled(),
                                            TextInput::make('updated_at')->disabled(),
                                        ]),

                                    Textarea::make('description')->disabled(),
                                ]),
                        ]),

                    EditAction::make()
                        ->extraAttributes(['class' => 'z-9999999'])
                        ->schema([
                            Section::make('Category Information')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('name')
                                                ->label('Category Name')
                                                ->required()
                                                ->maxLength(255)
                                                ->unique(ignoreRecord: true),

                                            Select::make('type')
                                                ->label('Category Type')
                                                ->options([
                                                    'income' => 'Income',
                                                    'expense' => 'Expense',
                                                ])
                                                ->required(),

                                            TextInput::make('allocated_amount')
                                                ->label('Allocated Amount')
                                                ->numeric()
                                                ->prefix('$')
                                                ->step(0.01)
                                                ->required(),

                                            Select::make('semester')
                                                ->label('Semester')
                                                ->options([
                                                    'Fall' => 'Fall',
                                                    'Spring' => 'Spring',
                                                    'Summer' => 'Summer',
                                                ])
                                                ->required(),
                                        ]),

                                    Grid::make(2)
                                        ->schema([
                                            Select::make('academic_year')
                                                ->label('Academic Year')
                                                ->options([
                                                    '2024-2025' => '2024-2025',
                                                    '2025-2026' => '2025-2026',
                                                    '2026-2027' => '2026-2027',
                                                ])
                                                ->required(),

                                            Toggle::make('is_active')
                                                ->label('Active'),
                                        ]),

                                    Textarea::make('description')
                                        ->label('Description')
                                        ->rows(3)
                                        ->maxLength(500)
                                        ->helperText('Optional description for this budget category'),
                                ]),
                        ])
                        ->using(function (BudgetCategory $record, array $data) {
                            $record->update($data);
                            
                            Notification::make()
                                ->title('Budget category updated successfully')
                                ->success()
                                ->send();

                            return $record;
                        }),

                    Action::make('toggle_active')
                        ->label(fn (BudgetCategory $record): string => $record->is_active ? 'Deactivate' : 'Activate')
                        ->icon(fn (BudgetCategory $record): string => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                        ->color(fn (BudgetCategory $record): string => $record->is_active ? 'danger' : 'success')
                        ->action(function (BudgetCategory $record) {
                            $record->update(['is_active' => !$record->is_active]);
                            
                            Notification::make()
                                ->title('Budget category status updated')
                                ->success()
                                ->send();
                        }),

                    DeleteAction::make()
                        ->requiresConfirmation()
                        ->action(function (BudgetCategory $record) {
                            $record->delete();
                            
                            Notification::make()
                                ->title('Budget category deleted')
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
                    BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (Collection $records) {
                            $records->each(function (BudgetCategory $record) {
                                $record->update(['is_active' => true]);
                            });
                            
                            Notification::make()
                                ->title('Categories activated')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function (Collection $records) {
                            $records->each(function (BudgetCategory $record) {
                                $record->update(['is_active' => false]);
                            });
                            
                            Notification::make()
                                ->title('Categories deactivated')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateHeading('No budget categories found')
            ->emptyStateDescription('Once you add budget categories, they will appear here.')
            ->emptyStateIcon('heroicon-o-folder')
            ->emptyStateActions([
                CreateAction::make()
                    ->label('Add Budget Category')
                    ->button(),
            ])
            ->striped()
            ->deferLoading();
    }

    public function render(): View
    {
        return view('livewire.admin.budget-category-management');
    }
}