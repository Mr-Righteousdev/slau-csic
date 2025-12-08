<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class PublicMemberProfile extends Component
{
    public User $user;

    public function mount(User $user)
    {
        // Check if user's profile is publicly visible
        if (! $user->isApproved() || ! $user->show_profile) {
            abort(404);
        }

        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.public-member-profile', [
            'member' => $this->user,
            'stats' => $this->user->getMemberStats(),
        ]);
    }
}
