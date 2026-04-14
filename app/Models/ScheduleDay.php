<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScheduleDay extends Model
{
    protected $fillable = [
        'study_schedule_id',
        'day_of_week',
        'is_study_day',
        'sessions_count',
        'day_start_time',
        'day_end_time',
    ];

    protected $casts = [
        'is_study_day' => 'boolean',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(StudySchedule::class, 'study_schedule_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(DailySession::class);
    }
}
