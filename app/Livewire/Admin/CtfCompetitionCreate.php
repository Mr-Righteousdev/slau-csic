<?php

namespace App\Livewire\Admin;

use App\Models\CtfCompetition;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;

class CtfCompetitionCreate extends Component
{
    public string $title = '';

    public string $slug = '';

    public ?string $description = '';

    public ?string $start_date = '';

    public ?string $end_date = '';

    public string $status = 'draft';

    public bool $is_public = true;

    public ?int $max_score = null;

    public function updatedTitle(string $value): void
    {
        $this->slug = Str::slug($value);
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:ctf_competitions,slug',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:draft,published,archived',
            'is_public' => 'boolean',
            'max_score' => 'nullable|integer|min:0',
        ];
    }

    public function save(): void
    {
        $this->validate();

        CtfCompetition::create([
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'start_date' => $this->start_date ? \Carbon\Carbon::parse($this->start_date) : null,
            'end_date' => $this->end_date ? \Carbon\Carbon::parse($this->end_date) : null,
            'status' => $this->status,
            'is_public' => $this->is_public,
            'max_score' => $this->max_score,
        ]);

        session()->flash('success', 'Competition created successfully.');
        $this->redirectRoute('admin.ctf-competitions');
    }

    public function render(): View
    {
        return view('livewire.admin.ctf-competition-create');
    }
}
