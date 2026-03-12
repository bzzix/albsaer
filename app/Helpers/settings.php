<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

if (!function_exists('parse_setting_value')) {
    /**
     * تحويل قيمة الإعداد حسب النوع (دالة موحدة)
     *
     * @param mixed $value
     * @param string $type
     * @param mixed $default
     * @return mixed
     */
    function parse_setting_value(mixed $value, string $type, mixed $default = null): mixed
    {
        if ($value === null) {
            return $default;
        }

        return match ($type) {
            'int' => (int) $value,
            'bool' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'json', 'array' => json_decode($value, true) ?? $default ?? [],
            default => $value,
        };
    }
}

if (!function_exists('supports_cache_tags')) {
    /**
     * التحقق من دعم Cache Tags
     *
     * @return bool
     */
    function supports_cache_tags(): bool
    {
        $driver = config('cache.default');
        return in_array($driver, ['redis', 'memcached', 'array']);
    }
}

if (!function_exists('settings_cache')) {
    /**
     * الحصول على Cache instance مع دعم Tags إن أمكن
     *
     * @return \Illuminate\Contracts\Cache\Repository
     */
    function settings_cache()
    {
        if (supports_cache_tags()) {
            return Cache::tags(['settings']);
        }
        
        return Cache::store();
    }
}

if (!function_exists('get_setting')) {
    /**
     * استرجاع قيمة إعداد من الكاش أو قاعدة البيانات
     *
     * @param string $key مفتاح الإعداد
     * @param mixed $default القيمة الافتراضية
     * @return mixed
     */
    function get_setting(string $key, mixed $default = null): mixed
    {
        $cacheKey = "setting_{$key}";

        return settings_cache()->rememberForever($cacheKey, function () use ($key, $default) {
            $setting = Setting::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }

            return parse_setting_value($setting->value, $setting->type, $default);
        });
    }
}

if (!function_exists('get_all_settings')) {
    /**
     * استرجاع جميع الإعدادات دفعة واحدة (محسّن للأداء)
     *
     * @param string|null $group مجموعة محددة
     * @return array
     */
    function get_all_settings(?string $group = null): array
    {
        $cacheKey = $group ? "settings_group_{$group}" : 'settings_all';

        return settings_cache()->rememberForever($cacheKey, function () use ($group) {
            $query = Setting::query();
            
            if ($group) {
                $query->where('group', $group);
            }

            return $query->get()->mapWithKeys(function ($setting) {
                $value = parse_setting_value($setting->value, $setting->type);
                return [$setting->key => $value];
            })->toArray();
        });
    }
}

if (!function_exists('set_setting')) {
    /**
     * حفظ أو تحديث إعداد مع تحديث الكاش
     *
     * @param string $key
     * @param mixed $value
     * @param string $type نوع البيانات: string, int, bool, json, array
     * @param string|null $group
     * @param string|null $description
     * @return \App\Models\Setting
     */
    function set_setting(
        string $key,
        mixed $value,
        string $type = 'string',
        ?string $group = null,
        ?string $description = null
    ): Setting {
        // تحويل القيمة للتخزين
        $storedValue = match ($type) {
            'bool' => $value ? '1' : '0',
            'json', 'array' => json_encode($value, JSON_UNESCAPED_UNICODE),
            default => (string) $value,
        };

        $setting = Setting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $storedValue,
                'type' => $type,
                'group' => $group,
                'description' => $description,
            ]
        );

        // مسح الكاش المتعلق
        clear_settings_cache($key, $group);

        return $setting;
    }
}

if (!function_exists('set_multiple_settings')) {
    /**
     * حفظ عدة إعدادات دفعة واحدة (محسّن للأداء)
     *
     * @param array $settings مصفوفة من ['key' => 'value']
     * @param string $type
     * @param string|null $group
     * @return void
     */
    function set_multiple_settings(array $settings, string $type = 'string', ?string $group = null): void
    {
        DB::transaction(function () use ($settings, $type, $group) {
            foreach ($settings as $key => $value) {
                // تحويل القيمة للتخزين
                $storedValue = match ($type) {
                    'bool' => $value ? '1' : '0',
                    'json', 'array' => json_encode($value, JSON_UNESCAPED_UNICODE),
                    default => (string) $value,
                };

                Setting::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => $storedValue,
                        'type' => $type,
                        'group' => $group,
                    ]
                );
            }
        });

        // مسح الكاش مرة واحدة بعد انتهاء العملية
        clear_settings_cache(null, $group);
    }
}

if (!function_exists('delete_setting')) {
    /**
     * حذف إعداد من قاعدة البيانات والكاش
     *
     * @param string $key
     * @return bool
     */
    function delete_setting(string $key): bool
    {
        $setting = Setting::where('key', $key)->first();
        
        if (!$setting) {
            return false;
        }

        $group = $setting->group;
        $deleted = $setting->delete();

        if ($deleted) {
            clear_settings_cache($key, $group);
        }

        return $deleted;
    }
}

if (!function_exists('clear_settings_cache')) {
    /**
     * مسح كاش الإعدادات
     *
     * @param string|null $key مفتاح محدد
     * @param string|null $group مجموعة محددة
     * @return void
     */
    function clear_settings_cache(?string $key = null, ?string $group = null): void
    {
        $cache = settings_cache();

        if ($key) {
            // مسح إعداد محدد
            $cache->forget("setting_{$key}");
            // مسح كاش التحقق من الوجود
            $cache->forget("setting_exists_{$key}");
        }

        if ($group) {
            // مسح مجموعة محددة
            $cache->forget("settings_group_{$group}");
        }

        // مسح كاش جميع الإعدادات
        $cache->forget('settings_all');

        // إذا لم يتم تحديد شيء، امسح كل الكاش
        if (!$key && !$group) {
            if (supports_cache_tags()) {
                Cache::tags(['settings'])->flush();
            } else {
                // مسح يدوي للمفاتيح المعروفة
                $allSettings = Setting::pluck('key');
                foreach ($allSettings as $settingKey) {
                    $cache->forget("setting_{$settingKey}");
                    $cache->forget("setting_exists_{$settingKey}");
                }
                $cache->forget('settings_all');
            }
        }
    }
}

