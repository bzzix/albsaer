@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'bg-red-50 border-r-4 border-red-500 p-4 rounded-xl shadow-sm mb-4']) }}>
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="font-bold text-red-700 text-sm">{{ __('Whoops! Something went wrong.') }}</div>
        </div>

        <ul class="mt-2 list-disc list-inside text-xs font-medium text-red-600 space-y-1 pr-6">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
