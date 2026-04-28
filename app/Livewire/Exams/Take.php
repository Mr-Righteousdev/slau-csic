<?php

namespace App\Livewire\Exams;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Services\ExamAttemptService;
use Livewire\Component;

class Take extends Component
{
    public Exam $exam;

    public ExamAttempt $attempt;

    public int $currentQuestionIndex = 0;

    public array $answers = [];

    public int $timeRemaining;

    public bool $isSubmitted = false;

    public function mount(Exam $exam): void
    {
        $this->exam = $exam;

        // Check if user already has an attempt
        $existingAttempt = app(ExamAttemptService::class)->getUserAttempt($exam, auth()->user());

        if ($existingAttempt && $existingAttempt->is_completed) {
            $this->redirect(route('exams.result', $existingAttempt), navigate: true);
        }

        if (! $existingAttempt) {
            $this->attempt = app(ExamAttemptService::class)->startAttempt($exam, auth()->user());
        } else {
            $this->attempt = $existingAttempt;
        }

        $this->timeRemaining = $this->attempt->time_remaining_seconds ?? ($exam->duration_minutes * 60);

        // Load existing answers if resuming
        foreach ($this->attempt->answers as $answer) {
            if ($answer->answer_text) {
                $this->answers[$answer->exam_question_id]['text'] = $answer->answer_text;
            }
            if ($answer->selected_option_id) {
                $this->answers[$answer->exam_question_id]['option_id'] = $answer->selected_option_id;
            }
        }
    }

    public function getQuestionsProperty()
    {
        return $this->exam->questions()->with(['question.options'])->get();
    }

    public function nextQuestion(): void
    {
        $this->saveCurrentAnswer();
        if ($this->currentQuestionIndex < count($this->questions) - 1) {
            $this->currentQuestionIndex++;
        }
    }

    public function prevQuestion(): void
    {
        $this->saveCurrentAnswer();
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }

    public function saveCurrentAnswer(): void
    {
        $question = $this->questions[$this->currentQuestionIndex] ?? null;
        if (! $question) {
            return;
        }

        $data = [];
        if (isset($this->answers[$question->id]['text'])) {
            $data['answer_text'] = $this->answers[$question->id]['text'];
        }
        if (isset($this->answers[$question->id]['option_id'])) {
            $data['selected_option_id'] = $this->answers[$question->id]['option_id'];
        }

        if (! empty($data)) {
            app(ExamAttemptService::class)->saveAnswer($this->attempt, $question, $data);
        }
    }

    public function submitExam(): void
    {
        $this->saveCurrentAnswer();

        // Validate all questions answered
        $unanswered = [];
        foreach ($this->questions as $question) {
            if (! isset($this->answers[$question->id])) {
                $unanswered[] = $question->id;
            }
        }

        if (count($unanswered) > 0) {
            session()->flash('error', 'Please answer all questions before submitting.');

            return;
        }

        $result = app(ExamAttemptService::class)->submitAttempt($this->attempt);

        $this->isSubmitted = true;

        $this->redirect(route('exams.result', $this->attempt), navigate: true);
    }

    public function getProgressProperty(): int
    {
        $answered = count(array_filter($this->answers, fn ($a) => ! empty($a)));

        return (int) (($answered / count($this->questions)) * 100);
    }

    public function render()
    {
        return view('livewire.exams.take', [
            'questions' => $this->questions,
            'currentQuestion' => $this->questions[$this->currentQuestionIndex] ?? null,
        ]);
    }
}
