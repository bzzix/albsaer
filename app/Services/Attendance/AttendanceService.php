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
     * فحص حالة الطالب اليومية بناءً على الحصص
     * حصتين: حاضر في الاثنتين = حضور كامل، واحدة = تنبيه، صفر = غياب
     */
    public function checkDailyStatus($studentId, $date)
    {
        // هذا المنطق سيستخدم لاحقاً لتوليد التنبيهات التلقائية
        $attendances = SessionAttendance::where('student_id', $studentId)
            ->where('attendance_date', $date)
            ->get();

        $presentCount = $attendances->whereIn('status', ['present', 'late'])->count();
        $totalSessions = 2; // مفترض بناءً على طلب العميل

        if ($presentCount == 0) {
            // غياب كامل -> إطلاق تنبيه غياب
        } elseif ($presentCount < $totalSessions) {
            // حضور جزئي -> إطلاق تنبيه نقص حضور
        }
    }
}
