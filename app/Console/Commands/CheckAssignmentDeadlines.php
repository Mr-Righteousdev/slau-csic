<?php

namespace App\Console\Commands;

use App\Models\StaffNotification;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckAssignmentDeadlines extends Command
{
    protected $signature = 'assignments:check-deadlines';

    protected $description = 'Check for assignments due soon and notify teachers';

    public function handle(): int
    {
        $this->info('Checking for assignments due in the next 3 days...');

        try {
            $assignments = $this->getAssignmentsDueSoon();

            $notified = 0;
            foreach ($assignments as $assignment) {
                $creator = $this->getAssignmentCreator($assignment);
                if ($creator) {
                    $this->notifyTeacher($creator, $assignment);
                    $notified++;
                }
            }

            $this->info("Completed. Checked {$assignments->count()} assignments, notified {$notified} teachers.");

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error: '.$e->getMessage());
            Log::error('CheckAssignmentDeadlines error: '.$e->getMessage());

            return self::FAILURE;
        }
    }

    protected function getAssignmentsDueSoon()
    {
        return \App\Models\Event::where('status', 'scheduled')
            ->where('start_date', '<=', now()->addDays(3))
            ->where('start_date', '>=', now())
            ->get();
    }

    protected function getAssignmentCreator($assignment)
    {
        return User::find($assignment->organizer_id);
    }

    protected function notifyTeacher(User $user, $assignment): void
    {
        StaffNotification::create([
            'staff_id' => $user->id,
            'type' => 'assignment_due',
            'title' => "Assignment Due Soon: {$assignment->title}",
            'message' => "Your assignment '{$assignment->title}' is due on {$assignment->start_date->format('M j, Y')} at {$assignment->start_date->format('g:i A')}",
            'action_url' => route('events.show', $assignment->slug),
            'priority' => 'high',
            'action_required' => true,
        ]);
    }
}
