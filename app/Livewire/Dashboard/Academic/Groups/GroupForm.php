<?php

namespace App\Livewire\Dashboard\Academic\Groups;

use App\Models\Group;
use App\Models\Project;
use App\Models\AcademicYear;
use App\Services\Academic\GroupService;
use Livewire\Component;

class GroupForm extends Component
{
    protected GroupService $groupService;

    public $groupId    = null;
    public $isEditMode = false;

    // حقل العام الدراسي (للفلترة)
    public $academic_year_id = null;

    // الحقول الرئيسية
    public $project_id   = null;
    public $name         = '';
    public $code         = '';
    public $max_students = 30;
    public $is_active    = true;

    public function booted()
    {
        $this->groupService = app(GroupService::class);
    }

    public function mount($groupId = null, $projectId = null)
    {
        // تعيين العام الدراسي الافتراضي
        $currentYear = AcademicYear::getCurrent();
        $this->academic_year_id = $currentYear?->id;

        $this->project_id = $projectId;

        if ($groupId) {
            $group = Group::findOrFail($groupId);
            $this->groupId          = $group->id;
            $this->isEditMode       = true;
            $this->project_id       = $group->project_id;
            $this->academic_year_id = $group->project?->academic_year_id ?? $this->academic_year_id;
            $this->name             = $group->name;
            $this->code             = $group->code;
            $this->max_students     = $group->max_students;
            $this->is_active        = $group->is_active;
        }
    }

    public function updatedAcademicYearId()
    {
        // عند تغيير العام أعد ضبط المشروع
        $this->project_id = null;
    }

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

    public function save()
    {
        $rules = [
            'academic_year_id' => 'required|exists:academic_years,id',
            'project_id'       => 'required|exists:projects,id',
            'name'             => 'required|string|max:255',
            'code'             => 'required|string|unique:groups,code' . ($this->groupId ? ',' . $this->groupId : ''),
            'max_students'     => 'required|integer|min:1',
            'is_active'        => 'boolean',
        ];

        $data = $this->validate($rules);
        unset($data['academic_year_id']); // لا يُخزَّن في جدول groups

        // إضافة قيم افتراضية
        $data['status'] = 'active';

        try {
            if ($this->groupId) {
                $this->groupService->updateGroup($this->groupId, $data);
                $message = 'تم تحديث المجموعة بنجاح';
            } else {
                $this->groupService->createGroup($data);
                $message = 'تم إضافة المجموعة بنجاح';
            }

            $this->dispatch('notify', ['type' => 'success', 'title' => 'تمت العملية', 'message' => $message]);
            $this->dispatch('groupSaved');
        } catch (\Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'title' => 'فشل', 'message' => 'حدث خطأ: ' . $e->getMessage()]);
        }
    }

    public function resetForm()
    {
        $this->reset(['groupId', 'isEditMode', 'project_id', 'name', 'code', 'max_students']);
        $this->is_active = true;
        $currentYear = AcademicYear::getCurrent();
        $this->academic_year_id = $currentYear?->id;
    }

    public function render()
    {
        return view('livewire.groups.group-form');
    }
}
