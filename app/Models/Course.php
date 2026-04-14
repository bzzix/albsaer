<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'grade_level_id',
        'code',
        'name',
        'short_description',
        'content',
        'description',
        'price',
        'sale_price',
        'level',
        'language',
        'enrollment_limit',
        'requirements',
        'learning_outcomes',
        'total_hours',
        'start_date',
        'end_date',
        'status',
        'image_path',
        'instructor_id',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'requirements' => 'array',
        'learning_outcomes' => 'array',
    ];

    public function projects(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_course');
    }

    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(CourseCategory::class, 'category_course');
    }

    public function modules(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseModule::class)->orderBy('module_order');
    }

    public function instructor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
