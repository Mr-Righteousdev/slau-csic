@extends('layouts.frontend')

@section('content')
    <section class="hero-backdrop border-b bg-cover bg-center" style="border-color: var(--page-border); background-image: url('{{ asset('images/club/cyber-team.jpg') }}');">
        <div class="mx-auto max-w-6xl px-4 pb-14 pt-12 sm:px-6 lg:px-8">
            <div class="grid items-center gap-8 lg:grid-cols-[0.95fr_1.05fr]">
                <div class="space-y-5">
                    <p class="eyebrow">Member Directory</p>
                    <h1 class="page-hero-title">A public directory should show the people shaping the club, not placeholder portraits.</h1>
                    <p class="page-hero-copy">
                        This directory is built from approved member records, profile photos, and public visibility settings so the site reflects a real student community with real contributors.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('register') }}" class="cyber-button">Register as a Member</a>
                        <a href="{{ route('projects') }}" class="cyber-outline-button">View Project Work</a>
                    </div>
                </div>

                <article class="spotlight-panel overflow-hidden rounded-md">
                    <img src="{{ asset('images/club/cyber-team.jpg') }}" alt="SLAU club members together" class="h-[420px] w-full object-cover object-center">
                </article>
            </div>
        </div>
    </section>

    <section class="reveal-fade py-18">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 md:grid-cols-3">
                <article class="proof-card rounded-md p-5">
                    <p class="eyebrow">Visible Community</p>
                    <p class="body-copy mt-3">Every approved profile helps the club look credible, accountable, and active to students, collaborators, and institutional partners.</p>
                </article>
                <article class="proof-card rounded-md p-5">
                    <p class="eyebrow">Real Bios</p>
                    <p class="body-copy mt-3">Each profile links to a member bio page with projects, competition progress, and the contribution story behind the photo.</p>
                </article>
                <article class="proof-card rounded-md p-5">
                    <p class="eyebrow">Controlled Visibility</p>
                    <p class="body-copy mt-3">Public display respects membership approval and profile visibility settings rather than exposing every account automatically.</p>
                </article>
            </div>
        </div>
    </section>

    @if ($featuredMembers->isNotEmpty())
        <section class="cyber-section reveal-fade">
            <div class="mx-auto max-w-6xl px-4 py-18 sm:px-6 lg:px-8">
                <div class="mb-8 max-w-3xl">
                    <p class="eyebrow">Featured Members</p>
                    <h2 class="section-title">Members with visible public profiles</h2>
                </div>

                <div class="grid gap-6 md:grid-cols-3">
                    @foreach ($featuredMembers as $member)
                        <article class="cyber-card overflow-hidden rounded-md">
                            <a href="{{ route('members.public.show', $member) }}" class="block p-6">
                                <img src="{{ $member->avatar_url }}" alt="{{ $member->name }} from the SLAU club" class="mx-auto h-40 w-40 rounded-md object-cover object-top ring-1 ring-white/10">
                                <div class="mt-5 text-center">
                                    <h3 class="text-2xl font-semibold" style="color: var(--page-text);">{{ $member->name }}</h3>
                                    <p class="mt-2 text-sm" style="color: var(--page-primary);">{{ $member->headline ?: 'Club member' }}</p>
                                    <p class="mt-3 text-sm" style="color: var(--page-text-soft);">
                                        {{ $member->program ?: 'Programme not provided' }}
                                        @if ($member->year_of_study)
                                            <span class="mx-2">·</span>
                                            Year {{ $member->year_of_study }}
                                        @endif
                                    </p>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="reveal-fade py-18">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 max-w-3xl">
                <p class="eyebrow">Club Directory</p>
                <h2 class="section-title">Approved members</h2>
                <p class="body-copy mt-4">Select a portrait to open the full profile, including project work, problems solved, and competition progress where recorded.</p>
            </div>

            @if ($members->isEmpty())
                <article class="identity-block">
                    <div class="space-y-5">
                        <p class="eyebrow">Directory Status</p>
                        <h3 class="dossier-title">No approved public member profiles are available yet.</h3>
                        <p class="dossier-copy">
                            Once members register, upload a profile photo, and are approved for public visibility, their records will appear here automatically.
                        </p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('register') }}" class="cyber-button">Start Registration</a>
                            <a href="{{ route('contact') }}" class="cyber-outline-button">Contact the Club</a>
                        </div>
                    </div>
                </article>
            @else
                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($members as $member)
                        <article class="dossier-card rounded-md p-5">
                            <div class="flex items-start gap-4">
                                <a href="{{ route('members.public.show', $member) }}" class="shrink-0">
                                    <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="h-24 w-24 rounded-md object-cover object-top">
                                </a>

                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('members.public.show', $member) }}" class="text-xl font-semibold transition hover:opacity-80" style="color: var(--page-text);">
                                        {{ $member->name }}
                                    </a>

                                    <p class="mt-1 text-sm font-medium" style="color: var(--page-primary);">
                                        {{ $member->headline ?: 'Member profile pending headline' }}
                                    </p>

                                    <p class="mt-2 text-sm leading-6" style="color: var(--page-text-soft);">
                                        {{ $member->program ?: 'Programme not listed' }}
                                        @if ($member->year_of_study)
                                            <span class="mx-2">·</span>
                                            Year {{ $member->year_of_study }}
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="mt-5 grid grid-cols-2 gap-3 text-sm">
                                <div class="rounded-sm border px-3 py-3" style="border-color: var(--page-border); background: var(--page-surface-strong);">
                                    <div style="color: var(--page-text-muted);">Projects</div>
                                    <div class="mt-1 font-semibold" style="color: var(--page-text);">{{ $member->projectMemberships->count() + $member->projects->count() }}</div>
                                </div>
                                <div class="rounded-sm border px-3 py-3" style="border-color: var(--page-border); background: var(--page-surface-strong);">
                                    <div style="color: var(--page-text-muted);">Competitions</div>
                                    <div class="mt-1 font-semibold" style="color: var(--page-text);">{{ $member->competitionParticipations->count() }}</div>
                                </div>
                            </div>

                            <div class="mt-5 flex items-center justify-between">
                                <p class="text-xs uppercase tracking-[0.18em]" style="color: var(--page-text-muted);">
                                    {{ $member->roles->isNotEmpty() ? $member->role_names : 'Club member' }}
                                </p>
                                <a href="{{ route('members.public.show', $member) }}" class="text-sm font-medium" style="color: var(--page-primary);">Open profile</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
