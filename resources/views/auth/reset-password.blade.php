<x-guest-layout>
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Dashboard Style Logo Header -->
        <div class="flex flex-col items-center justify-center">
            <a href="/" class="group flex flex-col items-center gap-4 transition-transform duration-300 hover:scale-105">
                @php
                    $siteName = get_setting('site_name', 'معهد البصائر');
                    $siteLogo = get_setting('site_logo');
                    $displayMode = get_setting('site_display_mode', 'both');
                    $siteInitial = mb_substr($siteName, 0, 1, 'UTF-8');
                @endphp
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
                    إعادة تعيين كلمة المرور
                </div>
            </a>
        </div>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px] reveal-anim">
        <!-- Glassmorphism Card (Dashboard Style) -->
        <div class="glass-panel overflow-hidden rounded-3xl p-8 sm:p-12 shadow-glass relative">
            <!-- Decorative inner glow -->
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-primary-400/15 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-secondary-400/10 rounded-full blur-3xl"></div>
            
            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50/50 p-4 backdrop-blur-sm relative z-20">
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h3 class="font-bold text-red-800 text-sm">يوجد خطأ في البيانات المدخلة</h3>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-6 relative z-10" x-data="{ showPw: false, showConfirmPw: false }">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Input -->
                <div class="space-y-2 group">
                    <label for="email" class="block text-sm font-bold text-surface-700 transition-colors group-focus-within:text-primary-600">البريد الإلكتروني</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                            <svg class="h-5 w-5 text-surface-400 group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email', $request->email) }}" autofocus
                            class="block w-full rounded-2xl border-surface-200 bg-surface-50/50 py-3.5 pr-12 pl-4 text-surface-900 shadow-sm placeholder:text-surface-400 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none" 
                            placeholder="user@example.com">
                    </div>
                </div>

                <!-- Password Input -->
                <div class="space-y-2 group">
                    <label for="password" class="block text-sm font-bold text-surface-700 transition-colors group-focus-within:text-primary-600">كلمة المرور الجديدة</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                            <svg class="h-5 w-5 text-surface-400 group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>
                        <input id="password" name="password" :type="showPw ? 'text' : 'password'" autocomplete="new-password" required
                            class="block w-full rounded-2xl border-surface-200 bg-surface-50/50 py-3.5 pr-12 pl-12 text-surface-900 shadow-sm placeholder:text-surface-400 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none"
                            placeholder="••••••••">
                        
                        <!-- Toggle Button -->
                        <button type="button" @click="showPw = !showPw" class="absolute inset-y-0 left-0 flex items-center pl-4 text-surface-400 hover:text-primary-500 transition-colors focus:outline-none">
                            <svg x-show="!showPw" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.399 9.943 7.213 7.5 12 7.5s8.601 2.443 9.964 4.178a1.012 1.012 0 010 .644C20.601 14.057 16.787 16.5 12 16.5s-8.601-2.443-9.964-4.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg x-show="showPw" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password Input -->
                <div class="space-y-2 group">
                    <label for="password_confirmation" class="block text-sm font-bold text-surface-700 transition-colors group-focus-within:text-primary-600">تأكيد كلمة المرور</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                            <svg class="h-5 w-5 text-surface-400 group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" :type="showConfirmPw ? 'text' : 'password'" autocomplete="new-password" required
                            class="block w-full rounded-2xl border-surface-200 bg-surface-50/50 py-3.5 pr-12 pl-12 text-surface-900 shadow-sm placeholder:text-surface-400 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none"
                            placeholder="••••••••">
                        
                        <!-- Toggle Button -->
                        <button type="button" @click="showConfirmPw = !showConfirmPw" class="absolute inset-y-0 left-0 flex items-center pl-4 text-surface-400 hover:text-primary-500 transition-colors focus:outline-none">
                            <svg x-show="!showConfirmPw" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.399 9.943 7.213 7.5 12 7.5s8.601 2.443 9.964 4.178a1.012 1.012 0 010 .644C20.601 14.057 16.787 16.5 12 16.5s-8.601-2.443-9.964-4.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg x-show="showConfirmPw" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="group relative flex w-full justify-center items-center gap-2 overflow-hidden rounded-2xl bg-primary-600 hover:bg-primary-700 px-4 py-4 text-sm font-bold text-white shadow-xl shadow-primary-500/20 transition-all active:scale-[0.98]">
                        <span>تحديث كلمة المرور</span>
                        <svg class="w-5 h-5 rtl:rotate-180 transition-transform group-hover:translate-x-1 rtl:group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>

