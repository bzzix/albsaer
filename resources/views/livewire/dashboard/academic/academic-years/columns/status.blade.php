<div>
    <button wire:click="toggleStatus({{ $year->id }})" 
            wire:loading.attr="disabled"
            class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium rounded-lg group focus:ring-4 focus:outline-none transition-all
            {{ $year->is_active ? 'text-green-700 bg-green-100 hover:bg-green-200 focus:ring-green-300 dark:bg-green-900 dark:text-green-300 dark:hover:bg-green-800 dark:focus:ring-green-800' : 'text-red-700 bg-red-100 hover:bg-red-200 focus:ring-red-300 dark:bg-red-900 dark:text-red-300 dark:hover:bg-red-800 dark:focus:ring-red-800' }}">
        <span class="relative px-3 py-1.5 transition-all ease-in duration-75 rounded-md flex items-center gap-2">
            @if($year->is_active)
                <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                متاح ومفعل
            @else
                <div class="w-2 h-2 rounded-full bg-red-500"></div>
                غير مفعل
            @endif
        </span>
    </button>
</div>
