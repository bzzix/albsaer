<?php

namespace App\Livewire\Dashboard\Academic\Courses;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\User;
use App\Models\CourseModule;
use App\Models\ModuleLesson;
use Livewire\Component;
use Livewire\WithFileUploads;

class CourseBuilder extends Component
{
    use WithFileUploads;

    public Course $course;
    public $activeTab = 'basic'; // basic, curriculum, additional
    
    // Basic Info
    public $name;
    public $code;
    public $short_description;
    public $content;
    public $price;
    public $sale_price;
    public $instructor_id;
    public $status;
    public $selectedCategories = [];
    
    // Image Upload
    public $featuredImage;
    public $existingImage;

    // Additional Info
    public $level;
    public $language;
    public $enrollment_limit;
    public $requirements = [];
    public $learning_outcomes = [];
    public $total_hours;

    // Curriculum Management
    public $editingModuleId = null;
    public $moduleTitle = '';
    
    public $editingLessonId = null;
    public $moduleIdForNewLesson = null;
    public $lessonTitle = '';
    public $lessonType = 'text';
    public $lessonContent = '';
    public $lessonVideoUrl = '';
    public $lessonDuration = 0; // minutes
    public $lessonFile; // For uploads (video, pdf, audio)
    public $existingLessonFile;
    public $lessonEmbedCode = '';
    public $lessonIsFree = false;
    public $videoSource = 'url'; // url or upload

    protected $listeners = ['refreshCurriculum' => '$refresh'];

    public function mount(Course $course)
    {
        $this->course = $course;
        $this->loadCourseData();
    }

