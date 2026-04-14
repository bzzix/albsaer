<?php

namespace App\Observers;

use App\Models\Excuse;
use App\Models\SessionAttendance;

class ExcuseObserver
{
    /**
     * Handle the Excuse "updated" event.
     */
    public function updated(Excuse $excuse): void
    {
        // إذا تم قبول العذر، نقوم بتحديث سجلات الحضور لهذا الطالب في نفس التواريخ
        if ($excuse->isDirty('status') && $excuse->status === 'approved') {
            $this->updateAttendanceStatuses($excuse);
        }
    }

    /**
     * تحديث حالات الحضور لتصبح "غياب بعذر"
     */
    protected function updateAttendanceStatuses(Excuse $excuse)
    {
        SessionAttendance::where('student_id', $excuse->student_id)
            ->whereBetween('attendance_date', [$excuse->start_date, $excuse->end_date])
            ->where('status', 'unexcused_absent') // فقط الغياب غير المبرر يتم تحويله لمبرر
            ->update([
                'status' => 'excused_absent',
                'notes' => ($excuse->notes ? $excuse->notes . ' | ' : '') . 'تم التحويل بناءً على العذر المعتمد رقم ' . $excuse->id
            ]);
    }
}
