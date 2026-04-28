<?php

namespace App\Livewire\Admin;

use App\Models\CtfCompetition;
use Illuminate\View\View;
use Livewire\Component;

class CtfCompetitionEdit extends Component
{
    public CtfCompetition $competition;

    public string $title = '';

    public string $slug = '';

    public ?string $description = '';

    public ?string $start_date = '';

    public ?string $end_date = '';

    public string $status = 'draft';

    public bool $is_public = true;

    public ?int $max_score = null;

    public function mount(CtfCompetition $competition): void
    {
        $this->competition = $competition;
        $this->title = $competition->title;
        $this->slug = $competition->slug;
        $this->description = $competition->description;
        $this->start_date = $competition->start_date?->format('Y-m-d H:i:s');
        $this->end_date = $competition->end_date?->format('Y-m-d H:i:s');
        $this->status = $competition->status;
        $this->is_public = $competition->is_public;
        $this->max_score = $competition->max_score;
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:ctf_competitions,slug,'.$this->competition->id,
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

        $this->competition->update([
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'start_date' => $this->start_date ? \Carbon\Carbon::parse($this->start_date) : null,
            'end_date' => $this->end_date ? \Carbon\Carbon::parse($this->end_date) : null,
            'status' => $this->status,
            'is_public' => $this->is_public,
            'max_score' => $this->max_score,
        ]);

        session()->flash('success', 'Competition updated successfully.');
        $this->redirectRoute('admin.ctf-competitions');
    }

    public function render(): View
    {
        return view('livewire.admin.ctf-competition-edit');
    }
}
