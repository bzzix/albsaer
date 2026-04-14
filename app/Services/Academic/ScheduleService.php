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
     * إنشاء جدول مرن مع فترات دراسية (صباحية/مسائية)
     * وتوليد الحصص بناءً على قالب زمني
     */
    public function createFlexibleSchedule(array $data)
    {
        return DB::transaction(function () use ($data) {
            $schedule = StudySchedule::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'group_id' => $data['group_id'] ?? null,
                'project_id' => $data['project_id'] ?? null,
                'semester_id' => $data['semester_id'] ?? null,
                'period' => $data['period'] ?? 'morning',
                'is_active' => $data['is_active'] ?? true,
            ]);

            $activeDays = $data['active_days'] ?? []; // ['sunday', 'monday', ...]
            $sessionTemplates = $data['session_templates'] ?? []; // [['name' => '', 'start' => '', 'end' => ''], ...]

            foreach ($activeDays as $dayOfWeek) {
                $day = $schedule->days()->create([
                    'day_of_week' => $dayOfWeek,
                    'is_study_day' => true,
                    'day_start_time' => $data['day_start_time'] ?? null,
                    'day_end_time' => $data['day_end_time'] ?? null,
                    'sessions_count' => count($sessionTemplates),
                ]);

                foreach ($sessionTemplates as $index => $template) {
                    $day->sessions()->create([
                        'session_number' => $index + 1,
                        'session_name' => $template['name'] ?? ('الحصة ' . ($index + 1)),
                        'start_time' => $template['start_time'],
                        'end_time' => $template['end_time'],
                        'subject_id' => $template['subject_id'] ?? null,
                    ]);
                }
            }

            return $schedule;
        });
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
