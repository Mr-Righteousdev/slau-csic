<?php

namespace App\Livewire\Admin;

use App\Models\Event;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EventsManagement extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Event::query()->with(['organizer', 'registrations'])
            )
            ->columns([
                ImageColumn::make('banner_image')
                    ->label('Image')
                    ->size(60)
                    ->circular()
                    ->defaultImageUrl(url('/images/events/default.jpg')),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'workshop' => 'primary',
                        'competition' => 'danger',
                        'social' => 'success',
                        'meeting' => 'warning',
                        'guest_speaker' => 'info',
                        'hackathon' => 'purple',
                    }),

                TextColumn::make('start_date')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),

                TextColumn::make('location')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('max_participants')
                    ->label('Capacity')
                    ->sortable(),

                TextColumn::make('registered_count')
                    ->label('Registered')
                    ->getStateUsing(fn (Event $record): int => $record->registered_count)
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'scheduled' => 'info',
                        'ongoing' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    }),

                TextColumn::make('organizer.name')
                    ->label('Creator')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'workshop' => 'Workshop',
                        'competition' => 'Competition',
                        'social' => 'Social',
                        'meeting' => 'Meeting',
                        'guest_speaker' => 'Guest Speaker',
                        'hackathon' => 'Hackathon',
                    ]),

                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'scheduled' => 'Scheduled',
                        'ongoing' => 'Ongoing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),

                Filter::make('start_date')
                    ->form([
                        DateTimePicker::make('start_date_from'),
                        DateTimePicker::make('start_date_to'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('start_date', '>=', $date),
                            )
                            ->when(
                                $data['start_date_to'],
                                fn (Builder $query, $date): Builder => $query->whereDate('start_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                ViewAction::make()
                    ->form($this->getEventFormSchema())
                    ->modalHeading('Event Details')
                    ->disabled(),

                EditAction::make()
                    ->form($this->getEventFormSchema()),

                Action::make('duplicate')
                    ->icon('heroicon-o-document-duplicate')
                    ->label('Duplicate')
                    ->action(function (Event $record) {
                        $newEvent = $record->replicate();
                        $newEvent->title = $record->title.' (Copy)';
                        $newEvent->slug = null;
                        $newEvent->status = 'draft';
                        $newEvent->save();

                        Notification::make()
                            ->title('Event duplicated')
                            ->success()
                            ->send();
                    }),

                Action::make('mark_complete')
                    ->icon('heroicon-o-check-circle')
                    ->label('Mark Complete')
                    ->color('success')
                    ->visible(fn (Event $record): bool => $record->status === 'ongoing')
                    ->action(function (Event $record) {
                        $record->update(['status' => 'completed']);

                        Notification::make()
                            ->title('Event marked as complete')
                            ->success()
                            ->send();
                    }),

                DeleteAction::make(),
            ])

            ->emptyStateActions([
                CreateAction::make()
                    ->form($this->getEventFormSchema()),
            ]);
    }

    protected function getEventFormSchema(): array
    {
        return [
            TextInput::make('title')
                ->required()
                ->maxLength(255),

            Select::make('type')
                ->options([
                    'workshop' => 'Workshop',
                    'competition' => 'Competition',
                    'social' => 'Social',
                    'meeting' => 'Meeting',
                    'guest_speaker' => 'Guest Speaker',
                    'hackathon' => 'Hackathon',
                ])
                ->required(),

            RichEditor::make('description')
                ->required(),

            FileUpload::make('banner_image')
                ->image()
                ->directory('events')
                ->maxSize(2048),

            DateTimePicker::make('start_date')
                ->required(),

            DateTimePicker::make('end_date')
                ->required()
                ->after('start_date'),

            TextInput::make('location')
                ->required()
                ->maxLength(255),

            TextInput::make('max_participants')
                ->numeric()
                ->minValue(1)
                ->label('Capacity'),

            Toggle::make('registration_required')
                ->label('Registration Required')
                ->default(true),

            Toggle::make('is_public')
                ->label('Public Event')
                ->default(true),

            DateTimePicker::make('registration_deadline')
                ->label('Registration Deadline'),

            Select::make('status')
                ->options([
                    'draft' => 'Draft',
                    'scheduled' => 'Scheduled',
                    'ongoing' => 'Ongoing',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                ])
                ->default('scheduled')
                ->required(),

            Select::make('organizer_id')
                ->relationship('organizer', 'name')
                ->default(Auth::id())
                ->required(),
        ];
    }

    public function render(): View
    {
        return view('livewire.admin.events-management');
    }
}
