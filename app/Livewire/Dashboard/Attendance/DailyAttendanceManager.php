<?php

namespace App\Livewire\Dashboard\Attendance;

use App\Models\Group;
use App\Models\DailySession;
use App\Models\GroupEnrollment;
use App\Models\SessionAttendance;
use App\Services\Attendance\AttendanceService;
use Livewire\Component;
use Livewire\Attributes\Url;
use Carbon\Carbon;

class DailyAttendanceManager extends Component
{
    protected AttendanceService $attendanceService;

    public $date;
    
    #[Url(as: 'group')]
    public $groupId = null;
    
    public $sessionId = null;
    public $sessions = [];
    public $students = [];
    public $attendanceData = []; // student_id => status

    public function booted()
    {
        $this->attendanceService = app(AttendanceService::class);
    }

    public function mount()
    {
        $this->date = Carbon::today()->toDateString();
        
        if ($this->groupId) {
            $this->loadSessions();
        }
    }

    /**
     * جلب الحصص المتاحة للمجموعة في اليوم المختار
     */
    public function loadSessions()
    {
        if (!$this->groupId) return;

        $dayOfWeek = strtolower(Carbon::parse($this->date)->format('l'));

        $this->sessions = DailySession::whereHas('scheduleDay', function ($query) use ($dayOfWeek) {
            $query->where('day_of_week', $dayOfWeek)
                  ->whereHas('schedule', function ($q) {
                      $q->where('group_id', $this->groupId)->where('is_active', true);
                  });
        })->with('subject')->orderBy('start_time')->get();

        // محاولة تحديد الحصة الحالية تلقائياً
        $current = $this->attendanceService->getCurrentSessionForGroup($this->groupId, $this->date);
        if ($current) {
            $this->sessionId = $current->id;
            $this->loadStudents();
        } elseif ($this->sessions->count() > 0) {
            $this->sessionId = $this->sessions->first()->id;
            $this->loadStudents();
        }
    }

    /**
     * جلب قائمة الطلاب وحالات حضورهم الحالية
     */
    public function loadStudents()
    {
        if (!$this->groupId || !$this->sessionId) {
            $this->students = [];
            return;
        }

        // جلب الطلاب المسجلين
        $this->students = GroupEnrollment::where('group_id', $this->groupId)
            ->where('status', 'active')
            ->with('student.user')
            ->get();

        // جلب حالات الحضور المخزنة مسبقاً لهذه الحصة
        $existing = SessionAttendance::where('daily_session_id', $this->sessionId)
            ->where('attendance_date', $this->date)
            ->pluck('status', 'student_id')
            ->toArray();

        // تهيئة البيانات
        $this->attendanceData = [];
        foreach ($this->students as $enrollment) {
            $this->attendanceData[$enrollment->student_id] = $existing[$enrollment->student_id] ?? 'pending';
        }
    }

    public function updatedGroupId()
    {
        $this->loadSessions();
    }

    public function updatedDate()
    {
        $this->loadSessions();
    }

    public function updatedSessionId()
    {
        $this->loadStudents();
    }

    /**
     * تسجيل الحضور بشكل فوري (Ajax-like)
     */
    public function markAttendance($studentId, $status)
    {
        $enrollment = collect($this->students)->where('student_id', $studentId)->first();

        $this->attendanceService->markAttendance([
            'student_id' => $studentId,
            'daily_session_id' => $this->sessionId,
            'attendance_date' => $this->date,
            'status' => $status,
            'group_enrollment_id' => $enrollment?->id,
        ]);

        $this->attendanceData[$studentId] = $status;
        
        // إشعار بسيط للواجهة
        $this->dispatch('notify', ['type' => 'success', 'message' => 'تم تحديث حالة الطالب.']);
    }

    public function render()
    {
        return view('livewire.dashboard.attendance.daily-attendance-manager', [
            'groups' => Group::active()->get(),
            'metrics' => $this->groupId ? $this->attendanceService->getDailyMetrics($this->groupId, $this->date) : null,
        ])->layout('layouts.dashboard');
    }
}
