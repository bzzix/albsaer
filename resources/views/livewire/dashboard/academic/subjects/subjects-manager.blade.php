<div class="min-h-screen bg-surface-50 p-4 md:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-black text-surface-900 arabic-font">إدارة المواد الدراسية</h1>
                <p class="text-surface-500 mt-1 arabic-font">إضافة وإدارة المواد الدراسية المستقلة في النظام</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2">
                <!-- Duplicate Subject Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.away="open = false" 
                        wire:loading.attr="disabled"
                        wire:target="duplicateFromYear"
                        class="inline-flex items-center gap-2 px-4 py-3 bg-white text-surface-700 border border-surface-200 rounded-xl font-bold shadow-sm hover:bg-surface-50 transition-all arabic-font disabled:opacity-50">
                        <svg wire:loading.remove wire:target="duplicateFromYear" class="w-5 h-5 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <svg wire:loading wire:target="duplicateFromYear" class="w-5 h-5 animate-spin text-primary-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        استيراد من عام سابق
                    </button>
                    
                    <div x-show="open" x-transition class="absolute left-0 mt-2 w-64 bg-white rounded-xl shadow-lg border border-surface-100 z-50 p-2 text-right">
                        <p class="text-xs font-bold text-surface-500 px-3 py-2 border-b border-surface-100 mb-1 arabic-font">اختر العام المصدر لاستيراد مواده</p>
                        @foreach($academicYears->where('id', '!=', $activeYearId) as $year)
                            <button wire:click="duplicateFromYear({{ $year->id }}); open = false" class="w-full text-right px-3 py-2 text-sm text-surface-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg arabic-font transition-colors">
                                {{ $year->name }}
                            </button>
                        @endforeach
                        @if($academicYears->where('id', '!=', $activeYearId)->isEmpty())
                            <div class="px-3 py-2 text-xs text-surface-400 arabic-font text-center">لا توجد أعوام سابقة للنقل.</div>
                        @endif
                    </div>
                </div>

                <button wire:click="create" 
                    wire:loading.attr="disabled"
                    wire:target="create"
                    class="inline-flex items-center gap-2 px-5 py-3 bg-primary-600 text-white rounded-xl font-bold shadow-lg shadow-primary-500/30 hover:bg-primary-700 transition-all arabic-font disabled:opacity-50">
                    <svg wire:loading.remove wire:target="create" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <svg wire:loading wire:target="create" class="w-5 h-5 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    مادة جديدة
                </button>
            </div>
        </div>

        <!-- Filters & Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-surface-100 p-4 mb-6 relative">
            <div class="mb-4 flex items-center gap-4">
                <div class="flex-1">
                    <x-label value="تصنيف حسب العام :" class="arabic-font text-surface-500 mb-2 inline-block font-bold" />
                    <select wire:model.live="activeYearId" class="w-full md:w-1/3 px-4 py-2 bg-surface-50 border border-surface-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 arabic-font transition-all font-bold text-surface-700">
                        <option value="">— كافة المواد (تجاهل العام) —</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}">{{ $year->name }} {{ $year->is_active ? '(العام الحالي)' : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Global Loader for Table operations -->
                <div wire:loading wire:target="activeYearId" class="flex items-center gap-2 text-primary-600 font-bold arabic-font text-xs">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    جاري التحميل...
                </div>
            </div>
            
            <div class="mt-4">
                <livewire:dashboard.academic.subjects.subjects-table :activeYearId="$activeYearId" />
            </div>
        </div>

        <!-- Modal -->
        <x-modal wire:model="showModal" maxWidth="md">
            <div class="p-8">
                <h3 class="text-2xl font-black text-surface-900 arabic-font mb-6 pb-4 border-b border-surface-100">
                    {{ $subjectId ? 'تعديل بيانات مادة' : 'إضافة مادة جديدة' }}
                </h3>

                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <x-label value="اسم المادة" class="arabic-font mb-2" />
                        <x-input wire:model="name" type="text" class="w-full arabic-font" placeholder="مثال: لغة إنجليزية مستوى أول" />
                        <x-input-error for="name" class="mt-2" />
                    </div>

                    <div>
                        <x-label value="وصف المادة (اختياري)" class="arabic-font mb-2" />
                        <textarea wire:model="description" rows="2" class="w-full border-surface-300 focus:border-primary-500 focus:ring-primary-500 rounded-xl shadow-sm arabic-font" placeholder="نبذة قصيرة عن محتوى المادة"></textarea>
                        <x-input-error for="description" class="mt-2" />
                    </div>

                    @if(!$subjectId && $activeYearId)
                        <div class="pt-2">
                            <x-label value="تعيين مدربين للمادة (تلقائياً)" class="arabic-font mb-2" />
                            <div class="max-h-32 overflow-y-auto p-2 bg-surface-50 rounded-xl border border-surface-200">
                                @forelse($availableInstructors ?? [] as $inst)
                                    <label class="flex items-center gap-2 cursor-pointer p-1.5 hover:bg-white rounded-lg transition-colors">
                                        <input type="checkbox" wire:model="assignedInstructors" value="{{ $inst->id }}" class="rounded text-primary-600 border-surface-300">
                                        <span class="text-xs font-bold text-surface-700 arabic-font">{{ $inst->user->name }}</span>
                                    </label>
                                @empty
                                    <p class="text-xs text-surface-400 p-2 arabic-font">لا يوجد مدربين معينين في هذا العام الأكاديمي.</p>
                                @endforelse
                            </div>
                        </div>
                    @endif

                    @if($subjectId)
                        <div class="p-3 bg-surface-50 rounded-xl border border-surface-200">
                            <p class="text-xs font-bold text-surface-500 arabic-font mb-1">كود المادة</p>
                            <p class="text-sm font-mono text-surface-700">{{ $subjectCode }}</p>
                        </div>
                    @endif

                    <div class="flex gap-3 pt-4 border-t border-surface-100">
                        <x-button type="submit" 
                            wire:loading.attr="disabled"
                            wire:target="save"
                            class="flex-1 justify-center py-3 bg-primary-600 hover:bg-primary-700 arabic-font">
                            <svg wire:loading wire:target="save" class="animate-spin -mr-1 ml-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            {{ $subjectId ? 'حفظ التعديلات' : 'إنشاء المادة' }}
                        </x-button>
                        <x-secondary-button type="button" wire:click="$set('showModal', false)" class="px-6 py-3 arabic-font">
                            إلغاء
                        </x-secondary-button>
                    </div>
                </form>
            </div>
        </x-modal>
    </div>
</div>
