<?php

namespace App\Livewire\Dashboard\Academic\CourseCategories;

use App\Models\CourseCategory;
use Livewire\Component;
use Livewire\WithPagination;

class CourseCategoryTable extends Component
{
    use WithPagination;

    public $search = '';
    
    protected $listeners = ['categorySaved' => '$refresh'];

    public function toggleStatus($id)
    {
        $category = CourseCategory::findOrFail($id);
        $category->update(['is_active' => !$category->is_active]);
        
        $this->dispatch('toast', [
            'type' => 'success',
            'title' => 'نجاح',
            'message' => 'تم تحديث حالة التصنيف بنجاح'
        ]);
    }

    public function confirmDelete($id)
    {
        $this->dispatch('confirm-delete', [
            'action' => 'deleteCategory',
            'id' => $id,
            'title' => 'تأكيد الحذف',
            'message' => 'هل أنت متأكد من رغبتك في حذف هذا التصنيف؟ لا يمكن التراجع عن هذه الخطوة.',
            'component' => $this->getId()
        ]);
    }

    public function deleteCategory($id)
    {
        $category = CourseCategory::findOrFail($id);
        $category->delete();

        $this->dispatch('toast', [
            'type' => 'success',
            'title' => 'نجاح',
            'message' => 'تم حذف التصنيف بنجاح'
        ]);
    }

    public function render()
    {
        $categories = CourseCategory::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.dashboard.academic.course-categories.course-category-table', [
            'categories' => $categories,
        ]);
    }
}
