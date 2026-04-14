<?php

namespace App\Livewire\Groups;

use Livewire\Component;

class GroupsManager extends Component
{
    public function render()
    {
        return view('livewire.groups.groups-manager')
            ->layout('dashboard.layouts.master');
    }
}
