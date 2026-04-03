@extends('layouts.frontend')

@php
    $projectItems = $member->projects->map(function ($project) {
        return [
            'name' => $project->name,
            'role' => 'Project lead',
            'progress' => $project->progress_percentage,
            'summary' => $project->description,
        ];
    })->merge(
        $member->projectMemberships->map(function ($membership) {
            return [
                'name' => $membership->project?->name ?? 'Project record',
                'role' => ucfirst($membership->role),
                'progress' => $membership->project?->progress_percentage ?? null,
                'summary' => $membership->contribution ?: $membership->project?->description,
            ];
        })
    );
@endphp

@section('content')
    <section class="hero-backdrop border-b bg-cover bg-center" style="border-color: var(--page-border); background-image: url('{{ asset('images/club/certificate-team.jpg') }}');">
        <div class="mx-auto max-w-6xl px-4 pb-14 pt-12 sm:px-6 lg:px-8">
            <div class="grid items-center gap-8 lg:grid-cols-[0.85fr_1.15fr]">
                <div class="space-y-5">
                    <p class="eyebrow">Member Bio</p>
                    <h1 class="page-hero-title">{{ $member->name }}</h1>
                    <p class="page-hero-copy">
                        {{ $member->headline ?: 'SLAU Cybersecurity & Innovations Club member' }}
                    </p>
                    <div class="flex flex-wrap gap-3 text-sm" style="color: var(--page-text-soft);">
                        @if ($member->program)
                            <span>{{ $member->program }}</span>
                        @endif
                        @if ($member->year_of_study)
                            <span>Year {{ $member->year_of_study }}</span>
                        @endif
                        @if ($member->faculty)
                            <span>{{ $member->faculty }}</span>
                        @endif
                    </div>
                </div>

                <article class="spotlight-panel rounded-md p-6">
                    <div class="grid gap-6 md:grid-cols-[160px_1fr] md:items-center">
                        <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="h-40 w-40 rounded-md object-cover object-top">
                        <div class="space-y-4">
                            <div class="grid gap-3 sm:grid-cols-3">
                                <div class="rounded-sm border px-4 py-4" style="border-color: var(--page-border); background: var(--page-surface-strong);">
                                    <div class="text-xs uppercase tracking-[0.18em]" style="color: var(--page-text-muted);">Meetings</div>
                                    <div class="mt-2 text-2xl font-semibold" style="color: var(--page-text);">{{ $stats['total_attendance'] }}</div>
                                </div>
                                <div class="rounded-sm border px-4 py-4" style="border-color: var(--page-border); background: var(--page-surface-strong);">
                                    <div class="text-xs uppercase tracking-[0.18em]" style="color: var(--page-text-muted);">Projects</div>
                                    <div class="mt-2 text-2xl font-semibold" style="color: var(--page-text);">{{ $stats['projects_led'] + $stats['projects_participated'] }}</div>
                                </div>
                                <div class="rounded-sm border px-4 py-4" style="border-color: var(--page-border); background: var(--page-surface-strong);">
                                    <div class="text-xs uppercase tracking-[0.18em]" style="color: var(--page-text-muted);">Challenges</div>
                                    <div class="mt-2 text-2xl font-semibold" style="color: var(--page-text);">{{ $stats['competition_entries'] }}</div>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                @foreach ($member->roles as $role)
                                    <span class="rounded-sm border px-3 py-2 text-xs uppercase tracking-[0.18em]" style="border-color: var(--page-border); color: var(--page-primary);">
                                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="reveal-fade py-18">
        <div class="mx-auto grid max-w-6xl gap-6 px-4 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-8">
            <article class="dossier-card rounded-md p-6">
                <p class="eyebrow">Professional Bio</p>
                <h2 class="section-title mt-3 text-3xl">About this member</h2>
                <p class="body-copy mt-4 whitespace-pre-line">{{ $member->bio }}</p>

                <dl class="mt-6 grid gap-4 text-sm">
                    @if ($member->specialization_track)
                        <div class="rounded-sm border px-4 py-4" style="border-color: var(--page-border); background: var(--page-surface-strong);">
                            <dt style="color: var(--page-text-muted);">Current focus</dt>
                            <dd class="mt-1 font-medium" style="color: var(--page-text);">{{ $member->specialization_track }}</dd>
                        </div>
                    @endif

                    @if ($member->competition_rank)
                        <div class="rounded-sm border px-4 py-4" style="border-color: var(--page-border); background: var(--page-surface-strong);">
                            <dt style="color: var(--page-text-muted);">Competition ranking</dt>
                            <dd class="mt-1 font-medium" style="color: var(--page-text);">{{ $member->competition_rank }}</dd>
                        </div>
                    @endif

                    @if ($member->joined_at)
                        <div class="rounded-sm border px-4 py-4" style="border-color: var(--page-border); background: var(--page-surface-strong);">
                            <dt style="color: var(--page-text-muted);">Member since</dt>
                            <dd class="mt-1 font-medium" style="color: var(--page-text);">{{ $member->joined_at->format('F Y') }}</dd>
                        </div>
                    @endif
                </dl>
            </article>

            <article class="identity-block">
                <p class="eyebrow">Contribution Record</p>
                <h2 class="dossier-title mt-3">Problems solved, work completed, and challenge progress</h2>

                @if ($member->notable_problems_solved)
                    <div class="mt-6 rounded-md border px-5 py-5" style="border-color: var(--page-border); background: var(--page-surface-elevated);">
                        <h3 class="text-lg font-semibold" style="color: var(--page-text);">Problems solved</h3>
                        <p class="body-copy mt-3 whitespace-pre-line">{{ $member->notable_problems_solved }}</p>
                    </div>
                @endif

                @if ($member->achievements_summary)
                    <div class="mt-4 rounded-md border px-5 py-5" style="border-color: var(--page-border); background: var(--page-surface-elevated);">
                        <h3 class="text-lg font-semibold" style="color: var(--page-text);">Progress and achievements</h3>
                        <p class="body-copy mt-3 whitespace-pre-line">{{ $member->achievements_summary }}</p>
                    </div>
                @endif

                <div class="mt-6 space-y-4">
                    @forelse ($projectItems as $projectItem)
                        <article class="proof-card rounded-md p-5">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <h3 class="text-lg font-semibold" style="color: var(--page-text);">{{ $projectItem['name'] }}</h3>
                                    <p class="mt-1 text-sm" style="color: var(--page-primary);">{{ $projectItem['role'] }}</p>
                                </div>
                                @if (! is_null($projectItem['progress']))
                                    <span class="rounded-sm border px-3 py-2 text-xs uppercase tracking-[0.18em]" style="border-color: var(--page-border); color: var(--page-text-soft);">
                                        {{ $projectItem['progress'] }}% progress
                                    </span>
                                @endif
                            </div>
                            @if ($projectItem['summary'])
                                <p class="body-copy mt-3">{{ $projectItem['summary'] }}</p>
                            @endif
                        </article>
                    @empty
                        <article class="proof-card rounded-md p-5">
                            <h3 class="text-lg font-semibold" style="color: var(--page-text);">Project record not yet published</h3>
                            <p class="body-copy mt-3">This member has a public profile, but project contributions have not yet been recorded in the club system.</p>
                        </article>
                    @endforelse
                </div>

                <div class="mt-6 space-y-4">
                    @forelse ($member->competitionParticipations as $participation)
                        <article class="proof-card rounded-md p-5">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <h3 class="text-lg font-semibold" style="color: var(--page-text);">{{ $participation->competition?->name ?? 'Competition record' }}</h3>
                                    <p class="mt-1 text-sm" style="color: var(--page-primary);">{{ ucfirst($participation->role) }}{{ $participation->team_name ? ' · '.$participation->team_name : '' }}</p>
                                </div>
                                @if ($participation->competition?->club_ranking)
                                    <span class="rounded-sm border px-3 py-2 text-xs uppercase tracking-[0.18em]" style="border-color: var(--page-border); color: var(--page-text-soft);">
                                        Club rank {{ $participation->competition->club_ranking }}
                                    </span>
                                @endif
                            </div>
                            @if ($participation->competition?->achievements)
                                <p class="body-copy mt-3">{{ $participation->competition->achievements }}</p>
                            @endif
                        </article>
                    @empty
                        <article class="proof-card rounded-md p-5">
                            <h3 class="text-lg font-semibold" style="color: var(--page-text);">Challenge progress not yet recorded</h3>
                            <p class="body-copy mt-3">Competition and challenge results will appear here once the club records participation and rankings.</p>
                        </article>
                    @endforelse
                </div>
            </article>
        </div>
    </section>

    <section class="cyber-section reveal-fade">
        <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="eyebrow">Directory Navigation</p>
                    <h2 class="section-title mt-3 text-3xl">Explore more members or view club projects</h2>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('members.public') }}" class="cyber-outline-button">Back to Directory</a>
                    <a href="{{ route('projects') }}" class="cyber-button">View Projects</a>
                </div>
            </div>
        </div>
    </section>
@endsection
