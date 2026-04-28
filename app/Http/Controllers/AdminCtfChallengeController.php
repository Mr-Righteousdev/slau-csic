<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCtfChallengeRequest;
use App\Http\Requests\UpdateCtfChallengeRequest;
use App\Models\CtfChallenge;
use App\Models\CtfCompetition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCtfChallengeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CtfCompetition $competition, Request $request): View
    {
        $categoryFilter = $request->query('category', '');
        $difficultyFilter = $request->query('difficulty', '');

        $query = CtfChallenge::where('ctf_competition_id', $competition->id);

        if ($categoryFilter) {
            $query->where('ctf_category_id', $categoryFilter);
        }

        if ($difficultyFilter) {
            $query->where('difficulty', $difficultyFilter);
        }

        $challenges = $query->with('category')->latest()->paginate(15);

        return view('livewire.admin.ctf-challenges', compact('competition', 'challenges', 'categoryFilter', 'difficultyFilter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CtfCompetition $competition): View
    {
        return view('livewire.admin.ctf-challenges', compact('competition'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCtfChallengeRequest $request, CtfCompetition $competition): RedirectResponse
    {
        $data = $request->validated();
        $data['ctf_competition_id'] = $competition->id;

        $challenge = CtfChallenge::create($data);

        return redirect()->route('admin.ctf-competitions.challenges.index', ['competition' => $competition])
            ->with('success', 'Challenge created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CtfCompetition $competition, CtfChallenge $challenge): View
    {
        return view('livewire.admin.ctf-challenges', compact('competition', 'challenge'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCtfChallengeRequest $request, CtfCompetition $competition, CtfChallenge $challenge): RedirectResponse
    {
        $data = $request->validated();

        $challenge->update($data);

        return redirect()->route('admin.ctf-competitions.challenges.index', ['competition' => $competition])
            ->with('success', 'Challenge updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CtfCompetition $competition, CtfChallenge $challenge): RedirectResponse
    {
        $challenge->delete();

        return redirect()->route('admin.ctf-competitions.challenges.index', ['competition' => $competition])
            ->with('success', 'Challenge deleted successfully.');
    }
}
