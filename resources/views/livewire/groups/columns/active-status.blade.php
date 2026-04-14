<button wire:click="toggleStatus({{ $group->id }})" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold transition-all
    @if($group->is_active) bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 hover:bg-red-100 dark:hover:bg-red-900/30 hover:text-red-700
    @else bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 hover:bg-green-100 dark:hover:bg-green-900/30 hover:text-green-700
    @endif" title="انقر للتغيير">
    @if($group->is_active) نشطة @else معطلة @endif
</button>
