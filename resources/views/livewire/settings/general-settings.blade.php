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
            <h2 class="text-2xl font-bold text-surface-900">الإعدادات العامة</h2>
            <p class="text-surface-500 mt-1">تخصيص الهوية البصرية والمعلومات الأساسية للمنصة</p>
        </div>
        <button wire:click="save" wire:loading.attr="disabled" wire:target="save" wire:key="save-general-btn"
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
            <span>حفظ التغييرات</span>
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="glass-panel p-6 rounded-2xl border border-white/40 shadow-glass">
                <h3 class="text-lg font-bold text-surface-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    المعلومات الأساسية
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-surface-700">اسم المنصة (بالعربي)</label>
                        <input type="text" wire:model="site_name" 
                            class="w-full px-4 py-2.5 rounded-xl premium-input transition-all">
                        @error('site_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-surface-700">اسم المنصة (بالإنجليزي)</label>
                        <input type="text" wire:model="site_name_en" 
                               class="w-full px-4 py-2.5 rounded-xl premium-input transition-all text-left dir-ltr">
                        @error('site_name_en') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label class="text-sm font-bold text-surface-700">وصف المنصة</label>
                        <textarea wire:model="site_description" rows="3"
                                  class="w-full px-4 py-2.5 rounded-xl premium-input transition-all"></textarea>
                        @error('site_description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-surface-700">البريد الإلكتروني للإشعارات</label>
                        <input type="email" wire:model="site_email" 
                               class="w-full px-4 py-2.5 rounded-xl premium-input transition-all">
                        @error('site_email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-surface-700">رقم الهاتف</label>
                        <input type="text" wire:model="site_phone" 
                               class="w-full px-4 py-2.5 rounded-xl premium-input transition-all">
                        @error('site_phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label class="text-sm font-bold text-surface-700">العنوان الفيزيائي</label>
                        <input type="text" wire:model="site_address" 
                               class="w-full px-4 py-2.5 rounded-xl premium-input transition-all">
                        @error('site_address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- SEO Settings -->
            <div class="glass-panel p-6 rounded-2xl border border-white/40 shadow-glass">
                <h3 class="text-lg font-bold text-surface-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    تحسين محركات البحث (SEO)
                </h3>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-surface-700">الكلمات المفتاحية (Keywords)</label>
                    <input type="text" wire:model="meta_keywords" placeholder="مثال: تعليم، معهد، تدريب"
                           class="w-full px-4 py-2.5 rounded-xl premium-input transition-all">
                    @error('meta_keywords') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    <p class="text-[11px] text-surface-500 mt-1 italic">افصل بين الكلمات بفاصلة (,)</p>
                </div>
            </div>
        </div>

        <!-- Sidebar Options -->
        <div class="space-y-8">
            <!-- Branding -->
            <div class="glass-panel p-6 rounded-2xl border border-white/40 shadow-glass">
                <h3 class="text-lg font-bold text-surface-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    الهوية البصرية
                </h3>

                <div class="space-y-6">
                    <!-- Logo -->
                    <div class="space-y-3">
                        <label class="text-sm font-bold text-surface-700 block">شعار المنصة</label>
                        <div class="flex items-center gap-4">
                            <div class="h-16 w-16 rounded-xl bg-surface-100 flex items-center justify-center overflow-hidden border border-surface-200">
                                @if ($new_logo)
                                    <img src="{{ $new_logo->temporaryUrl() }}" class="h-full w-full object-contain p-2">
                                @elseif ($site_logo)
                                    <img src="{{ $site_logo }}" class="h-full w-full object-contain p-2">
                                @else
                                    <svg class="w-8 h-8 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <label class="cursor-pointer block px-4 py-2 bg-white border border-surface-200 text-sm font-bold text-surface-700 rounded-lg hover:bg-surface-50 text-center transition-all">
                                    <span>تغيير الشعار</span>
                                    <input type="file" wire:model="new_logo" class="hidden">
                                </label>
                            </div>
                        </div>
                        @error('new_logo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Display Mode -->
                    <div class="space-y-3 pt-4 border-t border-surface-100">
                        <label class="text-sm font-bold text-surface-700 block">طريقة العرض في الترويسة</label>
                        <select wire:model="site_display_mode" class="w-full px-4 py-2 rounded-lg bg-white border border-surface-200 text-sm font-bold text-surface-700 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none">
                            <option value="both">الشعار واسم المنصة</option>
                            <option value="logo">الشعار فقط</option>
                            <option value="name">اسم المنصة فقط</option>
                        </select>
                        <p class="text-[11px] text-surface-500 italic">حدد ما سيتم إظهاره في الشريط الجانبي وصفحات الدخول.</p>
                    </div>

                    <!-- Favicon -->
                    <div class="space-y-3">
                        <label class="text-sm font-bold text-surface-700 block">أيقونة المتصفح (Favicon)</label>
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 rounded-lg bg-surface-100 flex items-center justify-center overflow-hidden border border-surface-200">
                                @if ($new_favicon)
                                    <img src="{{ $new_favicon->temporaryUrl() }}" class="h-full w-full object-contain p-2">
                                @elseif ($site_favicon)
                                    <img src="{{ $site_favicon }}" class="h-full w-full object-contain p-2">
                                @else
                                    <svg class="w-5 h-5 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <label class="cursor-pointer block px-4 py-2 bg-white border border-surface-200 text-sm font-bold text-surface-700 rounded-lg hover:bg-surface-50 text-center transition-all">
                                    <span>تغيير الأيقونة</span>
                                    <input type="file" wire:model="new_favicon" class="hidden">
                                </label>
                            </div>
                        </div>
                        @error('new_favicon') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Regional Settings -->
            <div class="glass-panel p-6 rounded-2xl border border-white/40 shadow-glass">
                <h3 class="text-lg font-bold text-surface-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    الإعدادات الإقليمية
                </h3>

                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-surface-700">المنطقة الزمنية</label>
                        <select wire:model="timezone" class="w-full px-4 py-2.5 rounded-xl premium-input transition-all text-sm">
                            <option value="Asia/Riyadh">Asia/Riyadh (+03:00)</option>
                            <option value="UTC">UTC</option>
                            <option value="Europe/London">Europe/London</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-surface-700">اللغة الافتراضية</label>
                        <select wire:model="locale" class="w-full px-4 py-2.5 rounded-xl premium-input transition-all text-sm">
                            <option value="ar">العربية (Arabic)</option>
                            <option value="en">الإنجليزية (English)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
