<?php

namespace App\Livewire\Admin;

use App\Models\Event;
use App\Models\EventRegistration;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EventAttendees extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public Event $event;

    public function mount(Event $event): void
    {
        $this->event = $event->load('registrations.user');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->event->registrations()->with('user'))
            ->columns([
                ImageColumn::make('user.photo')
                    ->label('')
                    ->getStateUsing(fn (EventRegistration $record): string => $record->user->avatar_url)
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name='.urlencode($record->user->name).'&color=FFFFFF&background=6366f1'),

                TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Email copied'),

                TextColumn::make('rsvp_status')
                    ->label('RSVP Status')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'attending' => 'success',
                        'not_attending' => 'danger',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'attending' => 'Attending',
                        'not_attending' => 'Not Attending',
                        default => ucfirst($state),
                    }),

                TextColumn::make('registered_at')
                    ->label('Registered')
                    ->date('M d, Y')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Registration Status')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'registered' => 'success',
                        'cancelled' => 'danger',
                        'waitlist' => 'warning',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
            ])
            ->recordActions([
                Action::make('cancel')
                    ->label('Cancel Registration')
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->visible(fn (EventRegistration $record): bool => $record->rsvp_status === 'attending')
                    ->requiresConfirmation()
                    ->modalHeading('Cancel Registration')
                    ->modalDescription(fn (EventRegistration $record): string => "Are you sure you want to cancel {$record->user->name}'s registration? They will no longer be marked as attending.")
                    ->modalSubmitActionLabel('Yes, Cancel Registration')
                    ->modalCancelActionLabel('Keep Registration')
                    ->action(function (EventRegistration $record) {
                        $record->update(['rsvp_status' => 'not_attending']);

                        Notification::make()
                            ->title('Registration cancelled')
                            ->body("{$record->user->name}'s registration has been cancelled.")
                            ->success()
                            ->send();
                    }),

                Action::make('restore')
                    ->label('Restore Registration')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (EventRegistration $record): bool => $record->rsvp_status === 'not_attending')
                    ->requiresConfirmation()
                    ->modalHeading('Restore Registration')
                    ->modalDescription(fn (EventRegistration $record): string => "Are you sure you want to restore {$record->user->name}'s registration?")
                    ->modalSubmitActionLabel('Yes, Restore')
                    ->modalCancelActionLabel('Cancel')
                    ->action(function (EventRegistration $record) {
                        $record->update(['rsvp_status' => 'attending']);

                        Notification::make()
                            ->title('Registration restored')
                            ->body("{$record->user->name}'s registration has been restored.")
                            ->success()
                            ->send();
                    }),

                Action::make('remove')
                    ->label('Remove Attendee')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Remove Attendee')
                    ->modalDescription(fn (EventRegistration $record): string => "Are you sure you want to remove {$record->user->name} from this event? This will delete their registration entirely.")
                    ->modalSubmitActionLabel('Yes, Remove')
                    ->modalCancelActionLabel('Cancel')
                    ->action(function (EventRegistration $record) {
                        $userName = $record->user->name;
                        $record->delete();

                        Notification::make()
                            ->title('Attendee removed')
                            ->body("{$userName} has been removed from the event.")
                            ->success()
                            ->send();
                    }),
            ])
            ->emptyStateHeading('No registrations yet')
            ->emptyStateDescription('When users register for this event, they will appear here.')
            ->emptyStateIcon('heroicon-o-user-group')
            ->striped()
            ->deferLoading();
    }

    public function render(): View
    {
        return view('livewire.admin.event-attendees');
    }
}
