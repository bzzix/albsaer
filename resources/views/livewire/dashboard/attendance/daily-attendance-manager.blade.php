<div class="p-8 max-w-7xl mx-auto">
    <!-- Header & Quick Navigation -->
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-6 bg-white p-6 rounded-[2rem] shadow-sm border border-surface-200">
        <div>
            <h1 class="text-3xl font-extrabold text-surface-900 arabic-font">نظام التحضير اليومي الذكي</h1>
            <p class="text-surface-500 mt-2 arabic-font text-lg">سجل حضور وغياب الطلاب، وتابع أداء المجموعة لحظة بلحظة.</p>
        </div>
        
        <div class="flex flex-wrap gap-4 w-full md:w-auto">
            <div class="flex-grow md:flex-grow-0">
                <label class="block text-[10px] font-bold text-surface-400 uppercase mb-1 arabic-font">المجموعة الدراسية</label>
                <select wire:model.live="groupId" class="w-full px-4 py-2 bg-surface-50 border-surface-200 rounded-xl focus:ring-primary-500 focus:border-primary-500 arabic-font text-sm">
                    <option value="">-- اختر المجموعة --</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-[10px] font-bold text-surface-400 uppercase mb-1 arabic-font">تاريخ التحضير</label>
                <input type="date" wire:model.live="date" class="w-full px-4 py-2 bg-surface-50 border-surface-200 rounded-xl focus:ring-primary-500 focus:border-primary-500 text-sm">
            </div>
        </div>
    </div>

    @if($groupId)
        <!-- Sessions Strip -->
        <div class="mb-8">
            <div class="flex gap-4 overflow-x-auto pb-4 no-scrollbar">
                @forelse($sessions as $session)
                    <button 
                        wire:click="$set('sessionId', {{ $session->id }})"
                        @class([
                            'flex-shrink-0 px-6 py-4 rounded-2xl border-2 transition-all flex flex-col gap-1 text-right min-w-[180px]',
                            'bg-primary-600 border-primary-600 text-white shadow-lg shadow-primary-500/20' => $sessionId == $session->id,
                            'bg-white border-surface-100 text-surface-600 hover:border-primary-200 hover:bg-surface-50' => $sessionId != $session->id
                        ])
                    >
                        <span class="text-xs font-bold opacity-70 arabic-font">الحصة {{ $session->session_number }}</span>
                        <span class="font-black arabic-font">{{ $session->subject?->name ?? 'مادة غير محددة' }}</span>
                        <span class="text-[10px] font-bold opacity-60">{{ $session->start_time }} - {{ $session->end_time }}</span>
                    </button>
                @empty
                    <div class="w-full p-8 bg-gold-50 border border-gold-100 rounded-2xl text-gold-700 arabic-font text-center">
                        لا يوجد حصص مجدولة لهذه المجموعة في التاريخ المختار.
                    </div>
                @endforelse
            </div>
        </div>

        @if($sessionId)
            <!-- Metrics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-surface-200 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-surface-400 uppercase arabic-font">إجمالي الطلاب</span>
                        <div class="text-2xl font-black text-surface-900">{{ $metrics['total_students'] }}</div>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-surface-200 flex items-center gap-4 text-red-600">
                    <div class="w-12 h-12 rounded-2xl bg-red-100 flex items-center justify-center text-xl">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-red-400 uppercase arabic-font">حالات الغياب</span>
                        <div class="text-2xl font-black">{{ $metrics['absent_count'] }}</div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-surface-200 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center text-xl font-black">
                        {{ $metrics['attendance_rate'] }}%
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-surface-400 uppercase arabic-font">نسبة الحضور</span>
                        <div class="w-32 h-2 bg-surface-100 rounded-full mt-2 overflow-hidden">
                            <div class="h-full bg-green-500" style="width: {{ $metrics['attendance_rate'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students List -->
            <div class="space-y-4">
                @foreach($students as $enrollment)
                    <div @class([
                        'bg-white p-4 rounded-3xl border transition-all flex flex-col md:flex-row items-center justify-between gap-6 hover:shadow-md',
                        'border-surface-200' => $attendanceData[$enrollment->student_id] == 'pending',
                        'border-green-200 bg-green-50/10' => $attendanceData[$enrollment->student_id] == 'present',
                        'border-red-200 bg-red-50/10' => $attendanceData[$enrollment->student_id] == 'unexcused_absent',
                        'border-gold-200 bg-gold-50/10' => $attendanceData[$enrollment->student_id] == 'late',
                    ])>
                        <div class="flex items-center gap-4 flex-grow w-full md:w-auto">
                            <img src="{{ $enrollment->student->user->profile_photo_url }}" class="w-14 h-14 rounded-2xl object-cover shadow-sm bg-surface-100">
                            <div>
                                <h4 class="font-bold text-surface-900 arabic-font text-lg">{{ $enrollment->student->user->name }}</h4>
                                <div class="flex gap-3 text-xs text-surface-400 arabic-font">
                                    <span>{{ $enrollment->student->national_id }}</span>
                                    <span>•</span>
                                    <span>{{ $enrollment->student->phone }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Status Controls -->
                        <div class="flex items-center p-1 bg-surface-100 rounded-2xl gap-1 w-full md:w-auto">
                            <button 
                                wire:click="markAttendance({{ $enrollment->student_id }}, 'present')"
                                @class([
                                    'flex-grow md:flex-none px-6 py-2 rounded-xl text-sm font-bold transition-all arabic-font',
                                    'bg-white text-green-600 shadow-sm' => $attendanceData[$enrollment->student_id] == 'present',
                                    'text-surface-500 hover:bg-white/50' => $attendanceData[$enrollment->student_id] != 'present'
                                ])
                            >
                                <i class="fas fa-check-circle ml-1"></i> حاضر
                            </button>
                            
                            <button 
                                wire:click="markAttendance({{ $enrollment->student_id }}, 'late')"
                                @class([
                                    'flex-grow md:flex-none px-6 py-2 rounded-xl text-sm font-bold transition-all arabic-font',
                                    'bg-white text-gold-600 shadow-sm' => $attendanceData[$enrollment->student_id] == 'late',
                                    'text-surface-500 hover:bg-white/50' => $attendanceData[$enrollment->student_id] != 'late'
                                ])
                            >
                                <i class="fas fa-clock ml-1"></i> متأخر
                            </button>
                            
                            <button 
                                wire:click="markAttendance({{ $enrollment->student_id }}, 'unexcused_absent')"
                                @class([
                                    'flex-grow md:flex-none px-6 py-2 rounded-xl text-sm font-bold transition-all arabic-font',
                                    'bg-white text-red-600 shadow-sm' => $attendanceData[$enrollment->student_id] == 'unexcused_absent',
                                    'text-surface-500 hover:bg-white/50' => $attendanceData[$enrollment->student_id] != 'unexcused_absent'
                                ])
                            >
                                <i class="fas fa-times-circle ml-1"></i> غياب
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="h-[400px] flex flex-col items-center justify-center text-center p-12 bg-white border-2 border-dashed border-surface-200 rounded-[3rem]">
                <div class="w-20 h-20 bg-surface-50 rounded-full flex items-center justify-center mb-6 text-surface-300">
                    <i class="fas fa-clock text-4xl"></i>
                </div>
                <h4 class="text-xl font-bold text-surface-800 arabic-font">يرجى اختيار حصة للبدء</h4>
                <p class="text-surface-500 arabic-font mt-2 max-w-sm">اختر الحصة الدراسية من الشريط العلوي لاستعراض قائمة الطلاب وتحضيرهم.</p>
            </div>
        @endif
    @else
        <!-- Selection State -->
        <div class="h-[600px] flex flex-col items-center justify-center text-center p-12 bg-white border-2 border-dashed border-surface-200 rounded-[3rem]">
            <div class="w-24 h-24 bg-surface-50 rounded-full flex items-center justify-center mb-6 text-surface-300">
                <i class="fas fa-layer-group text-5xl"></i>
            </div>
            <h4 class="text-2xl font-black text-surface-800 arabic-font">اختر المجموعة الدراسية</h4>
            <p class="text-surface-500 arabic-font mt-2 max-w-sm">اختر المجموعة من القائمة العلوية لعرض الحصص الدراسية المجدولة لهذا اليوم.</p>
        </div>
    @endif

    <!-- Loading State Overlay -->
    <div wire:loading.flex class="fixed inset-0 z-[100] bg-surface-900/40 backdrop-blur-sm items-center justify-center">
        <div class="bg-white p-8 rounded-[2rem] shadow-2xl flex flex-col items-center gap-4">
            <div class="w-16 h-16 border-4 border-primary-100 border-t-primary-600 rounded-full animate-spin"></div>
            <p class="font-bold text-surface-800 arabic-font">جاري مزامنة بيانات الحضور...</p>
        </div>
    </div>
</div>
