<div x-data="{ 
    open: false
}" 
     wire:ignore.self
     @open-modal.window="
         if ($event.detail[0] === 'course-category-form') { 
             open = true; 
         }
     " 
     @close-modal.window="
         if ($event.detail[0] === 'course-category-form') { 
             open = false; 
             $wire.resetForm();
         }
     " 
     @keydown.escape.window="open = false; $wire.resetForm();"
     class="fixed inset-0 z-50 overflow-y-auto pointer-events-none" 
     :class="{ 'pointer-events-auto': open }">
    
    <!-- Backdrop -->
    <div x-show="open" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>

    <!-- Modal Panel -->
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0 pointer-events-none">
        <div x-show="open"
             style="display: none;"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             class="pointer-events-auto relative bg-white dark:bg-surface-800 rounded-2xl text-start overflow-hidden shadow-2xl border border-surface-200 dark:border-surface-700 transform transition-all sm:my-8 sm:w-full sm:max-w-2xl">
            
            <form @submit.prevent="$wire.save()">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-surface-900 dark:text-surface-50">
                        {{ $isEditMode ? 'تعديل التصنيف' : 'إضافة تصنيف جديد' }}
                    </h3>
                    <button type="button" @click="$wire.resetForm(); open = false" class="text-surface-400 hover:text-surface-600 dark:hover:text-surface-300 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-6 space-y-6 max-h-[calc(100vh-200px)] overflow-y-auto">
                    <div class="space-y-6">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">اسم التصنيف <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.blur="name" class="premium-input w-full" placeholder="مثال: البرمجة وتطوير المواقع">
                            @error('name') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">الوصف</label>
                            <textarea wire:model.blur="description" rows="3" class="premium-input w-full" placeholder="وصف لمحتوى هذا التصنيف..."></textarea>
                            @error('description') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center justify-between p-4 bg-surface-100 dark:bg-surface-700/50 rounded-xl border border-surface-200 dark:border-surface-600">
                            <div>
                                <h4 class="text-sm font-bold text-surface-900 dark:text-surface-100">نشاط التصنيف</h4>
                                <p class="text-xs text-surface-500 dark:text-surface-400 mt-1">تحديد ما إذا كان هذا التصنيف متاحاً للإستخدام.</p>
                            </div>
                            <x-table-toggle wire:model="is_active" :active="$is_active" />
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-surface-50 dark:bg-surface-800/50 border-t border-surface-200 dark:border-surface-700 flex items-center justify-end gap-3">
                    <button type="button" @click="$wire.resetForm(); open = false" class="px-4 py-2 bg-white dark:bg-surface-700 border border-surface-200 dark:border-surface-600 text-surface-700 dark:text-surface-200 rounded-xl font-medium hover:bg-surface-50 dark:hover:bg-surface-600 transition-colors">
                        إلغاء
                    </button>
                    <button type="submit" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold shadow-sm shadow-primary-500/20 transition-all flex items-center justify-center gap-2 min-w-[120px]">
                        <span wire:loading.remove wire:target="save">
                            {{ $isEditMode ? 'حفظ التعديلات' : 'إضافة التصنيف' }}
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
