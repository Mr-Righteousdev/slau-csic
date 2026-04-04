<?php

namespace App\Livewire\Teaching;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TeacherAnalytics extends Component
{
    public $stats = [];

    public $recentActivity = [];

    public $performanceData = [];

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $user = Auth::user();

        $this->stats = [
            'totalStudents' => User::where('membership_status', 'active')->count(),
            'totalEvents' => \App\Models\Event::where('organizer_id', $user->id)->count(),
            'upcomingEvents' => \App\Models\Event::where('organizer_id', $user->id)
                ->where('start_date', '>=', now())
                ->count(),
            'totalMeetings' => \App\Models\Meeting::count(),
        ];

        $this->recentActivity = \App\Models\Event::where('organizer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.teaching.teacher-analytics');
    }
}
