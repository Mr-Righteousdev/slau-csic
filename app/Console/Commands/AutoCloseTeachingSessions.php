<?php

namespace App\Console\Commands;

use App\Models\Meeting;
use App\Services\AttendanceService;
use App\Services\LeaderboardService;
use Illuminate\Console\Command;

class AutoCloseTeachingSessions extends Command
{
    protected $signature = 'sessions:auto-status';

    protected $description = 'Automatically manage teaching session status based on time';

    public function handle(): int
    {
        $this->info('Checking teaching session statuses...');

        $this->autoStartSessions();
        $this->autoEndSessions();

        $this->info('Teaching session status check complete.');

        return Command::SUCCESS;
    }

    protected function autoStartSessions(): void
    {
        $sessionsToStart = Meeting::teachingSessions()
            ->whereNull('started_at')
            ->where('scheduled_at', '<=', now())
            ->get();

        if ($sessionsToStart->isEmpty()) {
            $this->line('No sessions to auto-start.');

            return;
        }

        $this->info("Auto-starting {$sessionsToStart->count()} session(s).");

        foreach ($sessionsToStart as $session) {
            $this->info("Starting: {$session->title}");

            $session->update([
                'started_at' => now(),
                'attendance_open' => true,
            ]);

            $this->line("  ✓ Attendance opened for {$session->title}");
        }
    }

    protected function autoEndSessions(): void
    {
        $sessionsToEnd = Meeting::teachingSessions()
            ->where('attendance_open', true)
            ->whereNotNull('ended_at')
            ->where('ended_at', '<=', now())
            ->get();

        if ($sessionsToEnd->isEmpty()) {
            $this->line('No sessions to auto-end.');

            return;
        }

        $this->info("Auto-ending {$sessionsToEnd->count()} session(s).");

        $attendanceService = app(AttendanceService::class);
        $leaderboardService = app(LeaderboardService::class);

        foreach ($sessionsToEnd as $session) {
            $this->info("Ending: {$session->title}");

            $attendanceService->finalizeAbsences($session);

            $session->update(['attendance_open' => false]);

            $leaderboardService->recalculateAll();

            $presentCount = $session->attendance()->where('status', 'present')->count();
            $lateCount = $session->attendance()->where('status', 'late')->count();
            $absentCount = $session->attendance()->where('status', 'absent')->count();

            $this->line("  ✓ Finalized: {$presentCount} present, {$lateCount} late, {$absentCount} absent");
            $this->line('  ✓ Scores recalculated');
        }
    }
}
