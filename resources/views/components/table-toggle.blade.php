@props(['active', 'action' => null])

<div class="flex items-center justify-center relative group">
    @if($action)
        <button type="button" 
                wire:click="{{ $action }}"
                wire:loading.attr="disabled"
                wire:target="{{ $action }}"
                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out focus:outline-none {{ $active ? 'bg-primary-600' : 'bg-surface-200 dark:bg-surface-700' }}">
            <span class="sr-only">Toggle</span>
            <span aria-hidden="true" 
                  wire:loading.remove
                  wire:target="{{ $action }}"
                  class="pointer-events-none absolute top-[2px] start-[2px] inline-block h-5 w-5 transform rounded-full bg-white border border-surface-300 dark:border-surface-600 transition duration-200 ease-in-out {{ $active ? 'translate-x-5 rtl:-translate-x-5' : 'translate-x-0' }}">
            </span>
            <span wire:loading 
                  wire:target="{{ $action }}"
                  class="absolute top-[3px] start-[3px] h-5 w-5 flex items-center justify-center {{ $active ? 'translate-x-5 rtl:-translate-x-5' : 'translate-x-0' }}">
                <svg class="animate-spin h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
        </button>
    @else
        <button type="button" 
                {{ $attributes }}
                wire:loading.attr="disabled"
                wire:target="{{ $attributes->get('wire:click') }}"
                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out focus:outline-none {{ $active ? 'bg-primary-600' : 'bg-surface-200 dark:bg-surface-700' }}">
            <span class="sr-only">Toggle</span>
            <span aria-hidden="true" 
                  wire:loading.remove
                  wire:target="{{ $attributes->get('wire:click') }}"
                  class="pointer-events-none absolute top-[2px] start-[2px] inline-block h-5 w-5 transform rounded-full bg-white border border-surface-300 dark:border-surface-600 transition duration-200 ease-in-out {{ $active ? 'translate-x-5 rtl:-translate-x-5' : 'translate-x-0' }}">
            </span>
            <span wire:loading 
                  wire:target="{{ $attributes->get('wire:click') }}"
                  class="absolute top-[3px] start-[3px] h-5 w-5 flex items-center justify-center {{ $active ? 'translate-x-5 rtl:-translate-x-5' : 'translate-x-0' }}">
                <svg class="animate-spin h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
        </button>
    @endif
</div>
