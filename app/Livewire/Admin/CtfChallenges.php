<?php

namespace App\Livewire\Admin;

use App\Models\CtfChallenge;
use App\Models\CtfCompetition;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class CtfChallenges extends Component
{
    use WithPagination;

    public CtfCompetition $competition;

    public string $search = '';

    public string $categoryFilter = 'all';

    public function mount(CtfCompetition $competition): void
    {
        $this->competition = $competition;
    }

    public function render(): View
    {
        $challenges = $this->competition->challenges()
            ->with('category')
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->categoryFilter !== 'all', fn ($q) => $q->where('ctf_category_id', $this->categoryFilter))
            ->orderBy('sort_order')
            ->orderBy('points')
            ->paginate(20);

        return view('livewire.admin.ctf-challenges', compact('challenges'));
    }

    public function delete(CtfChallenge $challenge): void
    {
        $challenge->delete();
        session()->flash('success', 'Challenge deleted.');
    }

    public function toggleActive(CtfChallenge $challenge): void
    {
        $challenge->update(['is_active' => ! $challenge->is_active]);
    }
}
