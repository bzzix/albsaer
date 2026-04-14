<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudySchedule extends Model
{
    protected $fillable = [
        'name',
        'description',
        'group_id',
        'project_id',
        'semester_id',
        'period', // morning, evening, etc.
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function days(): HasMany
    {
        return $this->hasMany(ScheduleDay::class);
    }
}
