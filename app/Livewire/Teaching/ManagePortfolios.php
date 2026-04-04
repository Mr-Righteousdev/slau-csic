<?php

namespace App\Livewire\Teaching;

use App\Models\StudentPortfolio;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ManagePortfolios extends Component
{
    public $portfolios = [];

    public $showModal = false;

    public $editingPortfolio = null;

    public $title = '';

    public $description = '';

    public $category = 'project';

    public $file = null;

    public $external_link = '';

    public $student_id = '';

    public $is_published = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'category' => 'required',
        'external_link' => 'nullable|url',
        'student_id' => 'required|exists:users,id',
        'is_published' => 'boolean',
    ];

    public function mount()
    {
        $this->loadPortfolios();
    }

    public function loadPortfolios()
    {
        $query = StudentPortfolio::with(['student', 'creator'])
            ->orderBy('created_at', 'desc');

        if (! Auth::user()->hasPermissionTo('portfolio.manage')) {
            $query->where('student_id', Auth::id());
        }

        $this->portfolios = $query->get()->toArray();
    }

    public function openModal()
    {
        $this->reset(['title', 'description', 'category', 'file', 'external_link', 'student_id', 'is_published', 'editingPortfolio']);
        $this->showModal = true;
    }

    public function editPortfolio($id)
    {
        $portfolio = StudentPortfolio::find($id);
        if ($portfolio) {
            $this->editingPortfolio = $portfolio;
            $this->title = $portfolio->title;
            $this->description = $portfolio->description ?? '';
            $this->category = $portfolio->category;
            $this->external_link = $portfolio->external_link ?? '';
            $this->student_id = $portfolio->student_id;
            $this->is_published = $portfolio->is_published;
            $this->showModal = true;
        }
    }

    public function savePortfolio()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'external_link' => $this->external_link,
            'student_id' => $this->student_id,
            'is_published' => $this->is_published,
            'created_by' => Auth::id(),
        ];

        if ($this->file) {
            $path = $this->file->store('portfolios', 'public');
            $data['file_path'] = $path;
        }

        if ($this->editingPortfolio) {
            $this->editingPortfolio->update($data);
            $message = 'Portfolio updated successfully';
        } else {
            StudentPortfolio::create($data);
            $message = 'Portfolio created successfully';
        }

        $this->showModal = false;
        $this->loadPortfolios();
        $this->dispatch('notify', ['message' => $message]);
    }

    public function deletePortfolio($id)
    {
        $portfolio = StudentPortfolio::find($id);
        if ($portfolio) {
            if ($portfolio->file_path && Storage::disk('public')->exists($portfolio->file_path)) {
                Storage::disk('public')->delete($portfolio->file_path);
            }
            $portfolio->delete();
            $this->loadPortfolios();
            $this->dispatch('notify', ['message' => 'Portfolio deleted successfully']);
        }
    }

    public function togglePublish($id)
    {
        $portfolio = StudentPortfolio::find($id);
        if ($portfolio) {
            $portfolio->update(['is_published' => ! $portfolio->is_published]);
            $this->loadPortfolios();
        }
    }

    public function getStudentsProperty()
    {
        return User::where('membership_status', 'active')
            ->orderBy('name')
            ->pluck('name', 'id');
    }

    public function render()
    {
        return view('livewire.teaching.manage-portfolios');
    }
}
