<div class="flex items-center gap-2 justify-end pr-4">
    <button wire:click="$dispatch('edit-project', [{{ $project->id }}])" 
            wire:loading.attr="disabled"
            wire:target="$dispatch('edit-project', [{{ $project->id }}])"
            class="relative p-2 text-surface-400 hover:text-primary-600 transition-colors bg-surface-50 hover:bg-primary-50 rounded-xl" title="تعديل">
        <svg wire:loading.remove wire:target="$dispatch('edit-project', [{{ $project->id }}])" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
        <svg wire:loading wire:target="$dispatch('edit-project', [{{ $project->id }}])" class="animate-spin w-5 h-5 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </button>
    
    <button wire:click="confirmDelete({{ $project->id }})" 
            wire:loading.attr="disabled"
            wire:target="confirmDelete({{ $project->id }})"
            class="relative p-2 text-surface-400 hover:text-red-600 transition-colors bg-surface-50 hover:bg-red-50 rounded-xl" title="حذف">
        <svg wire:loading.remove wire:target="confirmDelete({{ $project->id }})" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
        </svg>
        <svg wire:loading wire:target="confirmDelete({{ $project->id }})" class="animate-spin w-5 h-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </button>
</div>
