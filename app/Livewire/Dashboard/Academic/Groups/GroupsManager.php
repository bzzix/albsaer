<?php

namespace App\Livewire\Dashboard\Academic\Groups;

use Livewire\Component;
use Livewire\Attributes\Url;

class GroupsManager extends Component
{
    #[Url(as: 'project')]
    public $projectId = null;

    public function render()
    {
        return view('livewire.dashboard.academic.groups.groups-manager')
            ->layout('dashboard.layouts.master');
    }
}
