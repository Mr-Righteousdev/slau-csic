<?php

namespace App\Livewire\Admin\Exams;

use App\Models\Exam;
use App\Services\ExamService;
use Livewire\Component;

class Edit extends Component
{
    public Exam $exam;

    public string $title = '';

    public string $description = '';

    public int $duration_minutes = 60;

    public int $passing_score = 50;

    public function mount(Exam $exam): void
    {
        $this->exam = $exam;
        $this->title = $exam->title ?? '';
        $this->description = $exam->description ?? '';
        $this->duration_minutes = $exam->duration_minutes;
        $this->passing_score = $exam->passing_score;
    }

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'passing_score' => ['required', 'integer', 'min:0', 'max:100'],
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        app(ExamService::class)->updateExam($this->exam, $validated);

        session()->flash('message', 'Exam updated successfully.');

        return redirect()->route('admin.exams.index');
    }

    public function render()
    {
        return view('livewire.admin.exams.edit');
    }
}
