<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PendingMembers extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query()->pendingApproval()->with(['roles']))
            ->columns([
                ImageColumn::make('photo')
                    ->label('')
                    ->getStateUsing(fn (User $record): string => $record->avatar_url)
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name='.urlencode($record->name).'&color=FFFFFF&background=6366f1'),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Email copied'),

                TextColumn::make('student_id')
                    ->label('Student ID')
                    ->searchable(),

                TextColumn::make('program')
                    ->label('Program')
                    ->searchable()
                    ->limit(25),

                TextColumn::make('year_of_study')
                    ->label('Year')
                    ->formatStateUsing(fn ($state) => "Year {$state}"),

                TextColumn::make('joined_at')
                    ->label('Applied')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('joined_at', 'desc')
            ->actions([
                Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->form([
                        Textarea::make('notes')
                            ->label('Approval Notes (optional)')
                            ->placeholder('Add any notes about this approval...'),
                    ])
                    ->action(function ($record, array $data) {
                        if (! $record instanceof User) {
                            return;
                        }
                        $record->approve(Auth::user(), $data['notes'] ?? null);

                        Notification::make()
                            ->title('Member Approved')
                            ->success()
                            ->body("{$record->name} has been approved and can now access the member dashboard.")
                            ->send();
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->form([
                        Textarea::make('notes')
                            ->label('Rejection Reason')
                            ->placeholder('Reason for rejection...')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        if (! $record instanceof User) {
                            return;
                        }
                        $record->reject(Auth::user(), $data['notes']);

                        Notification::make()
                            ->title('Member Rejected')
                            ->warning()
                            ->body("{$record->name} has been rejected.")
                            ->send();
                    }),

                Action::make('view')
                    ->label('View Details')
                    ->color('gray')
                    ->icon('heroicon-o-eye')
                    ->url(fn (User $record) => route('admin.users', ['tableFilters[search][value]' => $record->email]))
                    ->openUrlInNewTab(),
            ])
            ->emptyStateHeading('No pending approvals')
            ->emptyStateDescription('All member applications have been processed.')
            ->emptyStateIcon('heroicon-o-user-group');
    }

    public function render(): View
    {
        return view('livewire.admin.pending-members');
    }
}
