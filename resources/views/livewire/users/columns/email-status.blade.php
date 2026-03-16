<x-table-toggle 
    :active="$user->email_verified_at !== null" 
    wire:click="toggleEmailStatus({{ $user->id }})" 
/>
