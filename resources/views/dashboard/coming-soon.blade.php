@extends('dashboard.layouts.master')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh] text-center px-4">
    <div class="w-24 h-24 bg-primary-50 rounded-3xl flex items-center justify-center text-primary-600 mb-6 shadow-lg shadow-primary-500/10">
        <svg class="w-12 h-12 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
    </div>
    <h1 class="text-3xl font-extrabold text-surface-900 tracking-tight mb-2">قريباً جداً!</h1>
    <p class="text-surface-500 max-w-md font-medium">نحن نعمل بجد لتوفير هذه الميزة في أقرب وقت ممكن. ترقبوا التحديثات القادمة.</p>
    
    <a href="{{ route('dashboard') }}" class="mt-8 inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-xl font-bold transition-all active:scale-95 shadow-md shadow-primary-500/20">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        العودة للرئيسية
    </a>
</div>
@endsection
