<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    protected $fillable = [
        'project_id',
        'course_id',
        'code',
        'name',
        'trainer_id',
        'supervisor_id',
        'start_date',
        'end_date',
        'max_students',
        'status',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Instructor::class, 'trainer_id');
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(GroupEnrollment::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'group_enrollments')
            ->withPivot(['enrolled_by', 'enrollment_date', 'status'])
            ->withTimestamps();
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(StudySchedule::class);
    }
}
