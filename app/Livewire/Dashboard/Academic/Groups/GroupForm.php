<?php

namespace App\Livewire\Dashboard\Academic\Groups;

use App\Models\Group;
use App\Models\Project;
use App\Models\Instructor;
use App\Models\User;
use App\Services\Academic\GroupService;
use Livewire\Component;
use Livewire\Attributes\Rule;

class GroupForm extends Component
{
    protected GroupService $groupService;

    public $groupId = null;
    
    #[Rule('required|exists:projects,id')]
    public $project_id = null;

    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('required|string|unique:groups,code')]
    public $code = '';

    #[Rule('nullable|exists:instructors,id')]
    public $trainer_id = null;

    #[Rule('nullable|exists:users,id')]
    public $supervisor_id = null;

    #[Rule('required|date')]
    public $start_date = '';

    #[Rule('required|date')]
    public $end_date = '';

    #[Rule('required|integer|min:1')]
    public $max_students = 30;

    #[Rule('required|in:active,completed,cancelled')]
    public $status = 'active';

    #[Rule('boolean')]
    public $is_active = true;

    public function booted()
    {
        $this->groupService = app(GroupService::class);
    }

    public function mount($groupId = null, $projectId = null)
    {
        $this->project_id = $projectId;

        if ($groupId) {
            $group = Group::findOrFail($groupId);
            $this->groupId = $group->id;
            $this->project_id = $group->project_id;
            $this->name = $group->name;
            $this->code = $group->code;
            $this->trainer_id = $group->trainer_id;
            $this->supervisor_id = $group->supervisor_id;
            $this->start_date = $group->start_date->format('Y-m-d');
            $this->end_date = $group->end_date->format('Y-m-d');
            $this->max_students = $group->max_students;
            $this->status = $group->status;
            $this->is_active = $group->is_active;
        } else {
            $this->start_date = now()->format('Y-m-d');
            $this->end_date = now()->addMonths(3)->format('Y-m-d');
        }
    }

    public function save()
    {
        // Custom validation for unique code on update
        $rules = $this->getRules();
        if ($this->groupId) {
            $rules['code'] = 'required|string|unique:groups,code,' . $this->groupId;
        }
        $data = $this->validate($rules);

        try {
            if ($this->groupId) {
                $this->groupService->updateGroup($this->groupId, $data);
                $message = 'تم تحديث المجموعة بنجاح';
            } else {
                $this->groupService->createGroup($data);
                $message = 'تم إضافة المجموعة بنجاح';
            }

            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'تمت العملية',
                'message' => $message,
            ]);

            $this->dispatch('groupSaved');
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'فشل',
                'message' => 'حدث خطأ أثناء حفظ المجموعة: ' . $e->getMessage(),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.dashboard.academic.groups.group-form', [
            'projects' => Project::active()->pluck('name', 'id'),
            'trainers' => Instructor::with('user')->get()->pluck('user.name', 'id'),
            // 'supervisors' => User::role('supervisor')->get()->pluck('name', 'id'), // Example
        ]);
    }
}
