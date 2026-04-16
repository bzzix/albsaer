<?php

namespace App\Livewire\Dashboard\Academic\Subjects;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Subject;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\DB;

class SubjectsManager extends Component
{
    public $activeYearId;
    public $showModal = false;
    
    // Form fields
    public $subjectId;
    public $subjectCode;
    public $name = '';
    public $description = '';
    public $is_active = true;
    public $assignedInstructors = [];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'assignedInstructors' => 'array',
        ];
    }

    public function mount()
    {
        $this->activeYearId = AcademicYear::where('is_active', true)->value('id');
    }

    public function updatedSearch()
    {
        // 
    }

    public function updatedActiveYearId()
    {
        $this->resetPage();
    }

    public function create()
    {
        if (!$this->activeYearId) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'تحذير',
                'message' => 'يرجى اختيار العام الدراسي أولاً.'
            ]);
            return;
        }
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        
        $this->subjectId = $subject->id;
        $this->subjectCode = $subject->code;
        $this->name = $subject->name;
        $this->description = $subject->description;
        $this->is_active = $subject->is_active;

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->subjectId) {
            $subject = Subject::findOrFail($this->subjectId);
            $subject->update([
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);
        } else {
            $tempCode = 'TMP-' . time();
            $subject = Subject::create([
                'academic_year_id' => $this->activeYearId,
                'code' => $tempCode,
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);
            
            $subject->code = 'SUBJ-' . str_pad($subject->id, 4, '0', STR_PAD_LEFT);
            $subject->save();
        }

        // Handle instructor assignment
        if (!empty($this->assignedInstructors)) {
            $records = DB::table('academic_year_instructor')
                         ->where('academic_year_id', $this->activeYearId)
                         ->whereIn('instructor_id', $this->assignedInstructors)
                         ->get();

            foreach ($records as $rec) {
                $subIds = is_string($rec->subject_ids) ? json_decode($rec->subject_ids, true) : ($rec->subject_ids ?? []);
                if (!in_array($subject->id, $subIds)) {
                    $subIds[] = $subject->id;
                    DB::table('academic_year_instructor')->where('id', $rec->id)->update([
                        'subject_ids' => json_encode($subIds)
                    ]);
                }
            }
        }

        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'تم الحفظ بنجاح',
            'message' => 'تم تسجيل بيانات المادة بنجاح.'
        ]);

        $this->showModal = false;
        $this->resetForm();
    }

    public function duplicateFromYear($sourceYearId)
    {
        if (!$sourceYearId || $sourceYearId == $this->activeYearId) {
            return;
        }

        $sourceSubjects = Subject::where('academic_year_id', $sourceYearId)->get();
        if ($sourceSubjects->isEmpty()) {
            $this->dispatch('notify', [
                'type' => 'warning',
                'title' => 'تنبيه',
                'message' => 'لا توجد مواد في العام المحدد لنسخها.'
            ]);
            return;
        }

        DB::beginTransaction();
        try {
            $count = 0;
            foreach ($sourceSubjects as $src) {
                // Check if a subject with the same name already exists in active year
                $exists = Subject::where('academic_year_id', $this->activeYearId)->where('name', $src->name)->exists();
                if (!$exists) {
                    $newSub = Subject::create([
                        'academic_year_id' => $this->activeYearId,
                        'code' => 'TMP-' . uniqid(),
                        'name' => $src->name,
                        'description' => $src->description,
                        'is_active' => $src->is_active,
                    ]);
                    $newSub->code = 'SUBJ-' . str_pad($newSub->id, 4, '0', STR_PAD_LEFT);
                    $newSub->save();
                    $count++;
                }
            }
            DB::commit();
            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'تم النسخ بنجاح',
                'message' => "تم استيراد $count مادة بنجاح."
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'خطأ في النسخ',
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ]);
        }
    }



    private function resetForm()
    {
        $this->subjectId = null;
        $this->name = '';
        $this->description = '';
        $this->is_active = true;
        $this->assignedInstructors = [];
    }

    public function render()
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        
        $availableInstructors = [];
        if ($this->activeYearId) {
            $availableInstructors = \App\Models\Instructor::whereHas('user')->join('academic_year_instructor', 'instructors.id', '=', 'academic_year_instructor.instructor_id')
                ->where('academic_year_instructor.academic_year_id', $this->activeYearId)
                ->select('instructors.*')
                ->get();
        }

        return view('livewire.dashboard.academic.subjects.subjects-manager', [
            'academicYears' => $academicYears,
            'availableInstructors' => $availableInstructors
        ])->layout('dashboard.layouts.master');
    }
}
