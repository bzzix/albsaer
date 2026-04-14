<?php

namespace App\Livewire\Dashboard\Academic\Courses;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;

class CoursesTable extends Component
{
    use WithPagination;

    public $search = '';
    
    protected $listeners = ['courseSaved' => '$refresh'];

    public function toggleStatus($id)
    {
        $course = Course::findOrFail($id);
        $status = $course->status === 'active' ? 'draft' : 'active';
        $course->update(['status' => $status]);
        
        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'نجاح',
            'message' => 'تم تحديث حالة الدورة بنجاح'
        ]);
    }

    public function confirmDelete($id)
    {
        $course = Course::findOrFail($id);
        $this->dispatch('confirm-delete', [
            'action' => 'deleteCourse',
            'id' => $id,
            'title' => 'تأكيد الحذف',
            'message' => "هل أنت متأكد من رغبتك في حذف الدورة '{$course->name}'؟ لا يمكن التراجع عن هذه الخطوة.",
            'component' => $this->getId()
        ]);
    }

    public function deleteCourse($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'نجاح',
            'message' => 'تم حذف الدورة بنجاح'
        ]);
    }

    public function render()
    {
        $courses = Course::with(['categories', 'instructor'])
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.dashboard.academic.courses.courses-table', [
            'courses' => $courses,
        ]);
    }
}
