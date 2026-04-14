<div x-data="{ open: false }"
     wire:ignore.self
     @open-modal.window="if ($event.detail[0] === 'group-form') { open = true; }"
     @close-modal.window="if ($event.detail[0] === 'group-form') { open = false; $wire.resetForm(); }"
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
         class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>

    <!-- Modal Panel -->
    <div class="flex items-center justify-center min-h-screen px-4 py-8 sm:p-0 pointer-events-none">
        <div x-show="open"
             style="display: none;"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
             class="pointer-events-auto relative bg-white dark:bg-surface-800 rounded-3xl text-start overflow-hidden shadow-2xl border border-surface-100 dark:border-surface-700 transform transition-all sm:my-8 sm:w-full sm:max-w-xl w-full">

            <form @submit.prevent="$wire.save()">

                <!-- ====== Header ====== -->
                <div class="px-8 pt-7 pb-6 border-b border-surface-100 dark:border-surface-700 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-primary-100 to-primary-200 text-primary-600 flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-surface-900 dark:text-surface-50 arabic-font leading-tight">
                                {{ $isEditMode ? 'تعديل بيانات المجموعة' : 'إضافة مجموعة جديدة' }}
                            </h3>
                            <p class="text-xs text-surface-400 arabic-font mt-0.5">
                                {{ $isEditMode ? 'قم بتحديث بيانات المجموعة' : 'أدخل بيانات المجموعة الجديدة' }}
                            </p>
                        </div>
                    </div>
                    <button type="button" @click="$wire.resetForm(); open = false"
                        class="w-8 h-8 flex items-center justify-center rounded-xl text-surface-400 hover:text-surface-700 hover:bg-surface-100 transition-all">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- ====== Body ====== -->
                <div class="px-8 py-7 space-y-6 max-h-[calc(100vh-260px)] overflow-y-auto">

                    <!-- 1. الكود والاسم -->
                    <div class="grid grid-cols-5 gap-4">
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-surface-500 uppercase tracking-widest mb-2 arabic-font">
                                كود المجموعة <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model.blur="code"
                                class="premium-input w-full rounded-2xl text-sm"
                                placeholder="GRP-001" dir="ltr">
                            @error('code')
                                <p class="mt-1.5 text-xs text-red-500 font-bold arabic-font">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-span-3">
                            <label class="block text-xs font-bold text-surface-500 uppercase tracking-widest mb-2 arabic-font">
                                اسم المجموعة <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model.blur="name"
                                class="premium-input w-full rounded-2xl arabic-font text-sm"
                                placeholder="أدخل اسم المجموعة">
                            @error('name')
                                <p class="mt-1.5 text-xs text-red-500 font-bold arabic-font">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- 2. العام الدراسي -->
                    <div>
                        <label class="block text-xs font-bold text-surface-500 uppercase tracking-widest mb-2 arabic-font">
                            العام الدراسي <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="academic_year_id"
                            class="premium-input w-full rounded-2xl arabic-font text-sm">
                            <option value="">— اختر العام الدراسي —</option>
                            @foreach($this->availableYears as $year)
                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                            @endforeach
                        </select>
                        @error('academic_year_id')
                            <p class="mt-1.5 text-xs text-red-500 font-bold arabic-font">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 3. المشروع -->
                    <div>
                        <label class="block text-xs font-bold text-surface-500 uppercase tracking-widest mb-2 arabic-font">
                            المشروع <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="project_id"
                            class="premium-input w-full rounded-2xl arabic-font text-sm"
                            @disabled(!$academic_year_id)>
                            <option value="">— اختر المشروع —</option>
                            @foreach($this->availableProjects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                        @if(!$academic_year_id)
                            <p class="mt-1.5 text-[11px] text-surface-400 arabic-font">اختر العام الدراسي أولاً لظهور المشاريع.</p>
                        @elseif($academic_year_id && count($this->availableProjects) === 0)
                            <p class="mt-1.5 text-[11px] text-amber-600 font-bold arabic-font">⚠ لا توجد مشاريع نشطة لهذا العام.</p>
                        @endif
                        @error('project_id')
                            <p class="mt-1.5 text-xs text-red-500 font-bold arabic-font">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 4. الحد الأقصى للطلاب -->
                    <div>
                        <label class="block text-xs font-bold text-surface-500 uppercase tracking-widest mb-2 arabic-font">
                            الحد الأقصى للطلاب <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model.blur="max_students"
                            class="premium-input w-full rounded-2xl text-sm"
                            placeholder="30" min="1" dir="ltr">
                        @error('max_students')
                            <p class="mt-1.5 text-xs text-red-500 font-bold arabic-font">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 5. حالة التفعيل -->
                    <div class="flex items-center justify-between px-5 py-4 bg-surface-50 dark:bg-surface-700/40 rounded-2xl border border-surface-200/80 dark:border-surface-600">
                        <div>
                            <h4 class="text-sm font-black text-surface-900 dark:text-surface-100 arabic-font">حالة المجموعة</h4>
                            <p class="text-xs text-surface-400 arabic-font mt-0.5">تحديد ما إذا كانت المجموعة نشطة حالياً</p>
                        </div>
                        <x-table-toggle wire:model="is_active" :active="$is_active" />
                    </div>
                </div>

                <!-- ====== Footer ====== -->
                <div class="px-8 py-5 bg-surface-50/80 dark:bg-surface-800/50 border-t border-surface-100 dark:border-surface-700 flex items-center justify-end gap-3">
                    <button type="button" @click="$wire.resetForm(); open = false"
                        class="px-5 py-2.5 bg-white dark:bg-surface-700 border border-surface-200 dark:border-surface-600 text-surface-700 dark:text-surface-300 rounded-xl font-bold hover:bg-surface-100 transition-colors arabic-font text-sm">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-7 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-black shadow-lg shadow-primary-500/25 transition-all flex items-center gap-2 arabic-font text-sm min-w-[140px] justify-center hover:-translate-y-0.5">
                        <span wire:loading.remove wire:target="save">
                            {{ $isEditMode ? 'حفظ التعديلات' : 'إضافة المجموعة' }}
                        </span>
                        <span wire:loading wire:target="save">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
