<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
        'is_public',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        // مسح الكاش عند الحفظ
        static::saved(function (Setting $setting) {
            clear_settings_cache($setting->key, $setting->group);
        });

        // مسح الكاش عند الحذف
        static::deleted(function (Setting $setting) {
            clear_settings_cache($setting->key, $setting->group);
        });
    }

    /**
     * Get the parsed value based on type
     *
     * @return mixed
     */
    public function getParsedValueAttribute(): mixed
    {
        return match ($this->type) {
            'int' => (int) $this->value,
            'bool' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'json', 'array' => json_decode($this->value, true),
            default => $this->value,
        };
    }

    /**
     * Scope للإعدادات العامة فقط
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope حسب المجموعة
     */
    public function scopeGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * استرجاع إعداد بالمفتاح
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return get_setting($key, $default);
    }

    /**
     * حفظ إعداد
     */
    public static function set(
        string $key,
        mixed $value,
        string $type = 'string',
        ?string $group = null,
        ?string $description = null
    ): self {
        return set_setting($key, $value, $type, $group, $description);
    }

    /**
     * حذف إعداد
     */
    public static function remove(string $key): bool
    {
        return delete_setting($key);
    }

    /**
     * التحقق من وجود إعداد
     */
    public static function has(string $key): bool
    {
        return setting_exists($key);
    }

    /**
     * استرجاع جميع الإعدادات
     */
    public static function getAll(?string $group = null): array
    {
        return get_all_settings($group);
    }

    /**
     * تحديث كاش الإعدادات
     * 
     * @return array إحصائيات عملية إعادة البناء
     */
    public static function refreshCache(): array
    {
        return rebuild_settings_cache();
    }

    /**
     * إعادة بناء كاش الإعدادات من قاعدة البيانات
     * 
     * @return array إحصائيات عملية إعادة البناء
     */
    public static function rebuildCache(): array
    {
        return rebuild_settings_cache();
    }
}
