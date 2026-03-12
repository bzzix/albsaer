<x-guest-layout>
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Dashboard Style Logo Header -->
        <div class="flex flex-col items-center justify-center">
            @php
                $siteName = get_setting('site_name', 'معهد البصائر');
                $siteLogo = get_setting('site_logo');
                $displayMode = get_setting('site_display_mode', 'both');
                $siteInitial = mb_substr($siteName, 0, 1, 'UTF-8');
            @endphp
            
            <a href="/" class="group flex flex-col items-center gap-4 transition-transform duration-300 hover:scale-105">
                <div class="flex items-center gap-3 px-2 h-12">
                    @if($displayMode === 'both' || $displayMode === 'logo')
                    <div class="{{ $displayMode === 'logo' ? 'h-24 w-24' : 'h-12 w-12' }} flex-shrink-0 rounded-2xl bg-surface-100 flex items-center justify-center text-surface-900 border border-surface-200 overflow-hidden shadow-sm transition-all duration-300">
                        @if($siteLogo)
                            <img src="{{ $siteLogo }}" alt="{{ $siteName }}" class="h-full w-full object-contain p-2">
                        @else
                            <span class="font-display font-bold {{ $displayMode === 'logo' ? 'text-4xl' : 'text-2xl' }}">{{ $siteInitial }}</span>
                        @endif
                    </div>
                    @endif
                    
                    @if($displayMode === 'both' || $displayMode === 'name')
                    <div class="text-right">
                        <h1 class="font-bold text-2xl tracking-tight text-surface-900 leading-none">{{ $siteName }}</h1>
                        <p class="text-[11px] text-surface-500 font-bold mt-1 tracking-wider uppercase">الإدارة الذكية</p>
                    </div>
                    @endif
                </div>
                <div class="mt-4 inline-flex items-center px-3 py-1 rounded-full bg-primary-50 border border-primary-100 text-primary-600 text-xs font-bold uppercase tracking-widest">
                    التحقق بخطوتين
                </div>
            </a>
        </div>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px]">
        <!-- Glassmorphism Card (Dashboard Style) -->
        <div class="glass-panel overflow-hidden rounded-3xl p-8 sm:p-12 shadow-glass relative">
            <!-- Decorative inner glow -->
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-primary-400/10 rounded-full blur-3xl"></div>
            
            <div x-data="{ recovery: false }" class="relative z-10">
                <div class="mb-8 text-sm leading-relaxed text-surface-600 text-right font-bold" x-show="! recovery">
                    {{ __('يرجى تأكيد وصولك إلى حسابك عن طريق إدخال رمز المصادقة المقدم من تطبيق المصادقة الخاص بك.') }}
                </div>

                <div class="mb-8 text-sm leading-relaxed text-surface-600 text-right font-bold" x-cloak x-show="recovery">
                    {{ __('يرجى تأكيد وصولك إلى حسابك عن طريق إدخال أحد رموز الاسترداد في حالات الطوارئ الخاصة بك.') }}
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50/50 p-4 backdrop-blur-sm">
                        <div class="flex items-center gap-3">
                            <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <h3 class="font-bold text-red-800 text-sm">أعد التحقق من الرمز المدخل</h3>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-6">
                    @csrf

                    <!-- Auth Code Input -->
                    <div class="space-y-2 group" x-show="!recovery">
                        <label for="code" class="block text-sm font-bold text-surface-700 transition-colors group-focus-within:text-primary-600">رمز المصادقة</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                                <svg class="h-5 w-5 text-surface-400 group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                </svg>
                            </div>
                            <input id="code" x-ref="code" class="block w-full rounded-2xl border-surface-200 bg-surface-50/50 py-3.5 pr-12 pl-4 text-surface-900 shadow-sm placeholder:text-surface-400 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none" type="text" inputmode="numeric" name="code" autofocus autocomplete="one-time-code" placeholder="123456" />
                        </div>
                    </div>

                    <!-- Recovery Code Input -->
                    <div class="space-y-2 group" x-cloak x-show="recovery">
                        <label for="recovery_code" class="block text-sm font-bold text-surface-700 transition-colors group-focus-within:text-primary-600">رمز الاسترداد</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                                <svg class="h-5 w-5 text-surface-400 group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1-.43-1.563A6 6 0 1121.75 8.25z" />
                                </svg>
                            </div>
                            <input id="recovery_code" x-ref="recovery_code" class="block w-full rounded-2xl border-surface-200 bg-surface-50/50 py-3.5 pr-12 pl-4 text-surface-900 shadow-sm placeholder:text-surface-400 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none" type="text" name="recovery_code" autocomplete="one-time-code" placeholder="abcdef-987654" />
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="group relative flex w-full justify-center items-center gap-2 overflow-hidden rounded-2xl bg-primary-600 hover:bg-primary-700 px-4 py-4 text-sm font-bold text-white shadow-xl shadow-primary-500/20 transition-all active:scale-[0.98]">
                            <span>تسجيل الدخول</span>
                            <svg class="w-5 h-5 rtl:rotate-180 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </button>
                    </div>

                    <div class="mt-8 pt-6 border-t border-surface-200 text-center text-sm font-bold">
                        <button type="button" class="text-primary-600 hover:text-primary-500 transition-colors focus:outline-none"
                            x-show="!recovery"
                            x-on:click="recovery = true; $nextTick(() => { $refs.recovery_code.focus() })">
                            {{ __('استخدام رمز الاسترداد') }}
                        </button>

                        <button type="button" class="text-primary-600 hover:text-primary-500 transition-colors focus:outline-none"
                            x-cloak x-show="recovery"
                            x-on:click="recovery = false; $nextTick(() => { $refs.code.focus() })">
                            {{ __('استخدام رمز المصادقة (التطبيق)') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>

