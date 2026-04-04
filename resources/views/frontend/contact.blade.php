@extends('layouts.frontend')

@section('content')
    @php
        $contactNotes = [
            'This page is for students who want to join, visitors who need clarification, and collaborators who want to connect with the club.',
            'Contact information should reduce hesitation by making the route to the club feel legitimate and simple.',
            'A strong contact page is part of institutional trust because it proves the club can be reached by real people.',
        ];
    @endphp

    <section class="hero-backdrop border-b bg-cover bg-center" style="border-color: var(--page-border); background-image: url('{{ asset('images/club/certificate-team.jpg') }}');">
        <div class="mx-auto max-w-6xl px-4 pb-14 pt-12 sm:px-6 lg:px-8">
            <div class="grid items-center gap-8 lg:grid-cols-[0.92fr_1.08fr]">
                <div class="space-y-5">
                    <p class="eyebrow">Contact the Club</p>
                    <h1 class="page-hero-title">A serious club should be easy to reach for the right reasons.</h1>
                    <p class="page-hero-copy">
                        The contact page is part of the site’s credibility. It helps students, speakers, collaborators, and campus visitors understand how to start a real conversation with the club.
                    </p>
                </div>

                <article class="spotlight-panel overflow-hidden rounded-md" style="background: color-mix(in srgb, var(--page-surface) 92%, transparent);">
                    <img src="{{ asset('images/club/kevin-sharon.jpg') }}" alt="SLAU club members" class="h-[420px] w-full object-contain object-center">
                </article>
            </div>
        </div>
    </section>

    <section class="reveal-fade py-18">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-8 md:grid-cols-[minmax(0,_3fr)_minmax(0,_2fr)] items-start">
                <form class="dossier-card rounded-md p-6 sm:p-8 space-y-5">
                    <div>
                        <p class="eyebrow">Message Route</p>
                        <h2 class="dossier-title">Questions, collaboration, or membership interest</h2>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="name" class="mb-2 block text-sm font-medium" style="color: var(--page-text-soft);">Full name</label>
                            <input id="name" type="text" class="w-full rounded-sm border px-4 py-3 text-sm" style="border-color: var(--page-border); background: var(--page-surface-strong); color: var(--page-text);" placeholder="Your name">
                        </div>
                        <div>
                            <label for="email" class="mb-2 block text-sm font-medium" style="color: var(--page-text-soft);">Email</label>
                            <input id="email" type="email" class="w-full rounded-sm border px-4 py-3 text-sm" style="border-color: var(--page-border); background: var(--page-surface-strong); color: var(--page-text);" placeholder="you@example.com">
                        </div>
                    </div>

                    <div>
                        <label for="topic" class="mb-2 block text-sm font-medium" style="color: var(--page-text-soft);">Topic</label>
                        <select id="topic" class="w-full rounded-sm border px-4 py-3 text-sm" style="border-color: var(--page-border); background: var(--page-surface-strong); color: var(--page-text);">
                            <option>Membership and joining</option>
                            <option>Event attendance or inquiry</option>
                            <option>Collaboration or partnership</option>
                            <option>Speaker invitation</option>
                        </select>
                    </div>

                    <div>
                        <label for="message" class="mb-2 block text-sm font-medium" style="color: var(--page-text-soft);">Message</label>
                        <textarea id="message" rows="5" class="w-full rounded-sm border px-4 py-3 text-sm" style="border-color: var(--page-border); background: var(--page-surface-strong); color: var(--page-text);" placeholder="Tell the club how it can help"></textarea>
                    </div>

                    <p class="body-copy">
                        This form structure supports membership questions, event inquiries, collaboration requests, partnership ideas, and general communication.
                    </p>

                    <button type="button" class="cyber-button">Send Message</button>
                </form>

                <aside class="space-y-5">
                    <article class="proof-card rounded-md p-6">
                        <p class="eyebrow">Contact Notes</p>
                        <ul class="mt-4 space-y-3 text-sm" style="color: var(--page-text-soft);">
                            <li><span style="color: var(--page-text-muted);">Email:</span> cyberclub@slau.ac.ug</li>
                            <li><span style="color: var(--page-text-muted);">Location:</span> St. Lawrence University, Kampala</li>
                            <li><span style="color: var(--page-text-muted);">Audience:</span> students, collaborators, and campus visitors</li>
                        </ul>
                    </article>

                    @foreach ($contactNotes as $note)
                        <article class="dossier-card rounded-md p-5">
                            <p class="body-copy">{{ $note }}</p>
                        </article>
                    @endforeach
                </aside>
            </div>
        </div>
    </section>
@endsection
