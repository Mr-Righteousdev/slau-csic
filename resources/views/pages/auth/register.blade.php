@extends('layouts.fullscreen-layout')

@section('content')
    <div class="portal-shell relative min-h-screen overflow-hidden">
        <div class="portal-grid absolute inset-0 opacity-40"></div>

        <div class="relative z-10 flex min-h-screen flex-col lg:flex-row">
            <section class="flex w-full lg:w-[54%]">
                <div class="mx-auto flex w-full max-w-3xl flex-1 flex-col justify-center px-6 py-12 sm:px-10 lg:px-14">
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
                        <p class="text-sm font-medium uppercase tracking-[0.28em]" style="color: var(--portal-secondary);">Member Registration</p>
                        <h1 class="mt-4 text-4xl font-semibold tracking-tight sm:text-5xl" style="color: var(--portal-text);">Create your member record</h1>
                        <p class="portal-copy mt-4 max-w-2xl text-sm leading-7 sm:text-base">
                            Register with the core academic, contact, and profile details the club needs for membership review, communication, and directory visibility.
                        </p>
                    </div>

                    <div class="portal-card rounded-md p-6 sm:p-8">
                        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-6">
                            @csrf

                            <div class="grid gap-5 md:grid-cols-2">
                                <div class="md:col-span-2">
                                    <label for="name" class="portal-copy mb-2 block text-sm font-medium">Full name <span style="color: var(--portal-primary);">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Your full name" autofocus class="portal-field h-12 w-full rounded-sm px-4 text-sm placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/10 @error('name') border-error-500 @enderror" />
                                    @error('name')
                                        <p class="mt-2 text-sm text-error-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="portal-copy mb-2 block text-sm font-medium">Email address <span style="color: var(--portal-primary);">*</span></label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="name@slau.ac.ug" class="portal-field h-12 w-full rounded-sm px-4 text-sm placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/10 @error('email') border-error-500 @enderror" />
                                    @error('email')
                                        <p class="mt-2 text-sm text-error-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="student_id" class="portal-copy mb-2 block text-sm font-medium">Student ID <span style="color: var(--portal-primary);">*</span></label>
                                    <input type="text" id="student_id" name="student_id" value="{{ old('student_id') }}" placeholder="SLAU registration number" class="portal-field h-12 w-full rounded-sm px-4 text-sm placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/10 @error('student_id') border-error-500 @enderror" />
                                    @error('student_id')
                                        <p class="mt-2 text-sm text-error-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone" class="portal-copy mb-2 block text-sm font-medium">Phone number <span style="color: var(--portal-primary);">*</span></label>
                                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="07xx xxx xxx" class="portal-field h-12 w-full rounded-sm px-4 text-sm placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/10 @error('phone') border-error-500 @enderror" />
                                    @error('phone')
                                        <p class="mt-2 text-sm text-error-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="program" class="portal-copy mb-2 block text-sm font-medium">Programme <span style="color: var(--portal-primary);">*</span></label>
                                    <input type="text" id="program" name="program" value="{{ old('program') }}" placeholder="Bachelor of Information Technology" class="portal-field h-12 w-full rounded-sm px-4 text-sm placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/10 @error('program') border-error-500 @enderror" />
                                    @error('program')
                                        <p class="mt-2 text-sm text-error-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="faculty" class="portal-copy mb-2 block text-sm font-medium">Faculty or school</label>
                                    <input type="text" id="faculty" name="faculty" value="{{ old('faculty') }}" placeholder="Faculty of Science and Technology" class="portal-field h-12 w-full rounded-sm px-4 text-sm placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/10 @error('faculty') border-error-500 @enderror" />
                                    @error('faculty')
                                        <p class="mt-2 text-sm text-error-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="year_of_study" class="portal-copy mb-2 block text-sm font-medium">Year of study <span style="color: var(--portal-primary);">*</span></label>
                                    <select id="year_of_study" name="year_of_study" class="portal-field h-12 w-full rounded-sm px-4 text-sm focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/10 @error('year_of_study') border-error-500 @enderror">
                                        <option value="">Select year</option>
                                        @for ($year = 1; $year <= 6; $year++)
                                            <option value="{{ $year }}" @selected(old('year_of_study') == $year)>Year {{ $year }}</option>
                                        @endfor
                                    </select>
                                    @error('year_of_study')
                                        <p class="mt-2 text-sm text-error-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="date_of_birth" class="portal-copy mb-2 block text-sm font-medium">Date of birth <span style="color: var(--portal-primary);">*</span></label>
                                    <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" class="portal-field h-12 w-full rounded-sm px-4 text-sm focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/10 @error('date_of_birth') border-error-500 @enderror" />
                                    @error('date_of_birth')
                                        <p class="mt-2 text-sm text-error-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="gender" class="portal-copy mb-2 block text-sm font-medium">Gender <span style="color: var(--portal-primary);">*</span></label>
                                    <input type="text" id="gender" name="gender" value="{{ old('gender') }}" placeholder="Gender" class="portal-field h-12 w-full rounded-sm px-4 text-sm placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/10 @error('gender') border-error-500 @enderror" />
                                    @error('gender')
                                        <p class="mt-2 text-sm text-error-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="residence" class="portal-copy mb-2 block text-sm font-medium">Residence <span style="color: var(--portal-primary);">*</span></label>
                                    <input type="text" id="residence" name="residence" value="{{ old('residence') }}" placeholder="Hostel, hall, or area of residence" class="portal-field h-12 w-full rounded-sm px-4 text-sm placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/10 @error('residence') border-error-500 @enderror" />
                                    @error('residence')
                                        <p class="mt-2 text-sm text-error-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="headline" class="portal-copy mb-2 block text-sm font-medium">Professional headline <span style="color: var(--portal-primary);">*</span></label>
                                    <input type="text" id="headline" name="headline" value="{{ old('headline') }}" placeholder="Aspiring SOC analyst and web security learner" class="portal-field h-12 w-full rounded-sm px-4 text-sm placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/10 @error('headline') border-error-500 @enderror" />
                                    @error('headline')
                                        <p class="mt-2 text-sm text-error-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="specialization_track" class="portal-copy mb-2 block text-sm font-medium">Current focus area</label>
                                    <input type="text" id="specialization_track" name="specialization_track" value="{{ old('specialization_track') }}" placeholder="Web security, digital forensics, secure development" class="portal-field h-12 w-full rounded-sm px-4 text-sm placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/10 @error('specialization_track') border-error-500 @enderror" />
                                    @error('specialization_track')
                                        <p class="mt-2 text-sm text-error-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="emergency_contact_name" class="portal-copy mb-2 block text-sm font-medium">Emergency contact name <span style="color: var(--portal-primary);">*</span></label>
                                    <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" placeholder="Emergency contact full name" class="portal-field h-12 w-full rounded-sm px-4 text-sm placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/10 @error('emergency_contact_name') border-error-500 @enderror" />
                                    @error('emergency_contact_name')
                                        <p class="mt-2 text-sm text-error-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="emergency_contact_phone" class="portal-copy mb-2 block text-sm font-medium">Emergency contact phone <span style="color: var(--portal-primary);">*</span></label>
                                    <input type="text" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" placeholder="Emergency contact phone" class="portal-field h-12 w-full rounded-sm px-4 text-sm placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/10 @error('emergency_contact_phone') border-error-500 @enderror" />
                                    @error('emergency_contact_phone')
                                        <p class="mt-2 text-sm text-error-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="github_username" class="portal-copy mb-2 block text-sm font-medium">GitHub username</label>
                                    <input type="text" id="github_username" name="github_username" value="{{ old('github_username') }}" placeholder="github username" class="portal-field h-12 w-full rounded-sm px-4 text-sm placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/10 @error('github_username') border-error-500 @enderror" />
                                    @error('github_username')
                                        <p class="mt-2 text-sm text-error-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="linkedin_url" class="portal-copy mb-2 block text-sm font-medium">LinkedIn profile</label>
                                    <input type="url" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url') }}" placeholder="https://linkedin.com/in/..." class="portal-field h-12 w-full rounded-sm px-4 text-sm placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/10 @error('linkedin_url') border-error-500 @enderror" />
                                    @error('linkedin_url')
                                        <p class="mt-2 text-sm text-error-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="discord_username" class="portal-copy mb-2 block text-sm font-medium">Discord username</label>
                                    <input type="text" id="discord_username" name="discord_username" value="{{ old('discord_username') }}" placeholder="@username" class="portal-field h-12 w-full rounded-sm px-4 text-sm placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/10 @error('discord_username') border-error-500 @enderror" />
                                    @error('discord_username')
                                        <p class="mt-2 text-sm text-error-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="bio" class="portal-copy mb-2 block text-sm font-medium">Short professional bio <span style="color: var(--portal-primary);">*</span></label>
                                    <textarea id="bio" name="bio" rows="4" placeholder="Summarise your interests, what you want to learn, and how you want to contribute to the club." class="portal-field w-full rounded-sm px-4 py-3 text-sm placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/10 @error('bio') border-error-500 @enderror">{{ old('bio') }}</textarea>
                                    @error('bio')
                                        <p class="mt-2 text-sm text-error-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="notable_problems_solved" class="portal-copy mb-2 block text-sm font-medium">Problems solved or security tasks completed</label>
                                    <textarea id="notable_problems_solved" name="notable_problems_solved" rows="3" placeholder="List practical problems you have solved, labs you have completed, or club challenges you have handled." class="portal-field w-full rounded-sm px-4 py-3 text-sm placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/10 @error('notable_problems_solved') border-error-500 @enderror">{{ old('notable_problems_solved') }}</textarea>
                                    @error('notable_problems_solved')
                                        <p class="mt-2 text-sm text-error-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="achievements_summary" class="portal-copy mb-2 block text-sm font-medium">Projects done or progress record</label>
                                    <textarea id="achievements_summary" name="achievements_summary" rows="3" placeholder="Add any projects built, competition progress, or notable learning milestones you want attached to your profile." class="portal-field w-full rounded-sm px-4 py-3 text-sm placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/10 @error('achievements_summary') border-error-500 @enderror">{{ old('achievements_summary') }}</textarea>
                                    @error('achievements_summary')
                                        <p class="mt-2 text-sm text-error-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="competition_rank" class="portal-copy mb-2 block text-sm font-medium">Current challenge ranking</label>
                                    <input type="text" id="competition_rank" name="competition_rank" value="{{ old('competition_rank') }}" placeholder="Example: Top 10 internal CTF standings" class="portal-field h-12 w-full rounded-sm px-4 text-sm placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/10 @error('competition_rank') border-error-500 @enderror" />
                                    @error('competition_rank')
                                        <p class="mt-2 text-sm text-error-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="profile_photo" class="portal-copy mb-2 block text-sm font-medium">Passport-style photo <span style="color: var(--portal-primary);">*</span></label>
                                    <input type="file" id="profile_photo" name="profile_photo" accept="image/*" class="portal-field flex h-12 w-full items-center rounded-sm px-3 py-2 text-sm file:mr-4 file:rounded-sm file:border-0 file:bg-emerald-400/15 file:px-3 file:py-2 file:font-medium file:text-emerald-200 @error('profile_photo') border-error-500 @enderror" />
                                    <p class="portal-muted mt-2 text-xs">Upload a clear headshot. This will be used in the member directory and public member profile.</p>
                                    @error('profile_photo')
                                        <p class="mt-2 text-sm text-error-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password" class="portal-copy mb-2 block text-sm font-medium">Password <span style="color: var(--portal-primary);">*</span></label>
                                    <div x-data="{ showPassword: false }" class="relative">
                                        <input :type="showPassword ? 'text' : 'password'" id="password" name="password" placeholder="Create a secure password" class="portal-field h-12 w-full rounded-sm px-4 pr-12 text-sm placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/10 @error('password') border-error-500 @enderror" />
                                        <button type="button" @click="showPassword = !showPassword" class="portal-muted absolute inset-y-0 right-4 inline-flex items-center">Show</button>
                                    </div>
                                    @error('password')
                                        <p class="mt-2 text-sm text-error-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="portal-copy mb-2 block text-sm font-medium">Confirm password <span style="color: var(--portal-primary);">*</span></label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" class="portal-field h-12 w-full rounded-sm px-4 text-sm placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-400/10" />
                                </div>
                            </div>

                            <label class="portal-copy inline-flex items-start gap-3 text-sm">
                                <input type="checkbox" id="terms" name="terms" value="1" class="mt-1 h-4 w-4 rounded-sm border-white/20 bg-transparent text-emerald-400 focus:ring-emerald-400/20" @checked(old('terms'))>
                                <span>
                                    I confirm that this information is accurate and I agree to the club platform terms, conduct expectations, and membership review process.
                                </span>
                            </label>
                            @error('terms')
                                <p class="text-sm text-error-400">{{ $message }}</p>
                            @enderror

                            <button type="submit" class="portal-button flex h-12 w-full items-center justify-center rounded-sm px-4 text-sm font-semibold transition">
                                Submit Membership Registration
                            </button>
                        </form>

                        <div class="portal-muted mt-6 text-sm">
                            Already have an account?
                            <a href="{{ route('login') }}" class="font-medium hover:text-sky-200" style="color: var(--portal-secondary);">Sign in here</a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="portal-shell-panel relative hidden lg:flex lg:w-[46%]">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/club/certificate-team.jpg') }}');"></div>
                <div class="portal-grid absolute inset-0 opacity-35"></div>

                <div class="relative z-10 flex flex-1 items-end p-10 xl:p-14">
                    <div class="max-w-xl">
                        <div class="portal-badge">Member Intake</div>

                        <h2 class="mt-6 text-4xl font-semibold tracking-tight text-white xl:text-5xl">
                            Register once, then let the club build a stronger member directory around real people.
                        </h2>

                        <p class="portal-side-copy mt-5 max-w-lg text-base leading-8">
                            The club uses registration details to review applications, manage member records, support communication, and publish public-facing bios only where visibility settings allow it.
                        </p>

                        <div class="portal-side-grid mt-8 sm:grid-cols-2">
                            <div class="portal-side-stat rounded-md">
                                <div class="portal-side-stat-value">Photo</div>
                                <div class="portal-side-stat-label">Directory ready</div>
                            </div>
                            <div class="portal-side-stat rounded-md">
                                <div class="portal-side-stat-value">Bio</div>
                                <div class="portal-side-stat-label">Public profile</div>
                            </div>
                            <div class="portal-side-stat rounded-md">
                                <div class="portal-side-stat-value">Record</div>
                                <div class="portal-side-stat-label">Club admin use</div>
                            </div>
                            <div class="portal-side-stat rounded-md">
                                <div class="portal-side-stat-value">Pending</div>
                                <div class="portal-side-stat-label">Membership review</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
