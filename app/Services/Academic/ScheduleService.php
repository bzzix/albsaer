<?php

namespace App\Services\Academic;

use App\Models\StudySchedule;
use App\Models\ScheduleDay;
use App\Models\DailySession;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class ScheduleService extends BaseService
{
    /**
     * إنشاء مرحلة دراسية جديدة وتوليد حصصها بناءً على قالب فترة
     */
    public function createScheduleFromTemplate(array $data)
    {
        return DB::transaction(function () use ($data) {
            $period = \App\Models\StudyPeriod::findOrFail($data['study_period_id']);
            $groupId = $data['group_id'];

            // إنشاء السجل الرئيسي للجدول (StudySchedule) للمجموعة
            $schedule = StudySchedule::create([
                'name' => $period->name . ' - ' . \App\Models\Group::find($groupId)?->name,
                'group_id' => $groupId,
                'project_id' => $data['project_id'] ?? null,
                'is_active' => true,
            ]);

            $activeDays = $period->active_days; // [0, 1, 2, 3, 4]
            $sessionsCount = $period->sessions_count;
            $startTime = $period->start_time;
            $sessionDuration = $period->session_duration;
            $breakDuration = $period->break_duration;

            foreach ($activeDays as $dayOfWeek) {
                $day = $schedule->days()->create([
                    'day_of_week' => $this->mapDayIndex($dayOfWeek),
                    'is_study_day' => true,
                    'sessions_count' => $sessionsCount,
                ]);

                // توليد حصص فارغة بتوقيتات محسوبة تراعي الاستراحات
                $currentTime = strtotime($startTime);

                for ($i = 0; $i < $sessionsCount; $i++) {
                    $sessionStart = date('H:i:s', $currentTime);
                    $sessionEnd = date('H:i:s', strtotime("+$sessionDuration minutes", $currentTime));

                    $day->sessions()->create([
                        'session_number' => $i + 1,
                        'start_time' => $sessionStart,
                        'end_time' => $sessionEnd,
                        'session_name' => "الحصة " . ($i + 1),
                    ]);

                    // إضافة وقت الحصة + وقت الاستراحة للحصة القادمة
                    $currentTime = strtotime("+$sessionDuration minutes", $currentTime);
                    if ($i < $sessionsCount - 1) {
                        $currentTime = strtotime("+$breakDuration minutes", $currentTime);
                    }
                }
            }

            return $schedule;
        });
    }

    /**
     * تحديث بيانات حصة معينة (تعيين مادة ومدرس)
     */
    public function updateSessionAssignment($sessionId, $subjectId, $instructorId)
    {
        return DB::transaction(function () use ($sessionId, $subjectId, $instructorId) {
            $session = DailySession::findOrFail($sessionId);
            $session->update(['subject_id' => $subjectId]);

            // تحديث المدرب (Many-to-Many عبر جدول الوسط)
            DB::table('session_instructors')->updateOrInsert(
                ['daily_session_id' => $sessionId],
                [
                    'instructor_id' => $instructorId,
                    'is_primary' => true,
                    'updated_at' => now()
                ]
            );

            return $session;
        });
    }

    /**
     * تحويل معامل اليوم (0-6) إلى الاسم المخزن في القاعدة
     */
    protected function mapDayIndex($index)
    {
        $days = [
            0 => 'saturday',
            1 => 'sunday',
            2 => 'monday',
            3 => 'tuesday',
            4 => 'wednesday',
            5 => 'thursday',
            6 => 'friday',
        ];
        return $days[$index] ?? 'sunday';
    }

    /**
     * الحصول على الحصص النشطة لمجموعة معينة في تاريخ معين
     */
    public function getActiveSessionsForGroup($groupId, $dayOfWeek)
    {
        return DailySession::whereHas('scheduleDay', function ($query) use ($groupId, $dayOfWeek) {
            $query->where('day_of_week', $dayOfWeek)
                  ->whereHas('schedule', function ($q) use ($groupId) {
                      $q->where('group_id', $groupId)->where('is_active', true);
                  });
        })->with(['subject', 'scheduleDay.schedule'])->get();
    }
}
