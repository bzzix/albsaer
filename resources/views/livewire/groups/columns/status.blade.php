<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
    @if($group->status === 'planned') bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300
    @elseif($group->status === 'ongoing') bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300
    @elseif($group->status === 'completed') bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300
    @else bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300
    @endif">
    @if($group->status === 'planned') مخطط
    @elseif($group->status === 'ongoing') جاري
    @elseif($group->status === 'completed') مكتمل
    @else ملغى
    @endif
</span>
