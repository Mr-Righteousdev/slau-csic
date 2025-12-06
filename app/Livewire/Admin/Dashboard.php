<?php

namespace App\Livewire\Admin;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        // Role::create(['name' => 'super-admin']);
        // if (Auth::user()->email === 'super@gmail.com') {
        //     Auth::user()->assignRole('super-admin');
        // }

        return view('livewire.admin.dashboard');
    }
}
