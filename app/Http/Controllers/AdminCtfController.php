<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCtfCompetitionRequest;
use App\Http\Requests\UpdateCtfCompetitionRequest;
use App\Models\CtfCompetition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCtfController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');

        $query = CtfCompetition::query();

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $competitions = $query->latest()->paginate(15);

        return view('livewire.admin.ctf-competitions', compact('competitions', 'statusFilter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('livewire.admin.ctf-competitions');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCtfCompetitionRequest $request): RedirectResponse
    {
        $competition = CtfCompetition::create($request->validated());

        return redirect()->route('admin.ctf-competitions.show', $competition)
            ->with('success', 'Competition created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CtfCompetition $competition): View
    {
        return view('livewire.admin.ctf-competitions', compact('competition'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CtfCompetition $competition): View
    {
        return view('livewire.admin.ctf-competitions', compact('competition'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCtfCompetitionRequest $request, CtfCompetition $competition): RedirectResponse
    {
        $competition->update($request->validated());

        return redirect()->route('admin.ctf-competitions.show', $competition)
            ->with('success', 'Competition updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CtfCompetition $competition): RedirectResponse
    {
        $competition->delete();

        return redirect()->route('admin.ctf-competitions.index')
            ->with('success', 'Competition deleted successfully.');
    }
}
