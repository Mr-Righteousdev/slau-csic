<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreElectionCandidateRequest;
use App\Http\Requests\StoreElectionRequest;
use App\Models\Election;
use App\Models\ElectionCandidate;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ElectionManagementController extends Controller
{
    public function index(): View
    {
        return view('pages.admin.elections.index', [
            'title' => 'Election Management',
            'elections' => Election::query()->withCount(['candidates', 'votes'])->latest()->get(),
        ]);
    }

    public function store(StoreElectionRequest $request): RedirectResponse
    {
        Election::query()->create([
            ...$request->validated(),
            'results_visible' => $request->boolean('results_visible'),
        ]);

        return back()->with('status', 'Election created successfully.');
    }

    public function show(Election $election): View
    {
        $election->load(['candidates.user', 'votes.candidate']);

        return view('pages.admin.elections.show', [
            'title' => 'Manage Election',
            'election' => $election,
            'users' => User::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(StoreElectionRequest $request, Election $election): RedirectResponse
    {
        $election->update([
            ...$request->validated(),
            'results_visible' => $request->boolean('results_visible'),
        ]);

        return back()->with('status', 'Election updated successfully.');
    }

    public function storeCandidate(StoreElectionCandidateRequest $request, Election $election): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('elections', 'public');
        }

        $election->candidates()->create($data);

        return back()->with('status', 'Candidate added successfully.');
    }

    public function destroyCandidate(Election $election, ElectionCandidate $candidate): RedirectResponse
    {
        abort_unless($candidate->election_id === $election->id, 404);

        if ($candidate->photo) {
            Storage::disk('public')->delete($candidate->photo);
        }

        $candidate->delete();

        return back()->with('status', 'Candidate removed successfully.');
    }
}
