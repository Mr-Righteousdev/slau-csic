<?php

namespace App\Livewire\Super;

use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkAction;
use App\Models\ActivityLog;
use Filament\Forms\Components\DatePicker;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Filters\SelectFilter;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Notifications\Notification;

class ManageUsers extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithActions;

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query())
            ->columns([

                ImageColumn::make('profile_photo')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(fn($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&color=FFFFFF&background=6366f1'),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Email copied to clipboard'),

                TextColumn::make('student_id')
                    ->label('Student ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('membership_type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'associate' => 'warning',
                        'alumni' => 'gray',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn($state) => ucfirst($state))
                    ->sortable(),

                TextColumn::make('membership_status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'pending' => 'warning',
                        'suspended' => 'danger',
                        'inactive' => 'gray',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn($state) => ucfirst($state))
                    ->sortable(),

                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->formatStateUsing(fn($state): string => str_replace('_', ' ', ucfirst($state)))
                    ->badge()
                    ->color('primary')
                    ->separator(','),

                TextColumn::make('year_of_study')
                    ->label('Year')
                    ->sortable(),

                TextColumn::make('program')
                    ->label('Program')
                    ->sortable()
                    ->limit(20)
                    ->tooltip(fn($state) => $state),

                IconColumn::make('is_discord_member')
                    ->label('Discord')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),

                TextColumn::make('joined_at')
                    ->label('Joined')
                    ->date('M d, Y')
                    ->sortable(),

                TextColumn::make('attendance_count')
                    ->label('Attendance')
                    ->numeric()
                    ->sortable()
                    ->description(fn($record) => $record->getAttendanceRate() . '% rate'),

                TextColumn::make('meetings_this_semester')
                    ->label('Semester')
                    ->getStateUsing(fn($record) => $record->meetingsThisSemester())
                    ->description(fn($record) => $record->isActiveThisSemester() ? 'Active' : 'Inactive')
                    ->color(fn($record) => $record->isActiveThisSemester() ? 'success' : 'warning'),
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

                TernaryFilter::make('is_discord_member')
                    ->label('Discord Member')
                    ->boolean()
                    ->trueLabel('Has Discord')
                    ->falseLabel('No Discord'),

                Filter::make('joined_at')
                    ->schema([
                        DatePicker::make('joined_from')
                            ->label('Joined From'),
                        DatePicker::make('joined_until')
                            ->label('Joined Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['joined_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('joined_at', '>=', $date),
                            )
                            ->when(
                                $data['joined_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('joined_at', '<=', $date),
                            );
                    }),

                Filter::make('executive_board')
                    ->label('Executive Board')
                    ->query(fn(Builder $query): Builder => $query->executiveBoard()),

                Filter::make('active_this_semester')
                    ->label('Active This Semester')
                    ->query(fn(Builder $query): Builder => $query->whereHas('attendance', function ($q) {
                        $semesterStart = now()->month >= 9
                            ? now()->setMonth(9)->startOfMonth()
                            : now()->setMonth(2)->startOfMonth();
                        $q->whereHas('meeting', fn($q) => $q->where('scheduled_at', '>=', $semesterStart));
                    }, '>=', 2)),
            ])
            ->headerActions([
                CreateAction::make()
                    ->modalHeading('Create New User')
                    ->slideOver()
                    ->label('Add New Member')
                    ->modalHeading('Add New Club Member')
                    ->schema([
                        FileUpload::make('profile_photo')  // Changed from 'logo' to 'profile_photo' to match your model
                            ->label('Profile Photo')
                            ->image()
                            ->imageEditor()
                            ->directory('users/profile-photos')
                            ->downloadable()
                            ->avatar()
                            ->circleCropper()
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif']),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(User::class, 'email')
                            ->maxLength(255),
                        TextInput::make('password')
                            ->password()
                            ->required()
                            ->dehydrateStateUsing(fn($state) => bcrypt($state))
                            ->revealable(),
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
                                'inactive' => 'Inactive',
                            ])
                            ->required(),
                        DatePicker::make('joined_at')
                            ->label('Joined Date')
                            ->default(now()),
                        Checkbox::make('is_discord_member')
                            ->label('Discord Member'),
                        TextInput::make('discord_username')
                            ->label('Discord Username')
                            ->maxLength(50),
                        Select::make('roles')
                            ->label('Assign Roles')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload()
                            ->searchable()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ])
                    ->using(function (array $data) {
                        $user = User::create($data);
                        if (isset($data['roles'])) {
                            $user->syncRoles($data['roles']);
                        }
                        if (User::findOrFail($user->id)) {
                            Notification::make()
                                ->title('User created successfully')
                                ->icon('heroicon-o-check-circle')
                                ->iconColor('success')
                                ->color('success')
                                ->send();
                        }
                        // Log activity
                        $user->logActivity('created', 'User', $user->id, null, $user->toArray());

                        return $user;
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->extraAttributes(['class' => 'z-9999999'])
                        ->schema([
                            TextInput::make('name')
                                ->disabled(),
                            TextInput::make('email')
                                ->disabled(),
                            TextInput::make('student_id')
                                ->disabled(),
                            TextInput::make('phone')
                                ->disabled(),
                            TextInput::make('program')
                                ->disabled(),
                            TextInput::make('year_of_study')
                                ->disabled(),
                            TextInput::make('membership_type')
                                ->disabled(),
                            TextInput::make('membership_status')
                                ->disabled(),
                            TextInput::make('joined_at')
                                ->disabled(),
                            TextInput::make('discord_username')
                                ->disabled(),
                            TextInput::make('github_username')
                                ->disabled(),
                            TextInput::make('linkedin_url')
                                ->disabled(),
                            TextInput::make('bio')
                                ->disabled()
                                ->columnSpanFull(),
                        ]),

                    EditAction::make()
                        ->modalHeading('Create New User')
                        ->slideOver()
                        ->extraAttributes(['class' => 'z-9999999'])
                        ->schema([
                            FileUpload::make('profile_photo')  // Changed from 'logo' to 'profile_photo' to match your model
                                ->label('Profile Photo')
                                ->image()
                                ->imageEditor()
                                ->directory('users/profile-photos')
                                ->downloadable()
                                ->avatar()
                                ->circleCropper()
                                ->maxSize(2048)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif']),
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
                                ])
                                ->required(),
                            DatePicker::make('joined_at')
                                ->label('Joined Date'),
                            Checkbox::make('is_discord_member')
                                ->label('Discord Member'),
                            TextInput::make('discord_username')
                                ->label('Discord Username')
                                ->maxLength(50),
                            TextInput::make('github_username')
                                ->label('GitHub Username')
                                ->maxLength(50),
                            TextInput::make('linkedin_url')
                                ->label('LinkedIn URL')
                                ->url()
                                ->maxLength(255),
                            TextInput::make('bio')
                                ->label('Bio')
                                ->maxLength(1000)
                                ->columnSpanFull(),
                            Select::make('roles')
                                ->label('Roles')
                                ->multiple()
                                ->relationship('roles', 'name')
                                ->preload()
                                ->searchable(),
                        ])
                        ->using(function (User $record, array $data) {
                            $oldValues = $record->toArray();
                            $record->update($data);

                            if (isset($data['roles'])) {
                                $record->syncRoles($data['roles']);
                            }

                            // Log activity
                            $record->logActivity('updated', 'User', $record->id, $oldValues, $record->toArray());

                            Notification::make()
                                ->title('Edited successfully')
                                ->icon('heroicon-o-document-text')
                                ->iconColor('success')
                                ->color('success')
                                ->send();

                            return $record;
                        }),

                    Action::make('reset_password')
                        ->label('Reset Password')
                        ->icon('heroicon-o-key')
                        ->color('warning')
                        ->schema([
                            TextInput::make('password')
                                ->label('New Password')
                                ->password()
                                ->required()
                                ->confirmed()
                                ->revealable(),
                            TextInput::make('password_confirmation')
                                ->label('Confirm Password')
                                ->password()
                                ->required()
                                ->revealable(),
                        ])
                        ->action(function (User $record, array $data) {
                            $record->update([
                                'password' => bcrypt($data['password']),
                            ]);

                            $record->logActivity('password_reset', 'User', $record->id, null, ['password_changed' => true]);

                            // You might want to send email notification here
                        }),

                    Action::make('impersonate')
                        ->label('Impersonate')
                        ->icon('heroicon-o-user')
                        ->color('gray')
                        ->action(function (User $record) {
                            Auth::user()->impersonate($record);
                            return redirect('/dashboard');
                        })
                        ->visible(fn(User $record) => Auth::user()->canImpersonate() && Auth::user()->canBeImpersonated()),

                    Action::make('toggle_status')
                        ->label(fn(User $record) => $record->membership_status === 'active' ? 'Suspend' : 'Activate')
                        ->icon(fn(User $record) => $record->membership_status === 'active' ? 'heroicon-o-no-symbol' : 'heroicon-o-check')
                        ->color(fn(User $record) => $record->membership_status === 'active' ? 'danger' : 'success')
                        ->action(function (User $record) {
                            $oldStatus = $record->membership_status;
                            $newStatus = $oldStatus === 'active' ? 'suspended' : 'active';

                            $record->update(['membership_status' => $newStatus]);

                            $record->logActivity(
                                'status_changed',
                                'User',
                                $record->id,
                                ['membership_status' => $oldStatus],
                                ['membership_status' => $newStatus]
                            );
                        }),

                    Action::make('view_attendance')
                        ->label('Attendance History')
                        ->icon('heroicon-o-clipboard-document-list')
                        ->color('info')
                        ->url(fn(User $record) => route('admin.users', $record)),

                    DeleteAction::make()
                        ->requiresConfirmation()
                        ->action(function (User $record) {
                            $oldValues = $record->toArray();
                            $record->delete();

                            // Log activity
                            ActivityLog::create([
                                'user_id' => Auth::user()->id,
                                'action' => 'deleted',
                                'model' => 'User',
                                'model_id' => $record->id,
                                'description' => "Deleted user {$record->name}",
                                'old_values' => $oldValues,
                                'ip_address' => request()->ip(),
                                'user_agent' => request()->userAgent(),
                            ]);

                            Notification::make()
                                ->title('User deleted successfully')
                                ->icon('heroicon-o-trash')
                                ->iconColor('danger')
                                ->color('success')
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
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(function (Collection $records) {
                            $records->each(function ($record) {
                                $oldStatus = $record->membership_status;
                                $record->update(['membership_status' => 'active']);

                                $record->logActivity(
                                    'bulk_status_changed',
                                    'User',
                                    $record->id,
                                    ['membership_status' => $oldStatus],
                                    ['membership_status' => 'active']
                                );
                            });
                        })
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('suspend')
                        ->label('Suspend Selected')
                        ->icon('heroicon-o-no-symbol')
                        ->color('danger')
                        ->action(function (Collection $records) {
                            $records->each(function ($record) {
                                $oldStatus = $record->membership_status;
                                $record->update(['membership_status' => 'suspended']);

                                $record->logActivity(
                                    'bulk_status_changed',
                                    'User',
                                    $record->id,
                                    ['membership_status' => $oldStatus],
                                    ['membership_status' => 'suspended']
                                );
                            });
                        })
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('assign_role')
                        ->label('Assign Role')
                        ->icon('heroicon-o-user-plus')
                        ->color('primary')
                        ->schema([
                            Select::make('role')
                                ->label('Role')
                                ->options(Role::all()->pluck('name', 'id'))
                                ->searchable()
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $role = Role::find($data['role']);
                            $records->each(function ($record) use ($role) {
                                $record->assignRole($role);

                                $record->logActivity(
                                    'role_assigned',
                                    'User',
                                    $record->id,
                                    null,
                                    ['role_assigned' => $role->name]
                                );
                            });
                        }),

                    DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateHeading('No users found')
            ->emptyStateDescription('Once you add users, they will appear here.')
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
        return view('livewire.super.manage-users');
    }
}
