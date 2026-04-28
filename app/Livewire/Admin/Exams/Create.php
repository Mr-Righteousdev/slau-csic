<?php

namespace App\Livewire\Admin\Exams;

use App\Services\ExamService;
use Livewire\Component;

class Create extends Component
{
    public string $title = '';

    public string $description = '';

    public int $duration_minutes = 60;

    public int $passing_score = 50;

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

        app(ExamService::class)->createExam(auth()->user(), $validated);

        session()->flash('message', 'Exam created successfully.');

        return redirect()->route('admin.exams.index');
    }

    public function saveAndContinue()
    {
        $validated = $this->validate();

        app(ExamService::class)->createExam(auth()->user(), $validated);

        session()->flash('message', 'Exam created successfully. Create another?');

        $this->reset(['title', 'description', 'duration_minutes', 'passing_score']);
    }

    public function render()
    {
        return view('livewire.admin.exams.create');
    }
}
