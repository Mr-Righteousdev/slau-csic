@extends('layouts.fullscreen-layout')

@section('content')
    <div class="portal-shell relative min-h-screen overflow-hidden">
        <div class="portal-grid absolute inset-0 opacity-40"></div>

        <div class="relative z-10 flex min-h-screen flex-col lg:flex-row">
            <section class="flex w-full lg:w-[46%]">
                <div class="mx-auto flex w-full max-w-xl flex-1 flex-col justify-center px-6 py-12 sm:px-10 lg:px-14">
                    <div class="mb-8 flex items-center justify-between gap-4">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                            <span class="flex h-12 w-12 items-center justify-center rounded-md bg-white p-1.5 shadow-[0_16px_40px_rgba(2,8,23,0.22)]">
                                <img src="{{ asset('images/club/logo1.jpg') }}" alt="SLAU Cybersecurity Club logo" class="h-9 w-9 object-contain">
                            </span>
                            <span>
                                <span class="block text-sm font-semibold uppercase tracking-[0.18em]" style="color: var(--portal-primary);">SLAU</span>
                                <span class="portal-muted block text-xs">Cybersecurity &amp; Innovations Club</span>
                            </span>
                        </a>

                        <button type="button" class="portal-toggle text-xs font-medium uppercase tracking-[0.18em]" data-theme-toggle>
                            <span data-theme-icon>☾</span>
                            <span data-theme-label>Dark</span>
                        </button>
                    </div>

                    <div class="mb-8">
                        <p class="text-sm font-medium uppercase tracking-[0.28em]" style="color: var(--portal-secondary);">Member Access</p>
                        <h1 class="mt-4 text-4xl font-semibold tracking-tight sm:text-5xl" style="color: var(--portal-text);">Sign in to your club account</h1>
                        <p class="portal-copy mt-4 max-w-lg text-sm leading-7 sm:text-base">
                            Access member tools, event registration, club updates, and your personal dashboard from one secure place.
                        </p>
                    </div>

                    <div class="portal-card rounded-md p-6 sm:p-8">
                        <form method="POST" action="{{ route('login') }}">
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

                                <div>
                                    <label for="password" class="portal-copy mb-2 block text-sm font-medium">
                                        Password <span style="color: var(--portal-primary);">*</span>
                                    </label>
                                    <div x-data="{ showPassword: false }" class="relative">
                                        <input
                                            :type="showPassword ? 'text' : 'password'"
                                            id="password"
                                            name="password"
                                            placeholder="Enter your password"
                                            class="portal-field h-12 w-full rounded-sm px-4 pr-12 text-sm placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/10 @error('password') border-error-500 @enderror"
                                        />
                                        <button type="button" @click="showPassword = !showPassword" class="portal-muted absolute inset-y-0 right-4 inline-flex items-center">
                                            <svg x-show="!showPassword" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.0002 13.8619C7.23361 13.8619 4.86803 12.1372 3.92328 9.70241C4.86804 7.26761 7.23361 5.54297 10.0002 5.54297C12.7667 5.54297 15.1323 7.26762 16.0771 9.70243C15.1323 12.1372 12.7667 13.8619 10.0002 13.8619ZM10.0002 4.04297C6.48191 4.04297 3.49489 6.30917 2.4155 9.4593C2.3615 9.61687 2.3615 9.78794 2.41549 9.94552C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C13.5184 15.3619 16.5055 13.0957 17.5849 9.94555C17.6389 9.78797 17.6389 9.6169 17.5849 9.45932C16.5055 6.30919 13.5184 4.04297 10.0002 4.04297ZM9.99151 7.84413C8.96527 7.84413 8.13333 8.67606 8.13333 9.70231C8.13333 10.7286 8.96527 11.5605 9.99151 11.5605H10.0064C11.0326 11.5605 11.8646 10.7286 11.8646 9.70231C11.8646 8.67606 11.0326 7.84413 10.0064 7.84413H9.99151Z" fill="currentColor" />
                                            </svg>
                                            <svg x-show="showPassword" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M4.63803 3.57709C4.34513 3.2842 3.87026 3.2842 3.57737 3.57709C3.28447 3.86999 3.28447 4.34486 3.57737 4.63775L4.85323 5.91362C3.74609 6.84199 2.89363 8.06395 2.4155 9.45936C2.3615 9.61694 2.3615 9.78801 2.41549 9.94558C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C11.255 15.3619 12.4422 15.0737 13.4994 14.5598L15.3625 16.4229C15.6554 16.7158 16.1302 16.7158 16.4231 16.4229C16.716 16.13 16.716 15.6551 16.4231 15.3622L4.63803 3.57709ZM12.3608 13.4212L10.4475 11.5079C10.3061 11.5423 10.1584 11.5606 10.0064 11.5606H9.99151C8.96527 11.5606 8.13333 10.7286 8.13333 9.70237C8.13333 9.5461 8.15262 9.39434 8.18895 9.24933L5.91885 6.97923C5.03505 7.69015 4.34057 8.62704 3.92328 9.70247C4.86803 12.1373 7.23361 13.8619 10.0002 13.8619C10.8326 13.8619 11.6287 13.7058 12.3608 13.4212ZM16.0771 9.70249C15.7843 10.4569 15.3552 11.1432 14.8199 11.7311L15.8813 12.7925C16.6329 11.9813 17.2187 11.0143 17.5849 9.94561C17.6389 9.78803 17.6389 9.61696 17.5849 9.45938C16.5055 6.30925 13.5184 4.04303 10.0002 4.04303C9.13525 4.04303 8.30244 4.17999 7.52218 4.43338L8.75139 5.66259C9.1556 5.58413 9.57311 5.54303 10.0002 5.54303C12.7667 5.54303 15.1323 7.26768 16.0771 9.70249Z" fill="currentColor" />
                                            </svg>
                                        </button>
                                    </div>
                                    @error('password')
                                        <p class="mt-2 text-sm text-error-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center justify-between gap-4">
                                    <label class="portal-copy inline-flex items-center gap-3 text-sm">
                                        <input type="checkbox" id="remember" name="remember" class="h-4 w-4 rounded border-white/20 bg-transparent text-emerald-400 focus:ring-emerald-400/20">
                                        <span>Keep me signed in</span>
                                    </label>

                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-sm transition hover:text-emerald-200" style="color: var(--portal-primary);">
                                            Forgot password?
                                        </a>
                                    @endif
                                </div>

                                <button type="submit" class="portal-button flex h-12 w-full items-center justify-center rounded-sm px-4 text-sm font-semibold transition">
                                    Sign In
                                </button>
                            </div>
                        </form>

                        <div class="portal-muted mt-6 text-sm">
                            Don’t have an account?
                            <a href="{{ route('register') }}" class="font-medium hover:text-sky-200" style="color: var(--portal-secondary);">Create one here</a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="relative hidden lg:flex lg:w-[54%]">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/club/cyber-team.jpg') }}');"></div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_rgba(7,16,29,0.1)_12%,_rgba(7,16,29,0.45)_54%,_rgba(7,16,29,0.92)_100%),linear-gradient(180deg,rgba(7,16,29,0.15)_0%,rgba(7,16,29,0.78)_100%)]"></div>
                <div class="portal-grid absolute inset-0 opacity-40"></div>

                <div class="relative z-10 flex flex-1 items-end p-10 xl:p-14">
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/8 px-4 py-2 text-xs font-medium uppercase tracking-[0.22em] text-white/90 backdrop-blur-md">
                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                            Member Portal
                        </div>

                        <h2 class="mt-6 text-4xl font-semibold tracking-tight text-white xl:text-5xl">
                            Stay connected to club learning, events, and member activity.
                        </h2>

                        <p class="mt-5 max-w-xl text-base leading-8 text-slate-200">
                            Sign in to register for activities, access your member profile, track event participation, and stay engaged with the club’s growing cybersecurity community.
                        </p>

                        <div class="mt-8 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-md border border-white/10 bg-[#07101d]/58 px-4 py-4 backdrop-blur-md">
                                <div class="text-2xl font-semibold text-white">Weekly</div>
                                <div class="mt-1 text-xs uppercase tracking-[0.18em] text-slate-300">Sessions</div>
                            </div>
                            <div class="rounded-md border border-white/10 bg-[#07101d]/58 px-4 py-4 backdrop-blur-md">
                                <div class="text-2xl font-semibold text-white">Monthly</div>
                                <div class="mt-1 text-xs uppercase tracking-[0.18em] text-slate-300">CTF</div>
                            </div>
                            <div class="rounded-md border border-white/10 bg-[#07101d]/58 px-4 py-4 backdrop-blur-md">
                                <div class="text-2xl font-semibold text-white">Secure</div>
                                <div class="mt-1 text-xs uppercase tracking-[0.18em] text-slate-300">Member Access</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
