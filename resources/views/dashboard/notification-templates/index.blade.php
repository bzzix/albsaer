@extends('dashboard.layouts.master')
@section('title', 'إدارة قوالب الرسائل')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl lg:text-3xl font-extrabold text-surface-900 tracking-tight">إدارة قوالب الرسائل ✉️</h2>
            <p class="text-surface-500 mt-1 text-sm font-medium">التحكم في محتوى وحالة إشعارات النظام، البريد الإلكتروني، والواتساب.</p>
        </div>
        <div class="flex items-center gap-2">
            <button class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-xl border border-primary-500 shadow-sm text-sm font-bold transition-all active:scale-95 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                إنشاء قالب جديد
            </button>
        </div>
    </div>

    <!-- Alert for Variables -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/50 rounded-2xl p-4 flex items-start gap-3">
        <div class="bg-blue-100 dark:bg-blue-800/30 p-2 rounded-lg text-blue-600 dark:text-blue-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <h4 class="text-sm font-bold text-blue-800 dark:text-blue-300">تلميح حول المتغيرات</h4>
            <p class="text-xs text-blue-700 dark:text-blue-400 mt-1">يمكنك استخدام المتغيرات مثل <code>@{{user_name}}</code> و <code>@{{site_name}}</code> داخل محتوى الرسائل ليتم استبدالها تلقائياً عند الإرسال.</p>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-surface-900 border border-surface-200/60 dark:border-surface-700/50 rounded-3xl shadow-soft dark:shadow-none overflow-hidden">
        <div class="p-6 border-b border-surface-100 dark:border-surface-700 bg-surface-50/30 dark:bg-surface-800/30">
            <h3 class="font-bold text-surface-900 dark:text-surface-100">قائمة القوالب النشطة</h3>
        </div>
        <div class="p-0">
            <livewire:notification-templates-table />
        </div>
    </div>
</div>
@endsection
