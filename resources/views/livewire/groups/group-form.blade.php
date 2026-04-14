<div x-data="{ 
    open: false
}" 
     wire:ignore.self
     @open-modal.window="
         if ($event.detail[0] === 'group-form') { 
             open = true; 
         }
     " 
     @close-modal.window="
         if ($event.detail[0] === 'group-form') { 
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
                        {{ $isEditMode ? 'تعديل بيانات المجموعة' : 'إضافة مجموعة جديدة' }}
                    </h3>
                    <button type="button" @click="$wire.resetForm(); open = false" class="text-surface-400 hover:text-surface-600 dark:hover:text-surface-300 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-6 space-y-6 max-h-[calc(100vh-200px)] overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Code -->
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">كود المجموعة <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.blur="code" class="premium-input w-full" placeholder="أدخل كود المجموعة">
                            @error('code') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">اسم المجموعة <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.blur="name" class="premium-input w-full" placeholder="أدخل اسم المجموعة">
                            @error('name') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Project -->
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">المشروع <span class="text-red-500">*</span></label>
                            <select wire:model="project_id" class="premium-input w-full">
                                <option value="">اختر المشروع</option>
                                @foreach($this->availableProjects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                            @error('project_id') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Course -->
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">الدورة <span class="text-red-500">*</span></label>
                            <select wire:model="course_id" class="premium-input w-full">
                                <option value="">اختر الدورة</option>
                                @foreach($this->availableCourses as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                @endforeach
                            </select>
                            @error('course_id') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Trainer -->
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">المدرب <span class="text-red-500">*</span></label>
                            <select wire:model="trainer_id" class="premium-input w-full">
                                <option value="">اختر المدرب</option>
                                @foreach($this->availableTrainers as $trainer)
                                    <option value="{{ $trainer->id }}">{{ $trainer->user?->name ?? '-' }}</option>
                                @endforeach
                            </select>
                            @error('trainer_id') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Supervisor -->
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">المشرف</label>
                            <select wire:model="supervisor_id" class="premium-input w-full">
                                <option value="">اختر المشرف</option>
                                @foreach($this->availableSupervisors as $supervisor)
                                    <option value="{{ $supervisor->id }}">{{ $supervisor->name }}</option>
                                @endforeach
                            </select>
                            @error('supervisor_id') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">الحالة <span class="text-red-500">*</span></label>
                            <select wire:model="status" class="premium-input w-full">
                                <option value="planned">مخطط</option>
                                <option value="ongoing">جاري</option>
                                <option value="completed">مكتمل</option>
                                <option value="cancelled">ملغى</option>
                            </select>
                            @error('status') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Max Students -->
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">عدد الطلاب الأقصى <span class="text-red-500">*</span></label>
                            <input type="number" wire:model.blur="max_students" class="premium-input w-full" placeholder="أدخل العدد الأقصى" min="1">
                            @error('max_students') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">تاريخ البداية <span class="text-red-500">*</span></label>
                            <input type="date" wire:model="start_date" class="premium-input w-full" dir="ltr">
                            @error('start_date') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- End Date -->
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">تاريخ النهاية <span class="text-red-500">*</span></label>
                            <input type="date" wire:model="end_date" class="premium-input w-full" dir="ltr">
                            @error('end_date') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="md:col-span-2 flex items-center justify-between p-4 bg-surface-100 dark:bg-surface-700/50 rounded-xl border border-surface-200 dark:border-surface-600">
                            <div>
                                <h4 class="text-sm font-bold text-surface-900 dark:text-surface-100">حالة المجموعة</h4>
                                <p class="text-xs text-surface-500 dark:text-surface-400 mt-1">تحديد ما إذا كانت المجموعة نشطة أم معطلة.</p>
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
                            {{ $isEditMode ? 'حفظ التعديلات' : 'إضافة المجموعة' }}
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
