<?php

namespace App\Livewire\Admin;

use App\Models\CtfCompetition;
use App\Models\CtfSubmission;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class CtfSubmissions extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $competitionFilter = null;

    public ?int $challengeFilter = null;

    public ?string $userFilter = '';

    public ?string $correctFilter = 'all';

    public function render(): View
    {
        $query = CtfSubmission::query()
            ->with(['challenge', 'user'])
            ->when($this->competitionFilter, fn ($q) => $q->whereHas('challenge', fn ($c) => $c->where('ctf_competition_id', $this->competitionFilter)))
            ->when($this->challengeFilter, fn ($q) => $q->where('ctf_challenge_id', $this->challengeFilter))
            ->when($this->userFilter, fn ($q) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$this->userFilter}%")->orWhere('student_id', 'like', "%{$this->userFilter}%")))
            ->when($this->correctFilter !== 'all', fn ($q) => $q->where('is_correct', $this->correctFilter === 'correct'))
            ->latest();

        $submissions = $query->paginate(50);
        $competitions = CtfCompetition::where('status', 'published')->get();

        return view('livewire.admin.ctf-submissions', compact('submissions', 'competitions'));
    }

    public function updatedCompetitionFilter(?int $competitionId): void
    {
        $this->challengeFilter = null;
        $this->resetPage();
    }
}
