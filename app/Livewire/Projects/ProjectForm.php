<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use App\Models\AcademicYear;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class ProjectForm extends Component
{
    public $projectId    = null;
    public $isEditMode   = false;

    public $academic_year_id = null;
    public $code         = '';
    public $name         = '';
    public $description  = '';
    public $start_date   = '';
    public $end_date     = '';
    public $is_active    = true;

    // حدود التاريخ المشتقة من السنة المختارة
    public $yearStartDate = null;
    public $yearEndDate   = null;

    // ------------------------------------------------------------------
    // Computed Properties
    // ------------------------------------------------------------------

    public function getAvailableAcademicYearsProperty()
    {
        // العام الحالي والمستقبلية فقط
        return AcademicYear::where('end_date', '>=', now()->toDateString())
            ->orderBy('start_date', 'asc')
            ->get();
    }

    // ------------------------------------------------------------------
    // Watchers
    // ------------------------------------------------------------------

    public function updatedAcademicYearId($value)
    {
        if (!$value) {
            $this->yearStartDate = null;
            $this->yearEndDate   = null;
            $this->start_date    = '';
            $this->end_date      = '';
            return;
        }

        $year = AcademicYear::find($value);
        if ($year) {
            $this->yearStartDate = $year->start_date->format('Y-m-d');
            $this->yearEndDate   = $year->end_date->format('Y-m-d');
            // تعيين التواريخ الافتراضية تلقائياً
            $this->start_date = $this->yearStartDate;
            $this->end_date   = $this->yearEndDate;
        }
    }

    // ------------------------------------------------------------------
    // Lifecycle
    // ------------------------------------------------------------------

    public function mount()
    {
        $currentYear = AcademicYear::getCurrent();
        if ($currentYear) {
            $this->academic_year_id = $currentYear->id;
            $this->yearStartDate    = $currentYear->start_date->format('Y-m-d');
            $this->yearEndDate      = $currentYear->end_date->format('Y-m-d');
            $this->start_date       = $this->yearStartDate;
            $this->end_date         = $this->yearEndDate;
        }
    }

    // ------------------------------------------------------------------
    // Events
    // ------------------------------------------------------------------

    #[On('edit-project')]
    public function editProject($id)
    {
        $this->resetValidation();
        $this->isEditMode = true;

        $project = Project::findOrFail($id);

        $this->projectId        = $project->id;
        $this->academic_year_id = $project->academic_year_id;
        $this->code             = $project->code;
        $this->name             = $project->name;
        $this->description      = $project->description;
        $this->start_date       = $project->start_date?->format('Y-m-d');
        $this->end_date         = $project->end_date?->format('Y-m-d');
        $this->is_active        = $project->is_active;

        // تحميل حدود السنة
        if ($project->academicYear) {
            $this->yearStartDate = $project->academicYear->start_date->format('Y-m-d');
            $this->yearEndDate   = $project->academicYear->end_date->format('Y-m-d');
        }

        $this->dispatch('open-modal', 'project-form');
    }

    public function resetForm()
    {
        $this->reset(['projectId', 'code', 'name', 'description']);
        $this->is_active    = true;
        $this->isEditMode   = false;
        $this->resetValidation();

        // إعادة تعيين السنة الافتراضية
        $currentYear = AcademicYear::getCurrent();
        if ($currentYear) {
            $this->academic_year_id = $currentYear->id;
            $this->yearStartDate    = $currentYear->start_date->format('Y-m-d');
            $this->yearEndDate      = $currentYear->end_date->format('Y-m-d');
            $this->start_date       = $this->yearStartDate;
            $this->end_date         = $this->yearEndDate;
        }
    }

    // ------------------------------------------------------------------
    // Save
    // ------------------------------------------------------------------

    public function save()
    {
        $this->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'code'             => [
                'required', 'string', 'max:50',
                Rule::unique('projects')->ignore($this->projectId),
            ],
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => [
                'required', 'date',
                'after_or_equal:' . ($this->yearStartDate ?? '1900-01-01'),
                'before_or_equal:end_date',
            ],
            'end_date'    => [
                'required', 'date',
                'after:start_date',
                'before_or_equal:' . ($this->yearEndDate ?? '2099-12-31'),
            ],
        ], [
            'academic_year_id.required' => 'يرجى اختيار السنة الدراسية.',
            'code.required'             => 'يرجى إدخال كود المشروع.',
            'code.unique'               => 'هذا الكود مستخدم مسبقاً.',
            'name.required'             => 'يرجى إدخال اسم المشروع.',
            'start_date.required'       => 'يرجى تحديد تاريخ البداية.',
            'start_date.after_or_equal' => 'تاريخ البداية يجب أن يكون ضمن نطاق السنة الدراسية.',
            'end_date.required'         => 'يرجى تحديد تاريخ النهاية.',
            'end_date.after'            => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية.',
            'end_date.before_or_equal'  => 'تاريخ النهاية يجب أن يكون ضمن نطاق السنة الدراسية.',
        ]);

        $data = [
            'academic_year_id' => $this->academic_year_id,
            'code'             => $this->code,
            'name'             => $this->name,
            'description'      => $this->description,
            'start_date'       => $this->start_date,
            'end_date'         => $this->end_date,
            'status'           => 'active',
            'is_active'        => $this->is_active,
        ];

        if ($this->isEditMode) {
            Project::findOrFail($this->projectId)->update($data);
            $message = 'تم تحديث بيانات المشروع بنجاح';
        } else {
            $data['created_by'] = auth()->id();
            Project::create($data);
            $message = 'تم إضافة المشروع بنجاح';
        }

        $this->dispatch('close-modal', 'project-form');
        $this->dispatch('refreshDatatable');
        $this->dispatch('notify', ['type' => 'success', 'title' => 'تم الحفظ', 'message' => $message]);
        $this->resetForm();
    }

    // ------------------------------------------------------------------

    public function render()
    {
        return view('livewire.projects.project-form');
    }
}
