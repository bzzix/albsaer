<?php

namespace App\Livewire\Roles;

use Livewire\Component;

class RolesManager extends Component
{
    public function render()
    {
        return view('livewire.roles.roles-manager')
            ->layout('dashboard.layouts.master');
    }
}
