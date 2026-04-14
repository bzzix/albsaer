<div class="p-8 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-4xl font-black text-surface-900 arabic-font tracking-tight">إدارة فترات الدراسة</h1>
            <p class="text-surface-500 mt-2 arabic-font text-lg">قم بتعريف قوالب فترات الدوام (المقاييس الزمنية والحصص) ليتم استخدامها في توليد الجداول.</p>
        </div>
        
        <button wire:click="openCreateModal" class="inline-flex items-center gap-3 px-6 py-3.5 bg-primary-600 text-white rounded-2xl font-bold shadow-lg shadow-primary-500/30 hover:bg-primary-700 transition-all transform hover:-translate-y-1 group">
            <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <span class="arabic-font">إضافة فترة جديدة</span>
        </button>
    </div>

    <!-- DataTable Section -->
    <div class="glass-panel rounded-[2.5rem] border border-white/40 shadow-glass overflow-hidden">
        <div class="p-8">
            <livewire:dashboard.academic.schedules.study-period-table />
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <x-modal wire:model="showCreateModal" maxWidth="2xl">
        <div class="p-8">
            <h3 class="text-2xl font-black text-surface-900 arabic-font mb-6 border-b pb-4">
                {{ $isEditing ? 'تعديل فترة دراسية' : 'إضافة فترة دراسية جديدة' }}
            </h3>

            <form wire:submit.prevent="save" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <x-label value="اسم الفترة (مثلاً: الفترة الصباحية)" class="arabic-font mb-1" />
                        <x-input wire:model="form.name" class="w-full px-4 py-3 rounded-2xl" placeholder="أدخل اسم الفترة..." />
                        @error('form.name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <x-label value="العام الدراسي" class="arabic-font mb-1" />
                        <select wire:model="form.academic_year_id" class="w-full px-4 py-3 bg-surface-50 border-surface-200 rounded-2xl focus:ring-primary-500">
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-label value="وقت بداية الدوام" class="arabic-font mb-1" />
                        <x-input type="time" wire:model="form.start_time" class="w-full px-4 py-3 rounded-2xl" />
                    </div>

                    <div>
                        <x-label value="عدد الحصص اليومية" class="arabic-font mb-1" />
                        <x-input type="number" wire:model="form.sessions_count" class="w-full px-4 py-3 rounded-2xl" />
                    </div>

                    <div>
                        <x-label value="مدة الحصة (بالدقائق)" class="arabic-font mb-1" />
                        <x-input type="number" wire:model="form.session_duration" class="w-full px-4 py-3 rounded-2xl" />
                    </div>

                    <div>
                        <x-label value="مدة الاستراحة بين الحصص (بالدقائق)" class="arabic-font mb-1" />
                        <x-input type="number" wire:model="form.break_duration" class="w-full px-4 py-3 rounded-2xl" />
                    </div>

                    <div class="col-span-2">
                        <x-label value="أيام الدراسة الأسبوعية" class="arabic-font mb-3" />
                        <div class="flex flex-wrap gap-3">
                            @foreach($daysOfWeek as $key => $label)
                                <label class="inline-flex items-center p-3 bg-surface-50 rounded-2xl border border-surface-200 cursor-pointer hover:bg-primary-50 transition-all select-none">
                                    <input type="checkbox" wire:model="form.active_days" value="{{ $key }}" class="rounded-lg text-primary-600 border-surface-300 focus:ring-primary-500">
                                    <span class="mr-3 text-sm font-bold text-surface-700 arabic-font">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('form.active_days') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex gap-4 pt-6 border-t mt-8">
                    <button type="submit" class="flex-1 bg-primary-600 text-white py-4 rounded-2xl font-black arabic-font hover:bg-primary-700 shadow-xl shadow-primary-500/30 transition-all">
                        {{ $isEditing ? 'تحديث البيانات' : 'حفظ الفترة الدراسية' }}
                    </button>
                    <button type="button" wire:click="$set('showCreateModal', false)" class="px-8 bg-surface-100 text-surface-700 py-4 rounded-2xl font-bold arabic-font hover:bg-surface-200 transition-all text-center">إلغاء</button>
                </div>
            </form>
        </div>
    </x-modal>
</div>
