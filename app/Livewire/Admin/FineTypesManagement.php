<?php

namespace App\Livewire\Admin;

use App\Models\FineType;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class FineTypesManagement extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(FineType::query())
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('default_amount')
                    ->money('USD')
                    ->sortable()
                    ->alignRight(),

                TextColumn::make('description')
                    ->limit(50)
                    ->tooltip(fn ($record): string => $record->description)
                    ->searchable(),

                TextColumn::make('auto_apply_trigger')
                    ->label('Auto Trigger')
                    ->formatStateUsing(fn ($state): string => $state ? FineType::getAutoApplyTriggers()[$state] ?? $state : 'Manual')
                    ->badge()
                    ->color(fn ($state): string => $state ? 'success' : 'gray'),

                TextColumn::make('auto_apply_threshold')
                    ->label('Threshold')
                    ->formatStateUsing(fn ($state): string => $state ? $state : '-')
                    ->alignRight(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Fine Type')
                    ->modalHeading('Add New Fine Type')
                    ->slideOver()
                    ->schema([
                        TextInput::make('name')
                            ->label('Fine Type Name')
                            ->required()
                            ->unique(FineType::class, 'name')
                            ->placeholder('e.g., Missed Meeting, Late Submission'),

                        TextInput::make('default_amount')
                            ->label('Default Amount')
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01)
                            ->required()
                            ->minValue(0)
                            ->maxValue(9999.99),

                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('Describe when this fine type is applied'),

                        Select::make('auto_apply_trigger')
                            ->label('Auto-Apply Trigger')
                            ->options(FineType::getAutoApplyTriggers())
                            ->placeholder('Select trigger (leave empty for manual only)')
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $state ? $set('auto_apply_threshold', 1) : $set('auto_apply_threshold', null)
                            ),

                        TextInput::make('auto_apply_threshold')
                            ->label('Threshold')
                            ->numeric()
                            ->minValue(1)
                            ->placeholder('e.g., 3 for 3 missed meetings')
                            ->helperText('Number of occurrences before fine is automatically applied')
                            ->visible(fn (callable $get) => $get('auto_apply_trigger') !== null),

                        Checkbox::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Inactive fine types cannot be used for new fines'),
                    ])
                    ->using(function (array $data) {
                        $fineType = FineType::create($data);

                        Notification::make()
                            ->title('Fine type created successfully')
                            ->success()
                            ->send();

                        return $fineType;
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->extraAttributes(['class' => 'z-9999999'])
                    ->schema([
                        TextInput::make('name')
                            ->label('Fine Type Name')
                            ->required()
                            ->unique(FineType::class, 'name', ignoreRecord: true),

                        TextInput::make('default_amount')
                            ->label('Default Amount')
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01)
                            ->required()
                            ->minValue(0)
                            ->maxValue(9999.99),

                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->maxLength(500),

                        Select::make('auto_apply_trigger')
                            ->label('Auto-Apply Trigger')
                            ->options(FineType::getAutoApplyTriggers())
                            ->placeholder('Select trigger (leave empty for manual only)')
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $state ? $set('auto_apply_threshold', 1) : $set('auto_apply_threshold', null)
                            ),

                        TextInput::make('auto_apply_threshold')
                            ->label('Threshold')
                            ->numeric()
                            ->minValue(1)
                            ->placeholder('e.g., 3 for 3 missed meetings')
                            ->helperText('Number of occurrences before fine is automatically applied')
                            ->visible(fn (callable $get) => $get('auto_apply_trigger') !== null),

                        Checkbox::make('is_active')
                            ->label('Active')
                            ->helperText('Inactive fine types cannot be used for new fines'),
                    ])
                    ->using(function (FineType $record, array $data) {
                        $record->update($data);

                        Notification::make()
                            ->title('Fine type updated successfully')
                            ->success()
                            ->send();

                        return $record;
                    }),

                DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Delete Fine Type')
                    ->modalDescription('Are you sure you want to delete this fine type? This action cannot be undone.')
                    ->modalSubmitActionLabel('Yes, delete it')
                    ->action(function (FineType $record) {
                        // Check if fine type is being used
                        if ($record->fines()->exists()) {
                            Notification::make()
                                ->title('Cannot delete fine type')
                                ->body('This fine type is already used in fines and cannot be deleted.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $record->delete();

                        Notification::make()
                            ->title('Fine type deleted successfully')
                            ->success()
                            ->send();
                    }),
            ])
            ->emptyStateHeading('No fine types found')
            ->emptyStateDescription('Once you add fine types, they will appear here.')
            ->emptyStateIcon('heroicon-o-tag')
            ->emptyStateActions([
                CreateAction::make()
                    ->label('Add Fine Type')
                    ->button(),
            ])
            ->striped()
            ->deferLoading();
    }

    public function render(): View
    {
        return view('livewire.admin.fine-types-management');
    }
}
