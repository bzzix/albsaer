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
                    تأكيد البريد الإلكتروني
                </div>
            </a>
        </div>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px]">
        <!-- Glassmorphism Card (Dashboard Style) -->
        <div class="glass-panel overflow-hidden rounded-3xl p-8 sm:p-12 shadow-glass relative">
            <!-- Decorative inner glow -->
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-primary-400/10 rounded-full blur-3xl"></div>
            
            <div class="mb-8 text-sm leading-relaxed text-surface-600 text-right font-bold relative z-10">
                شكراً لانضمامك إلينا! قبل البدء، هل يمكنك تأكيد بريدك الإلكتروني من خلال النقر على الرابط الذي أرسلناه لك للتو؟ لمتابعة استخدام النظام.
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-8 rounded-2xl border border-green-200 bg-green-50/50 p-4 font-bold text-sm text-green-700 text-right backdrop-blur-sm relative z-10">
                    تم إرسال رابط تأكيد جديد إلى البريد الإلكتروني الذي قمت بتسجيله.
                </div>
            @endif

            <div class="mt-8 flex flex-col gap-6 items-center relative z-10">
                <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                    @csrf
                    <button type="submit" class="group relative flex w-full justify-center items-center gap-2 overflow-hidden rounded-2xl bg-primary-600 hover:bg-primary-700 px-4 py-4 text-sm font-bold text-white shadow-xl shadow-primary-500/20 transition-all active:scale-[0.98]">
                        <span>إعادة إرسال رابط التأكيد</span>
                        <svg class="w-5 h-5 rtl:rotate-180 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.125A59.769 59.769 0 0121.485 12 59.768 59.768 0 013.27 20.875L5.999 12Zm0 0h7.5" />
                        </svg>
                    </button>
                </form>

                <div class="flex items-center justify-between w-full pt-6 border-t border-surface-200 text-sm font-bold">
                    <a href="{{ route('profile.show') }}" class="text-surface-600 hover:text-primary-600 transition-colors">
                        الملف الشخصي
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-700 transition-colors font-bold outline-none">
                            تسجيل الخروج
                        </button>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</x-guest-layout>

