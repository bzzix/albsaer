<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instructor extends Model
{
    protected $fillable = [
        'user_id',
        'instructor_code',
        'specialization',
        'bio',
        'hire_date',
        'status',
    ];

    protected $casts = [
        'hire_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'instructor_subjects')
            ->withPivot(['proficiency_level', 'assigned_date', 'is_active'])
            ->withTimestamps();
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(DailySession::class, 'instructor_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
