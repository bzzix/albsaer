<?php

namespace App\Livewire\Dashboard\Academic\Courses;

use App\Models\Course;
use Livewire\Component;
use Illuminate\Support\Str;

class CourseInitialForm extends Component
{
    public $name = '';
    public $code = '';
    
    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:courses,code',
        ]);

        try {
            // Create initial draft course
            $course = Course::create([
                'name' => $name = $this->name,
                'code' => $this->code,
                'status' => 'draft',
                'created_by' => auth()->id(),
                'start_date' => now(), // Default dates to satisfy migration constraints if they exist
                'end_date' => now()->addMonths(3),
            ]);

            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'نجاح',
                'message' => 'تم إنشاء المسودة بنجاح، جاري الانتقال لباني الدورات...'
            ]);

            return redirect()->route('dashboard.academic.courses.builder', $course->id);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'خطأ',
                'message' => 'حدث خطأ أثناء البدء: ' . $e->getMessage()
            ]);
        }
    }

    public function resetForm()
    {
        $this->reset(['name', 'code']);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.dashboard.academic.courses.course-initial-form');
    }
}
