<div class="flex items-center justify-end gap-2">
    <!-- Toggle Status Button -->
    <button wire:click="toggleSubjectStatus({{ $subject->id }})" 
        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $subject->is_active ? 'bg-primary-500' : 'bg-surface-300' }}">
        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $subject->is_active ? '-translate-x-6' : '-translate-x-1' }}"></span>
    </button>
    
    <!-- Edit Button -->
    <button wire:click="$parent.edit({{ $subject->id }})" 
        class="p-2 text-surface-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors">
        <svg wire:loading.remove wire:target="$parent.edit({{ $subject->id }})" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
        <svg wire:loading wire:target="$parent.edit({{ $subject->id }})" class="animate-spin h-4 w-4 text-primary-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
    </button>
    
    <!-- Delete Button -->
    <button wire:click="confirmDelete({{ $subject->id }})" 
        class="p-2 text-surface-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
        <svg wire:loading.remove wire:target="confirmDelete({{ $subject->id }})" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        <svg wire:loading wire:target="confirmDelete({{ $subject->id }})" class="animate-spin h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
    </button>
</div>
