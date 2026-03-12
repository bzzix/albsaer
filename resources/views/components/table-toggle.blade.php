@props(['active', 'action'])

<div class="flex items-center justify-center">
    <button type="button" 
            wire:click="{{ $action }}"
            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out focus:outline-none {{ $active ? 'bg-primary-600' : 'bg-surface-200 dark:bg-surface-700' }}">
        <span class="sr-only">Toggle</span>
        <span aria-hidden="true" 
              class="pointer-events-none absolute top-[2px] start-[2px] inline-block h-5 w-5 transform rounded-full bg-white border border-surface-300 dark:border-surface-600 transition duration-200 ease-in-out {{ $active ? 'translate-x-5 rtl:-translate-x-5' : 'translate-x-0' }}">
        </span>
    </button>
</div>
