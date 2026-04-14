<?php

namespace App\Services\Attendance;

use App\Models\SessionAttendance;
use App\Models\DailySession;
use App\Models\Student;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceService extends BaseService
{
    /**
     * تسجيل حضور الطالب في حصة معينة
     */
    public function markAttendance(array $data)
    {
        return DB::transaction(function () use ($data) {
            $attendance = SessionAttendance::updateOrCreate(
                [
                    'student_id' => $data['student_id'],
                    'daily_session_id' => $data['daily_session_id'],
                    'attendance_date' => $data['attendance_date'] ?? Carbon::today()->toDateString(),
                ],
                [
                    'status' => $data['status'],
                    'marked_by' => auth()->id() ?? $data['marked_by'],
                    'notes' => $data['notes'] ?? null,
                    'group_enrollment_id' => $data['group_enrollment_id'] ?? null,
                ]
            );

            // بعد التسجيل، يمكن إطلاق فحص للحالة اليومية (مثلاً عبر n8n لاحقاً أو كود محلي)
            $this->checkDailyStatus($data['student_id'], $attendance->attendance_date);

            return $attendance;
        });
    }

    /**
     * تحديد الحصة الحالية لمجموعة معينة بناءً على الوقت الحالي
     */
    public function getCurrentSessionForGroup($groupId, $date = null)
    {
        $date = $date ?? Carbon::today()->toDateString();
        $dayOfWeek = strtolower(Carbon::parse($date)->format('l'));
        $currentTime = Carbon::now()->toTimeString();

        return DailySession::whereHas('scheduleDay', function ($query) use ($groupId, $dayOfWeek) {
            $query->where('day_of_week', $dayOfWeek)
                  ->whereHas('schedule', function ($q) use ($groupId) {
                      $q->where('group_id', $groupId)->where('is_active', true);
                  });
        })
        ->where('start_time', '<=', $currentTime)
        ->where('end_time', '>=', $currentTime)
        ->first();
    }

    /**
     * جلب إحصائيات الحضور ليوم معين
     */
    public function getDailyMetrics($groupId, $date)
    {
        $date = $date ?? Carbon::today()->toDateString();
        
        $totalStudents = DB::table('group_enrollments')
            ->where('group_id', $groupId)
            ->where('status', 'active')
            ->count();

        $absentCount = SessionAttendance::where('attendance_date', $date)
            ->whereHas('dailySession.scheduleDay.schedule', function($q) use ($groupId) {
                $q->where('group_id', $groupId);
            })
            ->where('status', 'unexcused_absent')
            ->distinct('student_id')
            ->count();

        return [
            'total_students' => $totalStudents,
            'absent_count' => $absentCount,
            'attendance_rate' => $totalStudents > 0 ? round((($totalStudents - $absentCount) / $totalStudents) * 100) : 0,
        ];
    }
}
