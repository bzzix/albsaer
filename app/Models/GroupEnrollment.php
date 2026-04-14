<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupEnrollment extends Model
{
    protected $fillable = [
        'student_id',
        'group_id',
        'enrolled_by',
        'enrollment_date',
        'status',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function enroller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'enrolled_by');
    }
}
