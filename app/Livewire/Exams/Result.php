<?php

namespace App\Livewire\Exams;

use App\Models\ExamAttempt;
use Livewire\Component;

class Result extends Component
{
    public ExamAttempt $attempt;

    public bool $showAnswers = false;

    public function mount(ExamAttempt $attempt): void
    {
        $this->attempt = $attempt->load(['exam', 'answers.examQuestion.question.options']);
    }

    public function getScoreProperty()
    {
        return $this->attempt->total_score;
    }

    public function getPassedProperty()
    {
        return $this->attempt->passed;
    }

    public function getAnswersProperty()
    {
        return $this->attempt->answers;
    }

    public function toggleAnswers(): void
    {
        $this->showAnswers = ! $this->showAnswers;
    }

    public function render()
    {
        return view('livewire.exams.result');
    }
}
