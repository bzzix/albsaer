<?php

namespace App\Livewire\Dashboard\Academic\CourseCategories;

use App\Models\CourseCategory;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Str;

class CourseCategoryForm extends Component
{
    public $categoryId = null;
    public $name = '';
    public $description = '';
    public $icon = '';
    public $is_active = true;
    
    public $isEditMode = false;

    #[On('edit-course-category')]
    public function edit($id)
    {
        $category = CourseCategory::findOrFail($id);
        
        $this->isEditMode = true;
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->icon = $category->icon;
        $this->is_active = $category->is_active;

        $this->dispatch('open-modal', 'course-category-form');
    }

    public function save()
    {
        $data = $this->validate([
            'name' => 'required|string|max:255|unique:course_categories,name,' . $this->categoryId,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $data['slug'] = Str::slug($this->name);

        try {
            if ($this->categoryId) {
                $category = CourseCategory::findOrFail($this->categoryId);
                $category->update($data);
                $message = 'تم تحديث التصنيف بنجاح';
            } else {
                CourseCategory::create($data);
                $message = 'تم إضافة التصنيف بنجاح';
            }

            $this->dispatch('toast', [
                'type' => 'success',
                'title' => 'نجاح',
                'message' => $message
            ]);

            $this->dispatch('close-modal', 'course-category-form');
            $this->dispatch('categorySaved')->to(CourseCategoryTable::class);
            $this->resetForm();

        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'type' => 'error',
                'title' => 'خطأ',
                'message' => 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage()
            ]);
        }
    }

    #[On('reset-category-form')]
    public function resetForm()
    {
        $this->reset(['isEditMode', 'categoryId', 'name', 'description', 'icon', 'is_active']);
        $this->resetValidation();
        $this->is_active = true;
        
        $this->dispatch('open-modal', 'course-category-form');
    }

    public function render()
    {
        return view('livewire.dashboard.academic.course-categories.course-category-form');
    }
}
