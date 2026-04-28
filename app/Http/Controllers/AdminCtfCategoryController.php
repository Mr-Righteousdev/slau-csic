<?php

namespace App\Http\Controllers;

use App\Models\CtfCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCtfCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $categories = CtfCategory::ordered()->get();

        return view('livewire.admin.ctf-categories', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('livewire.admin.ctf-categories');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:ctf_categories,slug',
            'color' => 'string|size:7',
            'icon' => 'string',
            'sort_order' => 'nullable|integer',
        ]);

        CtfCategory::create($validated);

        return redirect()->route('admin.ctf-categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CtfCategory $category): View
    {
        return view('livewire.admin.ctf-categories', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CtfCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:ctf_categories,slug,'.$category->id,
            'color' => 'string|size:7',
            'icon' => 'string',
            'sort_order' => 'nullable|integer',
        ]);

        $category->update($validated);

        return redirect()->route('admin.ctf-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CtfCategory $category): RedirectResponse
    {
        // Prevent deletion of default categories if they have challenges
        if ($category->challenges()->exists()) {
            return redirect()->route('admin.ctf-categories.index')
                ->with('error', 'Cannot delete category with associated challenges.');
        }

        $category->delete();

        return redirect()->route('admin.ctf-categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    /**
     * Move category up in sort order
     */
    public function moveUp(CtfCategory $category): RedirectResponse
    {
        $categoryWithLowerOrder = CtfCategory::where('sort_order', '<', $category->sort_order)
            ->orderByDesc('sort_order')
            ->first();

        if ($categoryWithLowerOrder) {
            $tempOrder = $category->sort_order;
            $category->sort_order = $categoryWithLowerOrder->sort_order;
            $categoryWithLowerOrder->sort_order = $tempOrder;

            $category->save();
            $categoryWithLowerOrder->save();
        }

        return redirect()->route('admin.ctf-categories.index')
            ->with('success', 'Category moved up.');
    }

    /**
     * Move category down in sort order
     */
    public function moveDown(CtfCategory $category): RedirectResponse
    {
        $categoryWithHigherOrder = CtfCategory::where('sort_order', '>', $category->sort_order)
            ->orderBy('sort_order')
            ->first();

        if ($categoryWithHigherOrder) {
            $tempOrder = $category->sort_order;
            $category->sort_order = $categoryWithHigherOrder->sort_order;
            $categoryWithHigherOrder->sort_order = $tempOrder;

            $category->save();
            $categoryWithHigherOrder->save();
        }

        return redirect()->route('admin.ctf-categories.index')
            ->with('success', 'Category moved down.');
    }
}
