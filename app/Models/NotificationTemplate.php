<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'name',
        'email_subject',
        'email_content',
        'whatsapp_content',
        'variables',
        'is_active',
        'is_email_active',
        'is_whatsapp_active',
        'is_system_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
        'is_email_active' => 'boolean',
        'is_whatsapp_active' => 'boolean',
        'is_system_active' => 'boolean',
    ];

    /**
     * استبدال المتغيرات في المحتوى
     */
    public function parseContent(string $channel = 'email', array $data = [])
    {
        $content = ($channel === 'email') ? $this->email_content : $this->whatsapp_content;
        $subject = $this->email_subject;

        foreach ($data as $key => $value) {
            $placeholder = '{{' . $key . '}}';
            $content = str_replace($placeholder, $value, $content);
            $subject = str_replace($placeholder, $value, $subject);
        }

        return [
            'subject' => $subject,
            'content' => $content,
        ];
    }

    /**
     * Scope للأدوار النشطة فقط
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
