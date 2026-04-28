<?php

namespace App\Livewire\Admin;

use App\Models\CtfCategory;
use App\Models\CtfCompetition;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;

class CtfChallengeCreate extends Component
{
    public CtfCompetition $competition;

    public string $title = '';

    public string $slug = '';

    public ?string $description = '';

    public string $flag = '';

    public int $points = 100;

    public string $difficulty = 'medium';

    public int $ctf_category_id = 1;

    public bool $is_active = true;

    public ?string $hint = '';

    public int $hint_cost = 0;

    public int $max_attempts = 0;

    public ?string $tags = '';

    public int $sort_order = 0;

    public function mount(CtfCompetition $competition): void
    {
        $this->competition = $competition;
    }

    public function updatedTitle(string $value): void
    {
        $this->slug = Str::slug($value);
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:ctf_challenges,slug',
            'description' => 'nullable|string',
            'flag' => 'required|string|min:5',
            'points' => 'required|integer|min:1|max:10000',
            'difficulty' => 'required|in:easy,medium,hard,insane',
            'ctf_category_id' => 'required|exists:ctf_categories,id',
            'is_active' => 'boolean',
            'hint' => 'nullable|string',
            'hint_cost' => 'integer|min:0',
            'max_attempts' => 'integer|min:0',
            'tags' => 'nullable|string',
            'sort_order' => 'integer|min:0',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->competition->challenges()->create([
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'flag' => $this->flag, // Will be hashed via mutator
            'points' => $this->points,
            'difficulty' => $this->difficulty,
            'ctf_category_id' => $this->ctf_category_id,
            'is_active' => $this->is_active,
            'hint' => $this->hint,
            'hint_cost' => $this->hint_cost,
            'max_attempts' => $this->max_attempts,
            'tags' => $this->tags ? json_encode(explode(',', $this->tags)) : null,
            'sort_order' => $this->sort_order,
        ]);

        session()->flash('success', 'Challenge created successfully.');
        $this->redirectRoute('admin.ctf-challenges', ['competition' => $this->competition]);
    }

    public function render(): View
    {
        $categories = CtfCategory::ordered()->get();

        return view('livewire.admin.ctf-challenge-create', compact('categories'));
    }
}
