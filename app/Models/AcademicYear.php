<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    protected $fillable = ['name', 'code', 'start_date', 'end_date', 'is_active'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function scopeActiveUpcoming(\Illuminate\Database\Eloquent\Builder $query)
    {
        // Active and ended after today (which means it's current or upcoming)
        return $query->where('is_active', true)
                     ->whereDate('end_date', '>=', now());
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
