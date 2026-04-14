<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Subject;
use App\Models\Course;

class Project extends Model
{
    protected $fillable = [
        'academic_year_id',
        'code',
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }

    public function gradeLevels(): HasMany
    {
        return $this->hasMany(GradeLevel::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }
}
