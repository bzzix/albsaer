<?php

namespace App\Livewire\Groups;

use App\Models\Group;
use App\Models\Project;
use App\Models\AcademicYear;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class GroupForm extends Component
{
    public $groupId      = null;
    public $isEditMode   = false;

    // حقل العام الدراسي (للفلترة فقط، لا يُخزَّن)
    public $academic_year_id = null;

    // الحقول الأساسية
    public $project_id   = null;
    public $code         = '';
    public $name         = '';
    public $max_students = 30;
    public $is_active    = true;

    // ------------------------------------------------------------------
    // Computed Properties
    // ------------------------------------------------------------------

    public function getAvailableYearsProperty()
    {
        // العام الحالي والأعوام المستقبلية فقط
        return AcademicYear::where('end_date', '>=', now()->toDateString())
            ->orderBy('start_date', 'asc')
            ->get();
    }

    public function getAvailableProjectsProperty()
    {
        if (!$this->academic_year_id) return collect();
        return Project::where('academic_year_id', $this->academic_year_id)
            ->active()
            ->get();
    }

    // ------------------------------------------------------------------
    // Watchers
    // ------------------------------------------------------------------

    public function updatedAcademicYearId()
    {
        $this->project_id = null;
    }

    // ------------------------------------------------------------------
    // Lifecycle
    // ------------------------------------------------------------------

    public function mount()
    {
        $currentYear = AcademicYear::getCurrent();
        $this->academic_year_id = $currentYear?->id;
    }

    // ------------------------------------------------------------------
    // Events
    // ------------------------------------------------------------------

    #[On('edit-group')]
    public function editGroup($id)
    {
        $this->resetValidation();
        $this->isEditMode = true;

        $group = Group::findOrFail($id);

        $this->groupId          = $group->id;
        $this->project_id       = $group->project_id;
        $this->academic_year_id = $group->project?->academic_year_id
                                    ?? AcademicYear::getCurrent()?->id;
        $this->code             = $group->code;
        $this->name             = $group->name;
        $this->max_students     = $group->max_students;
        $this->is_active        = $group->is_active;

        $this->dispatch('open-modal', 'group-form');
    }

    public function resetForm()
    {
        $this->reset(['groupId', 'project_id', 'code', 'name', 'max_students']);
        $this->is_active    = true;
        $this->isEditMode   = false;
        $currentYear = AcademicYear::getCurrent();
        $this->academic_year_id = $currentYear?->id;
        $this->resetValidation();
    }

    // ------------------------------------------------------------------
    // Save
    // ------------------------------------------------------------------

    public function save()
    {
        $this->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'project_id'       => 'required|exists:projects,id',
            'code'             => [
                'required', 'string', 'max:50',
                Rule::unique('groups')->ignore($this->groupId),
            ],
            'name'             => 'required|string|max:255',
            'max_students'     => 'required|integer|min:1',
            'is_active'        => 'boolean',
        ], [
            'academic_year_id.required' => 'يرجى اختيار العام الدراسي.',
            'project_id.required'       => 'يرجى اختيار المشروع.',
            'code.required'             => 'يرجى إدخال كود المجموعة.',
            'code.unique'               => 'هذا الكود مستخدم من قبل، يرجى اختيار كود آخر.',
            'name.required'             => 'يرجى إدخال اسم المجموعة.',
            'max_students.required'     => 'يرجى تحديد الحد الأقصى للطلاب.',
        ]);

        $project = Project::findOrFail($this->project_id);

        $data = [
            'project_id'   => $this->project_id,
            'code'         => $this->code,
            'name'         => $this->name,
            'max_students' => $this->max_students,
            'is_active'    => $this->is_active,
            'status'       => 'active',
            // التواريخ تؤخذ من المشروع تلقائياً
            'start_date'   => $project->start_date,
            'end_date'     => $project->end_date,
        ];

        if ($this->isEditMode) {
            Group::findOrFail($this->groupId)->update($data);
            $message = 'تم تحديث بيانات المجموعة بنجاح';
        } else {
            Group::create($data);
            $message = 'تم إضافة المجموعة بنجاح';
        }

        $this->dispatch('close-modal', 'group-form');
        $this->dispatch('refreshDatatable');
        $this->dispatch('notify', ['type' => 'success', 'title' => 'تم الحفظ', 'message' => $message]);
        $this->resetForm();
    }

    // ------------------------------------------------------------------
    // Render
    // ------------------------------------------------------------------

    public function render()
    {
        return view('livewire.groups.group-form');
    }
}
