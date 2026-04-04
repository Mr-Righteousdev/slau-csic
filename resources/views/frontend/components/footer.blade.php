<footer class="mt-16 border-t" style="border-color: var(--page-border); background: color-mix(in srgb, var(--page-surface) 82%, transparent);">
    <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-8 md:grid-cols-[1.15fr_0.85fr_0.85fr]">
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center overflow-hidden rounded-md border border-white/70 bg-white shadow-[0_12px_30px_rgba(2,8,23,0.12)]">
                        <img src="{{ asset('images/club/logo1.jpg') }}" alt="SLAU Cybersecurity Club logo" class="h-9 w-9 object-contain">
                    </div>
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em]" style="color: var(--page-primary);">SLAU</p>
                        <p class="text-sm font-semibold" style="color: var(--page-text);">Cybersecurity &amp; Innovations Club</p>
                    </div>
                </div>

                <p class="max-w-md text-sm leading-7" style="color: var(--page-text-soft);">
                    A student-led community at St. Lawrence University dedicated to practical cybersecurity learning, responsible innovation, and visible technical participation on campus.
                </p>

                <div class="flex flex-wrap gap-3">
                    <span class="evidence-chip">Student-led technical community</span>
                    <span class="evidence-chip">Campus learning culture</span>
                    <span class="evidence-chip">Responsible practice</span>
                </div>
            </div>

            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em]" style="color: var(--page-text-muted);">Club Routes</p>
                <ul class="mt-4 space-y-3 text-sm" style="color: var(--page-text-soft);">
                    <li><a href="{{ route('about') }}">About the Club</a></li>
                    <li><a href="{{ route('projects') }}">Projects and Delivery</a></li>
                    <li><a href="{{ route('events-out') }}">Events and Sessions</a></li>
                    <li><a href="{{ route('team') }}">Leadership Team</a></li>
                    <li><a href="{{ route('members.public') }}">Member Directory</a></li>
                    <li><a href="{{ route('contact') }}">Contact and Partnerships</a></li>
                </ul>
            </div>

            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em]" style="color: var(--page-text-muted);">Institutional Notes</p>
                <div class="mt-4 space-y-4 text-sm" style="color: var(--page-text-soft);">
                    <p>St. Lawrence University, Kampala, Uganda.</p>
                    <p>Public-facing pages are designed to help students, collaborators, and visitors understand the club before they engage.</p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('contact') }}" class="cyber-outline-button text-xs">Contact the Club</a>
                        <a href="{{ route('register') }}" class="cyber-button px-4 py-3 text-xs">Member Access</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-10 flex flex-col gap-3 border-t pt-6 text-xs sm:flex-row sm:items-center sm:justify-between" style="border-color: var(--page-border); color: var(--page-text-muted);">
            <p>&copy; {{ date('Y') }} SLAU Cybersecurity &amp; Innovations Club. Built to communicate clarity, credibility, and student growth.</p>
            <div class="flex items-center gap-4">
                <a href="{{ route('about') }}">About</a>
                <a href="{{ route('contact') }}">Contact</a>
            </div>
        </div>
    </div>
</footer>
