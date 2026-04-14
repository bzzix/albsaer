<div>
    @section('title', 'باني الدورات')

    <!-- Header & Tabs Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <div class="flex items-center gap-2 mb-1 text-surface-500 text-sm font-medium">
                    <a href="{{ route('dashboard.academic.courses.index') }}" class="hover:text-primary-600 transition-colors">الدورات</a>
                    <span class="text-surface-300">/</span>
                    <span class="text-primary-600">باني الدورات</span>
                </div>
                <h2 class="text-xl font-bold text-surface-900 dark:text-white tracking-tight flex items-center gap-2">
                    {{ $course->name }}
                    <span class="px-2 py-0.5 bg-surface-100 text-surface-500 text-xs font-medium rounded border border-surface-200">
                        {{ $course->code }}
                    </span>
                </h2>
            </div>
            
            <div class="flex items-center gap-3">
                <button wire:click="saveBasicInfo" wire:loading.attr="disabled" class="px-4 py-2 bg-surface-900 text-white dark:bg-white dark:text-surface-900 rounded-lg font-bold text-sm shadow-sm transition-all flex items-center gap-2 active:scale-95 group">
                    <div class="relative w-4 h-4 flex items-center justify-center">
                        <div wire:loading wire:target="saveBasicInfo" class="absolute inset-x-0">
                            <svg class="animate-spin h-4 w-4 text-current" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>
                        <svg wire:loading.remove wire:target="saveBasicInfo" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                    </div>
                    <span>حفظ التغييرات</span>
                </button>
            </div>
        </div>

        <!-- Horizontal Top Tabs -->
        <div class="flex items-center gap-1 p-1 bg-surface-100 dark:bg-surface-800/50 rounded-xl border border-surface-200 dark:border-surface-700 shadow-sm overflow-x-auto no-scrollbar">
            @foreach([
                ['id' => 'basic', 'label' => 'المعلومات الأساسية', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['id' => 'curriculum', 'label' => 'المنهج الدراسي', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                ['id' => 'additional', 'label' => 'بيانات وصفية', 'icon' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4']
            ] as $tab)
                <button wire:click="setTab('{{ $tab['id'] }}')" 
                    class="flex-1 min-w-[120px] flex items-center justify-center gap-2 px-4 py-2 rounded-lg transition-all font-bold text-sm relative group {{ $activeTab == $tab['id'] ? 'bg-white dark:bg-surface-700 text-primary-600 shadow-sm' : 'text-surface-500 hover:text-surface-700' }}">
                    <div class="relative w-4 h-4 flex items-center justify-center">
                        <div wire:loading wire:target="setTab('{{ $tab['id'] }}')" class="absolute inset-0">
                            <svg class="animate-spin h-3.5 w-3.5 text-primary-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>
                        <svg wire:loading.remove wire:target="setTab('{{ $tab['id'] }}')" class="w-4 h-4 {{ $activeTab == $tab['id'] ? 'text-primary-500' : 'text-surface-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}" /></svg>
                    </div>
                    <span>{{ $tab['label'] }}</span>
                </button>
            @endforeach
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="bg-white dark:bg-surface-900 rounded-xl border border-surface-200 dark:border-surface-800 shadow-sm min-h-[500px] p-6">
        
        @if($activeTab == 'basic')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in">
                <div class="lg:col-span-2 space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-surface-700 mb-2">عنوان الدورة</label>
                        <input type="text" wire:model="name" class="premium-input w-full" placeholder="أدخل اسم الدورة">
                        @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-surface-700 mb-2">الوصف المختصر</label>
                        <textarea wire:model="short_description" rows="2" class="premium-input w-full resize-none" placeholder="نبذة سريعة عن الدورة"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-surface-700 mb-2">المحتوى التعليمي</label>
                        <div wire:ignore class="prose-editor-wrapper rounded-xl overflow-hidden border border-surface-200">
                            <textarea id="course-content-editor" wire:model="content" class="premium-input w-full min-h-[400px]"></textarea>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-surface-50 dark:bg-surface-800 p-5 rounded-xl border border-surface-200">
                        <label class="block text-sm font-bold text-surface-700 mb-4 uppercase tracking-wider">الصورة البارزة</label>
                        <div x-data="{ uploading: false, progress: 0 }" 
                             x-on:livewire-upload-start="uploading = true"
                             x-on:livewire-upload-finish="uploading = false"
                             x-on:livewire-upload-progress="progress = $event.detail.progress"
                             class="relative group/upload">
                            <div class="aspect-video rounded-lg border-2 border-dashed border-surface-300 flex flex-col items-center justify-center bg-white overflow-hidden relative">
                                @if($featuredImage)
                                    <img src="{{ $featuredImage->temporaryUrl() }}" class="w-full h-full object-cover">
                                @elseif($existingImage)
                                    <img src="{{ asset('storage/' . $existingImage) }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-10 h-10 text-surface-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-width="2" /></svg>
                                @endif
                                <button @click="$refs.imageInput.click()" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"></button>
                                <div x-show="uploading" class="absolute inset-0 bg-white/80 flex items-center justify-center px-4">
                                    <div class="w-full h-1 bg-surface-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-primary-600" :style="'width: ' + progress + '%'"></div>
                                    </div>
                                </div>
                            </div>
                            <input type="file" wire:model="featuredImage" x-ref="imageInput" class="hidden" accept="image/*">
                        </div>
                    </div>

                    <div class="bg-surface-50 p-5 rounded-xl border border-surface-200 space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-surface-700 mb-1">السعر</label>
                            <input type="number" wire:model="price" class="premium-input w-full text-left font-mono" dir="ltr">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-primary-600 mb-1">العرض المميز</label>
                            <input type="number" wire:model="sale_price" class="premium-input w-full text-left font-mono border-primary-100" dir="ltr">
                        </div>
                    </div>

                    <div class="bg-surface-50 p-5 rounded-xl border border-surface-200 space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-surface-700 mb-1">المدرب</label>
                            <select wire:model="instructor_id" class="premium-input w-full font-bold">
                                <option value="">اختر مدرباً</option>
                                @foreach($instructors as $inst) <option value="{{ $inst->id }}">{{ $inst->name }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-surface-700 mb-1">حالة النشر</label>
                            <select wire:model="status" class="premium-input w-full font-bold">
                                <option value="draft">📁 مسودة</option>
                                <option value="active">🌍 منشور</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($activeTab == 'curriculum')
            <div class="animate-fade-in py-2">
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-surface-100">
                    <div>
                        <h3 class="text-lg font-bold text-surface-900">إدارة محتوى المنهج</h3>
                        <p class="text-surface-400 text-sm">تنظيم الدروس والوحدات التعليمية</p>
                    </div>
                    <button wire:click="addModule" wire:loading.attr="disabled" class="px-3 py-1.5 bg-surface-900 text-white dark:bg-white dark:text-surface-900 rounded-lg font-bold text-sm transition-all flex items-center gap-2 active:scale-95 relative">
                         <div class="relative w-4 h-4 flex items-center justify-center">
                            <div wire:loading wire:target="addModule" class="absolute inset-0">
                                <svg class="animate-spin h-3.5 w-3.5 text-current" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </div>
                            <svg wire:loading.remove wire:target="addModule" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                         </div>
                         <span>إضافة وحدة</span>
                    </button>
                </div>

                <div class="space-y-4">
                    @foreach($modules as $module)
                        <div x-data="{ open: false }" class="flex flex-col">
                            <div @click="open = !open" 
                                 class="relative flex justify-between items-center p-4 bg-surface-50 border border-surface-200 cursor-pointer transition-all z-10 {{ $loop->first ? 'rounded-t-xl' : '' }} {{ $loop->last && !$module->lessons->count() ? 'rounded-b-xl' : '' }}"
                                 :class="open ? 'rounded-t-xl' : 'rounded-xl mb-1'">
                                
                                <div class="flex items-center gap-3 flex-1">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all bg-white shadow-sm"
                                         :class="open ? 'rotate-0' : '-rotate-90'">
                                        <svg class="w-4 h-4 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </div>
                                    <div class="flex flex-col flex-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded tracking-wider">وحدة {{ $loop->iteration }}</span>
                                            <span class="text-xs text-surface-400 font-bold">• {{ $module->lessons->count() }} درس</span>
                                        </div>
                                        <input type="text" 
                                            value="{{ $module->title }}"
                                            @blur="@this.updateModuleTitle({{ $module->id }}, $event.target.value)"
                                            @keydown.enter="$event.target.blur()"
                                            class="bg-transparent border-none focus:ring-0 focus:border-transparent hover:bg-surface-100/50 rounded px-1 font-bold text-surface-900 p-0 text-sm w-full mt-0.5 transition-colors cursor-text" 
                                            @click.stop>
                                    </div>
                                </div>

                                <button wire:click="$dispatch('confirm-delete', [{
                                            title: 'حذف الوحدة التعليمية',
                                            message: 'هل أنت متأكد من حذف هذه الوحدة؟ سيتم حذف جميع الدروس المرتبطة بها نهائياً.',
                                            component: '{{ $this->getId() }}',
                                            action: 'deleteModule',
                                            id: {{ $module->id }}
                                        }])" 
                                        @click.stop wire:loading.attr="disabled" class="w-8 h-8 flex items-center justify-center text-red-500/40 hover:text-red-600 rounded-lg transition-all relative">
                                    <div class="relative w-4 h-4 flex items-center justify-center">
                                        <div wire:loading wire:target="deleteModule({{ $module->id }})" class="absolute inset-0">
                                            <svg class="animate-spin h-4 w-4 text-current" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        </div>
                                        <svg wire:loading.remove wire:target="deleteModule({{ $module->id }})" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </div>
                                </button>
                            </div>

                            <div x-show="open" x-collapse class="bg-white border-x border-b border-surface-200 rounded-b-xl p-4 pt-6 -mt-2 space-y-2">
                                @forelse($module->lessons as $lesson)
                                    <div class="flex items-center justify-between p-3 rounded-xl border border-transparent hover:bg-surface-50 hover:border-surface-200 transition-all group/lesson">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-surface-100 flex items-center justify-center text-surface-400 group-hover/lesson:text-primary-500">
                                                @if($lesson->lesson_type == 'video')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 10l4.5 2.5L15 15V10z" /><rect x="3" y="6" width="12" height="12" rx="2" /></svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.5l5.5 5.5V19a2 2 0 01-2 2z" /></svg>
                                                @endif
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-surface-700 group-hover/lesson:text-surface-900">{{ $lesson->title }}</span>
                                                <span class="text-xs text-surface-400 font-bold uppercase tracking-wider mt-0.5">{{ $lesson->duration_minutes ?? 0 }} دقيقة</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1 opacity-20 group-hover:opacity-100 transition-opacity">
                                            <button wire:click="editLesson({{ $lesson->id }})" wire:loading.attr="disabled" class="w-8 h-8 flex items-center justify-center text-primary-600 hover:bg-primary-50 rounded-lg transition-all relative">
                                                <div class="relative w-4 h-4 flex items-center justify-center">
                                                    <div wire:loading wire:target="editLesson({{ $lesson->id }})" class="absolute inset-0">
                                                        <svg class="animate-spin h-4 w-4 text-current" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                    </div>
                                                    <svg wire:loading.remove wire:target="editLesson({{ $lesson->id }})" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                                </div>
                                            </button>
                                            <button wire:click="$dispatch('confirm-delete', [{
                                                        title: 'تأكيد حذف الدرس',
                                                        message: 'هل أنت متأكد من حذف درس ({{ $lesson->title }})؟ لن تتمكن من استعادته لاحقاً.',
                                                        component: '{{ $this->getId() }}',
                                                        action: 'deleteLesson',
                                                        id: {{ $lesson->id }}
                                                    }])" 
                                                    wire:loading.attr="disabled" class="w-8 h-8 flex items-center justify-center text-red-500 hover:bg-red-50 rounded-lg transition-all relative">
                                                <div class="relative w-4 h-4 flex items-center justify-center">
                                                    <div wire:loading wire:target="deleteLesson({{ $lesson->id }})" class="absolute inset-0">
                                                        <svg class="animate-spin h-4 w-4 text-current" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                    </div>
                                                    <svg wire:loading.remove wire:target="deleteLesson({{ $lesson->id }})" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="py-6 text-center italic text-surface-400 text-sm">لا توجد دروس مضافة لهذه الوحدة بعد.</div>
                                @endforelse

                                <div class="flex items-center gap-3 pt-4 border-t border-surface-100 mt-2">
                                    <button wire:click="addLesson({{ $module->id }})" wire:loading.attr="disabled" class="flex-1 py-1.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-bold text-xs flex items-center justify-center gap-2 transition-all active:scale-95 group relative">
                                        <div class="relative w-4 h-4 flex items-center justify-center">
                                            <div wire:loading wire:target="addLesson({{ $module->id }})" class="absolute inset-x-0">
                                                <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            </div>
                                            <svg wire:loading.remove wire:target="addLesson({{ $module->id }})" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                                        </div>
                                        <span>إضافة درس</span>
                                    </button>
                                    <button disabled class="flex-1 py-1.5 bg-surface-100 text-surface-400 rounded-lg font-bold text-xs cursor-not-allowed opacity-50">إضافة اختبار</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($activeTab == 'additional')
            <div class="animate-fade-in py-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    
                    <div class="space-y-6">
                        <h4 class="text-base font-bold text-surface-900 border-b border-surface-100 pb-2 mb-4">خصائص الدورة</h4>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-surface-700 mb-2">مستوى المهارة</label>
                                <select wire:model="level" class="premium-input w-full font-bold">
                                    <option value="beginner">🌟 مبتدئ</option>
                                    <option value="intermediate">📈 متوسط</option>
                                    <option value="advanced">🔥 متقدم</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-surface-700 mb-2">لغة الشرح</label>
                                <select wire:model="language" class="premium-input w-full font-bold">
                                    <option value="ar">العربية</option>
                                    <option value="en">English</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-surface-700 mb-2">المدة الزمنية (بالساعات)</label>
                                <input type="number" wire:model="total_hours" class="premium-input w-full text-center font-bold" dir="ltr" placeholder="0">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-surface-700 mb-2">حد التسجيل (0 للمفتوح)</label>
                                <input type="number" wire:model="enrollment_limit" class="premium-input w-full text-center font-bold" dir="ltr" placeholder="0">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-10">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center bg-surface-50 p-3 rounded-lg border border-surface-100">
                                <span class="text-sm font-bold text-surface-900">متطلبات الدورة</span>
                                <button wire:click="addRequirement" class="text-xs font-bold text-primary-600 hover:underline">إضافة حقل</button>
                            </div>
                            <div class="space-y-3">
                                @foreach($requirements as $idx => $val)
                                    <div class="flex gap-2 items-center animate-fade-in">
                                        <input type="text" wire:model="requirements.{{ $idx }}" class="premium-input flex-1 text-sm" placeholder="مثلاً: معرفة بأساسيات الحاسوب">
                                        <button wire:click="removeRequirement({{ $idx }})" class="text-red-400 hover:text-red-500 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center bg-green-50/50 p-3 rounded-lg border border-green-100">
                                <span class="text-sm font-bold text-surface-900">ماذا سيتعلم الطالب؟</span>
                                <button wire:click="addOutcome" class="text-xs font-bold text-green-600 hover:underline">إضافة حقل</button>
                            </div>
                            <div class="space-y-3">
                                @foreach($learning_outcomes as $idx => $val)
                                    <div class="flex gap-2 items-center animate-fade-in">
                                        <input type="text" wire:model="learning_outcomes.{{ $idx }}" class="premium-input flex-1 text-sm" placeholder="مثلاً: بناء تطبيقات الويب باحترافية">
                                        <button wire:click="removeOutcome({{ $idx }})" class="text-red-400 hover:text-red-500 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Footer Navigation -->
        <div class="mt-12 flex items-center justify-between pt-6 border-t border-surface-100">
            <div>
                @if($activeTab != 'basic')
                    <button wire:click="prevTab" wire:loading.attr="disabled" class="flex items-center gap-2 px-6 py-2 text-surface-500 hover:text-primary-600 font-bold text-sm transition-all group relative">
                         <div class="relative w-4 h-4 flex items-center justify-center">
                            <div wire:loading wire:target="prevTab" class="absolute inset-x-0">
                                <svg class="animate-spin h-4 w-4 text-primary-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </div>
                            <svg wire:loading.remove wire:target="prevTab" class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                         </div>
                         <span>السابق</span>
                    </button>
                @endif
            </div>
            
            @if($activeTab != 'additional')
                <button wire:click="nextTab" wire:loading.attr="disabled" class="px-8 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-black text-sm shadow-sm transition-all flex items-center gap-2 active:scale-95 group relative">
                    <div class="relative w-5 h-5 flex items-center justify-center">
                        <div wire:loading wire:target="nextTab" class="absolute inset-0">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>
                        <svg wire:loading.remove wire:target="nextTab" class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </div>
                    <span>التالي</span>
                </button>
            @else
                <button wire:click="saveAdditionalInfo" wire:loading.attr="disabled" class="px-8 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg font-black text-sm shadow-sm transition-all flex items-center gap-2 active:scale-95 group relative">
                    <div class="relative w-5 h-5 flex items-center justify-center">
                        <div wire:loading wire:target="saveAdditionalInfo" class="absolute inset-0">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>
                        <svg wire:loading.remove wire:target="saveAdditionalInfo" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <span>حفظ التغييرات</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Lesson Modal Section -->
    <template x-teleport="body">
        <div x-data="{ 
            open: false
        }" 
             wire:ignore.self
             @open-modal.window="
                 if ($event.detail[0] === 'lesson-form') { 
                     open = true; 
                 }
             " 
             @close-modal.window="
                 if ($event.detail[0] === 'lesson-form') { 
                     open = false; 
                     $wire.resetLesson();
                 }
             " 
             @keydown.escape.window="open = false; $wire.resetLesson();"
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
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="pointer-events-auto relative bg-white dark:bg-surface-800 rounded-2xl text-start overflow-hidden shadow-2xl border border-surface-200 dark:border-surface-700 transform transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                    
                    <form wire:submit.prevent="saveLesson">
                        <!-- Header -->
                        <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 flex justify-between items-center">
                            <h3 class="text-xl font-bold text-surface-900 dark:text-surface-50">
                                {{ $editingLessonId ? 'تعديل بيانات الدرس' : 'إضافة درس جديد' }}
                            </h3>
                            <button type="button" @click="$wire.resetLesson(); open = false" class="text-surface-400 hover:text-surface-600 dark:hover:text-surface-300 transition-colors">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="p-6 space-y-6 max-h-[calc(100vh-200px)] overflow-y-auto">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Title (Full Width) -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">عنوان الدرس <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model.blur="lessonTitle" class="premium-input w-full" placeholder="مثلاً: مقدمة في بناء تطبيقات الويب">
                                    @error('lessonTitle') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Content Type Selector -->
                                <div>
                                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">نوع المحتوى <span class="text-red-500">*</span></label>
                                    <select wire:model.live="lessonType" class="premium-input w-full">
                                        <option value="text">📄 نص / مقال</option>
                                        <option value="video">🎬 فيديو</option>
                                        <option value="pdf">📁 ملف / PDF</option>
                                        <option value="audio">🎵 صوت</option>
                                        <option value="embed">🌐 تضمين</option>
                                    </select>
                                    @error('lessonType') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Duration -->
                                <div>
                                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">المدة (بالدقائق)</label>
                                    <input type="number" wire:model.blur="lessonDuration" class="premium-input w-full" placeholder="0" min="0">
                                    @error('lessonDuration') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Free Preview Toggle -->
                                <div class="flex items-center justify-between p-4 bg-surface-100 dark:bg-surface-700/50 rounded-xl border border-surface-200 dark:border-surface-600">
                                    <div>
                                        <h4 class="text-sm font-bold text-surface-900 dark:text-surface-100">معاينة مجانية</h4>
                                        <p class="text-xs text-surface-500 dark:text-surface-400 mt-1">إتاحة هذا الدرس للمعاينة قبل الشراء</p>
                                    </div>
                                    <x-table-toggle wire:model="lessonIsFree" :active="$lessonIsFree" />
                                </div>

                                <!-- Conditional Content -->
                                <div class="md:col-span-2 space-y-4 border-t border-surface-200 dark:border-surface-700 pt-4 relative min-h-[150px]">
                                    
                                    <!-- Area Loading Indicator -->
                                    <div wire:loading wire:target="lessonType" class="absolute inset-x-0 top-10 flex flex-col items-center justify-center bg-white/80 dark:bg-surface-800/80 z-20 backdrop-blur-sm h-[calc(100%-40px)] rounded-xl">
                                        <div class="w-12 h-12 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
                                        <p class="text-xs font-bold text-surface-500 mt-4 animate-pulse">جاري تجهيز حقول المحتوى...</p>
                                    </div>

                                    @if($lessonType == 'text')
                                        <div wire:ignore class="animate-fade-in">
                                            <label class="block text-sm font-bold text-surface-700 dark:text-surface-300 mb-2">محتوى الدرس النصي</label>
                                            <div class="rounded-xl overflow-hidden border border-surface-200 dark:border-surface-600 shadow-inner">
                                                <textarea id="lesson-content-editor" wire:model="lessonContent" class="premium-input w-full min-h-[400px]"></textarea>
                                            </div>
                                        </div>
                                    @endif

                                    @if($lessonType == 'video')
                                        <div class="space-y-6 animate-fade-in">
                                            <!-- Video Source Toggle -->
                                            <div class="flex p-1 bg-surface-100 dark:bg-surface-700 rounded-xl w-fit mx-auto shadow-inner border border-surface-200 dark:border-surface-600">
                                                <button type="button" 
                                                    wire:click="$set('videoSource', 'url')"
                                                    class="px-6 py-2 rounded-lg text-xs font-black transition-all {{ $videoSource == 'url' ? 'bg-white dark:bg-surface-600 text-primary-600 shadow-md' : 'text-surface-500 hover:text-surface-700' }}">
                                                    📺 رابط فيديو خارجي
                                                </button>
                                                <button type="button" 
                                                    wire:click="$set('videoSource', 'upload')"
                                                    class="px-6 py-2 rounded-lg text-xs font-black transition-all {{ $videoSource == 'upload' ? 'bg-white dark:bg-surface-600 text-primary-600 shadow-md' : 'text-surface-500 hover:text-surface-700' }}">
                                                    ☁️ رفع ملف فيديو
                                                </button>
                                            </div>

                                            @if($videoSource == 'url')
                                                <div class="animate-slide-in">
                                                    <label class="block text-sm font-bold text-surface-700 dark:text-surface-300 mb-2">رابط فيديو (YouTube / Vimeo / Bunny)</label>
                                                    <div class="relative group">
                                                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-primary-500 group-focus-within:scale-110 transition-transform">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 10l4.5 2.5L15 15V10z" /><rect x="3" y="6" width="12" height="12" rx="2" stroke-width="2"/></svg>
                                                        </div>
                                                        <input type="text" wire:model.blur="lessonVideoUrl" class="premium-input w-full pl-12 font-mono text-sm leading-relaxed" dir="ltr" placeholder="https://www.youtube.com/watch?v=...">
                                                    </div>
                                                    @error('lessonVideoUrl') <span class="text-xs text-red-500 mt-2 block font-bold px-1">{{ $message }}</span> @enderror
                                                    
                                                    @if($lessonVideoUrl && !$errors->has('lessonVideoUrl'))
                                                        <div class="mt-4 p-4 bg-primary-50 rounded-xl border border-primary-100 flex items-center gap-3">
                                                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-primary-600 shadow-sm">
                                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" stroke-width="2"/></svg>
                                                            </div>
                                                            <div class="flex-1">
                                                                <p class="text-[11px] font-black text-primary-700 uppercase tracking-tighter">رابط الفيديو مفعل</p>
                                                                <p class="text-[10px] text-primary-500 truncate max-w-[400px]" dir="ltr">{{ $lessonVideoUrl }}</p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="animate-slide-in space-y-4">
                                                    <div x-data="{ uploading: false, progress: 0 }" 
                                                         x-on:livewire-upload-start="uploading = true" 
                                                         x-on:livewire-upload-finish="uploading = false" 
                                                         x-on:livewire-upload-progress="progress = $event.detail.progress">
                                                        <label class="block text-sm font-bold text-surface-700 dark:text-surface-300 mb-2">رفع ملف فيديو للمنصة</label>
                                                        <div class="border-2 border-dashed border-surface-300 dark:border-surface-600 rounded-2xl p-8 flex flex-col items-center justify-center bg-surface-50 dark:bg-surface-800/50 hover:bg-white hover:border-primary-400 transition-all cursor-pointer group/drop" @click="$refs.videoFile.click()">
                                                            <div class="w-14 h-14 bg-white dark:bg-surface-700 rounded-2xl shadow-sm flex items-center justify-center text-surface-400 group-hover/drop:text-primary-500 group-hover/drop:-translate-y-1 transition-all mb-4">
                                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" stroke-width="2" /></svg>
                                                            </div>
                                                            
                                                            @if($lessonFile)
                                                                <div class="text-xs text-green-600 font-black bg-green-50 px-4 py-2 rounded-full border border-green-200 shadow-sm animate-fade-in flex items-center gap-2">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3"/></svg>
                                                                    تم اختيار: {{ $lessonFile->getClientOriginalName() }}
                                                                </div>
                                                            @elseif($existingLessonFile && $lessonType == 'video')
                                                                <div class="text-xs text-primary-600 font-black bg-primary-50 px-4 py-2 rounded-full border border-primary-100 flex items-center gap-2">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4" stroke-width="2"/></svg>
                                                                    يوجد فيديو مرفوع بالفعل ✅
                                                                </div>
                                                            @else
                                                                <p class="text-sm font-bold text-surface-700 px-4 text-center">انقر هنا أو اسحب الملف لرفعه مباشرة</p>
                                                                <p class="text-[10px] text-surface-400 mt-1 uppercase tracking-widest font-bold italic">Max size: 100MB | MP4, WEBM</p>
                                                            @endif

                                                            <input type="file" wire:model="lessonFile" class="hidden" x-ref="videoFile" accept="video/*">
                                                            
                                                            <div x-show="uploading" class="mt-6 w-full max-w-xs" @click.stop>
                                                                <div class="h-2 bg-surface-200 rounded-full overflow-hidden shadow-inner">
                                                                    <div class="h-full bg-gradient-to-r from-primary-500 to-indigo-600 transition-all duration-300" :style="'width: ' + progress + '%'"></div>
                                                                </div>
                                                                <div class="flex justify-between mt-2 font-black italic"><span class="text-[10px] text-surface-500 uppercase">Uploading...</span><span class="text-[10px] text-primary-600" x-text="progress + '%'"></span></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    @if($lessonType == 'pdf' || $lessonType == 'audio')
                                        <div class="animate-fade-in space-y-4" 
                                             x-data="{ uploading: false, progress: 0 }" 
                                             x-on:livewire-upload-start="uploading = true" 
                                             x-on:livewire-upload-finish="uploading = false" 
                                             x-on:livewire-upload-progress="progress = $event.detail.progress">
                                            <label class="block text-sm font-bold text-surface-700 dark:text-surface-300 mb-2">
                                                {{ $lessonType == 'pdf' ? 'رفع مستند تعليمي (PDF / Word)' : 'رفع ملف صوتي (MP3 / WAV)' }}
                                            </label>
                                            <div class="border-2 border-dashed border-surface-300 dark:border-surface-600 rounded-2xl p-10 flex flex-col items-center justify-center bg-surface-50 dark:bg-surface-800 transition-all hover:bg-white hover:border-primary-400 group/file" @click="$refs.otherFile.click()">
                                                <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center text-surface-400 group-hover/file:scale-110 transition-all mb-4">
                                                    @if($lessonType == 'pdf')
                                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-width="2"/></svg>
                                                    @else
                                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" stroke-width="2"/></svg>
                                                    @endif
                                                </div>
                                                
                                                @if($lessonFile)
                                                    <div class="text-xs text-green-600 font-black bg-green-50 px-4 py-2 rounded-lg border border-green-200 mb-4 animate-fade-in">
                                                        ✅ {{ $lessonFile->getClientOriginalName() }}
                                                    </div>
                                                @elseif($existingLessonFile && ($lessonType == 'pdf' || $lessonType == 'audio'))
                                                    <div class="flex flex-col items-center gap-2 mb-4">
                                                        <div class="text-xs text-primary-600 font-black bg-primary-100/50 px-4 py-1.5 rounded-full border border-primary-200">الملف متوفر في السيرفر ✅</div>
                                                        <a href="{{ asset('storage/' . $existingLessonFile) }}" target="_blank" class="text-[10px] text-blue-500 font-black underline hover:text-blue-700" @click.stop>معاينة الملف الحالي</a>
                                                    </div>
                                                @else
                                                     <button type="button" class="px-8 py-3 bg-white dark:bg-surface-700 border border-surface-200 dark:border-surface-600 rounded-xl text-sm font-black shadow-sm hover:surface-100 transition-all flex items-center gap-2">
                                                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 13h6m-3-3v6M5 7h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2z" stroke-width="2" /></svg>
                                                        إرفاق الملف الآن
                                                    </button>
                                                @endif

                                                <input type="file" wire:model="lessonFile" class="hidden" x-ref="otherFile" accept="{{ $lessonType == 'pdf' ? '.pdf,.doc,.docx' : 'audio/*' }}">
                                                
                                                <div x-show="uploading" class="mt-6 w-full max-w-sm" @click.stop>
                                                    <div class="h-1.5 bg-surface-200 rounded-full overflow-hidden shadow-inner">
                                                        <div class="h-full bg-primary-600 transition-all" :style="'width: ' + progress + '%'"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($lessonType == 'embed')
                                        <div>
                                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">كود التضمين</label>
                                            <textarea wire:model.blur="lessonEmbedCode" class="premium-input w-full font-mono text-xs" placeholder="&lt;iframe src='...'&gt;&lt;/iframe&gt;" rows="4" dir="ltr"></textarea>
                                            @error('lessonEmbedCode') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="px-6 py-4 bg-surface-50 dark:bg-surface-800/50 border-t border-surface-200 dark:border-surface-700 flex items-center justify-end gap-3">
                            <button type="button" @click="$wire.resetLesson(); open = false" class="px-4 py-2 bg-white dark:bg-surface-700 border border-surface-200 dark:border-surface-600 text-surface-700 dark:text-surface-200 rounded-xl font-medium hover:bg-surface-50 dark:hover:bg-surface-600 transition-colors">
                                إلغاء
                            </button>
                            <button type="submit" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold shadow-sm shadow-primary-500/20 transition-all flex items-center justify-center gap-2 min-w-[120px]">
                                <span wire:loading.remove wire:target="saveLesson">
                                    {{ $editingLessonId ? 'حفظ التعديلات' : 'إضافة الدرس' }}
                                </span>
                                <span wire:loading wire:target="saveLesson">
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
    </template>

    <style>
        .premium-input {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.6rem 0.9rem;
            font-size: 0.875rem;
            color: #1e293b;
            transition: all 0.2s ease;
        }
        .dark .premium-input {
            background: #1e293b;
            border-color: #334155;
            color: #f8fafc;
        }
        .premium-input:focus {
            border-color: #3b82f6;
            ring: 1px solid #3b82f6;
            outline: none;
        }
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        .animate-slide-in { animation: slideIn 0.3s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideIn { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        [x-cloak] { display: none !important; }
    </style>

    @push('scripts')
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            function initEditor(selector, stateName) {
                tinymce.remove(selector);
                tinymce.init({
                    selector: selector,
                    directionality: 'rtl',
                    height: 380,
                    menubar: false,
                    branding: false,
                    promotion: false,
                    plugins: 'advlist autolink lists link image charmap preview anchor media table code wordcount emoticons codesample',
                    toolbar: 'undo redo | bold italic | bullist numlist | link image emoticons',
                    skin: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'oxide-dark' : 'oxide',
                    content_css: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'default',
                    content_style: 'body { font-family:Inter,sans-serif; font-size:15px; line-height: 1.6; padding: 15px; }',
                    setup: function (editor) {
                        editor.on('blur change keyup', () => {
                            @this.set(stateName, editor.getContent(), false);
                        });
                        editor.on('init', () => {
                            editor.setContent(@this.get(stateName) || '');
                        });
                    }
                });
            }

            if (document.querySelector('#course-content-editor')) initEditor('#course-content-editor', 'content');
            
            Livewire.on('open-modal', (data) => {
                if (data[0] === 'lesson-form') {
                    setTimeout(() => { 
                        if (document.querySelector('#lesson-content-editor')) {
                            initEditor('#lesson-content-editor', 'lessonContent');
                        }
                    }, 600); // stable delay for teleport
                }
            });

            Livewire.on('tabChanged', () => {
                setTimeout(() => { if (document.querySelector('#course-content-editor')) initEditor('#course-content-editor', 'content'); }, 150);
            });
        });
    </script>
    @endpush
</div>
