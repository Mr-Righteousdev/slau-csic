<?php

namespace App\Livewire\Admin;

use App\Models\CtfCategory;
use App\Models\CtfChallenge;
use App\Models\CtfCompetition;
use Illuminate\View\View;
use Livewire\Component;

class CtfChallengeEdit extends Component
{
    public CtfCompetition $competition;

    public CtfChallenge $challenge;

    public string $title = '';

    public string $slug = '';

    public ?string $description = '';

    public ?string $flag = '';

    public int $points = 100;

    public string $difficulty = 'medium';

    public int $ctf_category_id = 1;

    public bool $is_active = true;

    public ?string $hint = '';

    public int $hint_cost = 0;

    public int $max_attempts = 0;

    public ?string $tags = '';

    public int $sort_order = 0;

    public function mount(CtfCompetition $competition, CtfChallenge $challenge): void
    {
        $this->competition = $competition;
        $this->challenge = $challenge;
        $this->title = $challenge->title;
        $this->slug = $challenge->slug;
        $this->description = $challenge->description;
        $this->points = $challenge->points;
        $this->difficulty = $challenge->difficulty;
        $this->ctf_category_id = $challenge->ctf_category_id;
        $this->is_active = $challenge->is_active;
        $this->hint = $challenge->hint;
        $this->hint_cost = $challenge->hint_cost;
        $this->max_attempts = $challenge->max_attempts;
        $this->tags = $challenge->tags ? implode(',', $challenge->tags) : '';
        $this->sort_order = $challenge->sort_order;
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:ctf_challenges,slug,'.$this->challenge->id,
            'description' => 'nullable|string',
            'flag' => 'nullable|string|min:5',
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

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'points' => $this->points,
            'difficulty' => $this->difficulty,
            'ctf_category_id' => $this->ctf_category_id,
            'is_active' => $this->is_active,
            'hint' => $this->hint,
            'hint_cost' => $this->hint_cost,
            'max_attempts' => $this->max_attempts,
            'tags' => $this->tags ? json_encode(explode(',', $this->tags)) : null,
            'sort_order' => $this->sort_order,
        ];

        if ($this->flag) {
            $data['flag'] = $this->flag;
        }

        $this->challenge->update($data);

        session()->flash('success', 'Challenge updated successfully.');
        $this->redirectRoute('admin.ctf-challenges', ['competition' => $this->competition]);
    }

    public function render(): View
    {
        $categories = CtfCategory::ordered()->get();

        return view('livewire.admin.ctf-challenge-edit', compact('categories'));
    }
}
