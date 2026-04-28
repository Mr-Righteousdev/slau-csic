<?php

namespace App\Livewire\Admin;

use App\Models\CtfCategory;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class CtfCategories extends Component
{
    use WithPagination;

    public string $search = '';

    public function render(): View
    {
        $categories = CtfCategory::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->ordered()
            ->paginate(20);

        return view('livewire.admin.ctf-categories', compact('categories'));
    }

    public function delete(CtfCategory $category): void
    {
        if ($category->challenges()->exists()) {
            session()->flash('error', 'Cannot delete category with challenges.');

            return;
        }
        $category->delete();
        session()->flash('success', 'Category deleted.');
    }

    public function up(CtfCategory $category): void
    {
        $above = CtfCategory::where('sort_order', '<', $category->sort_order)
            ->orderByDesc('sort_order')
            ->first();

        if ($above) {
            $temp = $category->sort_order;
            $category->update(['sort_order' => $above->sort_order]);
            $above->update(['sort_order' => $temp]);
        }
    }

    public function down(CtfCategory $category): void
    {
        $below = CtfCategory::where('sort_order', '>', $category->sort_order)
            ->orderBy('sort_order')
            ->first();

        if ($below) {
            $temp = $category->sort_order;
            $category->update(['sort_order' => $below->sort_order]);
            $below->update(['sort_order' => $temp]);
        }
    }
}
