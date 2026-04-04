@extends('layouts.frontend')

@section('content')
    <section class="hero-backdrop border-b bg-cover bg-center" style="border-color: var(--page-border); background-image: url('{{ asset('images/club/certificate-team.jpg') }}');">
        <div class="mx-auto max-w-6xl px-4 pb-14 pt-12 sm:px-6 lg:px-8">
            <div class="grid items-center gap-8 lg:grid-cols-[0.92fr_1.08fr]">
                <div class="space-y-5">
                    <p class="eyebrow">Projects and Delivery</p>
                    <h1 class="page-hero-title">Projects should read like disciplined work, not a loose gallery of ideas.</h1>
                    <p class="page-hero-copy">
                        This page uses a clearer cybersecurity case-study rhythm: challenge, execution, outcome, and member contribution. The goal is to make club work feel structured, credible, and useful to partners as well as students.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('members.public') }}" class="cyber-button">Meet the Members</a>
                        <a href="{{ route('contact') }}" class="cyber-outline-button">Discuss a Project</a>
                    </div>
                </div>

                <article class="spotlight-panel rounded-md p-6">
                    <div class="grid gap-4 md:grid-cols-3">
                        @foreach ($deliveryPillars as $pillar)
                            <div class="rounded-sm border px-4 py-5" style="border-color: var(--page-border); background: var(--page-surface-strong);">
                                <p class="text-xs uppercase tracking-[0.18em]" style="color: var(--page-primary);">{{ $pillar['title'] }}</p>
                                <p class="mt-3 text-sm leading-7" style="color: var(--page-text-soft);">{{ $pillar['copy'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="reveal-fade py-18">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 max-w-3xl">
                <p class="eyebrow">Operating Tracks</p>
                <h2 class="section-title">How the club frames project work</h2>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                @foreach ($projectTracks as $track)
                    <article class="route-card rounded-md p-6">
                        <h3 class="text-2xl font-semibold" style="color: var(--page-text);">{{ $track['name'] }}</h3>
                        <p class="body-copy mt-4">{{ $track['focus'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="cyber-section reveal-fade">
        <div class="mx-auto max-w-6xl px-4 py-18 sm:px-6 lg:px-8">
            <div class="mb-8 max-w-3xl">
                <p class="eyebrow">Published Work</p>
                <h2 class="section-title">Project records</h2>
                <p class="body-copy mt-4">Where project data exists in the club system, it appears here as an operational record rather than a marketing card.</p>
            </div>

            <div class="space-y-5">
                @forelse ($projects as $project)
                    <article class="dossier-card rounded-md p-6">
                        <div class="grid gap-6 lg:grid-cols-[1fr_220px]">
                            <div>
                                <div class="flex flex-wrap items-center gap-3">
                                    <p class="text-xs uppercase tracking-[0.18em]" style="color: var(--page-primary);">{{ ucfirst(str_replace('_', ' ', $project->type)) }}</p>
                                    <span class="rounded-sm border px-3 py-2 text-xs uppercase tracking-[0.18em]" style="border-color: var(--page-border); color: var(--page-text-soft);">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</span>
                                </div>

                                <h3 class="mt-3 text-2xl font-semibold" style="color: var(--page-text);">{{ $project->name }}</h3>
                                <p class="body-copy mt-4">{{ $project->description }}</p>

                                @if ($project->objectives)
                                    <div class="mt-4 rounded-sm border px-4 py-4" style="border-color: var(--page-border); background: var(--page-surface-strong);">
                                        <div class="text-xs uppercase tracking-[0.18em]" style="color: var(--page-text-muted);">Objectives</div>
                                        <p class="body-copy mt-2">{{ $project->objectives }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="grid gap-3">
                                <div class="rounded-sm border px-4 py-4" style="border-color: var(--page-border); background: var(--page-surface-strong);">
                                    <div class="text-xs uppercase tracking-[0.18em]" style="color: var(--page-text-muted);">Lead</div>
                                    <div class="mt-2 font-semibold" style="color: var(--page-text);">{{ $project->lead?->name ?? 'Pending assignment' }}</div>
                                </div>
                                <div class="rounded-sm border px-4 py-4" style="border-color: var(--page-border); background: var(--page-surface-strong);">
                                    <div class="text-xs uppercase tracking-[0.18em]" style="color: var(--page-text-muted);">Progress</div>
                                    <div class="mt-2 font-semibold" style="color: var(--page-text);">{{ $project->progress_percentage }}%</div>
                                </div>
                                <div class="rounded-sm border px-4 py-4" style="border-color: var(--page-border); background: var(--page-surface-strong);">
                                    <div class="text-xs uppercase tracking-[0.18em]" style="color: var(--page-text-muted);">Team size</div>
                                    <div class="mt-2 font-semibold" style="color: var(--page-text);">{{ $project->members->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <article class="identity-block">
                        <div class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
                            <div>
                                <p class="eyebrow">Current State</p>
                                <h3 class="dossier-title mt-3">No public project entries have been published yet.</h3>
                            </div>
                            <p class="dossier-copy">
                                The project database is ready. Once club teams begin recording work, this page will present project challenges, delivery approach, leads, team members, and progress in a clear operational format.
                            </p>
                        </div>
                    </article>
                @endforelse
            </div>
        </div>
    </section>

    <section class="reveal-fade py-18">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <article class="identity-block">
                <div class="grid gap-8 lg:grid-cols-[1fr_1fr] lg:items-center">
                    <div class="space-y-5">
                        <p class="eyebrow">Distinctive Content System</p>
                        <h2 class="dossier-title">The club can present work like a disciplined security practice, even at student level.</h2>
                        <p class="dossier-copy">
                            A clear project page strengthens institutional identity. It shows that the club does not only host events. It also learns through execution, records outcomes, and builds visible student capability over time.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3 lg:justify-end">
                        <a href="{{ route('register') }}" class="cyber-button">Join the Club</a>
                        <a href="{{ route('members.public') }}" class="cyber-outline-button">View Members</a>
                    </div>
                </div>
            </article>
        </div>
    </section>
@endsection
