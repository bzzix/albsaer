<?php

namespace App\Livewire\Groups;

use App\Models\Group;
use App\Models\Project;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class GroupForm extends Component
{
    public $groupId;
    public $project_id;
    public $course_id;
    public $code;
    public $name;
    public $trainer_id;
    public $supervisor_id;
    public $start_date;
    public $end_date;
    public $max_students;
    public $status = 'planned';
    public $is_active = true;
    
    public $isEditMode = false;
    
    public function getAvailableProjectsProperty()
    {
        return Project::active()->get();
    }

    public function getAvailableCoursesProperty()
    {
        return Course::all();
    }

    public function getAvailableTrainersProperty()
    {
        return Instructor::all();
    }

    public function getAvailableSupervisorsProperty()
    {
        return User::role(['admin', 'supervisor'])->get();
    }

    #[On('edit-group')]
    public function editGroup($id)
    {
        $this->resetValidation();
        $this->isEditMode = true;
        
        $group = Group::findOrFail($id);
        
        $this->groupId = $group->id;
        $this->project_id = $group->project_id;
        $this->course_id = $group->course_id;
        $this->code = $group->code;
        $this->name = $group->name;
        $this->trainer_id = $group->trainer_id;
        $this->supervisor_id = $group->supervisor_id;
        $this->start_date = $group->start_date?->format('Y-m-d');
        $this->end_date = $group->end_date?->format('Y-m-d');
        $this->max_students = $group->max_students;
        $this->status = $group->status;
        $this->is_active = $group->is_active;
        
        $this->dispatch('open-modal', 'group-form');
    }

    public function resetForm()
    {
        $this->reset(['groupId', 'project_id', 'course_id', 'code', 'name', 'trainer_id', 'supervisor_id', 'start_date', 'end_date', 'max_students', 'status']);
        $this->is_active = true;
        $this->status = 'planned';
        $this->isEditMode = false;
        $this->resetValidation();
    }

    public function save()
    {
        $rules = [
            'project_id' => 'required|exists:projects,id',
            'course_id' => 'required|exists:courses,id',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('groups')->ignore($this->groupId),
            ],
            'name' => 'required|string|max:255',
            'trainer_id' => 'required|exists:instructors,id',
            'supervisor_id' => 'nullable|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'max_students' => 'required|integer|min:1',
            'status' => 'required|in:planned,ongoing,completed,cancelled',
        ];

        $this->validate($rules);

        $data = [
            'project_id' => $this->project_id,
            'course_id' => $this->course_id,
            'code' => $this->code,
            'name' => $this->name,
            'trainer_id' => $this->trainer_id,
            'supervisor_id' => $this->supervisor_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'max_students' => $this->max_students,
            'status' => $this->status,
            'is_active' => $this->is_active,
        ];

        if ($this->isEditMode) {
            $group = Group::findOrFail($this->groupId);
            $group->update($data);
            $message = 'تم تحديث بيانات المجموعة بنجاح';
        } else {
            $group = Group::create($data);
            $message = 'تم إضافة المجموعة بنجاح';
        }

        $this->dispatch('close-modal', 'group-form');
        $this->dispatch('refreshDatatable');
        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'تم الحفظ',
            'message' => $message,
        ]);
        
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.groups.group-form');
    }
}
