<aside id="sidebar"
    :class="{'sidebar-collapsed': !sidebarOpen, 'translate-x-0': mobileMenuOpen, 'translate-x-full lg:translate-x-0': !mobileMenuOpen}"
    class="w-72 h-full glass-panel shadow-glass flex flex-col justify-between py-6 px-4 z-50 fixed lg:relative right-0 border-l border-white transition-transform duration-300">

    <div class="flex-1 overflow-y-auto overflow-x-hidden no-scrollbar">
        <!-- Logo area -->
        <div class="flex items-center gap-3 px-2 mb-10 h-10">
            @php
                $siteName = get_setting('site_name', 'معهد البصائر');
                $siteLogo = get_setting('site_logo');
                $displayMode = get_setting('site_display_mode', 'both');
                $siteInitial = mb_substr($siteName, 0, 1, 'UTF-8');
            @endphp
            
            @if($displayMode === 'both' || $displayMode === 'logo')
            <div class="{{ $displayMode === 'logo' ? 'h-16 w-16' : 'h-10 w-10' }} flex-shrink-0 rounded-xl bg-surface-100 flex items-center justify-center text-surface-900 overflow-hidden border border-surface-200 shadow-sm transition-all duration-300">
                @if($siteLogo)
                    <img src="{{ $siteLogo }}" alt="{{ $siteName }}" class="h-full w-full object-contain p-1">
                @else
                    <span class="font-display font-bold {{ $displayMode === 'logo' ? 'text-2xl' : 'text-xl' }}">{{ $siteInitial }}</span>
                @endif
            </div>
            @endif
            
            @if($displayMode === 'both' || $displayMode === 'name')
            <div class="logo-text min-w-[150px]">
                <h1 class="font-bold text-xl tracking-tight text-surface-900 leading-none">{{ $siteName }}</h1>
                <p class="text-[10px] text-surface-500 font-bold mt-1 tracking-wider uppercase">الإدارة الذكية</p>
            </div>
            @endif
        </div>

        <!-- Navigation Links -->
        <nav class="space-y-1.5 px-2">
            <p class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-3 mt-4 nav-text px-2">
                الرئيسية</p>

            <a href="{{ route('dashboard') }}" tooltip="لوحة القيادة"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-primary-50 text-primary-700 font-bold transition-colors group">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                <span class="nav-text">لوحة القيادة</span>
            </a>

            <p class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-3 mt-6 nav-text px-2">
                الإدارة الأكاديمية</p>

            <div x-data="{ usersOpen: {{ (request()->routeIs('users.*') || request()->routeIs('roles.*')) ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="usersOpen = !usersOpen"
                    class="flex items-center justify-between w-full px-3 py-2.5 rounded-xl transition-colors font-medium cursor-pointer group outline-none {{ (request()->routeIs('users.*') || request()->routeIs('roles.*')) ? 'bg-primary-50 text-primary-700 font-bold' : 'text-surface-600 hover:bg-surface-100 hover:text-surface-900' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 {{ (request()->routeIs('users.*') || request()->routeIs('roles.*')) ? 'text-primary-600' : 'group-hover:text-primary-600' }} transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <span class="nav-text">إدارة المستخدمين</span>
                    </div>
                    <svg :class="{'rotate-180': usersOpen}" class="w-4 h-4 transition-transform duration-200 nav-text {{ (request()->routeIs('users.*') || request()->routeIs('roles.*')) ? 'text-primary-600' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <div x-show="usersOpen" 
                    x-transition:enter="transition ease-out duration-100" 
                    x-transition:enter-start="transform opacity-0 scale-95" 
                    x-transition:enter-end="transform opacity-100 scale-100" 
                    class="sub-menu pr-9 space-y-1 nav-text">
                    <a href="{{ route('users.index') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors font-medium {{ request()->routeIs('users.index') ? 'bg-primary-50 text-primary-600 font-bold' : 'text-surface-600 hover:bg-surface-100 hover:text-primary-600' }}">المستخدمين</a>
                    <a href="{{ route('roles.index') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors font-medium {{ request()->routeIs('roles.index') ? 'bg-primary-50 text-primary-600 font-bold' : 'text-surface-600 hover:bg-surface-100 hover:text-primary-600' }}">الصلاحيات والأدوار</a>
                </div>
            </div>

            <a href="javascript:void(0)"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-surface-600 font-medium cursor-not-allowed opacity-50 pointer-events-none">
                <svg class="w-5 h-5 flex-shrink-0 grayscale" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
                <span class="nav-text">المشاريع والدورات</span>
            </a>

            <a href="javascript:void(0)"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-surface-600 font-medium cursor-not-allowed opacity-50 pointer-events-none">
                <svg class="w-5 h-5 flex-shrink-0 grayscale" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                <span class="nav-text">إدارة المجموعات</span>
            </a>

            <a href="javascript:void(0)"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-surface-600 font-medium cursor-not-allowed opacity-50 pointer-events-none">
                <svg class="w-5 h-5 flex-shrink-0 grayscale" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <span class="nav-text">الجداول الدراسية</span>
            </a>

            <a href="javascript:void(0)"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-surface-600 font-medium cursor-not-allowed opacity-50 pointer-events-none">
                <svg class="w-5 h-5 flex-shrink-0 grayscale" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479L12 21l-6.825-3.943a12.083 12.083 0 01.665-6.479L12 14z" />
                </svg>
                <span class="nav-text">إدارة المعلمين</span>
            </a>

            <a href="javascript:void(0)"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-surface-600 font-medium cursor-not-allowed opacity-50 pointer-events-none">
                <svg class="w-5 h-5 flex-shrink-0 grayscale" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span class="nav-text">شؤون الطلاب</span>
            </a>

            <p class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-3 mt-6 nav-text px-2">
                الأداء والتقارير</p>

            <a href="javascript:void(0)"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-surface-600 font-medium cursor-not-allowed opacity-50 pointer-events-none">
                <svg class="w-5 h-5 flex-shrink-0 grayscale" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="nav-text">الحضور والأعذار</span>
            </a>

            <a href="javascript:void(0)"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-surface-600 font-medium cursor-not-allowed opacity-50 pointer-events-none">
                <svg class="w-5 h-5 flex-shrink-0 grayscale" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <span class="nav-text">الاختبارات والدرجات</span>
            </a>

            <a href="javascript:void(0)"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-surface-600 font-medium cursor-not-allowed opacity-50 pointer-events-none">
                <svg class="w-5 h-5 flex-shrink-0 grayscale" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                </svg>
                <span class="nav-text">التقارير والإحصائيات</span>
            </a>

            <p class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-3 mt-6 nav-text px-2">
                الإدارة المالية</p>

            <a href="javascript:void(0)"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-surface-600 font-medium cursor-not-allowed opacity-50 pointer-events-none">
                <svg class="w-5 h-5 flex-shrink-0 grayscale" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zM17 16v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-7a2 2 0 012-2h2m3-4H9a2 2 0 00-2 2v1m12-1a2 2 0 012 2v11a2 2 0 01-2 2H11a2 2 0 01-2-2v-5m-1-4h3m-6 0a2 2 0 002 2h2a2 2 0 002-2M9 4a2 2 0 012-2h2a2 2 0 012 2v15a2 2 0 01-2 2H11a2 2 0 01-2-2V4z" />
                </svg>
                <span class="nav-text">إدارة الشؤون المالية</span>
            </a>

            <p class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-3 mt-6 nav-text px-2">
                الموارد</p>

            <a href="javascript:void(0)"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-surface-600 font-medium cursor-not-allowed opacity-50 pointer-events-none">
                <svg class="w-5 h-5 flex-shrink-0 grayscale" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 14v3a2 2 0 002 2h4a2 2 0 002-2v-3M8 10V7a4 4 0 118 0v3m-8 0h8M5 10a2 2 0 012-2h10a2 2 0 012 2v4a2 2 0 01-2 2H7a2 2 0 01-2-2v-4z" />
                </svg>
                <span class="nav-text">مكتبة الموارد</span>
            </a>

            <p class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mb-3 mt-6 nav-text px-2">
                النظام</p>
            @can('manage_settings')
            <div x-data="{ settingsOpen: {{ (request()->routeIs('settings.*') || request()->is('dashboard/notification-templates*')) ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="settingsOpen = !settingsOpen"
                    class="flex items-center justify-between w-full px-3 py-2.5 rounded-xl transition-colors font-medium cursor-pointer group outline-none {{ (request()->routeIs('settings.*') || request()->is('dashboard/notification-templates*')) ? 'bg-primary-50 text-primary-700 font-bold' : 'text-surface-600 hover:bg-surface-100 hover:text-surface-900' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 {{ (request()->routeIs('settings.*') || request()->is('dashboard/notification-templates*')) ? 'text-primary-600' : 'group-hover:text-primary-600' }} transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="nav-text">الإعدادات</span>
                    </div>
                    <svg :class="{'rotate-180': settingsOpen}" class="w-4 h-4 transition-transform duration-200 nav-text {{ (request()->routeIs('settings.*') || request()->is('dashboard/notification-templates*')) ? 'text-primary-600' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <div x-show="settingsOpen" 
                    x-transition:enter="transition ease-out duration-100" 
                    x-transition:enter-start="transform opacity-0 scale-95" 
                    x-transition:enter-end="transform opacity-100 scale-100" 
                    class="sub-menu pr-9 space-y-1 nav-text">
                    <a href="{{ route('settings.general') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors font-medium {{ request()->routeIs('settings.general') ? 'bg-primary-50 text-primary-600 font-bold' : 'text-surface-600 hover:bg-surface-100 hover:text-primary-600' }}">إعدادات عامة</a>
                    <a href="{{ route('settings.design') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors font-medium {{ request()->routeIs('settings.design') ? 'bg-primary-50 text-primary-600 font-bold' : 'text-surface-600 hover:bg-surface-100 hover:text-primary-600' }}">إعدادات التصميم</a>
                    <a href="{{ route('settings.notifications') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors font-medium {{ request()->routeIs('settings.notifications') ? 'bg-primary-50 text-primary-600 font-bold' : 'text-surface-600 hover:bg-surface-100 hover:text-primary-600' }}">إعدادات الإشعارات</a>
                    <a href="{{ route('notification-templates.index') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors font-medium {{ request()->routeIs('notification-templates.*') ? 'bg-primary-50 text-primary-600 font-bold' : 'text-surface-600 hover:bg-surface-100 hover:text-primary-600' }}">قوالب الرسائل</a>
                </div>
            </div>
            @endcan
        </nav>
    </div>

    <!-- User Profile (Dynamic Login Data) -->
    <div
        class="mt-4 p-2 bg-surface-50 rounded-xl border border-surface-200/50 flex items-center gap-3 cursor-pointer hover:bg-surface-100 transition-colors group relative">
        <img src="{{ auth()->check() ? auth()->user()->profile_photo_url : 'https://ui-avatars.com/api/?name=Admin+User&background=3b82f6&color=fff&rounded=true&bold=true' }}"
            class="w-10 h-10 rounded-full flex-shrink-0" alt="User Avatar">
        <div class="flex-1 min-w-0 profile-details">
            <p class="text-sm font-bold text-surface-900 truncate">{{ auth()->check() ? auth()->user()->name : 'مدير النظام' }}</p>
            <p class="text-[11px] text-primary-600 font-bold truncate">{{ auth()->check() ? (auth()->user()->roles->first()->display_name ?? 'بدون صلاحية') : 'Super Admin' }}</p>
        </div>
        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}" id="logout-form" class="hidden">
            @csrf
        </form>
        <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
            class="p-2 rounded-lg text-red-500 bg-red-50 hover:bg-red-100 transition-all focus:outline-none group/logout" 
            tooltip="تسجيل الخروج">
            <svg class="w-5 h-5 stroke-2 group-hover/logout:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
            </svg>
        </button>
    </div>
</aside>
