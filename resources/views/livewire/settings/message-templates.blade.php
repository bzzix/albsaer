<div>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-surface-900">قوالب الرسائل</h2>
            <p class="text-surface-500 mt-1">إدارة قوالب البريد الإلكتروني والواتساب والمتغيرات الديناميكية</p>
        </div>
        @if(!$editingTemplateId)
        <button wire:click="$set('editingTemplateId', 'new')"
            class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold shadow-lg shadow-primary-500/30 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <span>إضافة قالب جديد</span>
        </button>
        @endif
    </div>

    @if (session()->has('message'))
        {{-- Removed and replaced by iziToast --}}
    @endif

    @if($editingTemplateId)
        <div class="glass-panel p-8 rounded-2xl border border-white/40 shadow-glass reveal-anim mb-12">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-bold text-surface-900">{{ $editingTemplateId === 'new' ? 'إنشاء قالب جديد' : 'تعديل القالب' }}</h3>
                <button wire:click="cancelEdit" class="text-surface-400 hover:text-surface-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="save" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-surface-700">اسم القالب (الرمز الكودي)</label>
                        <input type="text" wire:model="name" placeholder="مثلاً: student_welcome"
                               class="w-full px-4 py-2.5 rounded-xl premium-input transition-all text-sm">
                        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-surface-700">نوع القالب</label>
                        <select wire:model="type" class="w-full px-4 py-2.5 rounded-xl premium-input transition-all text-sm">
                            <option value="">اختر النوع...</option>
                            <option value="email">بريد إلكتروني (Email)</option>
                            <option value="whatsapp">واتساب (WhatsApp)</option>
                        </select>
                        @error('type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                @if($type === 'email')
                <div class="space-y-2">
                    <label class="text-sm font-bold text-surface-700">عنوان الرسالة (Subject)</label>
                    <input type="text" wire:model="subject" 
                           class="w-full px-4 py-2.5 rounded-xl premium-input transition-all text-sm">
                    @error('subject') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                @endif

                <div class="space-y-2">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-bold text-surface-700">المحتوى</label>
                        <div class="flex flex-wrap gap-2">
                            <span class="text-[10px] text-surface-400 font-bold uppercase py-1">المتغيرات المتاحة:</span>
                            @foreach(explode(',', $variables) as $var)
                                @if(trim($var))
                                <button type="button" onclick="insertVariable('{{ '{' . trim($var) . '}' }}')"
                                        class="px-2 py-0.5 bg-primary-50 text-primary-600 rounded text-[10px] font-bold hover:bg-primary-100 transition-colors">
                                    {{ '{' . trim($var) . '}' }}
                                </button>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <textarea id="template-content" wire:model="content" rows="8"
                              class="w-full px-4 py-2.5 rounded-xl premium-input transition-all text-sm leading-relaxed"></textarea>
                    @error('content') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-surface-700">المتغيرات (مفصولة بفاصلة)</label>
                        <input type="text" wire:model="variables" placeholder="student_name, course_name, ..."
                               class="w-full px-4 py-2.5 rounded-xl premium-input transition-all text-sm">
                        <p class="text-[10px] text-surface-400 mt-1 italic">المتغيرات التي سيتم تعويضها في المحتوى</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-surface-700">وقت الإرسال التلقائي</label>
                        <input type="time" wire:model="schedule_time" 
                               class="w-full px-4 py-2.5 rounded-xl premium-input transition-all text-sm">
                        <p class="text-[10px] text-surface-400 mt-1 italic">سيتم الإرسال في هذا الوقت إذا كان القالب مجدولاً</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4 border-t border-surface-100">
                    <button type="submit" wire:loading.attr="disabled" wire:target="save"
                        class="px-8 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold transition-all shadow-lg shadow-primary-500/20">
                        <span wire:loading.remove wire:target="save">حفظ القالب</span>
                        <span wire:loading wire:target="save" class="animate-spin inline-block h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
                    </button>
                    <button type="button" wire:click="cancelEdit"
                        class="px-8 py-2.5 bg-surface-100 hover:bg-surface-200 text-surface-600 rounded-xl font-bold transition-all">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($templates as $template)
            <div class="glass-panel p-6 rounded-2xl border border-white/40 shadow-glass hover:shadow-xl transition-all group relative overflow-hidden">
                <div class="absolute top-0 right-0 p-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <div class="flex gap-2">
                        <button wire:click="editTemplate({{ $template->id }})" class="p-1.5 bg-white/80 rounded-lg text-primary-600 shadow-sm border border-primary-100 hover:bg-primary-600 hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </button>
                        <button wire:click="deleteTemplate({{ $template->id }})" wire:confirm="هل أنت متأكد من حذف هذا القالب؟" 
                                class="p-1.5 bg-white/80 rounded-lg text-red-600 shadow-sm border border-red-100 hover:bg-red-600 hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-start justify-between mb-4">
                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $template->type === 'email' ? 'bg-blue-50 text-blue-600' : 'bg-green-50 text-green-600' }}">
                        {{ $template->type === 'email' ? 'Email' : 'WhatsApp' }}
                    </span>
                    <button wire:click="toggleStatus({{ $template->id }})" class="focus:outline-none">
                        <div class="w-10 h-5 bg-{{ $template->is_active ? 'primary-600' : 'surface-200' }} rounded-full p-0.5 transition-colors duration-200">
                            <div class="w-4 h-4 bg-white rounded-full transition-transform duration-200 {{ $template->is_active ? 'translate-x-5 rtl:-translate-x-5' : '' }}"></div>
                        </div>
                    </button>
                </div>

                <h4 class="text-lg font-bold text-surface-900 mb-2">{{ $template->name }}</h4>
                <p class="text-sm text-surface-500 line-clamp-2 leading-relaxed h-10">{{ $template->content }}</p>

                <div class="mt-6 pt-4 border-t border-surface-100 flex items-center justify-between">
                    <div class="flex flex-wrap gap-1">
                        @foreach(array_slice($template->variables ?? [], 0, 3) as $var)
                            <span class="text-[9px] font-bold text-surface-400">{{ '{'.$var.'}' }}</span>
                        @endforeach
                        @if(count($template->variables ?? []) > 3)
                            <span class="text-[9px] font-bold text-surface-400">...</span>
                        @endif
                    </div>
                    @if($template->schedule_time)
                        <span class="text-[10px] font-bold text-primary-500 bg-primary-50 px-2 py-0.5 rounded-full">
                            {{ $template->schedule_time }}
                        </span>
                    @endif
                </div>
            </div>
        @endforeach

        @if(count($templates) === 0 && !$editingTemplateId)
            <div class="md:col-span-2 lg:col-span-3 py-20 text-center glass-panel rounded-2xl border border-white/40">
                <svg class="w-16 h-16 text-surface-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p class="text-surface-500 font-medium">لا توجد قوالب مضافة بعد.</p>
                <button wire:click="$set('editingTemplateId', 'new')" class="text-primary-600 font-bold mt-2 hover:underline">أضف قالبك الأول الآن</button>
            </div>
        @endif
    </div>

    <script>
        function insertVariable(variable) {
            const textarea = document.getElementById('template-content');
            if (textarea) {
                const start = textarea.selectionStart;
                const end = textarea.selectionEnd;
                const text = textarea.value;
                const before = text.substring(0, start);
                const after = text.substring(end, text.length);
                textarea.value = before + variable + after;
                
                // Trigger Livewire data binding update
                textarea.dispatchEvent(new Event('input'));
                
                // Focus back to textarea
                textarea.focus();
                textarea.selectionStart = textarea.selectionEnd = start + variable.length;
            }
        }
    </script>
</div>
