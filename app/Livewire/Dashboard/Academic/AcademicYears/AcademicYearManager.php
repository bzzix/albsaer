<?php

namespace App\Livewire\Dashboard\Academic\AcademicYears;

use Livewire\Component;

class AcademicYearManager extends Component
{
    public function render()
    {
        return view('livewire.dashboard.academic.academic-years.academic-year-manager')
            ->layout('dashboard.layouts.master');
    }
}
