<x-table-toggle 
    :active="$user->is_active" 
    wire:click="toggleAccountStatus({{ $user->id }})" 
/>
