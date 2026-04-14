<?php

namespace App\Livewire\Dashboard\Academic\Schedules;

use App\Models\Group;
use App\Models\Subject;
use App\Models\Instructor;
use App\Models\StudySchedule;
use App\Models\StudyPeriod;
use App\Models\AcademicYear;
use App\Models\Project;
use App\Services\Academic\ScheduleService;
use Livewire\Component;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\DB;

class ScheduleBuilder extends Component
{
    protected ScheduleService $scheduleService;

    // الفلاتر
    public $academicYearId;
    public $projectId = null;
    public $groupId = null;
    
    #[Url(as: 'period')]
    public $periodId = null;

    // حالة توليد الجدول
    public $showGenerateModal = false;
    public $selectedProjectForGenerate = null;
    public $generateData = [
        'group_ids' => [],
        'study_period_id' => '',
    ];

    public $currentAcademicYear = null;

    // حالة تعديل الحصة
    public $showEditSessionModal = false;
    public $editingSessionData = [
        'id' => null,
        'subject_id' => null,
        'instructor_id' => null,
    ];

    public $daysOfWeek = [
        'sunday' => 'الأحد',
        'monday' => 'الإثنين',
        'tuesday' => 'الثلاثاء',
        'wednesday' => 'الأربعاء',
        'thursday' => 'الخميس',
        'friday' => 'الجمعة',
        'saturday' => 'السبت',
    ];

    public function booted()
    {
        $this->scheduleService = app(ScheduleService::class);
    }

    public function mount()
    {
        $this->currentAcademicYear = AcademicYear::getCurrent();
        $this->academicYearId = $this->currentAcademicYear?->id;

        if ($this->periodId) {
            $period = StudySchedule::find($this->periodId);
            if ($period) {
                $this->projectId = $period->project_id;
                $this->groupId = $period->group_id;
                $this->loadSchedules();
            }
        }
    }

    public function getProjectsProperty()
    {
        return Project::where('academic_year_id', $this->academicYearId)->active()->get();
    }

    public function getGroupsProperty()
    {
        if (!$this->projectId) return collect();
        return Group::where('project_id', $this->projectId)->get();
    }

    public function getPeriodsProperty()
    {
        if (!$this->groupId) return collect();
        return StudySchedule::where('group_id', $this->groupId)->get();
    }

    public function getPeriodTemplatesProperty()
    {
        return StudyPeriod::where('academic_year_id', $this->academicYearId)->active()->get();
    }

    // Computed Properties للمودل
    public function getProjectsForGenerateProperty()
    {
        return Project::where('academic_year_id', $this->academicYearId)->active()->get();
    }

    public function getGroupsForGenerateProperty()
    {
        if (!$this->selectedProjectForGenerate) return collect();
        return Group::where('project_id', $this->selectedProjectForGenerate)->get();
    }

    public function updatedProjectId()
    {
        $this->groupId = null;
        $this->periodId = null;
        $this->schedules = [];
    }

    public function updatedGroupId()
    {
        $this->periodId = null;
        $this->schedules = [];
    }

    public function updatedPeriodId()
    {
        $this->loadSchedules();
    }

    public $schedules = [];

    public function loadSchedules()
    {
        if ($this->periodId) {
            $this->schedules = StudySchedule::where('id', $this->periodId)
                ->with(['days.sessions.subject', 'days.sessions.instructors.user', 'days.sessions' => function($q) {
                    $q->orderBy('session_number');
                }])
                ->get();
        }
    }

    public function openGenerateModal()
    {
        $this->reset(['generateData', 'selectedProjectForGenerate']);
        $this->showGenerateModal = true;
    }

    /**
     * توليد جداول دراسية لعدة مجموعات بناءً على القالب المختار
     */
    public function generateSchedule()
    {
        $this->validate([
            'selectedProjectForGenerate' => 'required|exists:projects,id',
            'generateData.study_period_id' => 'required|exists:study_periods,id',
            'generateData.group_ids' => 'required|array|min:1',
            'generateData.group_ids.*' => 'exists:groups,id',
        ], [
            'selectedProjectForGenerate.required' => 'يرجى اختيار المشروع.',
            'generateData.study_period_id.required' => 'يرجى اختيار قالب الفترة الدراسية.',
            'generateData.group_ids.required' => 'يرجى اختيار مجموعة واحدة على الأقل.',
            'generateData.group_ids.min' => 'يرجى اختيار مجموعة واحدة على الأقل.',
        ]);

        $lastSchedule = null;

        foreach ($this->generateData['group_ids'] as $groupId) {
            $data = [
                'group_id'        => $groupId,
                'study_period_id' => $this->generateData['study_period_id'],
                'project_id'      => $this->selectedProjectForGenerate,
            ];
            $lastSchedule = $this->scheduleService->createScheduleFromTemplate($data);
        }

        if ($lastSchedule) {
            $this->periodId  = $lastSchedule->id;
            $this->projectId = $this->selectedProjectForGenerate;
            $this->groupId   = $this->generateData['group_ids'][0];
        }

        $this->showGenerateModal = false;
        $this->loadSchedules();

        $count = count($this->generateData['group_ids']);
        $this->dispatch('notify', ['type' => 'success', 'message' => "تم توليد الجداول الدراسية لـ {$count} مجموعة بنجاح."]);
    }

    public function openEditSession($sessionId)
    {
        $session = \App\Models\DailySession::with(['subject'])->findOrFail($sessionId);
        
        $primaryInstructorId = DB::table('session_instructors')
            ->where('daily_session_id', $sessionId)
            ->where('is_primary', true)
            ->value('instructor_id');

        $this->editingSessionData = [
            'id' => $session->id,
            'subject_id' => $session->subject_id,
            'instructor_id' => $primaryInstructorId,
        ];

        $this->showEditSessionModal = true;
    }

    public function updateSessionAssignment()
    {
        $this->validate([
            'editingSessionData.subject_id' => 'required|exists:subjects,id',
            'editingSessionData.instructor_id' => 'required|exists:instructors,id',
        ]);

        $this->scheduleService->updateSessionAssignment(
            $this->editingSessionData['id'],
            $this->editingSessionData['subject_id'],
            $this->editingSessionData['instructor_id']
        );

        $this->loadSchedules();
        $this->showEditSessionModal = false;
        $this->dispatch('notify', ['type' => 'success', 'message' => 'تم تحديث الحصة بنجاح.']);
    }

    public function deleteStage($scheduleId)
    {
        StudySchedule::find($scheduleId)?->delete();
        $this->periodId = null;
        $this->schedules = [];
        $this->dispatch('notify', ['type' => 'info', 'message' => 'تم حذف الجدول بنجاح.']);
    }

    public function render()
    {
        return view('livewire.dashboard.academic.schedules.schedule-builder', [
            'allSubjects'         => Subject::active()->get(),
            'availableInstructors' => $this->getAvailableInstructors(),
        ])->layout('dashboard.layouts.master');
    }

    protected function getAvailableInstructors()
    {
        if (!$this->editingSessionData['subject_id']) return collect();

        return Instructor::whereHas('subjects', function ($q) {
            $q->where('subjects.id', $this->editingSessionData['subject_id']);
        })->with('user')->get();
    }
}
