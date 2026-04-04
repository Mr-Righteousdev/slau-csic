@extends('layouts.frontend')

@section('content')
    @php
        $team = [
            [
                'name' => 'Kevin',
                'role' => 'Visible club representative',
                'image' => 'images/club/kevin-samuel.jpg',
                'summary' => 'Contributes to the public face of the club and helps make the community feel active, approachable, and credible.',
            ],
            [
                'name' => 'Sharon',
                'role' => 'Community-facing contributor',
                'image' => 'images/club/kevin-sharon.jpg',
                'summary' => 'Represents the inclusive and student-centered side of the club by helping the community feel welcoming and real.',
            ],
            [
                'name' => 'Samuel',
                'role' => 'Student contributor',
                'image' => 'images/club/kevin-samuel-2.jpg',
                'summary' => 'Supports the visible energy of the club and helps communicate that the community has active members behind it.',
            ],
        ];

        $leadershipNotes = [
            'Visible leadership reduces uncertainty for prospective members.',
            'Named people make the club feel accountable and organized.',
            'A team page should show both personality and institutional seriousness.',
        ];
    @endphp

    <section class="hero-backdrop border-b bg-cover bg-center" style="border-color: var(--page-border); background-image: url('{{ asset('images/club/with-gentlemen.jpg') }}');">
        <div class="mx-auto max-w-6xl px-4 pb-14 pt-12 sm:px-6 lg:px-8">
            <div class="grid items-center gap-8 lg:grid-cols-[0.92fr_1.08fr]">
                <div class="space-y-5">
                    <p class="eyebrow">Leadership and Contributors</p>
                    <h1 class="page-hero-title">A credible club should show the people who carry its identity.</h1>
                    <p class="page-hero-copy">
                        This page gives the club a visible human structure. Instead of hiding behind abstract branding, it introduces the students whose faces and participation help the community feel real.
                    </p>
                </div>

                <article class="spotlight-panel overflow-hidden rounded-md">
                    <img src="{{ asset('images/club/cyber-team.jpg') }}" alt="SLAU club members together" class="h-[420px] w-full object-cover">
                </article>
            </div>
        </div>
    </section>

    <section class="reveal-fade py-18">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[0.88fr_1.12fr] lg:items-start">
                <div class="space-y-5">
                    <p class="eyebrow">Why This Page Matters</p>
                    <h2 class="section-title">Leadership visibility is part of institutional trust.</h2>
                </div>

                <div class="grid gap-4">
                    @foreach ($leadershipNotes as $note)
                        <article class="proof-card rounded-md px-5 py-5">
                            <p class="body-copy">{{ $note }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="cyber-section reveal-fade">
        <div class="mx-auto max-w-6xl px-4 py-18 sm:px-6 lg:px-8">
            <div class="grid gap-6 md:grid-cols-3">
                @foreach ($team as $member)
                    <article class="cyber-card overflow-hidden rounded-md">
                        <img src="{{ asset($member['image']) }}" alt="{{ $member['name'] }} from the SLAU club" class="h-80 w-full object-cover object-top">
                        <div class="p-6">
                            <p class="dossier-label">{{ $member['role'] }}</p>
                            <h2 class="dossier-title">{{ $member['name'] }}</h2>
                            <p class="dossier-copy">{{ $member['summary'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="reveal-fade py-18">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <article class="identity-block">
                <div class="grid gap-8 lg:grid-cols-[1fr_1fr] lg:items-center">
                    <article class="quote-panel rounded-md p-6">
                        <p class="eyebrow">Team Position</p>
                        <p class="mt-5 text-xl font-semibold leading-9" style="color: var(--page-text);">
                            The team page is not only about profile cards. It is about proving that the club has real people responsible for its culture, visibility, and continuity.
                        </p>
                    </article>

                    <div class="flex flex-wrap gap-3 lg:justify-end">
                        <a href="{{ route('members.public') }}" class="cyber-button">View Member Directory</a>
                        <a href="{{ route('about') }}" class="cyber-outline-button">Read Club Identity</a>
                    </div>
                </div>
            </article>
        </div>
    </section>
@endsection
