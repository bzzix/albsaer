<?php

namespace App\Livewire\Dashboard\Academic\Projects;

use Livewire\Component;

class ProjectsManager extends Component
{
    public function render()
    {
        return view('livewire.dashboard.academic.projects.projects-manager')
            ->layout('dashboard.layouts.master');
    }
}