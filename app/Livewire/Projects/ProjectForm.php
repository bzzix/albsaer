<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use App\Models\AcademicYear;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class ProjectForm extends Component
{
    public $projectId;
    public $academic_year_id;
    public $code;
    public $name;
    public $description;
    public $start_date;
    public $end_date;
    public $status = 'planned';
    public $is_active = true;
    
    public $isEditMode = false;
    
    public function getAvailableAcademicYearsProperty()
    {
        return AcademicYear::all();
    }

    #[On('edit-project')]
    public function editProject($id)
    {
        $this->resetValidation();
        $this->isEditMode = true;
        
        $project = Project::findOrFail($id);
        
        $this->projectId = $project->id;
        $this->academic_year_id = $project->academic_year_id;
        $this->code = $project->code;
        $this->name = $project->name;
        $this->description = $project->description;
        $this->start_date = $project->start_date?->format('Y-m-d');
        $this->end_date = $project->end_date?->format('Y-m-d');
        $this->status = $project->status;
        $this->is_active = $project->is_active;
        
        $this->dispatch('open-modal', 'project-form');
    }

    public function resetForm()
    {
        $this->reset(['projectId', 'academic_year_id', 'code', 'name', 'description', 'start_date', 'end_date', 'status']);
        $this->is_active = true;
        $this->status = 'planned';
        $this->isEditMode = false;
        $this->resetValidation();
    }

    public function save()
    {
        $rules = [
            'academic_year_id' => 'required|exists:academic_years,id',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('projects')->ignore($this->projectId),
            ],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:planned,ongoing,completed,cancelled',
        ];

        $this->validate($rules);

        $data = [
            'academic_year_id' => $this->academic_year_id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'is_active' => $this->is_active,
        ];

        if ($this->isEditMode) {
            $project = Project::findOrFail($this->projectId);
            $project->update($data);
            $message = 'تم تحديث بيانات المشروع بنجاح';
        } else {
            $data['created_by'] = auth()->id();
            $project = Project::create($data);
            $message = 'تم إضافة المشروع بنجاح';
        }

        $this->dispatch('close-modal', 'project-form');
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
        return view('livewire.projects.project-form');
    }
}
