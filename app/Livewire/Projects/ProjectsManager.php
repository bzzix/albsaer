<?php

namespace App\Livewire\Projects;

use Livewire\Component;

class ProjectsManager extends Component
{
    public function render()
    {
        return view('livewire.projects.projects-manager')
            ->layout('dashboard.layouts.master');
    }
}
