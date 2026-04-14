<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleLesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title',
        'content',
        'lesson_type', // video, text, pdf, interactive, embed, audio
        'lesson_order',
        'duration_minutes',
        'video_url',
        'file_path',
        'embed_code',
        'is_published',
        'is_free',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_free' => 'boolean',
    ];

    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }
}
