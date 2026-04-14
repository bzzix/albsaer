<div class="space-y-4">
    <!-- Toolbar -->
    <div class="flex flex-col sm:flex-row justify-between gap-4">
        <div class="relative w-full sm:w-96">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="ابحث عن اسم أو كود الدورة..." class="premium-input w-full pl-10 pr-4 py-2">
            <svg class="absolute left-3 top-2.5 h-5 w-5 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800">
        <table class="w-full text-sm text-right">
            <thead class="text-xs text-surface-500 bg-surface-50 dark:bg-surface-800/50 uppercase border-b border-surface-200 dark:border-surface-700">
                <tr>
                    <th scope="col" class="px-6 py-4 font-bold whitespace-nowrap">الدورة</th>
                    <th scope="col" class="px-6 py-4 font-bold whitespace-nowrap">الكود</th>
                    <th scope="col" class="px-6 py-4 font-bold whitespace-nowrap">التصنيفات</th>
                    <th scope="col" class="px-6 py-4 font-bold whitespace-nowrap px-2">المدرب</th>
                    <th scope="col" class="px-6 py-4 font-bold text-center whitespace-nowrap">الحالة</th>
                    <th scope="col" class="px-6 py-4 font-bold text-left whitespace-nowrap">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-200 dark:divide-surface-700">
                @forelse($courses as $course)
                    <tr class="hover:bg-surface-50/50 dark:hover:bg-surface-700/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-surface-900 dark:text-surface-100 min-w-[200px]">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-white text-xs shadow-sm shadow-primary-500/20">
                                    {{ mb_substr($course->name, 0, 2) }}
                                </div>
                                <div>
                                    <p>{{ $course->name }}</p>
                                    <p class="text-[10px] text-surface-400 font-normal mt-0.5">تاريخ الإضافة: {{ $course->created_at->format('Y-m-d') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs">
                            <span class="px-2 py-1 bg-surface-100 dark:bg-surface-700 text-surface-600 dark:text-surface-300 rounded-md">
                                {{ $course->code }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @forelse($course->categories as $category)
                                    <span class="px-2 py-0.5 bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-lg text-[10px] font-bold">
                                        {{ $category->name }}
                                    </span>
                                @empty
                                    <span class="text-surface-400 text-[11px]">بدون تصنيف</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs">
                            {{ $course->instructor->name ?? 'غير محدد' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusMap = [
                                    'draft' => ['label' => 'مسودة', 'class' => 'bg-yellow-100 text-yellow-700 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-400'],
                                    'active' => ['label' => 'نشط', 'class' => 'bg-green-100 text-green-700 border-green-200 dark:bg-green-900/30 dark:text-green-400'],
                                    'completed' => ['label' => 'مكتمل', 'class' => 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400'],
                                    'cancelled' => ['label' => 'ملغى', 'class' => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-400'],
                                ];
                                $status = $statusMap[$course->status] ?? ['label' => $course->status, 'class' => 'bg-surface-100 text-surface-700 border-surface-200'];
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $status['class'] }}">
                                {{ $status['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-left">
                            <div class="flex items-center justify-end gap-2">
                                <!-- Builder Button -->
                                <a href="{{ route('dashboard.academic.courses.builder', $course->id) }}" 
                                    class="p-2 text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 hover:text-primary-700 transition-colors tooltip-trigger relative group" title="فتح باني الدورات">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                                    </svg>
                                </a>

                                <!-- Delete Button -->
                                <button wire:click="confirmDelete({{ $course->id }})" 
                                    wire:loading.attr="disabled"
                                    class="p-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 hover:text-red-700 transition-colors tooltip-trigger relative group" title="حذف">
                                    <svg wire:loading.remove wire:target="confirmDelete({{ $course->id }})" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <svg wire:loading wire:target="confirmDelete({{ $course->id }})" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-surface-500 dark:text-surface-400">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 mb-4 text-surface-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                                <p class="text-lg font-medium text-surface-900 dark:text-surface-100">لا توجد دورات</p>
                                <p class="text-sm">ابدأ بإنشاء أول دورة تدريبية للانطلاق في بناء المحتوى.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $courses->links() }}
    </div>
</div>
