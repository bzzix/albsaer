<?php

namespace App\Observers;

use App\Models\DailySession;
use Illuminate\Support\Facades\Log;

class DailySessionObserver
{
    /**
     * Handle the DailySession "created" event.
     */
    public function created(DailySession $dailySession): void
    {
        $this->notifyAutomation($dailySession, 'created');
    }

    /**
     * Handle the DailySession "updated" event.
     */
    public function updated(DailySession $dailySession): void
    {
        $this->notifyAutomation($dailySession, 'updated');
    }

    /**
     * Handle the DailySession "deleted" event.
     */
    public function deleted(DailySession $dailySession): void
    {
        $this->notifyAutomation($dailySession, 'deleted');
    }

    /**
     * إرسال تنبيه لنظام الأتمتة عند تعديل تفاصيل حصة معينة
     */
    protected function notifyAutomation(DailySession $session, string $event): void
    {
        Log::info("DailySession Event: {$event}", [
            'id' => $session->id,
            'session_number' => $session->session_number,
            'subject_id' => $session->subject_id,
            'start_time' => $session->start_time,
        ]);
    }
}
