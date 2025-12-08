<?php

namespace App\Livewire\Admin;

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
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MemberManagement extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query()->with(['roles', 'approvedByUser', 'suspendedByUser']))
            ->columns([
                ImageColumn::make('profile_photo')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name='.urlencode($record->name).'&color=FFFFFF&background=6366f1'),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Email copied'),

                TextColumn::make('student_id')
                    ->label('Student ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('program')
                    ->label('Program')
                    ->searchable()
                    ->sortable()
                    ->limit(20)
                    ->tooltip(fn ($record): string => $record->program),

                TextColumn::make('year_of_study')
                    ->label('Year')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state ? "Year {$state}" : '-'),

                TextColumn::make('membership_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'associate' => 'warning',
                        'alumni' => 'gray',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                TextColumn::make('membership_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'pending' => 'warning',
                        'suspended' => 'danger',
                        'inactive' => 'gray',
                        'rejected' => 'danger',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->formatStateUsing(fn ($state): string => str_replace('_', ' ', ucfirst($state)))
                    ->badge()
                    ->color('primary')
                    ->separator(','),

                TextColumn::make('joined_at')
                    ->label('Joined')
                    ->date('M d, Y')
                    ->sortable(),

                TextColumn::make('approved_at')
                    ->label('Approved')
                    ->date('M d, Y')
                    ->sortable()
                    ->placeholder('Pending'),
            ])
            ->filters([
                SelectFilter::make('membership_type')
                    ->options([
                        'active' => 'Active Member',
                        'associate' => 'Associate Member',
                        'alumni' => 'Alumni',
                    ])
                    ->label('Membership Type'),

                SelectFilter::make('membership_status')
                    ->options([
                        'active' => 'Active',
                        'pending' => 'Pending',
                        'suspended' => 'Suspended',
                        'inactive' => 'Inactive',
                        'rejected' => 'Rejected',
                    ])
                    ->label('Membership Status'),

                SelectFilter::make('year_of_study')
                    ->options([
                        1 => 'Year 1',
                        2 => 'Year 2',
                        3 => 'Year 3',
                        4 => 'Year 4',
                        5 => 'Year 5',
                    ])
                    ->label('Year of Study'),

                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->label('Roles')
                    ->searchable(),

                Filter::make('pending_approval')
                    ->label('Pending Approval')
                    ->query(fn (Builder $query): Builder => $query->where('membership_status', 'pending')),

                Filter::make('approved')
                    ->label('Approved')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('approved_at')),

                Filter::make('joined_at')
                    ->form([
                        DatePicker::make('joined_from')
                            ->label('Joined From'),
                        DatePicker::make('joined_until')
                            ->label('Joined Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['joined_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('joined_at', '>=', $date),
                            )
                            ->when(
                                $data['joined_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('joined_at', '<=', $date),
                            );
                    }),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add New Member')
                    ->modalHeading('Add New Club Member')
                    ->slideOver()
                    ->schema([
                        FileUpload::make('profile_photo')
                            ->label('Profile Photo')
                            ->image()
                            ->imageEditor()
                            ->directory('users/profile-photos')
                            ->avatar()
                            ->circleCropper()
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif']),
                        
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                
                                TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->unique(User::class, 'email')
                                    ->maxLength(255),
                                
                                TextInput::make('student_id')
                                    ->label('Student ID')
                                    ->required()
                                    ->unique(User::class, 'student_id')
                                    ->maxLength(50),
                                
                                TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(20),
                                
                                TextInput::make('program')
                                    ->label('Program/Course')
                                    ->maxLength(100),
                                
                                Select::make('year_of_study')
                                    ->label('Year of Study')
                                    ->options([
                                        1 => 'Year 1',
                                        2 => 'Year 2',
                                        3 => 'Year 3',
                                        4 => 'Year 4',
                                        5 => 'Year 5',
                                    ]),
                            ]),
                        
                        Grid::make(2)
                            ->schema([
                                Select::make('membership_type')
                                    ->label('Membership Type')
                                    ->options([
                                        'active' => 'Active Member',
                                        'associate' => 'Associate Member',
                                        'alumni' => 'Alumni',
                                    ])
                                    ->required(),
                                
                                Select::make('membership_status')
                                    ->label('Membership Status')
                                    ->options([
                                        'active' => 'Active',
                                        'pending' => 'Pending Approval',
                                        'suspended' => 'Suspended',
                                        'inactive' => 'Inactive',
                                    ])
                                    ->required(),
                                
                                DatePicker::make('joined_at')
                                    ->label('Joined Date')
                                    ->default(now()),
                                
                                CheckboxList::make('privacy_settings')
                                    ->label('Privacy Settings')
                                    ->options([
                                        'show_email' => 'Show Email',
                                        'show_phone' => 'Show Phone',
                                        'show_discord' => 'Show Discord',
                                        'show_attendance' => 'Show Attendance Stats',
                                        'show_program' => 'Show Program',
                                        'show_year' => 'Show Year of Study',
                                        'allow_contact' => 'Allow Contact Form',
                                        'show_profile' => 'Show in Public Directory',
                                    ])
                                    ->columns(2)
                                    ->default([
                                        'show_discord' => true,
                                        'show_program' => true,
                                        'show_year' => true,
                                        'allow_contact' => true,
                                        'show_profile' => true,
                                    ]),
                            ]),
                        
                        Select::make('roles')
                            ->label('Assign Roles')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload()
                            ->searchable(),
                    ])
                    ->using(function (array $data) {
                        $user = User::create($data);
                        if (isset($data['roles'])) {
                            $user->syncRoles($data['roles']);
                        }
                        
                        Notification::make()
                            ->title('Member created successfully')
                            ->success()
                            ->send();
                        
                        return $user;
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->extraAttributes(['class' => 'z-9999999'])
                        ->schema([
                            Section::make('Member Information')
                                ->schema([
                                    FileUpload::make('profile_photo')
                                        ->label('Profile Photo')
                                        ->image()
                                        ->disabled(),
                                    
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('name')->disabled(),
                                            TextInput::make('email')->disabled(),
                                            TextInput::make('student_id')->disabled(),
                                            TextInput::make('phone')->disabled(),
                                            TextInput::make('program')->disabled(),
                                            TextInput::make('year_of_study')->disabled(),
                                        ]),
                                    
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('membership_type')->disabled(),
                                            TextInput::make('membership_status')->disabled(),
                                            TextInput::make('joined_at')->disabled(),
                                            TextInput::make('approved_at')->disabled(),
                                        ]),
                                ]),
                            
                            Section::make('Privacy Settings')
                                ->schema([
                                    CheckboxList::make('privacy_settings')
                                        ->label('Public Profile Visibility')
                                        ->options([
                                            'show_email' => 'Show Email',
                                            'show_phone' => 'Show Phone',
                                            'show_discord' => 'Show Discord',
                                            'show_attendance' => 'Show Attendance Stats',
                                            'show_program' => 'Show Program',
                                            'show_year' => 'Show Year of Study',
                                            'allow_contact' => 'Allow Contact Form',
                                            'show_profile' => 'Show in Public Directory',
                                        ])
                                        ->disabled(),
                                ]),
                            
                            Section::make('Approval Information')
                                ->schema([
                                    TextInput::make('approval_notes')->disabled(),
                                    TextInput::make('approved_by_name')
                                        ->label('Approved By')
                                        ->default(fn ($record) => $record?->approvedByUser?->name ?? 'Not approved')
                                        ->disabled(),
                                ]),
                        ]),

                    EditAction::make()
                        ->extraAttributes(['class' => 'z-9999999'])
                        ->schema([
                            Section::make('Member Information')
                                ->schema([
                                    FileUpload::make('profile_photo')
                                        ->label('Profile Photo')
                                        ->image()
                                        ->imageEditor()
                                        ->directory('users/profile-photos')
                                        ->avatar()
                                        ->circleCropper()
                                        ->maxSize(2048)
                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif']),
                                    
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('name')
                                                ->required()
                                                ->maxLength(255),
                                            
                                            TextInput::make('email')
                                                ->email()
                                                ->required()
                                                ->unique(ignoreRecord: true)
                                                ->maxLength(255),
                                            
                                            TextInput::make('student_id')
                                                ->label('Student ID')
                                                ->required()
                                                ->unique(ignoreRecord: true)
                                                ->maxLength(50),
                                            
                                            TextInput::make('phone')
                                                ->tel()
                                                ->maxLength(20),
                                            
                                            TextInput::make('program')
                                                ->label('Program/Course')
                                                ->maxLength(100),
                                            
                                            Select::make('year_of_study')
                                                ->label('Year of Study')
                                                ->options([
                                                    1 => 'Year 1',
                                                    2 => 'Year 2',
                                                    3 => 'Year 3',
                                                    4 => 'Year 4',
                                                    5 => 'Year 5',
                                                ]),
                                        ]),
                                    
                                    Grid::make(2)
                                        ->schema([
                                            Select::make('membership_type')
                                                ->label('Membership Type')
                                                ->options([
                                                    'active' => 'Active Member',
                                                    'associate' => 'Associate Member',
                                                    'alumni' => 'Alumni',
                                                ])
                                                ->required(),
                                            
                                            Select::make('membership_status')
                                                ->label('Membership Status')
                                                ->options([
                                                    'active' => 'Active',
                                                    'pending' => 'Pending',
                                                    'suspended' => 'Suspended',
                                                    'inactive' => 'Inactive',
                                                    'rejected' => 'Rejected',
                                                ])
                                                ->required(),
                                            
                                            DatePicker::make('joined_at')
                                                ->label('Joined Date'),
                                            
                                            CheckboxList::make('privacy_settings')
                                                ->label('Privacy Settings')
                                                ->options([
                                                    'show_email' => 'Show Email',
                                                    'show_phone' => 'Show Phone',
                                                    'show_discord' => 'Show Discord',
                                                    'show_attendance' => 'Show Attendance Stats',
                                                    'show_program' => 'Show Program',
                                                    'show_year' => 'Show Year of Study',
                                                    'allow_contact' => 'Allow Contact Form',
                                                    'show_profile' => 'Show in Public Directory',
                                                ])
                                                ->columns(2),
                                        ]),
                                    
                                    Select::make('roles')
                                        ->label('Roles')
                                        ->multiple()
                                        ->relationship('roles', 'name')
                                        ->preload()
                                        ->searchable(),
                                ]),
                            
                            Section::make('Approval Information')
                                ->schema([
                                    Textarea::make('approval_notes')
                                        ->label('Approval/Rejection Notes')
                                        ->rows(3),
                                ]),
                        ])
                        ->using(function (User $record, array $data) {
                            $oldValues = $record->toArray();
                            $record->update($data);

                            if (isset($data['roles'])) {
                                $record->syncRoles($data['roles']);
                            }

                            $record->logActivity('updated', 'User', $record->id, $oldValues, $record->toArray());

                            Notification::make()
                                ->title('Member updated successfully')
                                ->success()
                                ->send();

                            return $record;
                        }),

                    Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (User $record): bool => $record->isPendingApproval())
                        ->form([
                            Textarea::make('approval_notes')
                                ->label('Approval Notes')
                                ->rows(3)
                                ->required(),
                            Select::make('roles')
                                ->label('Assign Roles')
                                ->multiple()
                                ->relationship('roles', 'name')
                                ->preload()
                                ->searchable(),
                        ])
                        ->action(function (User $record, array $data) {
                            $record->approve(Auth::user(), $data['approval_notes']);
                            
                            if (!empty($data['roles'])) {
                                $record->syncRoles($data['roles']);
                            }
                            
                            Notification::make()
                                ->title('Member approved successfully')
                                ->success()
                                ->send();
                        }),

                    Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (User $record): bool => $record->isPendingApproval())
                        ->form([
                            Textarea::make('rejection_notes')
                                ->label('Rejection Reason')
                                ->rows(3)
                                ->required(),
                        ])
                        ->action(function (User $record, array $data) {
                            $record->reject(Auth::user(), $data['rejection_notes']);
                            
                            Notification::make()
                                ->title('Member rejected')
                                ->danger()
                                ->send();
                        }),

                    Action::make('suspend')
                        ->label('Suspend')
                        ->icon('heroicon-o-no-symbol')
                        ->color('warning')
                        ->visible(fn (User $record): bool => $record->membership_status === 'active')
                        ->form([
                            Select::make('reason')
                                ->label('Suspension Reason')
                                ->options([
                                    'violation' => 'Code of Conduct Violation',
                                    'inactivity' => 'Inactivity',
                                    'graduated' => 'Graduated',
                                    'other' => 'Other',
                                ])
                                ->required(),
                            DatePicker::make('suspended_until')
                                ->label('Suspension Duration')
                                ->helperText('Leave empty for permanent suspension'),
                            Textarea::make('notes')
                                ->label('Additional Notes')
                                ->rows(3),
                        ])
                        ->action(function (User $record, array $data) {
                            $until = $data['suspended_until'] ? \Carbon\Carbon::parse($data['suspended_until']) : null;
                            $record->suspend($data['reason'], Auth::user(), $until);
                            
                            Notification::make()
                                ->title('Member suspended')
                                ->warning()
                                ->send();
                        }),

                    DeleteAction::make()
                        ->requiresConfirmation()
                        ->visible(fn (User $record): bool => !$record->isSuperAdmin())
                        ->action(function (User $record) {
                            $oldValues = $record->toArray();
                            $record->delete();

                            $record->logActivity('deleted', 'User', $record->id, $oldValues, null);

                            Notification::make()
                                ->title('Member deleted successfully')
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
                        ->form([
                            Textarea::make('bulk_approval_notes')
                                ->label('Approval Notes')
                                ->rows(3)
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(function (User $record) use ($data) {
                                if ($record->isPendingApproval()) {
                                    $record->approve(Auth::user(), $data['bulk_approval_notes']);
                                }
                            });
                            
                            Notification::make()
                                ->title('Members approved successfully')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('suspend')
                        ->label('Suspend Selected')
                        ->icon('heroicon-o-no-symbol')
                        ->color('danger')
                        ->form([
                            Select::make('reason')
                                ->label('Suspension Reason')
                                ->options([
                                    'violation' => 'Code of Conduct Violation',
                                    'inactivity' => 'Inactivity',
                                    'graduated' => 'Graduated',
                                    'other' => 'Other',
                                ])
                                ->required(),
                            DatePicker::make('suspended_until')
                                ->label('Suspension Duration')
                                ->helperText('Leave empty for permanent suspension'),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $until = $data['suspended_until'] ? \Carbon\Carbon::parse($data['suspended_until']) : null;
                            $records->each(function (User $record) use ($data, $until) {
                                if ($record->membership_status === 'active') {
                                    $record->suspend($data['reason'], Auth::user(), $until);
                                }
                            });
                            
                            Notification::make()
                                ->title('Members suspended successfully')
                                ->warning()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateHeading('No members found')
            ->emptyStateDescription('Once you add members, they will appear here.')
            ->emptyStateIcon('heroicon-o-user-group')
            ->emptyStateActions([
                CreateAction::make()
                    ->label('Add New Member')
                    ->button(),
            ])
            ->striped()
            ->deferLoading();
    }

    public function render(): View
    {
        return view('livewire.admin.member-management');
    }
}