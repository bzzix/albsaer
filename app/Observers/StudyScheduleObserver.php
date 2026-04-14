<?php

namespace App\Observers;

use App\Models\StudySchedule;
use Illuminate\Support\Facades\Log;

class StudyScheduleObserver
{
    /**
     * Handle the StudySchedule "created" event.
     */
    public function created(StudySchedule $studySchedule): void
    {
        $this->notifyAutomation($studySchedule, 'created');
    }

    /**
     * Handle the StudySchedule "updated" event.
     */
    public function updated(StudySchedule $studySchedule): void
    {
        $this->notifyAutomation($studySchedule, 'updated');
    }

    /**
     * Handle the StudySchedule "deleted" event.
     */
    public function deleted(StudySchedule $studySchedule): void
    {
        $this->notifyAutomation($studySchedule, 'deleted');
    }

    /**
     * إرسال تنبيه لنظام الأتمتة (n8n)
     */
    protected function notifyAutomation(StudySchedule $schedule, string $event): void
    {
        // هنا يتم استدعاء Webhook أو تسجيل Log ليقوم n8n بسحبه
        Log::info("StudySchedule Event: {$event}", [
            'id' => $schedule->id,
            'name' => $schedule->name,
            'group_id' => $schedule->group_id,
            'period' => $schedule->period,
        ]);

        // يمكن إضافة منطق إرسال رسائل WhatsApp هنا عبر خدمة مخصصة مستقبلاً
    }
}
