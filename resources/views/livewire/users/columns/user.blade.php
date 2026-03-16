<div class="flex items-center gap-3">
    @if ($user->profile_photo_url)
        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-primary-100">
    @else
        <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center font-bold ring-2 ring-primary-50">
            {{ substr($user->name, 0, 1) }}
        </div>
    @endif
    
    <div class="flex flex-col">
        <span class="font-bold text-surface-900 group-hover:text-primary-600 transition-colors">{{ $user->name }}</span>
        <span class="text-xs text-surface-500">{{ $user->email }}</span>
    </div>
</div>
