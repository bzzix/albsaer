<?php

namespace App\Livewire\Dashboard\Academic\Schedules;

use App\Models\StudyPeriod;
use App\Models\AcademicYear;
use Livewire\Component;
use Livewire\Attributes\On;

class StudyPeriodsManager extends Component
{
    public $academicYearId;

    public $showCreateModal = false;
    public $isEditing = false;
    public $selectedPeriodId;
    
    // Form data
    public $form = [
        'name' => '',
        'academic_year_id' => '',
        'active_days' => [0, 1, 2, 3, 4], // الأحد - الخميس
        'start_time' => '08:00',
        'end_time' => '14:00',
        'sessions_count' => 6,
        'break_duration' => 15,
        'session_duration' => 45,
        'is_active' => true,
    ];

    public $daysOfWeek = [
        0 => 'الأحد',
        1 => 'الإثنين',
        2 => 'الثلاثاء',
        3 => 'الأربعاء',
        4 => 'الخميس',
        5 => 'الجمعة',
        6 => 'السبت',
    ];

    public function mount()
    {
        $currentYear = AcademicYear::getCurrent();
        $this->academicYearId = $currentYear?->id;
        $this->form['academic_year_id'] = $this->academicYearId;
    }

    public function openCreateModal()
    {
        $this->reset('form');
        $this->form['academic_year_id'] = $this->academicYearId;
        $this->form['active_days'] = [0, 1, 2, 3, 4];
        $this->isEditing = false;
        $this->showCreateModal = true;
    }

    #[On('edit-period')]
    public function editPeriod($id)
    {
        $period = StudyPeriod::findOrFail($id);
        $this->selectedPeriodId = $id;
        $this->form = $period->toArray();
        $this->isEditing = true;
        $this->showCreateModal = true;
    }

    public function save()
    {
        $this->validate([
            'form.name' => 'required|string|max:100',
            'form.academic_year_id' => 'required|exists:academic_years,id',
            'form.active_days' => 'required|array|min:1',
            'form.start_time' => 'required',
            'form.sessions_count' => 'required|integer|min:1',
            'form.session_duration' => 'required|integer|min:1',
            'form.break_duration' => 'required|integer|min:0',
        ]);

        // حساب وقت الانتهاء تلقائياً
        $minutesToAdd = ($this->form['sessions_count'] * $this->form['session_duration']) + 
                        (($this->form['sessions_count'] - 1) * $this->form['break_duration']);
        
        $this->form['end_time'] = date('H:i:s', strtotime($this->form['start_time'] . " + $minutesToAdd minutes"));

        if ($this->isEditing) {
            StudyPeriod::find($this->selectedPeriodId)->update($this->form);
            $message = 'تم تحديث الفترة بنجاح';
        } else {
            StudyPeriod::create($this->form);
            $message = 'تم إضافة الفترة بنجاح';
        }

        $this->showCreateModal = false;
        $this->dispatch('refreshDatatable')->to(StudyPeriodTable::class);
        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
    }

    public function deletePeriod($id)
    {
        StudyPeriod::find($id)?->delete();
        $this->dispatch('refreshDatatable')->to(StudyPeriodTable::class);
        $this->dispatch('notify', ['type' => 'success', 'message' => 'تم حذف الفترة بنجاح']);
    }

    public function render()
    {
        return view('livewire.dashboard.academic.schedules.study-periods-manager', [
            'academicYears' => AcademicYear::all(),
        ])->layout('dashboard.layouts.master');
    }
}
