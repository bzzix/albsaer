<div class="flex flex-wrap gap-1">
    @forelse($roles as $role)
        @php
            $color = $role->color ?? '#64748b';
            $displayName = $role->display_name ?? $role->name;
        @endphp
        
        <span class="px-2.5 py-1 text-[11px] font-bold rounded-lg border" 
              style="background-color: {{ $color }}20; color: {{ $color }}; border-color: {{ $color }}40">
            {{ $displayName }}
        </span>
    @empty
        <span class="px-2.5 py-1 text-[11px] font-bold rounded-lg border bg-surface-100 text-surface-500 border-surface-200">
            بلا دور
        </span>
    @endforelse
</div>
