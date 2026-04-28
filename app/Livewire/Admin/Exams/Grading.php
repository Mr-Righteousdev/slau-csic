<?php

namespace App\Livewire\Admin\Exams;

use App\Models\ExamAttempt;
use App\Services\ExamGradingService;
use Livewire\Component;

class Grading extends Component
{
    public ExamAttempt $attempt;

    public array $manualGrades = [];

    public string $adminNotes = '';

    public function mount(ExamAttempt $attempt): void
    {
        $this->attempt = $attempt->load(['answers.examQuestion.question.options', 'exam', 'user']);
        $this->adminNotes = $attempt->admin_notes ?? '';

        foreach ($this->attempt->answers as $answer) {
            $this->manualGrades[$answer->id] = [
                'marks_awarded' => $answer->marks_awarded,
                'is_correct' => $answer->is_correct,
            ];
        }
    }

    public function getQuestionsProperty()
    {
        return $this->attempt->answers->map(fn ($a) => $a);
    }

    public function regrade(): void
    {
        app(ExamGradingService::class)->gradeAttempt($this->attempt->fresh(['answers.examQuestion.question.options']));
        $this->redirect(route('admin.exams.grading', $this->attempt));
    }

    public function updateManualGrade(int $answerId, int $marks, ?bool $isCorrect): void
    {
        $this->attempt->answers()->where('id', $answerId)->update([
            'marks_awarded' => $marks,
            'is_correct' => $isCorrect,
        ]);

        // Recalculate total score
        $this->attempt = $this->attempt->fresh();
        $totalScore = $this->attempt->answers->sum('marks_awarded');
        $totalMarks = $this->attempt->exam->questions->sum(fn ($eq) => $eq->effective_marks);
        $passed = $totalMarks > 0 ? ($totalScore / $totalMarks) >= ($this->attempt->exam->passing_score / 100) : false;
        $this->attempt->update(['total_score' => $totalScore, 'passed' => $passed]);
    }

    public function saveNotes(): void
    {
        $this->attempt->update(['admin_notes' => $this->adminNotes]);
        session()->flash('message', 'Notes saved.');
    }

    public function render()
    {
        return view('livewire.admin.exams.grading');
    }
}
