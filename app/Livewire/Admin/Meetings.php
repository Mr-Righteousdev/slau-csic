<?php

namespace App\Livewire\Admin;

use App\Models\Meeting as MeetingModel;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Meetings extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public $title = '';

    public $description = '';

    public $type = 'general';

    public $scheduled_date = '';

    public $scheduled_time = '';

    public $location = '';

    public $duration_minutes = 60;

    public $expected_attendees = 0;

    public $agenda = '';

    public $showSuccessMessage = false;

    public $createdMeeting = null;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'type' => 'required|in:general,executive,special,training,workshop',
        'scheduled_date' => 'required|date|after_or_equal:today',
        'scheduled_time' => 'required',
        'location' => 'required|string|max:255',
        'duration_minutes' => 'required|integer|min:15|max:480',
        'expected_attendees' => 'nullable|integer|min:0',
        'agenda' => 'nullable|string',
    ];

    protected $messages = [
        'title.required' => 'Meeting title is required',
        'scheduled_date.required' => 'Please select a date',
        'scheduled_date.after_or_equal' => 'Meeting date cannot be in the past',
        'scheduled_time.required' => 'Please select a time',
        'location.required' => 'Meeting location is required',
        'duration_minutes.min' => 'Meeting duration must be at least 15 minutes',
        'duration_minutes.max' => 'Meeting duration cannot exceed 8 hours',
    ];


    public function table(Table $table): Table
    {
        return $table
            ->query(MeetingModel::query()->with('creator'))
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'executive' => 'primary',
                        'special' => 'warning',
                        'training' => 'success',
                        'workshop' => 'info',
                        default => 'secondary',
                    }),
                TextColumn::make('scheduled_at')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
                TextColumn::make('location')
                    ->searchable(),
                TextColumn::make('duration_minutes')
                    ->formatStateUsing(fn ($state) => "{$state} min"),
                TextColumn::make('expected_attendees'),
                TextColumn::make('creator.name')
                    ->label('Created By'),
            ])
            ->filters([
                // Add filters here if needed
            ])
            ->recordActions([
                Action::make('viewMeeting')
                    ->label('View Details')
                    ->url(fn (MeetingModel $record) => route('admin.meeting.details', ['meeting' => $record->id])),
            ])
            ->headerActions([
                Action::make('createMeeting')
                    ->label('Create Meeting')
                    ->color('info')
                    ->action('openCreateMeetingModal'),
            ])
            ->toolbarActions([

            ]);
    }

    public function closeAddMeetingModal()
    {
        $this->dispatch('close-modal', id: 'create-meeting-modal');
    }

    public function openCreateMeetingModal()
    {
        $this->dispatch('open-modal', id: 'create-meeting-modal');
    }

    public function mount()
    {
        // Set default values
        $this->scheduled_date = today()->addDay()->format('Y-m-d');
        $this->scheduled_time = '14:00';
    }

    public function submit()
    {
        $this->validate();

        // Combine date and time
        $scheduledAt = $this->scheduled_date.' '.$this->scheduled_time;

        // Create the meeting
        $meeting = MeetingModel::create([
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'scheduled_at' => $scheduledAt,
            'location' => $this->location,
            'duration_minutes' => $this->duration_minutes,
            'expected_attendees' => $this->expected_attendees ?: 0,
            'agenda' => $this->agenda,
            'created_by' => Auth::id(),
            'attendance_open' => false,
        ]);

        // Log activity
        Auth::user()->logActivity('created', 'Meeting', $meeting->id, null, $meeting->toArray());

        // Show success message
        $this->showSuccessMessage = true;
        $this->createdMeeting = $meeting;

        // Emit event to refresh meeting list if on same page
        $this->dispatch('meeting-created', meetingId: $meeting->id);

        // Reset form after 2 seconds
        $this->resetFormLater();
    }

    public function resetFormLater()
    {
        // Reset form fields
        $this->reset([
            'title',
            'description',
            'type',
            'location',
            'agenda',
        ]);

        $this->type = 'general';
        $this->duration_minutes = 60;
        $this->expected_attendees = 0;
        $this->scheduled_date = today()->addDay()->format('Y-m-d');
        $this->scheduled_time = '14:00';
    }

    public function closeSuccessMessage()
    {
        $this->showSuccessMessage = false;
        $this->createdMeeting = null;
    }

    public function render(): View
    {
        return view('livewire.admin.meetings');
    }
}
