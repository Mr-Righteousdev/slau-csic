<?php

namespace App\Livewire;

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserProfile extends Component
{
    use WithFileUploads;

    public $user;

    public $profile_photo;

    public $profile_photo_preview;

    public $temp_photo;

    public $editMode = false;

    #[Validate('required|string|max:255')]
    public $name;

    #[Validate('required|email|max:255')]
    public $email;

    #[Validate('nullable|string|max:20')]
    public $phone;

    #[Validate('nullable|string|max:100')]
    public $student_id;

    #[Validate('nullable|string|max:100')]
    public $program;

    #[Validate('nullable|integer|min:1|max:5')]
    public $year_of_study;

    #[Validate('nullable|in:active,associate,alumni')]
    public $membership_type;

    #[Validate('nullable|string|max:1000')]
    public $bio;

    #[Validate('nullable|string|max:50')]
    public $github_username;

    #[Validate('nullable|url|max:255')]
    public $linkedin_url;

    #[Validate('nullable|string|max:50')]
    public $discord_username;

    public $is_discord_member = false;

    public $password;

    public $password_confirmation;

    public $showSuccess = false;

    public $successMessage = '';

    public function mount(): void
    {
        $this->user = Auth::user();

        // Populate form fields with current user data
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
        $this->student_id = $this->user->student_id;
        $this->program = $this->user->program;
        $this->year_of_study = $this->user->year_of_study;
        $this->membership_type = $this->user->membership_type;
        $this->bio = $this->user->bio;
        $this->github_username = $this->user->github_username;
        $this->linkedin_url = $this->user->linkedin_url;
        $this->discord_username = $this->user->discord_username;
        $this->is_discord_member = $this->user->is_discord_member;

        // Set current profile photo preview
        $this->profile_photo_preview = $this->user->avatar_url;
    }

    public function updatedProfilePhoto(): void
    {
        $this->validate([
            'profile_photo' => 'nullable|image|max:5120',
        ]);

        $this->temp_photo = $this->profile_photo->temporaryUrl();
        $this->profile_photo_preview = $this->temp_photo;
    }

    public function removePhoto(): void
    {
        $this->profile_photo = null;
        $this->temp_photo = null;
        $this->profile_photo_preview = $this->user->avatar_url;
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$this->user->id,
            'phone' => 'nullable|string|max:20',
            'student_id' => 'nullable|string|max:100|unique:users,student_id,'.$this->user->id,
            'program' => 'nullable|string|max:100',
            'year_of_study' => 'nullable|integer|min:1|max:5',
            'membership_type' => 'nullable|in:active,associate,alumni',
            'bio' => 'nullable|string|max:1000',
            'github_username' => 'nullable|string|max:50',
            'linkedin_url' => 'nullable|url|max:255',
            'discord_username' => 'nullable|string|max:50',
            'profile_photo' => 'nullable|image|max:5120',
            'password' => 'nullable|min:8|confirmed',
        ]);

        if ($this->profile_photo) {
            if ($this->user->profile_photo && Storage::disk('public')->exists($this->user->profile_photo)) {
                Storage::disk('public')->delete($this->user->profile_photo);
            }

            $path = $this->profile_photo->store('profile-photos', 'public');
            $this->user->profile_photo = $path;
        }

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'student_id' => $this->student_id,
            'program' => $this->program,
            'year_of_study' => $this->year_of_study,
            'membership_type' => $this->membership_type,
            'bio' => $this->bio,
            'github_username' => $this->github_username,
            'linkedin_url' => $this->linkedin_url,
            'discord_username' => $this->discord_username,
            'is_discord_member' => $this->is_discord_member,
        ]);

        if ($this->password) {
            $this->user->update([
                'password' => bcrypt($this->password),
            ]);
        }

        $this->user->refresh();
        $this->showSuccess = true;
        $this->successMessage = 'Profile updated successfully!';

        $this->reset(['password', 'password_confirmation']);

        $this->profile_photo_preview = $this->user->avatar_url;

        Notification::make()
            ->title('Profile updated successfully!')
            ->color('success')
            ->success()
            ->send();
        $this->dispatch('profile-updated');

        $this->editMode = false;
        $this->showSuccess = true;
        $this->successMessage = 'Profile updated successfully!';
    }

    public function render()
    {
        return view('livewire.user-profile');
    }
}
