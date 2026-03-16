<?php

namespace App\Livewire\Users;

use Livewire\Component;

class UsersManager extends Component
{
    public function render()
    {
        return view('livewire.users.users-manager')
            ->layout('dashboard.layouts.master');
    }
}
