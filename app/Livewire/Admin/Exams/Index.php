<?php

namespace App\Livewire\Admin\Exams;

use App\Models\Exam;
use App\Services\ExamService;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public ?string $statusFilter = null;

    public int $perPage = 20;

    public function delete($id)
    {
        $exam = Exam::withTrashed()->find($id);

        if ($exam->trashed()) {
            $exam->forceDelete();
            session()->flash('message', 'Exam permanently deleted.');
        } else {
            app(ExamService::class)->deleteExam($exam);
            session()->flash('message', 'Exam moved to trash.');
        }
    }

    public function toggleStatus($id)
    {
        $exam = Exam::find($id);
        $newStatus = app(ExamService::class)->toggleStatus($exam);
        session()->flash('message', "Exam status changed to {$newStatus}.");
    }

    public function render()
    {
        $query = Exam::with(['creator', 'questions'])->withCount('questions');

        if ($this->search) {
            $query->where('title', 'like', "%{$this->search}%");
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $exams = $query->orderByDesc('created_at')->paginate($this->perPage);

        // Add total marks to each exam
        $exams->getCollection()->transform(function ($exam) {
            $exam->total_marks = app(ExamService::class)->getExamTotalMarks($exam);

            return $exam;
        });

        return view('livewire.admin.exams.index', compact('exams'));
    }
}
