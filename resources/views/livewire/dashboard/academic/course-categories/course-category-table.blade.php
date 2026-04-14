<div class="space-y-4">
    <!-- Toolbar -->
    <div class="flex flex-col sm:flex-row justify-between gap-4">
        <div class="relative w-full sm:w-96">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="ابحث عن تصنيف الدورة..." class="premium-input w-full pl-10 pr-4 py-2">
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
                    <th scope="col" class="px-6 py-4 font-bold">اسم التصنيف</th>
                    <th scope="col" class="px-6 py-4 font-bold">الوصف</th>
                    <th scope="col" class="px-6 py-4 font-bold">الرابط الوهمي (Slug)</th>
                    <th scope="col" class="px-6 py-4 font-bold text-center">الحالة</th>
                    <th scope="col" class="px-6 py-4 font-bold text-left">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-200 dark:divide-surface-700">
                @forelse($categories as $category)
                    <tr class="hover:bg-surface-50/50 dark:hover:bg-surface-700/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-surface-900 dark:text-surface-100 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center font-bold">
                                {{ mb_substr($category->name, 0, 1) }}
                            </span>
                            {{ $category->name }}
                        </td>
                        <td class="px-6 py-4 text-surface-600 dark:text-surface-300">
                            {{ Str::limit($category->description, 40) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-surface-100 text-surface-600 rounded-md text-xs" dir="ltr">
                                {{ $category->slug }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div>
                                <button wire:click="toggleStatus({{ $category->id }})" 
                                        wire:loading.attr="disabled"
                                        class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium rounded-lg group focus:ring-4 focus:outline-none transition-all
                                        {{ $category->is_active ? 'text-green-700 bg-green-100 hover:bg-green-200 focus:ring-green-300 dark:bg-green-900 dark:text-green-300 dark:hover:bg-green-800 dark:focus:ring-green-800' : 'text-red-700 bg-red-100 hover:bg-red-200 focus:ring-red-300 dark:bg-red-900 dark:text-red-300 dark:hover:bg-red-800 dark:focus:ring-red-800' }}">
                                    <span class="relative px-3 py-1.5 transition-all ease-in duration-75 rounded-md flex items-center gap-2">
                                        @if($category->is_active)
                                            <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                                            متاح ومفعل
                                        @else
                                            <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                            غير مفعل
                                        @endif
                                    </span>
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-left">
                            <div class="flex items-center justify-end gap-2">
                                <!-- Edit Button -->
                                <button wire:click="$dispatch('edit-course-category', { id: {{ $category->id }} })" 
                                    wire:loading.attr="disabled"
                                    class="p-2 text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 hover:text-primary-700 transition-colors tooltip-trigger relative group" title="تعديل التصنيف">
                                    <svg wire:loading.remove wire:target="$dispatch('edit-course-category', { id: {{ $category->id }} })" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <svg wire:loading wire:target="$dispatch('edit-course-category', { id: {{ $category->id }} })" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </button>

                                <!-- Delete Button -->
                                <button wire:click="confirmDelete({{ $category->id }})" 
                                    wire:loading.attr="disabled"
                                    class="p-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 hover:text-red-700 transition-colors tooltip-trigger relative group" title="حذف">
                                    <svg wire:loading.remove wire:target="confirmDelete({{ $category->id }})" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <svg wire:loading wire:target="confirmDelete({{ $category->id }})" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-surface-500 dark:text-surface-400">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 mb-4 text-surface-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="text-lg font-medium text-surface-900 dark:text-surface-100">لا توجد تصنيفات</p>
                                <p class="text-sm">لم يتم العثور على أي تصنيفات متطابقة.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>