    public function loadCourseData()
    {
        $this->name = $this->course->name;
        $this->code = $this->course->code;
        $this->short_description = $this->course->short_description;
        $this->content = $this->course->content;
        $this->price = $this->course->price;
        $this->sale_price = $this->course->sale_price;
        $this->instructor_id = $this->course->instructor_id;
        $this->status = $this->course->status;
        $this->selectedCategories = $this->course->categories->pluck('id')->toArray();
        $this->existingImage = $this->course->image_path;

        $this->level = $this->course->level ?? 'beginner';
        $this->language = $this->course->language ?? 'ar';
        $this->enrollment_limit = $this->course->enrollment_limit;
        $this->requirements = $this->course->requirements ?? [];
        $this->learning_outcomes = $this->course->learning_outcomes ?? [];
        $this->total_hours = $this->course->total_hours;
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function nextTab()
    {
        $tabs = ['basic', 'curriculum', 'additional'];
        $index = array_search($this->activeTab, $tabs);
        if ($index < count($tabs) - 1) {
            $this->activeTab = $tabs[$index + 1];
        }
    }

    public function prevTab()
    {
        $tabs = ['basic', 'curriculum', 'additional'];
        $index = array_search($this->activeTab, $tabs);
        if ($index > 0) {
            $this->activeTab = $tabs[$index - 1];
        }
    }

    public function saveBasicInfo()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:courses,code,' . $this->course->id,
            'short_description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'instructor_id' => 'nullable|exists:users,id',
            'status' => 'required|in:draft,active,completed,cancelled',
            'featuredImage' => 'nullable|image|max:2048', // 2MB Max
        ]);

        $imagePath = $this->course->image_path;
        if ($this->featuredImage) {
            $imagePath = $this->featuredImage->store('courses', 'public');
        }

        $this->course->update([
            'name' => $this->name,
            'code' => $this->code,
            'short_description' => $this->short_description,
            'content' => $this->content,
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'instructor_id' => $this->instructor_id,
            'status' => $this->status,
            'image_path' => $imagePath,
        ]);

        $this->course->categories()->sync($this->selectedCategories);

        $this->dispatch('notify', ['type' => 'success', 'message' => 'تم حفظ المعلومات الأساسية بنجاح']);
    }

    public function saveAdditionalInfo()
    {
        $this->validate([
            'level' => 'required|string',
            'language' => 'required|string',
            'enrollment_limit' => 'nullable|integer|min:0',
        ]);

        $this->course->update([
            'level' => $this->level,
            'language' => $this->language,
            'enrollment_limit' => $this->enrollment_limit,
            'requirements' => $this->requirements,
            'learning_outcomes' => $this->learning_outcomes,
            'total_hours' => $this->total_hours,
        ]);

        $this->dispatch('notify', ['type' => 'success', 'message' => 'تم حفظ التفاصيل الإضافية بنجاح']);
    }

    // Curriculum Actions
    public function addModule()
    {
        $maxOrder = $this->course->modules()->max('module_order') ?? 0;
        $order = $maxOrder + 1;
        $this->course->modules()->create([
            'title' => 'عنوان وحدة جديد...',
            'module_order' => $order
        ]);
        $this->dispatch('refreshCurriculum');
    }

    public function deleteModule($id)
    {
         CourseModule::where('id', $id)->where('course_id', $this->course->id)->delete();
        $this->dispatch('refreshCurriculum');
        $this->dispatch('notify', ['type' => 'success', 'message' => 'تم حذف الوحدة بنجاح']);
    }

    public function updateModuleTitle($id, $title)
    {
        $module = CourseModule::find($id);
        if ($module && !empty($title)) {
            $module->update(['title' => $title]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'تم تحديث عنوان الوحدة']);
        }
    }

    public function addLesson($moduleId)
    {
        $this->editingLessonId = null;
        $this->resetLesson();
        $this->moduleIdForNewLesson = $moduleId;
        $this->dispatch('open-modal', 'lesson-form');
    }

    public function editLesson($id)
    {
        $this->editingLessonId = $id;
        $lesson = ModuleLesson::findOrFail($id);
        $this->lessonTitle = $lesson->title;
        $this->lessonType = $lesson->lesson_type;
        $this->lessonContent = $lesson->content;
        $this->lessonVideoUrl = $lesson->video_url;
        $this->lessonDuration = $lesson->duration_minutes ?? 0;
        $this->lessonIsFree = $lesson->is_free;
        $this->lessonEmbedCode = $lesson->embed_code ?? '';
        $this->existingLessonFile = $lesson->file_path;
        $this->lessonFile = null;

        // Determine video source
        if ($this->lessonType == 'video') {
            $this->videoSource = ($lesson->video_url) ? 'url' : 'upload';
        }

        $this->dispatch('open-modal', 'lesson-form');
    }

    public function resetLesson()
    {
        $this->editingLessonId = null;
        $this->moduleIdForNewLesson = null;
        $this->lessonTitle = '';
        $this->lessonType = 'text';
        $this->lessonContent = '';
        $this->lessonVideoUrl = '';
        $this->lessonDuration = 0;
        $this->lessonFile = null;
        $this->existingLessonFile = null;
        $this->lessonEmbedCode = '';
        $this->lessonIsFree = false;
        $this->resetValidation();
    }

    public function saveLesson()
    {
        $this->validate([
            'lessonTitle' => 'required|string|max:255',
            'lessonType' => 'required|in:text,video,pdf,embed,audio',
            'lessonDuration' => 'nullable|integer|min:0',
            'lessonFile' => 'nullable|file|max:102400', // 100MB Max
        ]);

        $data = [
            'title' => $this->lessonTitle,
            'lesson_type' => $this->lessonType,
            'duration_minutes' => $this->lessonDuration,
            'is_free' => $this->lessonIsFree,
        ];

        // Cleanup and route data based on type
        if ($this->lessonType == 'text') {
            $data['content'] = $this->lessonContent;
            $data['video_url'] = null;
            $data['file_path'] = null;
            $data['embed_code'] = null;
        } elseif ($this->lessonType == 'video') {
            $data['content'] = null;
            $data['embed_code'] = null;
            if ($this->videoSource == 'url') {
                $data['video_url'] = $this->lessonVideoUrl;
                if (!empty($this->lessonVideoUrl)) {
                    $data['file_path'] = null;
                }
            } else {
                $data['video_url'] = null;
                if ($this->lessonFile) {
                    $data['file_path'] = $this->lessonFile->store('lessons', 'public');
                }
            }
        } elseif ($this->lessonType == 'pdf' || $this->lessonType == 'audio') {
            $data['content'] = null;
            $data['video_url'] = null;
            $data['embed_code'] = null;
            if ($this->lessonFile) {
                $data['file_path'] = $this->lessonFile->store('lessons', 'public');
            }
        } elseif ($this->lessonType == 'embed') {
            $data['content'] = null;
            $data['video_url'] = null;
            $data['file_path'] = null;
            $data['embed_code'] = $this->lessonEmbedCode;
        }

        // Save
        if ($this->editingLessonId) {
            $lesson = ModuleLesson::findOrFail($this->editingLessonId);
            $lesson->update($data);
        } elseif ($this->moduleIdForNewLesson) {
            $module = CourseModule::findOrFail($this->moduleIdForNewLesson);
            $maxOrder = $module->lessons()->max('lesson_order') ?? 0;
            $data['lesson_order'] = $maxOrder + 1;
            $module->lessons()->create($data);
        }

        $this->dispatch('close-modal', 'lesson-form');
        $this->resetLesson();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'تم حفظ الدرس بنجاح']);
        $this->dispatch('refreshCurriculum');
    }

    public function deleteLesson($id)
    {
        ModuleLesson::where('id', $id)->delete();
        $this->dispatch('refreshCurriculum');
    }

    public function addRequirement()
    {
        $this->requirements[] = '';
    }

    public function removeRequirement($index)
    {
        unset($this->requirements[$index]);
        $this->requirements = array_values($this->requirements);
    }

    public function addOutcome()
    {
        $this->learning_outcomes[] = '';
    }

    public function removeOutcome($index)
    {
        unset($this->learning_outcomes[$index]);
        $this->learning_outcomes = array_values($this->learning_outcomes);
    }

    public function render()
    {
        return view('livewire.dashboard.academic.courses.course-builder', [
            'categories' => CourseCategory::active()->get(),
            'instructors' => User::role('instructor')->get(),
            'modules' => $this->course->modules()->with('lessons')->get()
        ])->layout('dashboard.layouts.master');
    }
}