if (!function_exists('refresh_settings_cache')) {
    /**
     * تحديث كاش الإعدادات (مسح وإعادة بناء)
     *
     * @deprecated استخدم rebuild_settings_cache() بدلاً منها
     * @return void
     */
    function refresh_settings_cache(): void
    {
        rebuild_settings_cache();
    }
}

if (!function_exists('rebuild_settings_cache')) {
    /**
     * حذف كل كاش الإعدادات وإعادة بناءه من قاعدة البيانات
     * 
     * هذه الدالة تقوم بـ:
     * 1. حذف كل كاش الإعدادات (setting_*, setting_exists_*, settings_all, settings_group_*)
     * 2. تحميل جميع الإعدادات من قاعدة البيانات
     * 3. إعادة بناء الكاش لكل إعداد
     *
     * @return array إحصائيات عملية إعادة البناء
     */
    function rebuild_settings_cache(): array
    {
        $startTime = microtime(true);
        $cache = settings_cache();
        
        // 1. حذف كل الكاش
        if (supports_cache_tags()) {
            // استخدام Tags للمسح السريع
            Cache::tags(['settings'])->flush();
        } else {
            // مسح يدوي لكل المفاتيح
            $allSettings = Setting::pluck('key');
            foreach ($allSettings as $key) {
                $cache->forget("setting_{$key}");
                $cache->forget("setting_exists_{$key}");
            }
            
            // مسح كاش المجموعات
            $groups = Setting::distinct()->pluck('group')->filter();
            foreach ($groups as $group) {
                $cache->forget("settings_group_{$group}");
            }
            
            // مسح كاش الكل
            $cache->forget('settings_all');
        }

        // 2. تحميل جميع الإعدادات من قاعدة البيانات
        $allSettings = Setting::all();
        
        // 3. إعادة بناء الكاش
        $rebuilt = [
            'individual' => 0,
            'groups' => 0,
            'all' => 0,
        ];

        // إعادة بناء كاش كل إعداد على حدة
        foreach ($allSettings as $setting) {
            $cacheKey = "setting_{$setting->key}";
            $value = parse_setting_value($setting->value, $setting->type);
            
            $cache->forever($cacheKey, $value);
            
            // كاش التحقق من الوجود
            $cache->put("setting_exists_{$setting->key}", true, now()->addDay());
            
            $rebuilt['individual']++;
        }

        // إعادة بناء كاش المجموعات
        $groups = $allSettings->groupBy('group')->filter(fn($g, $k) => $k !== null);
        foreach ($groups as $groupName => $groupSettings) {
            $cacheKey = "settings_group_{$groupName}";
            $groupData = $groupSettings->mapWithKeys(function ($setting) {
                $value = parse_setting_value($setting->value, $setting->type);
                return [$setting->key => $value];
            })->toArray();
            
            $cache->forever($cacheKey, $groupData);
            $rebuilt['groups']++;
        }

        // إعادة بناء كاش جميع الإعدادات
        $allData = $allSettings->mapWithKeys(function ($setting) {
            $value = parse_setting_value($setting->value, $setting->type);
            return [$setting->key => $value];
        })->toArray();
        
        $cache->forever('settings_all', $allData);
        $rebuilt['all'] = 1;

        $endTime = microtime(true);
        $duration = round(($endTime - $startTime) * 1000, 2);

        return [
            'success' => true,
            'rebuilt' => $rebuilt,
            'total_settings' => $allSettings->count(),
            'total_groups' => $groups->count(),
            'duration_ms' => $duration,
            'cache_driver' => config('cache.default'),
            'supports_tags' => supports_cache_tags(),
        ];
    }
}

if (!function_exists('setting_exists')) {
    /**
     * التحقق من وجود إعداد
     *
     * @param string $key
     * @return bool
     */
    function setting_exists(string $key): bool
    {
        return settings_cache()->remember(
            "setting_exists_{$key}",
            now()->addDay(),
            fn() => Setting::where('key', $key)->exists()
        );
    }
}

if (!function_exists('adjust_brightness')) {
    /**
     * تعديل سطوع لون (Hex)
     *
     * @param string $hex
     * @param int $steps -255 to 255
     * @return string
     */
    function adjust_brightness(string $hex, int $steps): string
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = max(0, min(255, $r + $steps));
        $g = max(0, min(255, $g + $steps));
        $b = max(0, min(255, $b + $steps));

        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }
}

if (!function_exists('get_color_palette')) {
    /**
     * توليد مصفوفة تدرجات لونية من لون أساسي
     *
     * @param string $hex
     * @return array
     */
    function get_color_palette(string $hex): array
    {
        return [
            50  => adjust_brightness($hex, 200),
            100 => adjust_brightness($hex, 160),
            200 => adjust_brightness($hex, 120),
            300 => adjust_brightness($hex, 80),
            400 => adjust_brightness($hex, 40),
            500 => $hex,
            600 => adjust_brightness($hex, -40),
            700 => adjust_brightness($hex, -80),
            800 => adjust_brightness($hex, -120),
            900 => adjust_brightness($hex, -160),
        ];
    }
}
