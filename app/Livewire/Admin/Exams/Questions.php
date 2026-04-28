<?php

namespace App\Livewire\Admin\Exams;

use App\Models\Exam;
use App\Services\ExamService;
use Livewire\Component;

class Questions extends Component
{
    public Exam $exam;

    public int $totalMarks = 0;

    public function mount(Exam $exam): void
    {
        $this->exam = $exam;
        $this->calculateTotalMarks();
    }

    public function getQuestionsProperty()
    {
        return $this->exam->questions()->with('question.options')->get();
    }

    public function removeQuestion($examQuestionId)
    {
        app(ExamService::class)->removeQuestion($this->exam, $examQuestionId);

        session()->flash('message', 'Question removed from exam.');
        $this->calculateTotalMarks();
    }

    public function reorder($orderedIds)
    {
        app(ExamService::class)->reorderQuestions($this->exam, $orderedIds);

        session()->flash('message', 'Questions reordered.');
    }

    private function calculateTotalMarks(): void
    {
        $this->totalMarks = app(ExamService::class)->getExamTotalMarks($this->exam);
    }

    public function render()
    {
        return view('livewire.admin.exams.questions', [
            'questions' => $this->questions,
        ]);
    }
}
