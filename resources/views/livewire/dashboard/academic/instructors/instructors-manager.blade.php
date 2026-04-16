<div class="min-h-screen bg-surface-50 p-4 md:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-black text-surface-900 arabic-font">إدارة المعلمين</h1>
                <p class="text-surface-500 mt-1 arabic-font">إدارة أطقم التدريب وربطهم بالمواد والأعوام الأكاديمية</p>
            </div>
            <div class="flex gap-2">
                <button wire:click="openCreateModal('select')" 
                    wire:loading.attr="disabled"
                    wire:target="openCreateModal('select')"
                    class="inline-flex items-center gap-2 px-5 py-3 bg-white text-primary-600 border border-primary-200 rounded-xl font-bold shadow-sm hover:bg-primary-50 transition-all arabic-font disabled:opacity-50">
                    <svg wire:loading.remove wire:target="openCreateModal('select')" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <svg wire:loading wire:target="openCreateModal('select')" class="w-5 h-5 animate-spin text-primary-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    تعيين من القائمة
                </button>
                <button wire:click="openCreateModal('new')" 
                    wire:loading.attr="disabled"
                    wire:target="openCreateModal('new')"
                    class="inline-flex items-center gap-2 px-5 py-3 bg-primary-600 text-white rounded-xl font-bold shadow-lg shadow-primary-500/30 hover:bg-primary-700 transition-all arabic-font disabled:opacity-50">
                    <svg wire:loading.remove wire:target="openCreateModal('new')" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <svg wire:loading wire:target="openCreateModal('new')" class="w-5 h-5 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    مدرب جديد
                </button>
            </div>
        </div>

        <!-- Filters & Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-surface-100 p-4 mb-6 relative">
            <div class="mb-4 flex items-center gap-4">
                <div class="flex-1">
                    <x-label value="تصنيف حسب العام الأكاديمي :" class="arabic-font text-surface-500 mb-2 inline-block font-bold" />
                    <select wire:model.live="activeYearId" class="w-full md:w-1/3 px-4 py-2 bg-surface-50 border border-surface-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 arabic-font transition-all font-bold text-surface-700">
                        <option value="">— كافة الأعوام —</option>
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
                <livewire:dashboard.academic.instructors.instructors-table :activeYearId="$activeYearId" />
            </div>
        </div>

        <!-- Creation/Assignment Modal -->
        <x-modal wire:model="showCreateModal" maxWidth="lg">
            <div class="p-8">
                <h3 class="text-2xl font-black text-surface-900 arabic-font mb-2">
                    {{ $creationMode === 'new' ? 'تسجيل مدرب جديد' : 'تعيين مدرب من القائمة' }}
                </h3>
                <p class="text-sm text-surface-500 arabic-font mb-6 pb-4 border-b border-surface-100">هذا المدرب سيتم تعيينه وتنشيطه ضمن العام الأكاديمي الحالي المختار في الفلتر.</p>

                <form wire:submit.prevent="saveInstructor" class="space-y-4">
                    
                    @if($creationMode === 'select')
                        <div>
                            <x-label value="اختر المستخدم (حساب النظام)" class="arabic-font mb-2" />
                            <select wire:model="selectedUserId" class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-xl focus:ring-2 focus:ring-primary-500 font-bold text-surface-800 arabic-font">
                                <option value="">— اختر مستخدماً لترقيته —</option>
                                @foreach($availableUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            @error('selectedUserId') <span class="text-xs text-red-500 arabic-font">{{ $message }}</span> @enderror
                        </div>
                    @else
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <x-label value="الاسم الكامل" class="arabic-font mb-1" />
                                <x-input wire:model="name" type="text" class="w-full arabic-font" placeholder="الاسم ثلاثي" />
                                @error('name') <span class="text-xs text-red-500 arabic-font">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <x-label value="البريد الإلكتروني" class="arabic-font mb-1" />
                                <x-input wire:model="email" type="email" class="w-full" placeholder="email@example.com" />
                                @error('email') <span class="text-xs text-red-500 arabic-font">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <x-label value="كلمة المرور" class="arabic-font mb-1" />
                                <x-input wire:model="password" type="password" class="w-full" placeholder="****" />
                                @error('password') <span class="text-xs text-red-500 arabic-font">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif

                    <div class="border-t border-surface-100 pt-4 mt-4">
                        <x-label value="التخصص الأكاديمي" class="arabic-font mb-1" />
                        <x-input wire:model="specialization" type="text" class="w-full arabic-font" placeholder="مثال: الرياضيات، اللغات" />
                        @error('specialization') <span class="text-xs text-red-500 arabic-font">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-label value="نبذة أو ملاحظات" class="arabic-font mb-1" />
                        <textarea wire:model="bio" rows="2" class="w-full border-surface-300 focus:border-primary-500 rounded-xl shadow-sm arabic-font"></textarea>
                    </div>

                    <div class="pt-2">
                        <x-label value="المواد المسندة إليه في هذا العام" class="arabic-font mb-2" />
                        <div class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto p-2 bg-surface-50 rounded-xl border border-surface-200">
                            @foreach($subjects as $subj)
                                <label class="flex items-center gap-2 cursor-pointer p-1.5 hover:bg-white rounded-lg transition-colors">
                                    <input type="checkbox" wire:model="assignedSubjects" value="{{ $subj->id }}" class="rounded text-primary-600 border-surface-300">
                                    <span class="text-xs font-bold text-surface-700 arabic-font">{{ $subj->name }}</span>
                                </label>
                            @endforeach
                            @if(count($subjects) === 0)
                                <span class="text-xs text-surface-400 p-2 col-span-2 arabic-font">لا توجد مواد مسجلة في النظام الأساسي بعد.</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4 border-t border-surface-100">
                        <x-button type="submit" 
                            wire:loading.attr="disabled"
                            wire:target="saveInstructor"
                            class="flex-1 justify-center py-3 bg-primary-600 hover:bg-primary-700 arabic-font">
                            <svg wire:loading wire:target="saveInstructor" class="animate-spin -mr-1 ml-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            حفظ وتعيين المدرب
                        </x-button>
                        <x-secondary-button type="button" wire:click="$set('showCreateModal', false)" class="px-6 py-3 arabic-font">
                            إلغاء
                        </x-secondary-button>
                    </div>

                </form>
            </div>
        </x-modal>
    </div>
</div>
