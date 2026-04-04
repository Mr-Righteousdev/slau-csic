@extends('layouts.frontend')

@section('content')
    @php
        $eventTypes = [
            [
                'label' => 'Weekly Session',
                'title' => 'Structured practical learning',
                'text' => 'The club runs repeated learning moments so students can build continuity, not just attend a one-time activity and disappear.',
            ],
            [
                'label' => 'Challenge Activity',
                'title' => 'Team-based problem solving',
                'text' => 'Challenge sessions give members space to practice under pressure, collaborate with peers, and experience the energy of applied cybersecurity work.',
            ],
            [
                'label' => 'Public Representation',
                'title' => 'Activity that can be seen and remembered',
                'text' => 'Events also matter because they give the club a visible footprint. A community becomes more credible when its activity can be observed.',
            ],
        ];

        $operatingModel = [
            'Visitors should be able to attend before they decide to join.',
            'Event language should explain what the session is for and who it helps.',
            'Activities should make the club feel active, not speculative.',
            'Every event page should guide the visitor toward the right next action.',
        ];
    @endphp

    <section class="hero-backdrop border-b bg-cover bg-center" style="border-color: var(--page-border); background-image: url('{{ asset('images/club/cyber-team.jpg') }}');">
        <div class="mx-auto max-w-6xl px-4 pb-14 pt-12 sm:px-6 lg:px-8">
            <div class="grid items-center gap-8 lg:grid-cols-[0.92fr_1.08fr]">
                <div class="space-y-5">
                    <p class="eyebrow">Events and Activities</p>
                    <h1 class="page-hero-title">The events page should explain how the club actually moves.</h1>
                    <p class="page-hero-copy">
                        This page is no longer only a list of possible sessions. It now positions events as the club’s operating model: the place where new visitors observe the culture and members develop through repeated participation.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('register') }}" class="cyber-button">Create Member Access</a>
                        <a href="{{ route('contact') }}" class="cyber-outline-button">Ask About Participation</a>
                    </div>
                </div>

                <article class="spotlight-panel overflow-hidden rounded-md">
                    <img src="{{ asset('images/club/certificate-team.jpg') }}" alt="SLAU club event moment" class="h-[420px] w-full object-cover">
                </article>
            </div>
        </div>
    </section>

    <section class="reveal-fade py-18">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 max-w-3xl">
                <p class="eyebrow">Operating Model</p>
                <h2 class="section-title">A cybersecurity club becomes credible when its rhythm is visible.</h2>
            </div>

            <div class="grid gap-5 md:grid-cols-3">
                @foreach ($eventTypes as $type)
                    <article class="route-card rounded-md p-6">
                        <p class="dossier-label">{{ $type['label'] }}</p>
                        <h3 class="dossier-title">{{ $type['title'] }}</h3>
                        <p class="dossier-copy">{{ $type['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="cyber-section reveal-fade">
        <div class="mx-auto max-w-6xl px-4 py-18 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[1fr_1fr] lg:items-start">
                <div class="space-y-5">
                    <p class="eyebrow">Clarity Rules</p>
                    <h2 class="section-title">Visitors should understand what to expect before they register for anything.</h2>
                    <p class="lead-copy">
                        That is why this page now treats event communication as part of trust-building. The goal is not to sound exciting first. The goal is to make participation easy to understand.
                    </p>
                </div>

                <div class="grid gap-4">
                    @foreach ($operatingModel as $item)
                        <article class="dossier-card rounded-md px-5 py-5">
                            <p class="body-copy">{{ $item }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="reveal-fade py-18">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <article class="identity-block">
                <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
                    <div class="space-y-5">
                        <p class="eyebrow">Event Proof</p>
                        <h2 class="dossier-title">Visible event culture helps the club feel active before a visitor ever attends.</h2>
                        <p class="dossier-copy">
                            The use of real club imagery and public participation moments makes the events page feel more grounded. Instead of promising activity abstractly, the site now shows that the club has a visible presence.
                        </p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('contact') }}" class="cyber-button">Contact Organizers</a>
                            <a href="{{ route('members.public') }}" class="cyber-outline-button">View Member Profiles</a>
                        </div>
                    </div>

                    <article class="story-panel overflow-hidden rounded-md">
                        <div class="story-panel-image-wrap">
                            <img src="{{ asset('images/club/with-gentlemen.jpg') }}" alt="SLAU club members at an event" class="story-panel-image">
                        </div>
                    </article>
                </div>
            </article>
        </div>
    </section>
@endsection
