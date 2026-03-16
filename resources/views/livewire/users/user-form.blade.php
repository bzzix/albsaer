<div x-data="{ open: false }" 
     x-show="open" 
     @open-modal.window="if ($event.detail[0] === 'user-form') open = true" 
     @close-modal.window="if ($event.detail[0] === 'user-form') open = false" 
     @keydown.escape.window="$wire.resetForm(); open = false"
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    
    <!-- Backdrop -->
    <div x-show="open" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-surface-900/60 backdrop-blur-sm transition-opacity"></div>

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
            
            <form wire:submit="save">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-surface-900 dark:text-surface-50">
                        {{ $isEditMode ? 'تعديل بيانات المستخدم' : 'إضافة مستخدم جديد' }}
                    </h3>
                    <button type="button" @click="$wire.resetForm(); open = false" class="text-surface-400 hover:text-surface-600 dark:hover:text-surface-300 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">الاسم رباعي <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="name" class="premium-input w-full" placeholder="أدخل اسم المستخدم بالكامل">
                            @error('name') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">البريد الإلكتروني <span class="text-red-500">*</span></label>
                            <input type="email" wire:model="email" class="premium-input w-full text-left" dir="ltr" placeholder="user@example.com">
                            @error('email') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">رقم الجوال</label>
                            <input type="text" wire:model="phone" class="premium-input w-full text-left" dir="ltr" placeholder="+966xxxxxxxxx">
                            @error('phone') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- National ID -->
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">رقم الهوية</label>
                            <input type="text" wire:model="national_id" class="premium-input w-full text-left" dir="ltr" placeholder="10xxxxxxxx">
                            @error('national_id') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- WhatsApp Number -->
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">رقم الواتساب</label>
                            <input type="text" wire:model="whatsapp_number" class="premium-input w-full text-left" dir="ltr" placeholder="+966xxxxxxxxx">
                            @error('whatsapp_number') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Role -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">الصلاحية (الدور) <span class="text-red-500">*</span></label>
                            <select wire:model="role" class="premium-input w-full">
                                <option value="">اختر الصلاحية المناسبة</option>
                                @foreach($this->availableRoles as $availableRole)
                                    @php
                                        $roleNamesAr = [
                                            'super-admin' => 'مدير النظام',
                                            'admin'       => 'مشرف',
                                            'teacher'     => 'مدرب',
                                            'student'     => 'طالب',
                                            'parent'      => 'ولي أمر',
                                        ];
                                        $displayName = $roleNamesAr[$availableRole->name] ?? $availableRole->name;
                                    @endphp
                                    <option value="{{ $availableRole->name }}">{{ $displayName }}</option>
                                @endforeach
                            </select>
                            @error('role') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Password -->
                        <div x-data="{ show: false }">
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">
                                كلمة المرور 
                                @if(!$isEditMode) <span class="text-red-500">*</span> @endif
                            </label>
                            <div class="relative group">
                                <input :type="show ? 'text' : 'password'" 
                                       wire:model="password" 
                                       class="premium-input w-full text-left pl-10" 
                                       dir="ltr" 
                                       placeholder="{{ $isEditMode ? 'اتركه فارغاً للاحتفاظ بكلمة المرور الحالية' : '********' }}">
                                <button type="button" @click="show = !show" class="absolute inset-y-0 left-0 px-3 flex items-center text-surface-400 hover:text-primary-600 transition-colors">
                                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            @error('password') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>



                        <!-- Active Status -->
                        <div class="md:col-span-2 flex items-center justify-between p-4 bg-surface-100 dark:bg-surface-700/50 rounded-xl border border-surface-200 dark:border-surface-600">
                            <div>
                                <h4 class="text-sm font-bold text-surface-900 dark:text-surface-100">حالة الحساب</h4>
                                <p class="text-xs text-surface-500 dark:text-surface-400 mt-1">تحديد ما إذا كان المستخدم قادراً على تسجيل الدخول للنظام أم لا.</p>
                            </div>
                            <!-- Removed ID check in UI, handled in backend or you can hide if $userId === 1 -->
                            @if($userId === 1)
                                <span class="text-xs font-bold text-red-500">مفعل دائماً</span>
                            @else
                                <x-table-toggle wire:model="is_active" :active="$is_active" />
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-surface-50 dark:bg-surface-800/50 border-t border-surface-200 dark:border-surface-700 flex items-center justify-end gap-3">
                    <button type="button" @click="$wire.resetForm(); open = false" class="px-4 py-2 bg-white dark:bg-surface-700 border border-surface-200 dark:border-surface-600 text-surface-700 dark:text-surface-200 rounded-xl font-medium hover:bg-surface-50 dark:hover:bg-surface-600 transition-colors">
                        إلغاء
                    </button>
                    <button type="submit" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold shadow-sm shadow-primary-500/20 transition-all flex items-center justify-center gap-2 min-w-[120px]">
                        <span wire:loading.remove wire:target="save">
                            {{ $isEditMode ? 'حفظ التعديلات' : 'إضافة المستخدم' }}
                        </span>
                        <span wire:loading wire:target="save">
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
