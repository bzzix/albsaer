<div>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-surface-900">إعدادات الإشعارات</h2>
            <p class="text-surface-500 mt-1">تهيئة بروتوكولات الربط للبريد الإلكتروني والواتساب</p>
        </div>
        <button wire:click="save" wire:loading.attr="disabled" wire:target="save"
            class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold shadow-lg shadow-primary-500/30 transition-all flex items-center gap-2">
            <svg wire:loading.remove wire:target="save" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <svg wire:loading wire:target="save" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>حفظ الإعدادات</span>
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Email SMTP Settings -->
        <div class="space-y-6">
            <div class="glass-panel p-6 rounded-2xl border border-white/40 shadow-glass">
                <h3 class="text-lg font-bold text-surface-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    إعدادات البريد (SMTP)
                </h3>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-sm font-bold text-surface-700">خادم البريد (Host)</label>
                            <input type="text" wire:model="email_host" placeholder="smtp.gmail.com"
                                   class="w-full px-4 py-2.5 rounded-xl premium-input transition-all text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-surface-700">المنفذ (Port)</label>
                            <input type="number" wire:model="email_port" placeholder="587"
                                   class="w-full px-4 py-2.5 rounded-xl premium-input transition-all text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-surface-700">المزود (Driver)</label>
                            <select wire:model="email_driver" class="w-full px-4 py-2.5 rounded-xl premium-input transition-all text-sm">
                                <option value="smtp">SMTP</option>
                                <option value="mailgun">Mailgun</option>
                                <option value="log">Log (للبرمجة)</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-surface-700">التشفير (Encryption)</label>
                            <select wire:model="email_encryption" class="w-full px-4 py-2.5 rounded-xl premium-input transition-all text-sm">
                                <option value="tls">TLS</option>
                                <option value="ssl">SSL</option>
                                <option value="">بدون تشفير</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-surface-700">اسم المستخدم</label>
                        <input type="text" wire:model="email_username" placeholder="info@example.com"
                               class="w-full px-4 py-2.5 rounded-xl premium-input transition-all text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-surface-700">كلمة المرور</label>
                        <input type="password" wire:model="email_password" placeholder="••••••••"
                               class="w-full px-4 py-2.5 rounded-xl premium-input transition-all text-sm">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-surface-700">اسم المرسل</label>
                            <input type="text" wire:model="email_from_name"
                                   class="w-full px-4 py-2.5 rounded-xl premium-input transition-all text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-surface-700">بريد المرسل</label>
                            <input type="email" wire:model="email_from_address"
                                   class="w-full px-4 py-2.5 rounded-xl premium-input transition-all text-sm">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- WhatsApp Respond.io Settings -->
        <div class="space-y-6">
            <div class="glass-panel p-6 rounded-2xl border border-white/40 shadow-glass">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-surface-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        إعدادات واتساب (Respond.io)
                    </h3>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="whatsapp_enabled" class="sr-only peer">
                        <div class="w-11 h-6 bg-surface-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>

                <div class="space-y-4 {{ $whatsapp_enabled ? '' : 'opacity-50 pointer-events-none' }}">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-surface-700">رابط Webhook / API URL</label>
                        <input type="text" wire:model="whatsapp_api_url" placeholder="https://api.respond.io"
                               class="w-full px-4 py-2.5 rounded-xl premium-input transition-all text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-surface-700">مفتاح API (Bearer Token)</label>
                        <input type="text" wire:model="whatsapp_api_key" placeholder="Bearer ..."
                               class="w-full px-4 py-2.5 rounded-xl premium-input transition-all text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-surface-700">معرف القناة (Channel ID)</label>
                        <input type="text" wire:model="whatsapp_channel_id"
                               class="w-full px-4 py-2.5 rounded-xl premium-input transition-all text-sm">
                    </div>

                    <div class="mt-8 p-4 bg-green-50 rounded-xl border border-green-100">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-[11px] text-green-800 leading-relaxed font-medium">
                                <p class="font-bold mb-1 italic underline">تذكير هام:</p>
                                <p>يجب تفعيل الطوابير (Queues) لضمان إرسال إشعارات الواتساب في الخلفية دون التأثير على سرعة الواجهة.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
