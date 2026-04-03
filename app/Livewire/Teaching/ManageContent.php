<?php

namespace App\Livewire\Teaching;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ManageContent extends Component
{
    public $materials = [];

    public $showUploadModal = false;

    public $editingMaterial = null;

    public $title = '';

    public $description = '';

    public $type = 'note';

    public $file = null;

    public $external_link = '';

    public $visibility = 'all';

    public $search = '';

    public $filterType = '';

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'type' => 'required|in:video,pdf,note,link',
        'external_link' => 'nullable|url',
        'visibility' => 'required|in:all,teachers,students',
    ];

    public function mount()
    {
        $this->loadMaterials();
    }

    public function loadMaterials()
    {
        $query = \App\Models\EventResource::query()
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->filterType, fn ($q) => $q->where('type', $this->filterType))
            ->orderBy('created_at', 'desc');

        $this->materials = $query->get()->toArray();
    }

    public function updatedSearch()
    {
        $this->loadMaterials();
    }

    public function updatedFilterType()
    {
        $this->loadMaterials();
    }

    public function openUploadModal()
    {
        $this->reset(['title', 'description', 'type', 'file', 'external_link', 'visibility', 'editingMaterial']);
        $this->showUploadModal = true;
    }

    public function editMaterial($id)
    {
        $material = \App\Models\EventResource::find($id);
        if ($material) {
            $this->editingMaterial = $material;
            $this->title = $material->title;
            $this->description = $material->description ?? '';
            $this->type = $material->type;
            $this->external_link = $material->url ?? '';
            $this->visibility = $material->visibility ?? 'all';
            $this->showUploadModal = true;
        }
    }

    public function saveMaterial()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'url' => $this->external_link,
            'visibility' => $this->visibility,
            'event_id' => null,
        ];

        if ($this->file) {
            $path = $this->file->store('course-materials', 'public');
            $data['file_path'] = $path;
        }

        if ($this->editingMaterial) {
            $this->editingMaterial->update($data);
            $message = 'Material updated successfully';
        } else {
            \App\Models\EventResource::create($data);
            $message = 'Material uploaded successfully';
        }

        $this->showUploadModal = false;
        $this->loadMaterials();
        $this->dispatch('notify', ['message' => $message]);
    }

    public function deleteMaterial($id)
    {
        $material = \App\Models\EventResource::find($id);
        if ($material) {
            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }
            $material->delete();
            $this->loadMaterials();
            $this->dispatch('notify', ['message' => 'Material deleted successfully']);
        }
    }

    public function render()
    {
        return view('livewire.teaching.manage-content');
    }
}
