<?php

namespace App\Observers;

use App\Models\SessionAttendance;
use Illuminate\Support\Facades\Log;

class SessionAttendanceObserver
{
    /**
     * Handle the SessionAttendance "created" event.
     */
    public function created(SessionAttendance $attendance): void
    {
        $this->handleAutomation($attendance, 'created');
    }

    /**
     * Handle the SessionAttendance "updated" event.
     */
    public function updated(SessionAttendance $attendance): void
    {
        $this->handleAutomation($attendance, 'updated');
    }

    /**
     * التعامل مع الأتمتة وإرسال التنبيهات لنظام n8n
     */
    protected function handleAutomation(SessionAttendance $attendance, string $event): void
    {
        // إطلاق الحدث فقط في حالة الغياب غير المبرر أو التأخير الملحوظ
        if ($attendance->status === 'unexcused_absent') {
            Log::info("Attendance Automation: Unexcused Absence detected for student ID: {$attendance->student_id}", [
                'session_id' => $attendance->daily_session_id,
                'date' => $attendance->attendance_date,
            ]);
            
            // هنا يتم استدعاء Webhook لنظام n8n لإرسال رسالة WhatsApp لولي الأمر
            // Example: Http::post(config('services.n8n.attendance_webhook'), $attendance->toArray());
        }
    }
}
