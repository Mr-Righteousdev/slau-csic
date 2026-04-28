<?php

namespace App\Livewire\Admin\Exams;

use App\Models\Exam;
use App\Models\ExamAttempt;
use Livewire\Component;
use Livewire\WithPagination;

class Submissions extends Component
{
    use WithPagination;

    public ?int $examFilter = null;

    public string $search = '';

    public ?string $statusFilter = null;

    public function getSubmissionsProperty()
    {
        $query = ExamAttempt::with(['user', 'exam'])->completed();

        if ($this->examFilter) {
            $query->where('exam_id', $this->examFilter);
        }

        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        if ($this->statusFilter === 'passed') {
            $query->where('passed', true);
        } elseif ($this->statusFilter === 'failed') {
            $query->where('passed', false);
        } elseif ($this->statusFilter === 'pending') {
            $query->where('passed', null);
        }

        return $query->orderByDesc('submitted_at')->paginate(20);
    }

    public function getExamsProperty()
    {
        return Exam::orderBy('title')->get();
    }

    public function render()
    {
        return view('livewire.admin.exams.submissions');
    }
}
