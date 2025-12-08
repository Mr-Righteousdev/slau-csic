<div>
    <x-common.page-breadcrumb pageTitle="User Profile" />
    <div class="rounded-2xl border border-gray-200 bg-white p-1 dark:border-gray-800 dark:bg-white/[0.03] lg:p-2 mb-8">
        <!-- Edit Mode Toggle -->
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Profile</h3>
            <button type="button"
                    wire:click="$toggle('editMode')"
                    :class="$wire.editMode ? 'bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400' : 'bg-brand-100 text-brand-700 hover:bg-brand-200 dark:bg-brand-900/30 dark:text-brand-400'"
                    class="px-4 py-2 text-sm font-medium rounded-lg transition-colors">
                <span x-show="!$wire.editMode">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Profile
                </span>
                <span x-show="$wire.editMode">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Cancel Editing
                </span>
            </button>
        </div>

        @if($showSuccess)
            
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-green-800 dark:text-green-300">{{ $successMessage }}</span>
                    </div>
                    <button wire:click="$set('showSuccess', false)" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        <div class="" x-data="{ activeTab: 'personal' }">
            <div class="overflow-hidden">
                <!-- Tabs Navigation -->
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex -mb-px">
                        <button @click="activeTab = 'personal'"
                                :class="activeTab === 'personal' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
                                class="py-4 px-6 text-sm font-medium border-b-2 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2 inline" :class="activeTab === 'personal' ? 'text-brand-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Personal Info
                        </button>
                        <button @click="activeTab = 'academic'"
                                :class="activeTab === 'academic' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
                                class="py-4 px-6 text-sm font-medium border-b-2 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2 inline" :class="activeTab === 'academic' ? 'text-brand-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" opacity="0.5" transform="translate(0 7)" />
                            </svg>
                            Academic Info
                        </button>
                        <button @click="activeTab = 'social'"
                                :class="activeTab === 'social' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
                                class="py-4 px-6 text-sm font-medium border-b-2 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2 inline" :class="activeTab === 'social' ? 'text-brand-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Social & Links
                        </button>
                        <button @click="activeTab = 'security'"
                                :class="activeTab === 'security' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
                                class="py-4 px-6 text-sm font-medium border-b-2 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2 inline" :class="activeTab === 'security' ? 'text-brand-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Security
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-2">
                    <form wire:submit.prevent="save" x-show="$wire.editMode" x-transition>
                        <!-- Personal Info Tab - Edit Mode -->
                        <div x-show="activeTab === 'personal'" x-transition>
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <!-- Profile Photo Section -->
                                <div class="lg:col-span-1">
                                    <div class="space-y-4">
                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Profile Photo</h3>

                                        <div class="flex flex-col items-center">
                                            <div class="relative">
                                                <div class="h-40 w-40 rounded-full overflow-hidden border-4 border-white dark:border-gray-800 shadow-lg">
                                                    <img src="{{ $profile_photo_preview }}"
                                                        alt="Profile Photo"
                                                        class="h-full w-full object-cover"
                                                        id="profile-photo-preview">
                                                </div>

                                                <!-- Upload Overlay -->
                                                <div class="absolute inset-0 rounded-full bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-200 flex items-center justify-center cursor-pointer"
                                                    onclick="document.getElementById('profile-photo-input').click()">
                                                    <div class="text-white text-center">
                                                        <svg class="w-8 h-8 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        </svg>
                                                        <span class="text-sm font-medium">Change</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Hidden File Input -->
                                            <input type="file"
                                                id="profile-photo-input"
                                                wire:model="profile_photo"
                                                class="hidden"
                                                accept="image/*">

                                            <div class="flex space-x-2 mt-4">
                                                <button type="button"
                                                        wire:click="removePhoto"
                                                        class="px-4 py-2 text-sm bg-red-100 text-red-700 rounded-lg hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 transition-colors">
                                                    Remove Photo
                                                </button>
                                            </div>

                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">
                                                JPG, PNG or GIF. Max size 2MB.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Personal Info Fields -->
                                <div class="lg:col-span-2 space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="name" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                                                Full Name *
                                            </label>
                                            <input type="text"
                                                id="name"
                                                wire:model="name"
                                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-colors"
                                                placeholder="Enter your full name">
                                            @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label for="email" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                                                Email Address *
                                            </label>
                                            <input type="email"
                                                id="email"
                                                wire:model="email"
                                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-colors"
                                                placeholder="Enter your email">
                                            @error('email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="phone" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                                                Phone Number
                                            </label>
                                            <input type="tel"
                                                id="phone"
                                                wire:model="phone"
                                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-colors"
                                                placeholder="+1 (555) 123-4567">
                                            @error('phone') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label for="membership_type" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                                                Membership Type
                                            </label>
                                            <select id="membership_type"
                                                    wire:model="membership_type"
                                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-colors">
                                                <option value="">Select membership type</option>
                                                <option value="active">Active Member</option>
                                                <option value="associate">Associate Member</option>
                                                <option value="alumni">Alumni</option>
                                            </select>
                                            @error('membership_type') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label for="bio" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                                            Bio / About Me
                                        </label>
                                        <textarea id="bio"
                                                wire:model="bio"
                                                rows="4"
                                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-colors"
                                                placeholder="Tell us about yourself..."></textarea>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ strlen($bio) }}/1000 characters
                                        </p>
                                        @error('bio') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="flex items-center">
                                        <input type="checkbox"
                                            id="is_discord_member"
                                            wire:model="is_discord_member"
                                            class="h-4 w-4 text-brand-600 rounded border-gray-300 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700">
                                        <label for="is_discord_member" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                            I am a Discord member
                                        </label>
                                    </div>

                                    <div x-show="$wire.is_discord_member" x-transition>
                                        <label for="discord_username" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                                            Discord Username
                                        </label>
                                        <input type="text"
                                            id="discord_username"
                                            wire:model="discord_username"
                                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-colors"
                                            placeholder="username#1234">
                                        @error('discord_username') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Academic Info Tab - Edit Mode -->
                        <div x-show="activeTab === 'academic'" x-transition style="display: none;">
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="student_id" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                                            Student ID
                                        </label>
                                        <input type="text"
                                            id="student_id"
                                            wire:model="student_id"
                                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-colors"
                                            placeholder="e.g., S1234567">
                                        @error('student_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="program" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                                            Program / Course
                                        </label>
                                        <input type="text"
                                            id="program"
                                            wire:model="program"
                                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-colors"
                                            placeholder="e.g., Computer Science">
                                        @error('program') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="year_of_study" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                                        Year of Study
                                    </label>
                                    <select id="year_of_study"
                                            wire:model="year_of_study"
                                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-colors">
                                        <option value="">Select year</option>
                                        <option value="1">Year 1</option>
                                        <option value="2">Year 2</option>
                                        <option value="3">Year 3</option>
                                        <option value="4">Year 4</option>
                                        <option value="5">Year 5</option>
                                    </select>
                                    @error('year_of_study') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Social & Links Tab - Edit Mode -->
                        <div x-show="activeTab === 'social'" x-transition style="display: none;">
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="github_username" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                                            GitHub Username
                                        </label>
                                        <div class="flex">
                                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-sm">
                                                github.com/
                                            </span>
                                            <input type="text"
                                                id="github_username"
                                                wire:model="github_username"
                                                class="flex-1 px-4 py-2.5 rounded-r-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-colors"
                                                placeholder="username">
                                        </div>
                                        @error('github_username') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="linkedin_url" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                                            LinkedIn Profile URL
                                        </label>
                                        <input type="url"
                                            id="linkedin_url"
                                            wire:model="linkedin_url"
                                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-colors"
                                            placeholder="https://linkedin.com/in/username">
                                        @error('linkedin_url') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Tab - Edit Mode -->
                        <div x-show="activeTab === 'security'" x-transition style="display: none;">
                            <div class="space-y-6">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Change Password</h4>
                                    <div class="space-y-4">
                                        <div>
                                            <label for="password" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                                                New Password
                                            </label>
                                            <input type="password"
                                                id="password"
                                                wire:model="password"
                                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-colors"
                                                placeholder="Enter new password">
                                            @error('password') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label for="password_confirmation" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                                                Confirm New Password
                                            </label>
                                            <input type="password"
                                                id="password_confirmation"
                                                wire:model="password_confirmation"
                                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-colors"
                                                placeholder="Confirm new password">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions - Only shown in edit mode -->
                        <div class="flex items-center justify-between pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                Last updated: {{ $user->updated_at->diffForHumans() }}
                            </div>
                            <div class="flex space-x-3">
                                <button type="button"
                                        wire:click="$set('editMode', false)"
                                        class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                                    Cancel
                                </button>
                                <button type="submit"
                                        wire:loading.attr="disabled"
                                        class="px-6 py-2.5 text-sm font-medium text-white bg-brand-600 rounded-lg hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                    <span wire:loading.remove wire:target="save">Save Changes</span>
                                    <span wire:loading wire:target="save">
                                        <svg class="animate-spin h-4 w-4 text-white inline-block mr-2" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Saving...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- View Mode Content -->
                    <div x-show="!$wire.editMode" x-transition>
                        <!-- Personal Info Tab - View Mode -->
                        <div x-show="activeTab === 'personal'" x-transition>
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <!-- Profile Photo Section -->
                                <div class="lg:col-span-1">
                                    <div class="space-y-4">
                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Profile Photo</h3>
                                        <div class="flex flex-col items-center">
                                            <div class="h-40 w-40 rounded-full overflow-hidden border-4 border-white dark:border-gray-800 shadow-lg">
                                                <img src="{{ $user->profile_photo_url ?? 'https://via.placeholder.com/150' }}"
                                                    alt="Profile Photo"
                                                    class="h-full w-full object-cover">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Personal Info Display -->
                                <div class="lg:col-span-2 space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Full Name</p>
                                            <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ $user->name }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Email Address</p>
                                            <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ $user->email }}</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Phone Number</p>
                                            <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ $user->phone ?? 'Not provided' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Membership Type</p>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                @if($user->membership_type === 'active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                                @elseif($user->membership_type === 'associate') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                                @elseif($user->membership_type === 'alumni') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300 @endif">
                                                {{ ucfirst($user->membership_type ?? 'Not set') }}
                                            </span>
                                        </div>
                                    </div>

                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Bio / About Me</p>
                                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $user->bio ?? 'No bio provided' }}</p>
                                    </div>

                                    @if($user->is_discord_member)
                                        <div>
                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Discord Username</p>
                                            <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ $user->discord_username }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Academic Info Tab - View Mode -->
                        <div x-show="activeTab === 'academic'" x-transition style="display: none;">
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Student ID</p>
                                        <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ $user->student_id ?? 'Not provided' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Program / Course</p>
                                        <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ $user->program ?? 'Not provided' }}</p>
                                    </div>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Year of Study</p>
                                    <p class="text-lg font-semibold text-gray-800 dark:text-white">
                                        @if($user->year_of_study)
                                            Year {{ $user->year_of_study }}
                                        @else
                                            Not specified
                                        @endif
                                    </p>
                                </div>

                                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Club Information</h4>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <p class="text-gray-500 dark:text-gray-400">Joined Date</p>
                                            <p class="font-medium text-gray-800 dark:text-white">{{ $user->joined_at?->format('F d, Y') ?? 'Not set' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 dark:text-gray-400">Attendance Rate</p>
                                            <p class="font-medium text-gray-800 dark:text-white">{{ $user->getAttendanceRate() }}%</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 dark:text-gray-400">Meetings This Semester</p>
                                            <p class="font-medium text-gray-800 dark:text-white">{{ $user->meetingsThisSemester() }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 dark:text-gray-400">Semester Status</p>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->isActiveThisSemester() ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' }}">
                                                {{ $user->isActiveThisSemester() ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Social & Links Tab - View Mode -->
                        <div x-show="activeTab === 'social'" x-transition style="display: none;">
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">GitHub</p>
                                        @if($user->github_username)
                                            <a href="https://github.com/{{ $user->github_username }}"
                                               target="_blank"
                                               class="text-brand-600 dark:text-brand-400 hover:underline flex items-center">
                                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                    <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                                                </svg>
                                                github.com/{{ $user->github_username }}
                                            </a>
                                        @else
                                            <p class="text-gray-700 dark:text-gray-300">Not provided</p>
                                        @endif
                                    </div>

                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">LinkedIn</p>
                                        @if($user->linkedin_url)
                                            <a href="{{ $user->linkedin_url }}"
                                               target="_blank"
                                               class="text-brand-600 dark:text-brand-400 hover:underline flex items-center">
                                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                                </svg>
                                                View Profile
                                            </a>
                                        @else
                                            <p class="text-gray-700 dark:text-gray-300">Not provided</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Tab - View Mode -->
                        <div x-show="activeTab === 'security'" x-transition style="display: none;">
                            <div class="space-y-6">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Account Security</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Password</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Last changed: {{ $user->password_changed_at?->diffForHumans() ?? 'Never' }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Two-Factor Authentication</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Not enabled</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Login History</h4>
                                    <div class="space-y-2">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Last login: {{ $user->last_login_at?->format('M d, Y h:i A') ?? 'Never' }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Account created: {{ $user->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

