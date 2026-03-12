<div>
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-r-4 border-red-500 rounded-xl">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div class="text-sm font-bold text-red-800">يوجد أخطاء في البيانات المدخلة، يرجى مراجعة الحقول الحمراء.</div>
            </div>
        </div>
    @endif

    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-surface-900">إعدادات التصميم</h2>
            <p class="text-surface-500 mt-1">تخصيص المظهر العام والألوان والأنماط البصرية</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="resetDefaults" wire:loading.attr="disabled" wire:confirm="هل أنت متأكد من استعادة كافة الإعدادات الافتراضية؟"
                class="px-6 py-2.5 bg-surface-100 hover:bg-surface-200 text-surface-600 rounded-xl font-bold transition-all flex items-center gap-2">
                <svg wire:loading.remove wire:target="resetDefaults" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <div wire:loading wire:target="resetDefaults">
                    <svg class="animate-spin h-5 w-5 text-surface-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <span>الوضع الافتراضي</span>
            </button>

            <button wire:click="save" wire:loading.attr="disabled" wire:target="save" wire:key="save-design-btn"
                class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold shadow-lg shadow-primary-500/30 transition-all flex items-center gap-2">
                <svg wire:loading.remove wire:target="save" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <div wire:loading wire:target="save">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <span>حفظ الإعدادات</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Colors & Themes -->
        <div class="space-y-6">
            <div class="glass-panel p-6 rounded-2xl border border-white/40 shadow-glass">
                <h3 class="text-lg font-bold text-surface-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                    الألوان الأساسية
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <label class="text-sm font-bold text-surface-700 block">اللون الرئيسي (Primary)</label>
                        <div class="flex items-center gap-3 p-2 rounded-xl premium-input">
                            <input type="color" wire:model="primary_color" class="w-10 h-10 rounded-lg border-none cursor-pointer bg-transparent">
                            <input type="text" wire:model="primary_color" class="flex-1 bg-transparent border-none text-sm font-mono focus:ring-0">
                        </div>
                        @error('primary_color') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-4">
                        <label class="text-sm font-bold text-surface-700 block">اللون الثانوي (Secondary)</label>
                        <div class="flex items-center gap-3 p-2 rounded-xl premium-input">
                            <input type="color" wire:model="secondary_color" class="w-10 h-10 rounded-lg border-none cursor-pointer bg-transparent">
                            <input type="text" wire:model="secondary_color" class="flex-1 bg-transparent border-none text-sm font-mono focus:ring-0">
                        </div>
                        @error('secondary_color') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-8 p-4 bg-primary-50 rounded-xl border border-primary-100">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-primary-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-xs text-primary-800 leading-relaxed">
                            ملاحظة: تغيير الألوان سيؤثر على القائمة الجانبية، الأزرار، والروابط في كافة أنحاء لوحة التحكم.
                        </p>
                    </div>
                </div>
            </div>

            <div class="glass-panel p-6 rounded-2xl border border-white/40 shadow-glass">
                <h3 class="text-lg font-bold text-surface-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    الوضع الليلي (Dark Mode)
                </h3>

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-surface-700">تفعيل الوضع الليلي</p>
                        <p class="text-xs text-surface-500 mt-1">تغيير واجهة النظام للون الداكن</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="dark_mode_enabled" class="sr-only peer">
                        <div class="w-11 h-6 bg-surface-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Typography & Effects -->
        <div class="space-y-6">
            <div class="glass-panel p-6 rounded-2xl border border-white/40 shadow-glass">
                <h3 class="text-lg font-bold text-surface-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    الخطوط والحواف
                </h3>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-surface-700">الخط الأساسي</label>
                        <select wire:model="font_family" class="w-full px-4 py-2.5 rounded-xl premium-input transition-all text-sm">
                            <option value="Cairo">Cairo (الافتراضي)</option>
                            <option value="Inter">Inter</option>
                            <option value="Tajawal">Tajawal</option>
                            <option value="Almarai">Almarai</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-surface-700">نصف قطر الحواف (Border Radius)</label>
                        <div class="grid grid-cols-4 gap-3">
                            @foreach(['none', 'md', 'lg', 'xl', '2xl', '3xl'] as $radius)
                                <button wire:click="$set('border_radius', '{{ $radius }}')" 
                                    class="px-3 py-2 rounded-lg text-xs font-bold transition-all {{ $border_radius == $radius ? 'bg-primary-600 text-white' : 'bg-surface-100 text-surface-600 hover:bg-surface-200' }}">
                                    {{ strtoupper($radius) }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-panel p-6 rounded-2xl border border-white/40 shadow-glass">
                <h3 class="text-lg font-bold text-surface-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    تأثيرات الزجاج (Glassmorphism)
                </h3>

                <div class="space-y-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-bold text-surface-700">قوة التأثير</label>
                            <span class="text-xs font-bold text-primary-600">{{ strtoupper($glass_intensity) }}</span>
                        </div>
                        <input type="range" wire:model="glass_intensity" min="low" max="high" step="1" 
                               class="w-full h-2 bg-surface-200 rounded-lg appearance-none cursor-pointer accent-primary-600">
                        <div class="flex justify-between text-[10px] text-surface-400 font-bold uppercase">
                            <span>خفيف</span>
                            <span>متوسط</span>
                            <span>قوي</span>
                        </div>
                    </div>

                    <div class="p-4 rounded-{{ $border_radius }} border border-white/40 bg-white/20 backdrop-blur-md shadow-glass text-center">
                        <span class="text-xs font-bold text-surface-700 italic">معاينة تأثير الزجاج والحواف</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
