<div class="min-h-screen bg-gradient-to-br from-surface-50 via-white to-primary-50/20">
    <div class="p-8 max-w-7xl mx-auto">

        <!-- ===== Header ===== -->
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-4xl font-black text-surface-900 arabic-font tracking-tight">الجدول الدراسي التفاعلي</h1>
                <p class="text-surface-500 mt-1 arabic-font">إنشاء وإدارة الجداول الدراسية للمجموعات</p>
            </div>
            <button wire:click="openGenerateModal"
                class="inline-flex items-center gap-3 px-6 py-4 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl font-bold shadow-lg shadow-primary-500/30 hover:-translate-y-0.5 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <span class="arabic-font">توليد جدول دراسي جديد</span>
            </button>
        </div>

        <!-- ===== Professional Horizontal Filter Bar ===== -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-surface-100 p-6 mb-8">
            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-surface-100">
                <div class="w-8 h-8 rounded-xl bg-primary-100 flex items-center justify-center text-primary-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                </div>
                <span class="font-black text-surface-700 arabic-font text-sm">فلترة وعرض الجداول</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- العام الدراسي - للقراءة فقط -->
                <div>
                    <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2 arabic-font">العام الدراسي</label>
                    <div class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl text-sm text-surface-600 arabic-font font-bold flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-500 shrink-0"></span>
                        {{ $currentAcademicYear?->name ?? 'غير محدد' }}
                    </div>
                </div>

                <!-- المشروع -->
                <div>
                    <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2 arabic-font">المشروع</label>
                    <select wire:model.live="projectId" class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl focus:ring-2 focus:ring-primary-400 focus:border-primary-400 arabic-font text-sm transition-all">
                        <option value="">— اختر المشروع —</option>
                        @foreach($this->projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- المجموعة -->
                <div>
                    <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2 arabic-font">المجموعة</label>
                    <select wire:model.live="groupId" class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl focus:ring-2 focus:ring-primary-400 focus:border-primary-400 arabic-font text-sm transition-all" @disabled(!$projectId)>
                        <option value="">— اختر المجموعة —</option>
                        @foreach($this->groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- الجدول المتاح -->
                <div>
                    <label class="block text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-2 arabic-font">الجدول الدراسي</label>
                    <select wire:model.live="periodId" class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl focus:ring-2 focus:ring-primary-400 focus:border-primary-400 arabic-font text-sm transition-all" @disabled(!$groupId)>
                        <option value="">— اختر الجدول —</option>
                        @foreach($this->periods as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- ===== Schedule Grid ===== -->
        @if($periodId && count($schedules) > 0)
            @foreach($schedules as $schedule)
                <div class="mb-10" wire:key="schedule-{{ $schedule->id }}">
                    <!-- Schedule Header -->
                    <div class="flex items-center justify-between mb-4 px-2">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-primary-100 text-primary-600 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-surface-900 arabic-font">{{ $schedule->name }}</h2>
                                <span class="text-xs font-bold text-surface-400 arabic-font bg-surface-100 px-3 py-1 rounded-full inline-block mt-1">{{ $schedule->group?->name }}</span>
                            </div>
                        </div>
                        <button wire:click="deleteStage({{ $schedule->id }})" wire:confirm="هل أنت متأكد من حذف هذا الجدول الدراسي بالكامل؟"
                            class="flex items-center gap-2 text-sm font-bold text-red-600 bg-red-50 hover:bg-red-100 hover:text-red-700 px-4 py-2 rounded-xl transition-all arabic-font border border-red-100 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            حذف الجدول بالكامل
                        </button>
                    </div>

                    <!-- Grid Table -->
                    <div class="bg-white rounded-[2rem] shadow-sm border border-surface-100 overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-surface-50/50">
                                    <th class="p-5 text-right border-b border-l border-surface-100 min-w-[130px] arabic-font font-black text-surface-400 text-[10px] uppercase tracking-widest">اليوم</th>
                                    @php $maxSessions = $schedule->days->max(fn($d) => $d->sessions->count()); @endphp
                                    @for($i = 1; $i <= $maxSessions; $i++)
                                        <th class="p-5 text-center border-b border-surface-100 min-w-[190px] arabic-font font-black text-surface-400 text-[10px] uppercase tracking-widest">الحصة {{ $i }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schedule->days as $day)
                                    <tr class="group hover:bg-surface-50/50 transition-colors">
                                        <td class="p-5 border-b border-l border-surface-100 bg-surface-50/30">
                                            <span class="font-black text-surface-900 arabic-font">{{ $daysOfWeek[$day->day_of_week] }}</span>
                                        </td>
                                        @foreach($day->sessions as $session)
                                            <td class="p-3 border-b border-surface-100">
                                                <div wire:click="openEditSession({{ $session->id }})"
                                                    @class([
                                                        'p-4 rounded-3xl border-2 transition-all cursor-pointer flex flex-col items-center justify-center gap-2 min-h-[100px]',
                                                        'bg-surface-50 border-dashed border-surface-300 text-surface-500 hover:border-primary-500 hover:text-primary-600 hover:bg-primary-50/50 shadow-sm' => !$session->subject_id,
                                                        'bg-gradient-to-br from-white to-primary-50/50 border-primary-200 hover:border-primary-400 shadow-md' => $session->subject_id,
                                                    ])>
                                                    @if($session->subject_id)
                                                        <span class="text-primary-800 font-black arabic-font text-center text-sm leading-tight">{{ $session->subject->name }}</span>
                                                        @php $instructor = $session->instructors->first(); @endphp
                                                        <div class="flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-full border border-primary-100 shadow-sm mt-1">
                                                            <div class="w-2 h-2 rounded-full bg-primary-500"></div>
                                                            <span class="text-[10px] font-bold text-primary-700 arabic-font">{{ $instructor->user->name ?? 'غير معين' }}</span>
                                                        </div>
                                                    @else
                                                        <div class="w-8 h-8 rounded-full bg-surface-200 text-surface-500 flex items-center justify-center mb-1 group-hover:bg-primary-100 group-hover:text-primary-600 transition-colors">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                            </svg>
                                                        </div>
                                                        <span class="text-[10px] font-black uppercase tracking-tight arabic-font text-surface-500">تعيين مادة</span>
                                                    @endif
                                                    <span class="text-[10px] font-bold text-surface-400 bg-white px-2 py-0.5 rounded-md border border-surface-100">{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</span>
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @else
            <!-- Empty State -->
            <div class="h-[500px] flex flex-col items-center justify-center text-center p-12 bg-white border-2 border-dashed border-surface-200 rounded-[3rem]">
                <div class="w-20 h-20 bg-surface-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-surface-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h4 class="text-2xl font-black text-surface-700 arabic-font">لا يوجد جدول دراسي للعرض</h4>
                <p class="text-surface-400 arabic-font mt-2 max-w-sm text-sm">اختر مشروعاً ومجموعة من الفلاتر أعلاه، أو ابدأ بتوليد جدول جديد للمجموعة.</p>
                <button wire:click="openGenerateModal" class="mt-6 inline-flex items-center gap-2 px-5 py-3 bg-primary-600 text-white rounded-xl font-bold text-sm arabic-font hover:bg-primary-700 transition-all shadow-md shadow-primary-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    توليد جدول الآن
                </button>
            </div>
        @endif

        <!-- ===== Modal: توليد جدول دراسي ===== -->
        <x-modal wire:model="showGenerateModal" maxWidth="2xl">
            <div class="p-8">
                <!-- Modal Header -->
                <div class="flex items-center gap-4 mb-6 pb-5 border-b border-surface-100">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 text-white flex items-center justify-center shadow-lg shadow-primary-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-surface-900 arabic-font">توليد جدول دراسي جديد</h3>
                        <p class="text-xs text-surface-400 arabic-font mt-0.5">سيتم توليد الجداول للمجموعات المختارة بناءً على إعدادات الفترة</p>
                    </div>
                </div>

                <form wire:submit.prevent="generateSchedule" class="space-y-6">
                    
                    <!-- العام الدراسي (للقراءة فقط) -->
                    <div class="p-4 bg-green-50 border border-green-100 rounded-2xl flex items-center gap-3">
                        <div class="w-9 h-9 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-green-600 uppercase tracking-widest arabic-font">العام الدراسي الحالي</p>
                            <p class="font-black text-green-800 arabic-font">{{ $currentAcademicYear?->name ?? 'غير محدد' }}</p>
                        </div>
                    </div>

                    <!-- اختيار المشروع -->
                    <div>
                        <label class="block text-sm font-bold text-surface-700 arabic-font mb-2">
                            المشروع <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="selectedProjectForGenerate"
                            class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-2xl focus:ring-2 focus:ring-primary-400 focus:border-primary-400 arabic-font text-sm transition-all">
                            <option value="">— اختر المشروع —</option>
                            @foreach($this->projectsForGenerate as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedProjectForGenerate')
                            <p class="mt-1.5 text-xs text-red-500 font-bold arabic-font flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- اختيار المجموعات -->
                    @if($selectedProjectForGenerate)
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <label class="block text-sm font-bold text-surface-700 arabic-font">
                                    المجموعات <span class="text-red-500">*</span>
                                </label>
                                @if(count($this->groupsForGenerate) > 0)
                                    <button type="button"
                                        wire:click="$set('generateData.group_ids', {{ json_encode($this->groupsForGenerate->pluck('id')->toArray()) }})"
                                        class="text-xs text-primary-600 font-bold arabic-font hover:underline">
                                        اختيار الكل
                                    </button>
                                @endif
                            </div>

                            @if(count($this->groupsForGenerate) > 0)
                                <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto p-1">
                                    @foreach($this->groupsForGenerate as $group)
                                        <label class="flex items-center gap-3 p-3 bg-surface-50 border border-surface-200 rounded-xl cursor-pointer hover:bg-primary-50 hover:border-primary-200 transition-all select-none
                                            {{ in_array($group->id, $generateData['group_ids']) ? 'bg-primary-50 border-primary-300' : '' }}">
                                            <input type="checkbox"
                                                wire:model="generateData.group_ids"
                                                value="{{ $group->id }}"
                                                class="rounded-lg text-primary-600 border-surface-300 focus:ring-primary-500 w-4 h-4">
                                            <span class="text-sm font-bold text-surface-700 arabic-font">{{ $group->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-4 bg-yellow-50 border border-yellow-100 rounded-xl text-center">
                                    <p class="text-xs text-yellow-700 font-bold arabic-font">لا توجد مجموعات في هذا المشروع.</p>
                                </div>
                            @endif

                            @error('generateData.group_ids')
                                <p class="mt-1.5 text-xs text-red-500 font-bold arabic-font flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    @endif

                    <!-- قالب الفترة الدراسية -->
                    <div>
                        <label class="block text-sm font-bold text-surface-700 arabic-font mb-2">
                            قالب الفترة الدراسية <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="generateData.study_period_id"
                            class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-2xl focus:ring-2 focus:ring-primary-400 focus:border-primary-400 arabic-font text-sm transition-all">
                            <option value="">— اختر قالب الفترة —</option>
                            @foreach($this->periodTemplates as $template)
                                <option value="{{ $template->id }}">
                                    {{ $template->name }} — {{ $template->sessions_count }} حصة / يبدأ {{ $template->start_time }}
                                </option>
                            @endforeach
                        </select>
                        @if(count($this->periodTemplates) === 0)
                            <p class="mt-1.5 text-[10px] text-amber-600 font-bold arabic-font">
                                ⚠ لا توجد قوالب فترات للعام الحالي. أضفها من صفحة "فترات الدراسة" أولاً.
                            </p>
                        @endif
                        @error('generateData.study_period_id')
                            <p class="mt-1.5 text-xs text-red-500 font-bold arabic-font flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- أزرار -->
                    <div class="flex gap-3 pt-2">
                        <button type="submit"
                            class="flex-1 bg-primary-600 text-white py-4 rounded-2xl font-black arabic-font hover:bg-primary-700 hover:shadow-lg hover:shadow-primary-500/30 transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            توليد الجداول الدراسية
                        </button>
                        <button type="button" wire:click="$set('showGenerateModal', false)"
                            class="px-8 bg-surface-100 text-surface-700 py-4 rounded-2xl font-bold arabic-font hover:bg-surface-200 transition-all">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </x-modal>

        <!-- ===== Modal: تعديل حصة ===== -->
        <x-modal wire:model="showEditSessionModal" maxWidth="md">
            <div class="p-8">
                <h3 class="text-xl font-black text-surface-900 arabic-font mb-6 pb-4 border-b">تعديل بيانات الحصة</h3>

                <form wire:submit.prevent="updateSessionAssignment" class="space-y-5">
                    <div>
                        <x-label value="المادة الدراسية" class="arabic-font mb-2" />
                        <select wire:model.live="editingSessionData.subject_id"
                            class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-2xl focus:ring-2 focus:ring-primary-400 focus:border-primary-400 arabic-font text-sm">
                            <option value="">— اختر المادة —</option>
                            @foreach($allSubjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                        @error('editingSessionData.subject_id')
                            <p class="mt-1 text-xs text-red-500 font-bold arabic-font">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-label value="المدرب / المعلم" class="arabic-font mb-2" />
                        <select wire:model="editingSessionData.instructor_id"
                            class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-2xl focus:ring-2 focus:ring-primary-400 focus:border-primary-400 arabic-font text-sm"
                            @disabled(!$editingSessionData['subject_id'])>
                            <option value="">— اختر المدرب —</option>
                            @foreach($availableInstructors as $instructor)
                                <option value="{{ $instructor->id }}">{{ $instructor->user->name }}</option>
                            @endforeach
                        </select>
                        @error('editingSessionData.instructor_id')
                            <p class="mt-1 text-xs text-red-500 font-bold arabic-font">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit"
                            class="flex-1 bg-primary-600 text-white py-3.5 rounded-2xl font-bold arabic-font hover:bg-primary-700 transition-all shadow-md shadow-primary-500/20">
                            حفظ التعديلات
                        </button>
                        <button type="button" wire:click="$set('showEditSessionModal', false)"
                            class="px-6 bg-surface-100 text-surface-700 py-3.5 rounded-2xl font-bold arabic-font hover:bg-surface-200 transition-all">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </x-modal>
    </div>
</div>
