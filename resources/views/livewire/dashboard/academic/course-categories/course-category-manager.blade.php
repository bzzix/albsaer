<div>
    @section('title', 'تصنيفات الدورات')

    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-surface-900">تصنيفات الدورات</h2>
                <p class="text-surface-500 mt-1">إضافة وتعديل التصنيفات الأساسية لتنظيم الدورات والبرامج التدريبية</p>
            </div>
            
            <button wire:click="$dispatch('reset-category-form')" 
                wire:loading.attr="disabled"
                wire:target="$dispatch('reset-category-form')"
                class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold shadow-sm shadow-primary-500/20 transition-all flex items-center gap-2">
                <svg wire:loading.remove wire:target="$dispatch('open-modal', 'course-category-form')" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <svg wire:loading wire:target="$dispatch('open-modal', 'course-category-form')" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                إضافة تصنيف جديد
            </button>
        </div>

        <!-- DataTable -->
        <div class="glass-panel rounded-2xl border border-white/40 shadow-glass overflow-hidden">
            <div class="p-6">
                <livewire:dashboard.academic.course-categories.course-category-table />
            </div>
        </div>

        <!-- Form Modal Component -->
        <livewire:dashboard.academic.course-categories.course-category-form />
    </div>
</div>
