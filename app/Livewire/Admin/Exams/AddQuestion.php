<?php

namespace App\Livewire\Admin\Exams;

use App\Models\Exam;
use App\Services\ExamService;
use Livewire\Component;

class AddQuestion extends Component
{
    public Exam $exam;

    public string $search = '';

    public ?string $typeFilter = null;

    public array $selectedQuestions = [];

    public array $customMarks = [];

    public function mount(Exam $exam): void
    {
        $this->exam = $exam;
    }

    public function getAvailableQuestionsProperty()
    {
        return app(ExamService::class)->getAvailableQuestions($this->exam, $this->search, $this->typeFilter);
    }

    public function toggleSelect($questionId)
    {
        if (in_array($questionId, $this->selectedQuestions)) {
            $this->selectedQuestions = array_diff($this->selectedQuestions, [$questionId]);
            unset($this->customMarks[$questionId]);
        } else {
            $this->selectedQuestions[] = $questionId;
        }
    }

    public function addSelected()
    {
        if (empty($this->selectedQuestions)) {
            session()->flash('error', 'Please select at least one question.');

            return;
        }

        foreach ($this->selectedQuestions as $questionId) {
            $customMarks = $this->customMarks[$questionId] ?? null;
            app(ExamService::class)->addQuestion($this->exam, $questionId, $customMarks ? (int) $customMarks : null);
        }

        session()->flash('message', count($this->selectedQuestions).' question(s) added to exam.');

        return redirect()->route('admin.exams.questions', $this->exam);
    }

    public function render()
    {
        return view('livewire.admin.exams.add-question', [
            'questions' => $this->availableQuestions,
        ]);
    }
}
