<div class="p-8 max-w-7xl mx-auto">
    <div class="mb-8 border-b border-surface-200 pb-6 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-extrabold text-surface-900 arabic-font">منشئ الجداول الأكاديمية الذكي</h1>
            <p class="text-surface-500 mt-2 arabic-font text-lg">صمم الجدول الزمني للمجموعة، وحدد الحصص والمواد والمدربين، ثم دع النظام يولد الحصص اليومية تلقائياً.</p>
        </div>
        <div class="flex gap-4">
            <div class="px-4 py-2 bg-gold-100 text-gold-700 rounded-lg border border-gold-200 font-bold arabic-font shadow-sm">
                <i class="fas fa-magic ml-2"></i>
                توليد آلي للهيكل
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar: Configuration -->
        <div class="space-y-6">
            <!-- Group Selection Card -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-surface-200">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-600">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <h3 class="text-lg font-bold text-surface-800 arabic-font">المسار الأكاديمي</h3>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-surface-600 arabic-font mb-2">المجموعة المستهدفة</label>
                        <select wire:model="groupId" class="w-full px-4 py-2 bg-surface-50 border-surface-200 rounded-xl focus:ring-primary-500 focus:border-primary-500 arabic-font @if($groupId) border-primary-500 @endif">
                            <option value="">-- اختر المجموعة --</option>
                            @foreach($groups as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-surface-600 arabic-font mb-2">الفترة الزمنية (الدوام)</label>
                        <div class="grid grid-cols-2 gap-2 p-1 bg-surface-100 rounded-xl border border-surface-200">
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="period" value="morning" class="hidden peer">
                                <span class="block px-4 py-2 rounded-lg text-center text-sm font-bold transition-all peer-checked:bg-white peer-checked:text-primary-600 peer-checked:shadow-sm text-surface-500 arabic-font">صباحي</span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="period" value="evening" class="hidden peer">
                                <span class="block px-4 py-2 rounded-lg text-center text-sm font-bold transition-all peer-checked:bg-white peer-checked:text-primary-600 peer-checked:shadow-sm text-surface-500 arabic-font">مسائي</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Date Range Card -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-surface-200">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-gold-100 flex items-center justify-center text-gold-600">
                        <i class="far fa-calendar-alt"></i>
                    </div>
                    <h3 class="text-lg font-bold text-surface-800 arabic-font">النطاق الزمني</h3>
                </div>
                
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-surface-600 arabic-font mb-2">من تاريخ</label>
                        <input type="date" wire:model="startDate" class="w-full px-4 py-2 border-surface-200 rounded-xl focus:ring-primary-500 focus:border-primary-500 arabic-font">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-600 arabic-font mb-2">إلى تاريخ</label>
                        <input type="date" wire:model="endDate" class="w-full px-4 py-2 border-surface-200 rounded-xl focus:ring-primary-500 focus:border-primary-500 arabic-font">
                    </div>
                </div>
            </div>

            <!-- Days Selection Card -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-surface-200">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-surface-100 flex items-center justify-center text-surface-600">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <h3 class="text-lg font-bold text-surface-800 arabic-font">أيام التكرار</h3>
                </div>
                
                <div class="grid grid-cols-2 gap-2">
                    @foreach($daysOfWeek as $index => $day)
                        <label class="flex items-center p-3 rounded-xl border border-surface-100 hover:bg-surface-50 transition cursor-pointer group @if(in_array($index, $selectedDays)) bg-primary-50 border-primary-200 @endif">
                            <input type="checkbox" wire:model="selectedDays" value="{{ $index }}" class="w-4 h-4 text-primary-600 rounded border-surface-300 focus:ring-primary-500">
                            <span class="mr-3 text-sm font-semibold text-surface-700 arabic-font group-hover:text-primary-600 @if(in_array($index, $selectedDays)) text-primary-700 @endif">{{ $day }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Main Content: Sessions Builder -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-surface-200 overflow-hidden min-h-[600px] flex flex-col">
                <div class="p-6 border-b border-surface-100 flex justify-between items-center bg-surface-50/50">
                    <div>
                        <h3 class="text-xl font-extrabold text-surface-900 arabic-font">هيكلية اليوم الدراسي</h3>
                        <p class="text-sm text-surface-500 arabic-font">أضف الحصص الدراسية، حدد المواد، والمدربين المتاحين.</p>
                    </div>
                    <button wire:click="addSession" class="inline-flex items-center px-4 py-2.5 bg-primary-600 text-white rounded-xl font-bold hover:bg-primary-700 transition shadow-lg shadow-primary-500/25 arabic-font text-sm">
                        <i class="fas fa-plus ml-2"></i>
                        إضافة حصة
                    </button>
                </div>

                <div class="p-6 flex-grow">
                    @if(count($sessions) > 0)
                        <div class="space-y-4">
                            @foreach($sessions as $index => $session)
                                <div class="bg-white p-5 rounded-2xl border border-surface-200 shadow-sm hover:shadow-md transition-all relative group flex flex-col md:flex-row gap-6 items-end md:items-center">
                                    <div class="absolute -right-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-surface-100 border border-surface-200 flex items-center justify-center text-xs font-bold text-surface-500 arabic-font">
                                        {{ $index + 1 }}
                                    </div>

                                    <!-- Time Slots -->
                                    <div class="flex gap-2 items-center">
                                        <div class="w-full">
                                            <label class="block text-[10px] uppercase tracking-wider text-surface-400 font-bold mb-1 arabic-font">البداية</label>
                                            <input type="time" wire:model.defer="sessions.{{ $index }}.start_time" class="w-32 px-3 py-2 bg-surface-50 border-surface-200 rounded-lg text-sm focus:ring-primary-500 focus:border-primary-500">
                                        </div>
                                        <div class="mt-4 text-surface-300 font-bold">/</div>
                                        <div class="w-full">
                                            <label class="block text-[10px] uppercase tracking-wider text-surface-400 font-bold mb-1 arabic-font">النهاية</label>
                                            <input type="time" wire:model.defer="sessions.{{ $index }}.end_time" class="w-32 px-3 py-2 bg-surface-50 border-surface-200 rounded-lg text-sm focus:ring-primary-500 focus:border-primary-500">
                                        </div>
                                    </div>

                                    <!-- Subject -->
                                    <div class="flex-grow min-w-[200px]">
                                        <label class="block text-xs font-bold text-surface-700 arabic-font mb-2">المادة العلمية</label>
                                        <select wire:model="sessions.{{ $index }}.subject_id" class="w-full px-4 py-2.5 bg-surface-50 border-surface-200 rounded-xl focus:ring-primary-500 focus:border-primary-500 arabic-font text-sm">
                                            <option value="">-- اختر المادة --</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Instructor (Filtered) -->
                                    <div class="flex-grow min-w-[200px]">
                                        <label class="block text-xs font-bold text-surface-700 arabic-font mb-2">المدرب المتاح</label>
                                        <select wire:model.defer="sessions.{{ $index }}.instructor_id" class="w-full px-4 py-2.5 bg-surface-50 border-surface-200 rounded-xl focus:ring-primary-500 focus:border-primary-500 arabic-font text-sm @if(count($session['available_instructors']) == 0) opacity-50 @endif" @if(count($session['available_instructors']) == 0) disabled @endif>
                                            <option value="">-- اختر المدرب --</option>
                                            @foreach($session['available_instructors'] as $ins)
                                                <option value="{{ $ins['id'] }}">{{ $ins['name'] }}</option>
                                            @endforeach
                                        </select>
                                        @if($session['subject_id'] && count($session['available_instructors']) == 0)
                                            <span class="text-[10px] text-red-500 arabic-font mt-1 inline-block">لا يوجد مدربون لهذه المادة!</span>
                                        @endif
                                    </div>

                                    <!-- Remove Button -->
                                    <button wire:click="removeSession({{ $index }})" class="p-2.5 text-surface-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all border border-transparent hover:border-red-100">
                                        <i class="far fa-trash-alt text-lg"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="h-full flex flex-col items-center justify-center text-center p-12 border-2 border-dashed border-surface-200 rounded-3xl">
                            <div class="w-20 h-20 bg-surface-50 rounded-full flex items-center justify-center mb-4 text-surface-300">
                                <i class="fas fa-tasks text-4xl"></i>
                            </div>
                            <h4 class="text-xl font-bold text-surface-800 arabic-font">لم يتم إضافة أي حصص بعد</h4>
                            <p class="text-surface-500 arabic-font mt-2 max-w-sm">ابدأ بإضافة الحصص الدراسية وتوزيع المواد لليوم الدراسي النمذجي.</p>
                            <button wire:click="addSession" class="mt-6 px-6 py-2 bg-primary-600 text-white rounded-xl font-bold hover:bg-primary-700 transition arabic-font">أضف الحصة الأولى</button>
                        </div>
                    @endif
                </div>

                <!-- Footer Summary & Action -->
                <div class="p-8 bg-surface-50 border-t border-surface-100 flex flex-col sm:flex-row gap-6 items-center justify-between">
                    <div class="flex gap-8">
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-surface-400 arabic-font mb-1">إجمالي الحصص</span>
                            <span class="text-2xl font-black text-primary-600">{{ count($sessions) }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-surface-400 arabic-font mb-1">أيام العمل الأسبوعية</span>
                            <span class="text-2xl font-black text-gold-600">{{ count($selectedDays) }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-surface-400 arabic-font mb-1">فترة الجدولة</span>
                            <span class="text-lg font-bold text-surface-700 arabic-font">
                                {{ \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) }} يوم
                            </span>
                        </div>
                    </div>

                    <button 
                        wire:click="generateSchedule"
                        wire:loading.attr="disabled"
                        class="relative inline-flex items-center px-10 py-4 bg-primary-600 text-white rounded-2xl font-black text-xl hover:bg-primary-700 transition shadow-2xl shadow-primary-500/40 arabic-font hover:scale-105 active:scale-95 disabled:opacity-50"
                    >
                        <span wire:loading.remove>تحرير وتوليد الجدول</span>
                        <span wire:loading>جاري التوليد...</span>
                        <i class="fas fa-magic mr-3" wire:loading.remove></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Stylings for radios and checkboxes handled by Tailwind peer-checked -->
</div>
