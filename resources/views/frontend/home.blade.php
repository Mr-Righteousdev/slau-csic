@extends('layouts.frontend')

@section('content')
    @php
        $heroSlides = [
            [
                'image' => 'images/club/certificate-team.jpg',
                'eyebrow' => 'Documented Representation',
                'title' => 'The club already has visible proof of student participation.',
                'summary' => 'The website now leads with evidence of real activity: club members represented in public, documented participation, and a clear student identity.',
                'badge' => 'Proof',
                'detail' => 'Public exhibition presence',
            ],
            [
                'image' => 'images/club/cyber-team.jpg',
                'eyebrow' => 'Institutional Identity',
                'title' => 'SLAU needs a cybersecurity club that feels credible and campus-rooted.',
                'summary' => 'This platform positions the club as a university-based community for practical learning, responsible digital practice, and visible student growth.',
                'badge' => 'Identity',
                'detail' => 'Built for campus trust',
            ],
            [
                'image' => 'images/club/with-gentlemen.jpg',
                'eyebrow' => 'Member Culture',
                'title' => 'A strong club is defined by the people who participate in it.',
                'summary' => 'Real photos, visible student faces, and a clearer growth path help visitors understand that the club is human, active, and worth joining.',
                'badge' => 'Community',
                'detail' => 'Student-led and visible',
            ],
        ];

        $proofStats = [
            ['value' => 'Weekly', 'label' => 'club rhythm'],
            ['value' => 'Campus', 'label' => 'institutional context'],
            ['value' => 'Real', 'label' => 'member photography'],
            ['value' => 'Visible', 'label' => 'public representation'],
        ];

        $routes = [
            [
                'label' => 'Identity Dossier',
                'title' => 'What the club is',
                'text' => 'A student-led cybersecurity and innovations community at St. Lawrence University built around practical learning, discipline, and responsible technical growth.',
                'cta' => 'Read about the club',
                'route' => route('about'),
            ],
            [
                'label' => 'Operating Model',
                'title' => 'How the club works',
                'text' => 'Members learn through regular sessions, collaborative problem solving, guided activities, and opportunities to represent the club visibly.',
                'cta' => 'See events',
                'route' => route('events-out'),
            ],
            [
                'label' => 'People and Structure',
                'title' => 'Who gives the club its face',
                'text' => 'Leadership, contributors, and members give the community its character. Visitors should be able to see the people behind the work.',
                'cta' => 'Meet the team',
                'route' => route('team'),
            ],
        ];

        $proofPanels = [
            [
                'label' => 'Proof 01',
                'title' => 'Documented participation',
                'text' => 'The site uses actual club imagery, including evidence of participation in a public academic and exhibition setting.',
            ],
            [
                'label' => 'Proof 02',
                'title' => 'Visible student identity',
                'text' => 'Real student faces strengthen trust. They show that the club is not hypothetical, anonymous, or inactive.',
            ],
            [
                'label' => 'Proof 03',
                'title' => 'Institutional grounding',
                'text' => 'Every page now makes the university relationship clear so the club feels attached to a real place, community, and audience.',
            ],
        ];

        $systemBlocks = [
            [
                'eyebrow' => 'Identity',
                'title' => 'A clear institutional position',
                'text' => 'The club is presented as part of St. Lawrence University life, not as a floating technology brand. That creates immediate context and legitimacy.',
            ],
            [
                'eyebrow' => 'Clarity',
                'title' => 'A website that explains before it impresses',
                'text' => 'Visitors should understand the club quickly: what it stands for, how students join, what members do, and why the community matters.',
            ],
            [
                'eyebrow' => 'Distinctiveness',
                'title' => 'One repeated content system across the site',
                'text' => 'Each page now follows the same pattern: position the club, show evidence, explain the operating model, and guide the next action.',
            ],
        ];

        $faqs = [
            [
                'question' => 'What makes this website different from a generic club page?',
                'answer' => 'It is built around institutional identity, real proof, and a repeated content system. Instead of only looking modern, it explains the club clearly and shows why it should be trusted.',
            ],
            [
                'question' => 'Is the site only for existing members?',
                'answer' => 'No. The public pages are designed for visitors, new students, collaborators, and prospective members who need to understand the club before they join.',
            ],
            [
                'question' => 'Why use both dark and light modes?',
                'answer' => 'Cybersecurity websites often need technical depth without visual fatigue. A proper theme system allows the site to feel serious in dark mode and clearer in light mode while preserving the same structure.',
            ],
            [
                'question' => 'What is the main message of the homepage now?',
                'answer' => 'The homepage now says one thing clearly: SLAU has a student-led cybersecurity club with visible members, practical activity, and a credible institutional presence.',
            ],
        ];
    @endphp

    <section class="home-carousel min-h-[92vh] border-b" style="border-color: var(--page-border);" data-home-carousel>
        @foreach ($heroSlides as $index => $slide)
            <article
                class="home-carousel-slide {{ $index === 0 ? 'is-active' : '' }} bg-cover bg-center"
                data-home-slide
                style="background-image: url('{{ asset($slide['image']) }}');"
            ></article>
        @endforeach

        <div class="home-carousel-grid"></div>
        <div class="home-carousel-shade"></div>

        <div class="home-carousel-content">
            <div class="mx-auto flex min-h-[92vh] max-w-6xl items-end px-4 pb-12 pt-28 sm:px-6 lg:px-8">
                <div class="grid w-full gap-10 lg:grid-cols-[1.05fr_0.95fr] lg:items-end">
                    <div class="space-y-8">
                        <div class="evidence-chip">St. Lawrence University, Kampala</div>

                        <div class="space-y-5">
                            <p class="eyebrow">Cybersecurity and Innovations Club</p>
                            <h1 class="page-hero-title">A cybersecurity club with a clearer identity, real proof, and a stronger institutional presence.</h1>
                            <p class="page-hero-copy">
                                The Cybersecurity and Innovations Club at SLAU is presented here as a real student community: one that supports practical learning, responsible innovation, visible representation, and disciplined participation on campus.
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('register') }}" class="cyber-button">Join the Club</a>
                            <a href="{{ route('about') }}" class="cyber-outline-button">Read the Club Dossier</a>
                            <a href="{{ route('members.public') }}" class="cyber-outline-button">See Member Profiles</a>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-4">
                            @foreach ($proofStats as $stat)
                                <div class="proof-stat">
                                    <span class="proof-stat-value">{{ $stat['value'] }}</span>
                                    <span class="proof-stat-label">{{ $stat['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-4 lg:pl-6">
                        <article class="spotlight-panel rounded-md p-6 sm:p-7">
                            <p class="eyebrow">Homepage Evidence Panel</p>
                            <h2 class="dossier-title">The first screen should already explain why the club deserves attention.</h2>

                            <div class="mt-6 space-y-3">
                                @foreach ($heroSlides as $index => $slide)
                                    <button type="button" class="hero-slide-card {{ $index === 0 ? 'is-active' : '' }}" data-home-panel>
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="dossier-label">{{ $slide['eyebrow'] }}</p>
                                                <h3 class="mt-2 text-lg font-semibold" style="color: var(--page-text);">{{ $slide['title'] }}</h3>
                                            </div>
                                            <span class="rounded-full border px-3 py-1 text-[11px] font-medium uppercase tracking-[0.18em]" style="border-color: var(--page-border); color: var(--page-text-muted);">
                                                {{ $slide['badge'] }}
                                            </span>
                                        </div>
                                        <p class="mt-3 text-sm leading-7" style="color: var(--page-text-soft);">{{ $slide['summary'] }}</p>
                                        <div class="mt-4 flex items-center justify-between gap-3">
                                            <span class="text-xs uppercase tracking-[0.2em]" style="color: var(--page-text-muted);">{{ $slide['detail'] }}</span>
                                            <span class="hero-slide-progress"><span></span></span>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </article>

                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <button type="button" class="home-carousel-control" data-home-prev aria-label="Previous slide">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <button type="button" class="home-carousel-control" data-home-next aria-label="Next slide">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>

                            <div class="flex items-center gap-3 rounded-full border px-5 py-4 backdrop-blur-md" style="border-color: var(--page-border); background: color-mix(in srgb, var(--page-surface) 78%, transparent);">
                                @foreach ($heroSlides as $index => $slide)
                                    <button type="button" class="home-carousel-dot {{ $index === 0 ? 'is-active' : '' }}" data-home-dot aria-label="Go to slide {{ $index + 1 }}"></button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="border-b" style="border-color: var(--page-border); background: color-mix(in srgb, var(--page-surface) 66%, transparent);">
        <div class="mx-auto max-w-6xl overflow-hidden px-4 sm:px-6 lg:px-8">
            <div class="hero-ticker">
                @foreach (['Institutional identity', 'real proof', 'clear visitor journey', 'student visibility', 'responsible technical growth', 'campus credibility', 'member access', 'practical learning'] as $item)
                    <span>{{ $item }}</span>
                @endforeach
                @foreach (['Institutional identity', 'real proof', 'clear visitor journey', 'student visibility', 'responsible technical growth', 'campus credibility', 'member access', 'practical learning'] as $item)
                    <span>{{ $item }}</span>
                @endforeach
            </div>
        </div>
    </section>

    <section class="reveal-fade py-18">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr]">
                <div class="space-y-5">
                    <p class="eyebrow">Distinctive Content System</p>
                    <h2 class="section-title">Every page now follows the same logic: identity, proof, operating model, and next step.</h2>
                    <p class="lead-copy">
                        That system makes the site more original because it is not just using random cards and effects. It is using a repeated structure that belongs to this club and supports understanding.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    @foreach ($routes as $route)
                        <article class="route-card rounded-md p-6">
                            <p class="dossier-label">{{ $route['label'] }}</p>
                            <h3 class="dossier-title">{{ $route['title'] }}</h3>
                            <p class="dossier-copy">{{ $route['text'] }}</p>
                            <a href="{{ $route['route'] }}" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold" style="color: var(--page-primary);">
                                {{ $route['cta'] }}
                                <span aria-hidden="true">→</span>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="cyber-section reveal-fade">
        <div class="mx-auto max-w-6xl px-4 py-18 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[1.02fr_0.98fr] lg:items-center">
                <div class="grid gap-4">
                    @foreach ($proofPanels as $panel)
                        <article class="proof-card rounded-md p-6">
                            <p class="dossier-label">{{ $panel['label'] }}</p>
                            <h3 class="dossier-title">{{ $panel['title'] }}</h3>
                            <p class="dossier-copy">{{ $panel['text'] }}</p>
                        </article>
                    @endforeach
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <article class="story-panel overflow-hidden rounded-md sm:col-span-2">
                        <div class="story-panel-image-wrap">
                            <img src="{{ asset('images/club/certificate-team.jpg') }}" alt="SLAU club members holding a certificate" class="story-panel-image">
                        </div>
                    </article>
                    <article class="story-panel overflow-hidden rounded-md">
                        <div class="story-panel-image-wrap min-h-[18rem]">
                            <img src="{{ asset('images/club/kevin-samuel-2.jpg') }}" alt="SLAU club member portrait" class="story-panel-image object-contain bg-[#07101d]">
                        </div>
                    </article>
                    <article class="quote-panel rounded-md p-6">
                        <p class="eyebrow">Institutional Position</p>
                        <p class="mt-5 text-xl font-semibold leading-9" style="color: var(--page-text);">
                            The website should make it immediately clear that this is a real university club, not a generic technology landing page.
                        </p>
                        <div class="mt-8 flex items-center gap-4">
                            <img src="{{ asset('images/club/logo1.jpg') }}" alt="SLAU cybersecurity club logo" class="h-14 w-14 rounded-md bg-white p-1.5 object-contain">
                            <div>
                                <p class="text-sm font-semibold" style="color: var(--page-text);">SLAU Cybersecurity &amp; Innovations Club</p>
                                <p class="text-sm" style="color: var(--page-text-soft);">Student-led identity anchored in campus life</p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="reveal-fade py-18">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 max-w-3xl">
                <p class="eyebrow">Uniqueness Rules Applied</p>
                <h2 class="section-title">The site now follows stronger cybersecurity design rules without becoming a cliché.</h2>
            </div>

            <div class="grid gap-5 lg:grid-cols-3">
                @foreach ($systemBlocks as $block)
                    <article class="identity-card rounded-md p-7">
                        <p class="dossier-label">{{ $block['eyebrow'] }}</p>
                        <h3 class="dossier-title">{{ $block['title'] }}</h3>
                        <p class="dossier-copy">{{ $block['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="cyber-section reveal-fade">
        <div class="mx-auto max-w-6xl px-4 py-18 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <p class="eyebrow">Frequently Asked Questions</p>
                    <h2 class="section-title">Clarifying the homepage message</h2>
                </div>
                <a href="{{ route('contact') }}" class="cyber-outline-button">Contact the Club Cabinet</a>
            </div>

            <div class="mt-8 grid gap-5 lg:grid-cols-2">
                @foreach ($faqs as $faq)
                    <article class="cyber-card rounded-md p-6">
                        <h3 class="text-lg font-semibold" style="color: var(--page-text);">{{ $faq['question'] }}</h3>
                        <p class="mt-3 body-copy">{{ $faq['answer'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
