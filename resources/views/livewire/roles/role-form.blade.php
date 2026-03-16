<div x-data="{ open: false }" 
     x-show="open" 
     @open-modal.window="if ($event.detail[0] === 'role-form') open = true" 
     @close-modal.window="if ($event.detail[0] === 'role-form') open = false" 
     @keydown.escape.window="$wire.resetForm(); open = false"
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    
    <!-- Backdrop -->
    <div x-show="open" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-surface-900/60 backdrop-blur-sm transition-opacity"></div>

    <!-- Modal Panel -->
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0 pointer-events-none">
        <div x-show="open" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             class="pointer-events-auto relative bg-white dark:bg-surface-800 rounded-2xl text-start overflow-hidden shadow-2xl border border-surface-200 dark:border-surface-700 transform transition-all sm:my-8 sm:w-full sm:max-w-2xl">
            
            <form wire:submit="save">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-surface-900 dark:text-surface-50">
                        {{ $isEditMode ? 'تعديل الصلاحيات والدور' : 'إضافة دور جديد' }}
                    </h3>
                    <button type="button" @click="$wire.resetForm(); open = false" class="text-surface-400 hover:text-surface-600 dark:hover:text-surface-300 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">اسم الدور (باللغة الإنجليزية) <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="name" class="premium-input w-full text-left" dir="ltr" placeholder="مثال: custom-role-name" {{ in_array($name, ['super-admin', 'admin', 'teacher', 'student', 'parent']) ? 'readonly' : '' }}>
                            <p class="text-xs text-surface-500 mt-1">اسم فريد للنظام (أحرف صغيرة إنجليزية).</p>
                            @error('name') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">الاسم المعروض (بالعربية) <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="display_name" class="premium-input w-full" placeholder="مثال: مدير المبيعات">
                            <p class="text-xs text-surface-500 mt-1">الاسم الذي سيظهر للمستخدمين في القوائم.</p>
                            @error('display_name') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">لون الدور</label>
                        <div class="flex items-center gap-3">
                            <input type="color" wire:model="color" class="w-12 h-12 rounded-lg border-2 border-surface-200 dark:border-surface-700 cursor-pointer p-1 bg-white dark:bg-surface-800">
                            <input type="text" wire:model="color" class="premium-input flex-1" dir="ltr" placeholder="#000000">
                            <div class="px-3 py-1.5 rounded-lg border font-bold text-xs" :style="'background-color: ' + $wire.color + '20; color: ' + $wire.color + '; border-color: ' + $wire.color + '40'">
                                معالجة العرض
                            </div>
                        </div>
                        @error('color') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-3">الصلاحيات المتاحة <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 max-h-60 overflow-y-auto p-4 bg-white dark:bg-surface-800/50 rounded-xl border border-surface-200 dark:border-surface-700 shadow-sm">
                            @forelse($this->availablePermissions as $permission)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->name }}" class="peer sr-only">
                                        <div class="w-5 h-5 border-2 border-surface-300 dark:border-surface-600 rounded bg-white dark:bg-surface-800 peer-checked:bg-primary-600 peer-checked:border-primary-600 transition-colors flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    </div>
                                    <span class="text-sm text-surface-700 dark:text-surface-300 font-medium group-hover:text-primary-600 transition-colors">{{ $permission->name }}</span>
                                </label>
                            @empty
                                <div class="col-span-full text-center text-sm text-surface-50 py-4">لا توجد صلاحيات لعرضها، يرجى تشغيل (Seeders).</div>
                            @endforelse
                        </div>
                        @error('selectedPermissions') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-surface-50 dark:bg-surface-800/50 border-t border-surface-200 dark:border-surface-700 flex items-center justify-end gap-3">
                    <button type="button" @click="$wire.resetForm(); open = false" class="px-4 py-2 bg-white dark:bg-surface-700 border border-surface-200 dark:border-surface-600 text-surface-700 dark:text-surface-200 rounded-xl font-medium hover:bg-surface-50 dark:hover:bg-surface-600 transition-colors">
                        إلغاء
                    </button>
                    <button type="submit" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold shadow-sm shadow-primary-500/20 transition-all flex items-center justify-center gap-2 min-w-[120px]">
                        <span wire:loading.remove wire:target="save">
                            {{ $isEditMode ? 'حفظ التعديلات' : 'إضافة الدور' }}
                        </span>
                        <span wire:loading wire:target="save">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
