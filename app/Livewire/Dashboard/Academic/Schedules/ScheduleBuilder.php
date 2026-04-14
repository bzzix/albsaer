<?php

namespace App\Livewire\Dashboard\Academic\Schedules;

use App\Models\Group;
use App\Models\Subject;
use App\Models\Instructor;
use App\Models\StudySchedule;
use App\Services\Academic\ScheduleService;
use Livewire\Component;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\DB;

class ScheduleBuilder extends Component
{
    protected ScheduleService $scheduleService;

    #[Url(as: 'group')]
    public $groupId = null;

    public $selectedDays = [];
    public $sessions = []; // Dynamic sessions: ['start_time', 'end_time', 'subject_id', 'instructor_id']
    
    public $period = 'morning'; // morning, evening
    public $startDate;
    public $endDate;

    public $daysOfWeek = [
        1 => 'الأحد',
        2 => 'الإثنين',
        3 => 'الثلاثاء',
        4 => 'الأربعاء',
        5 => 'الخميس',
        6 => 'الجمعة',
        0 => 'السبت',
    ];

    public function booted()
    {
        $this->scheduleService = app(ScheduleService::class);
    }

    public function mount($groupId = null)
    {
        if ($groupId) {
            $this->groupId = $groupId;
            $group = Group::findOrFail($groupId);
            $this->startDate = $group->start_date->format('Y-m-d');
            $this->endDate = $group->end_date->format('Y-m-d');
        } else {
            $this->startDate = now()->format('Y-m-d');
            $this->endDate = now()->addMonths(3)->format('Y-m-d');
        }

        // Initialize with one empty session
        $this->addSession();
    }

    public function addSession()
    {
        $this->sessions[] = [
            'start_time' => '08:00',
            'end_time' => '09:00',
            'subject_id' => null,
            'instructor_id' => null,
            'available_instructors' => [],
        ];
    }

    public function removeSession($index)
    {
        unset($this->sessions[$index]);
        $this->sessions = array_values($this->sessions);
    }

    public function updatedSessions($value, $key)
    {
        // Example: sessions.0.subject_id
        if (str_contains($key, 'subject_id')) {
            $index = explode('.', $key)[1];
            $subjectId = $value;
            
            if ($subjectId) {
                $this->sessions[$index]['available_instructors'] = Subject::find($subjectId)
                    ->instructors()
                    ->with('user')
                    ->get()
                    ->map(fn($ins) => ['id' => $ins->id, 'name' => $ins->user->name])
                    ->toArray();
            } else {
                $this->sessions[$index]['available_instructors'] = [];
            }
            
            $this->sessions[$index]['instructor_id'] = null;
        }
    }

    public function generateSchedule()
    {
        $this->validate([
            'groupId' => 'required|exists:groups,id',
            'selectedDays' => 'required|array|min:1',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after:startDate',
            'sessions' => 'required|array|min:1',
            'sessions.*.subject_id' => 'required|exists:subjects,id',
            'sessions.*.instructor_id' => 'required|exists:instructors,id',
        ]);

        try {
            DB::beginTransaction();

            $schedule = StudySchedule::create([
                'group_id' => $this->groupId,
                'name' => 'جدول تلقائي لمجموعة ' . Group::find($this->groupId)->name,
                'period' => $this->period,
                'is_active' => true,
            ]);

            // Create template days and sessions via service
            // This is a simplification; the service should handle the date range loop
            foreach ($this->selectedDays as $dayIndex) {
                $day = $schedule->days()->create([
                    'day_of_week' => $dayIndex,
                ]);

                foreach ($this->sessions as $sessionData) {
                    $day->sessions()->create([
                        'subject_id' => $sessionData['subject_id'],
                        'instructor_id' => $sessionData['instructor_id'],
                        'start_time' => $sessionData['start_time'],
                        'end_time' => $sessionData['end_time'],
                    ]);
                }
            }

            // Real generation (DailySessions) based on date range
            $this->scheduleService->generateDailySessionsFromSchedule($schedule->id, $this->startDate, $this->endDate);

            DB::commit();

            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'تم بنجاح',
                'message' => 'تم إنشاء الجدول وتوليد الحصص اليومية للفترة المحددة.',
            ]);

            return redirect()->route('dashboard.academic.groups.index');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'خطأ',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.dashboard.academic.schedules.schedule-builder', [
            'groups' => Group::active()->pluck('name', 'id'),
            'subjects' => Subject::where('is_active', true)->get(),
        ])->layout('layouts.dashboard');
    }
}
