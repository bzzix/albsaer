<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function instructors()
    {
        return $this->belongsToMany(Instructor::class, 'instructor_subjects')
            ->withPivot(['proficiency_level', 'assigned_date', 'is_active'])
            ->withTimestamps();
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_subjects');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }
}
