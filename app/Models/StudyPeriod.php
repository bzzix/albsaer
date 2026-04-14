<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyPeriod extends Model
{
    protected $fillable = [
        'name',
        'academic_year_id',
        'active_days',
        'start_time',
        'end_time',
        'sessions_count',
        'session_duration',
        'break_duration',
        'is_active',
    ];

    protected $casts = [
        'active_days' => 'array',
        'is_active' => 'boolean',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
