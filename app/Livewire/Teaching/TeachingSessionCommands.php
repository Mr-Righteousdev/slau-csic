<?php

namespace App\Livewire\Teaching;

use App\Models\Meeting;
use App\Services\AttendanceService;
use App\Services\LeaderboardService;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class TeachingSessionCommands extends Component
{
    public string $output = '';

    public string $status = '';

    public function runAutoStatus(): void
    {
        $this->output = '';
        $this->status = 'running';

        $result = Artisan::call('sessions:auto-status');

        $this->output = Artisan::output();
        $this->status = 'success';

        session()->flash('success', 'Auto-status command executed successfully!');
    }

    public function recalculateScores(): void
    {
        $this->output = '';
        $this->status = 'running';

        $leaderboardService = app(LeaderboardService::class);
        $leaderboardService->recalculateAll();

        $this->output = 'Scores recalculated for all members.';
        $this->status = 'success';

        session()->flash('success', 'Scores recalculated for all members!');
    }

    public function finalizeAbsences(): void
    {
        $this->output = '';
        $this->status = 'running';

        $attendanceService = app(AttendanceService::class);

        $ongoingSessions = Meeting::teachingSessions()
            ->where('attendance_open', true)
            ->get();

        $totalFinalized = 0;

        foreach ($ongoingSessions as $session) {
            $count = $attendanceService->finalizeAbsences($session);
            $totalFinalized += $count;
        }

        $this->output = "Finalized absences for {$ongoingSessions->count()} session(s). Total absent marked: {$totalFinalized}";
        $this->status = 'success';

        session()->flash('success', "Finalized absences for {$ongoingSessions->count()} session(s)!");
    }

    public function getOngoingSessionsCount(): int
    {
        return Meeting::teachingSessions()
            ->where('attendance_open', true)
            ->count();
    }

    public function getScheduledSessionsCount(): int
    {
        return Meeting::teachingSessions()
            ->whereNull('started_at')
            ->where('scheduled_at', '<=', now())
            ->count();
    }

    public function render()
    {
        return view('livewire.teaching.teaching-session-commands');
    }
}
