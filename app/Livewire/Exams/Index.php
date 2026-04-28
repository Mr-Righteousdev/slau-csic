<?php

namespace App\Livewire\Exams;

use App\Models\Exam;
use Livewire\Component;

class Index extends Component
{
    public $exams;

    public function mount(): void
    {
        $this->exams = Exam::published()
            ->with(['questions', 'attempts' => function ($query) {
                $query->where('user_id', auth()->id());
            }])
            ->get();
    }

    public function render()
    {
        return view('livewire.exams.index', [
            'exams' => $this->exams,
        ]);
    }
}
