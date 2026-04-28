<?php

namespace App\Livewire\Admin;

use App\Models\CtfCompetition;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Withpagination;

class CtfCompetitions extends Component
{
    use Withpagination;

    public string $search = '';

    public string $statusFilter = 'all';

    public function render(): View
    {
        $query = CtfCompetition::query();

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search) {
            $query->where('title', 'like', "%{$this->search}%");
        }

        $competitions = $query->latest()->paginate(15);

        return view('livewire.admin.ctf-competitions', compact('competitions'));
    }

    public function delete(CtfCompetition $competition): void
    {
        $competition->delete();
        session()->flash('success', 'Competition deleted.');
    }
}
