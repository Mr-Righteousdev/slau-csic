@extends('layouts.fullscreen-layout')

@section('content')
    <div class="portal-shell relative min-h-screen overflow-hidden">
        <div class="portal-grid absolute inset-0 opacity-40"></div>
        <div class="portal-orb portal-orb-secondary left-[8%] top-[10%] h-48 w-48"></div>
        <div class="portal-orb portal-orb-primary bottom-[8%] right-[12%] h-56 w-56"></div>

        <div class="relative z-10 flex min-h-screen flex-col lg:flex-row">
            <section class="flex w-full lg:w-[46%]">
                <div class="mx-auto flex w-full max-w-xl flex-1 flex-col justify-center px-6 py-12 sm:px-10 lg:px-14">
                    <div class="mb-8 flex items-center justify-between gap-4">
                        <a href="{{ route('login') }}" class="portal-backlink">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7" />
                            </svg>
                            Back to sign in
                        </a>

                        <button type="button" class="portal-toggle text-xs font-medium uppercase tracking-[0.18em]" data-theme-toggle>
                            <span data-theme-icon>☾</span>
                            <span data-theme-label>Dark</span>
                        </button>
                    </div>

                    <div class="mb-8">
                        <p class="text-sm font-medium uppercase tracking-[0.28em]" style="color: var(--portal-secondary);">Account Recovery</p>
                        <h1 class="mt-4 text-4xl font-semibold tracking-tight sm:text-5xl" style="color: var(--portal-text);">Recover access securely</h1>
                        <p class="portal-copy mt-4 max-w-lg text-sm leading-7 sm:text-base">
                            Enter your email address and the system will send you a secure password reset link for your club account.
                        </p>
                    </div>

                    <div class="portal-card rounded-md p-6 sm:p-8">
                        @if (session('status'))
                            <div class="mb-5 rounded-md border px-4 py-3 text-sm" style="border-color: rgba(25, 226, 143, 0.24); background: rgba(25, 226, 143, 0.08); color: var(--portal-text-soft);">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="space-y-5">
                                <div>
                                    <label for="email" class="portal-copy mb-2 block text-sm font-medium">
                                        Email address <span style="color: var(--portal-primary);">*</span>
                                    </label>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        placeholder="name@slau.ac.ug"
                                        autofocus
                                        class="portal-field h-12 w-full rounded-sm px-4 text-sm placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/10 @error('email') border-error-500 @enderror"
                                    />
                                    @error('email')
                                        <p class="mt-2 text-sm text-error-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit" class="portal-button flex h-12 w-full items-center justify-center rounded-sm px-4 text-sm font-semibold transition">
                                    Send Reset Link
                                </button>
                            </div>
                        </form>

                        <div class="portal-muted mt-6 text-sm">
                            Remembered your password?
                            <a href="{{ route('login') }}" class="font-medium hover:text-sky-200" style="color: var(--portal-secondary);">Sign in</a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="portal-shell-panel relative hidden lg:flex lg:w-[54%]">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/club/certificate-team.jpg') }}');"></div>
                <div class="portal-grid absolute inset-0 opacity-40"></div>

                <div class="flex flex-1 items-end p-10 xl:p-14">
                    <div class="max-w-2xl">
                        <div class="portal-badge">Recovery Channel</div>
                        <h2 class="mt-6 text-4xl font-semibold tracking-tight text-white xl:text-5xl">
                            Account recovery should feel secure, calm, and trustworthy.
                        </h2>
                        <p class="portal-side-copy mt-5 max-w-xl text-base leading-8">
                            This recovery flow is designed as part of the same premium member portal experience, with a clearer visual hierarchy and a more dependable security tone.
                        </p>

                        <div class="portal-side-grid mt-8 sm:grid-cols-3">
                            <div class="portal-side-stat">
                                <div class="portal-side-stat-value">Secure</div>
                                <div class="portal-side-stat-label">Reset link delivery</div>
                            </div>
                            <div class="portal-side-stat">
                                <div class="portal-side-stat-value">Member</div>
                                <div class="portal-side-stat-label">Account continuity</div>
                            </div>
                            <div class="portal-side-stat">
                                <div class="portal-side-stat-value">SLAU</div>
                                <div class="portal-side-stat-label">Portal identity</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
