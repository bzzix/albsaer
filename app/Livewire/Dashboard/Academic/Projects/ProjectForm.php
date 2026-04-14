<?php

namespace App\Livewire\Dashboard\Academic\Projects;

use App\Models\Project;
use App\Models\AcademicYear;
use App\Models\Subject;
use App\Models\Course;
use App\Services\Academic\ProjectService;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Validation\Rule;

class ProjectForm extends Component
{
    protected ProjectService $projectService;

    public $projectId = null;
    
    public $name = '';

    public $code = '';

    public $description = '';

    public $is_active = true;

    public $start_date = '';

    public $end_date = '';

    public $academic_year_id = null;
    
    public $subjects = [];
    
    public $courses = [];

    public function updatedAcademicYearId($value)
    {
        if ($value) {
            $year = AcademicYear::find($value);
            if ($year) {
                $this->start_date = $year->start_date->format('Y-m-d');
                $this->end_date = $year->end_date->format('Y-m-d');
            }
        }
    }

    public function boot(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function mount($projectId = null)
    {
        if ($projectId) {
            $this->loadProject($projectId);
        }
    }

    #[On('edit-project')]
    public function loadProject($id)
    {
        $this->resetValidation();
        $project = Project::findOrFail($id);
        $this->projectId = $project->id;
        $this->name = $project->name;
        $this->code = $project->code;
        $this->academic_year_id = $project->academic_year_id;
        $this->description = $project->description;
        $this->is_active = $project->is_active;
        $this->start_date = $project->start_date ? $project->start_date->format('Y-m-d') : '';
        $this->end_date = $project->end_date ? $project->end_date->format('Y-m-d') : '';
        
        $this->subjects = $project->subjects->pluck('id')->toArray();
        $this->courses = $project->courses->pluck('id')->toArray();

        $this->dispatch('open-modal', 'project-form');
    }

    #[On('reset-project-form')]
    public function resetForm()
    {
        $this->reset(['projectId', 'name', 'code', 'description', 'academic_year_id', 'subjects', 'courses']);
        $this->is_active = true;
        $this->start_date = '';
        $this->end_date = '';
        $this->resetValidation();
    }

    public function save()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('projects', 'code')->ignore($this->projectId),
            ],
            'academic_year_id' => ['nullable', 'exists:academic_years,id'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'subjects' => ['nullable', 'array'],
            'subjects.*' => ['exists:subjects,id'],
            'courses' => ['nullable', 'array'],
            'courses.*' => ['exists:courses,id'],
        ];

        // Academic Year bounds checking
        if ($this->academic_year_id) {
            $year = AcademicYear::find($this->academic_year_id);
            if ($year) {
                $rules['start_date'][] = 'after_or_equal:' . $year->start_date->format('Y-m-d');
                $rules['start_date'][] = 'before_or_equal:' . $year->end_date->format('Y-m-d');
                $rules['end_date'][] = 'before_or_equal:' . $year->end_date->format('Y-m-d');
            }
        }

        $data = $this->validate($rules);

        try {
            if ($this->projectId) {
                $this->projectService->updateProject($this->projectId, $data);
                $message = 'تم تحديث المشروع بنجاح';
            } else {
                $this->projectService->createProject($data);
                $message = 'تم إضافة المشروع بنجاح';
            }

            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'تمت العملية',
                'message' => $message,
            ]);

            $this->dispatch('projectSaved');
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'فشل',
                'message' => 'حدث خطأ أثناء حفظ المشروع: ' . $e->getMessage(),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.dashboard.academic.projects.project-form', [
            'academicYears' => AcademicYear::activeUpcoming()->get(),
            'allSubjects' => Subject::all(),
            'allCourses' => Course::all(),
        ]);
    }
}
