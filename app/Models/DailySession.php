<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailySession extends Model
{
    protected $fillable = [
        'schedule_day_id',
        'session_number',
        'start_time',
        'end_time',
        'subject_id',
        'session_name',
    ];

    public function scheduleDay(): BelongsTo
    {
        return $this->belongsTo(ScheduleDay::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(SessionAttendance::class);
    }
}
