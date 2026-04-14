<header class="h-[72px] px-4 lg:px-8 flex items-center justify-between sticky top-0 z-30 glass-panel border-0 border-b border-surface-200/50 backdrop-blur-xl bg-white/70">
    <div class="flex items-center gap-4">
        <!-- Burger Button -->
        <button @click="sidebarOpen = !sidebarOpen; mobileMenuOpen = !mobileMenuOpen"
            class="p-2 rounded-lg text-surface-500 hover:bg-surface-100 focus:outline-none focus:ring-2 focus:ring-primary-500/50 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- Breadcrumbs/Title -->
        <div class="flex items-center gap-2 hidden sm:flex">
            <div class="p-1.5 bg-surface-100 rounded-md text-surface-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
            </div>
            <span class="text-sm text-surface-400">/</span>
            <span class="text-sm font-bold text-surface-900">@yield('title', 'نظرة عامة')</span>
        </div>
    </div>

    <div class="flex items-center gap-3 lg:gap-5">
        <!-- Search -->
        <div class="relative hidden md:block w-64 lg:w-80">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <svg class="w-4 h-4 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" placeholder="البحث برقم الهاتف، اسم الطالب..."
                class="w-full bg-surface-100/50 border border-surface-200/50 rounded-lg py-2 pr-10 pl-4 text-sm font-medium text-surface-700 hover:bg-surface-100 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-all outline-none placeholder:text-surface-400">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <kbd
                    class="hidden sm:inline-block border border-surface-200 rounded px-1.5 text-[10px] font-display font-medium text-surface-400">CTRL+K</kbd>
            </div>
        </div>

        <!-- Quick Actions (Add New) -->
        <button
            class="hidden sm:flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm shadow-primary-500/20 transition-all active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            تسجيل طالب
        </button>

        <!-- Dark Mode Toggle -->
        <button @click="document.documentElement.classList.toggle('dark'); fetch('{{ route('settings.toggle-dark-mode') }}', {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}})"
            class="p-2 rounded-lg text-surface-500 hover:text-primary-600 hover:bg-primary-50 transition-colors focus:outline-none dark:hover:bg-surface-200">
            <!-- Sun Icon (Show in Dark) -->
            <svg x-show="document.documentElement.classList.contains('dark')" class="w-5 h-5 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m0 13.5V21m8.966-8.966h-2.25m-13.5 0H3m16.035-6.763l-1.591 1.591M6.763 17.237l-1.591 1.591m12.444 0l-1.591-1.591M6.763 6.763L5.172 5.172M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <!-- Moon Icon (Show in Light) -->
            <svg x-show="!document.documentElement.classList.contains('dark')" class="w-5 h-5 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
            </svg>
        </button>

        <!-- Notification -->
        <button
            class="relative p-2 rounded-lg text-surface-500 hover:text-surface-900 hover:bg-surface-100 transition-colors">
            <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                <span
                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span
                    class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500 border-2 border-white"></span>
            </span>
            <svg class="w-5 h-5 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                </path>
            </svg>
        </button>
    </div>
</header>
