<?php

namespace App\Livewire\Dashboard\Academic\Courses;

use Livewire\Component;

class CoursesManager extends Component
{
    public function render()
    {
        return view('livewire.dashboard.academic.courses.courses-manager')->layout('dashboard.layouts.master');
    }
}
