<?php

namespace App\Livewire\Dashboard\Academic\AcademicYears;

use App\Models\AcademicYear;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class AcademicYearForm extends Component
{
    public $academicYearId;
    public $name;
    public $code;
    public $start_date;
    public $end_date;
    public $is_active = true;
    
    public $isEditMode = false;

    #[On('edit-academic-year')]
    public function editAcademicYear($id)
    {
        $this->resetValidation();
        $this->isEditMode = true;
        
        $academicYear = AcademicYear::findOrFail($id);
        
        $this->academicYearId = $academicYear->id;
        $this->name = $academicYear->name;
        $this->code = $academicYear->code;
        $this->start_date = $academicYear->start_date->format('Y-m-d');
        $this->end_date = $academicYear->end_date->format('Y-m-d');
        $this->is_active = $academicYear->is_active;
        
        $this->dispatch('open-modal', 'academic-year-form');
    }

    public function resetForm()
    {
        $this->reset(['academicYearId', 'name', 'code', 'start_date', 'end_date']);
        $this->is_active = true;
        $this->isEditMode = false;
        $this->resetValidation();
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:100',
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('academic_years')->ignore($this->academicYearId),
            ],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ];

        $this->validate($rules);

        // Validation for Overlap
        $overlapQuery = AcademicYear::where(function($query) {
            $query->whereBetween('start_date', [$this->start_date, $this->end_date])
                  ->orWhereBetween('end_date', [$this->start_date, $this->end_date])
                  ->orWhere(function($q) {
                      $q->where('start_date', '<=', $this->start_date)
                        ->where('end_date', '>=', $this->end_date);
                  });
        });

        if ($this->isEditMode) {
            $overlapQuery->where('id', '!=', $this->academicYearId);
        }

        if ($overlapQuery->exists()) {
            $this->addError('start_date', 'تاريخ العام الأكاديمي يتداخل مع عام أكاديمي آخر مسجل.');
            $this->addError('end_date', 'تاريخ العام الأكاديمي يتداخل مع عام أكاديمي آخر مسجل.');
            return;
        }

        $data = [
            'name' => $this->name,
            'code' => $this->code,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_active' => $this->is_active,
        ];

        if ($this->isEditMode) {
            $academicYear = AcademicYear::findOrFail($this->academicYearId);
            $academicYear->update($data);
            $message = 'تم تحديث العام الأكاديمي بنجاح';
        } else {
            AcademicYear::create($data);
            $message = 'تم إضافة العام الأكاديمي بنجاح';
        }

        $this->dispatch('close-modal', 'academic-year-form');
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
        return view('livewire.dashboard.academic.academic-years.academic-year-form');
    }
}
