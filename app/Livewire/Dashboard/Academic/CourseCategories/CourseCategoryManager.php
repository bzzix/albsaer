<?php

namespace App\Livewire\Dashboard\Academic\CourseCategories;

use Livewire\Component;

class CourseCategoryManager extends Component
{
    public function render()
    {
        return view('livewire.dashboard.academic.course-categories.course-category-manager')->layout('dashboard.layouts.master');
    }
}
