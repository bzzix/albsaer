@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-surface-200 focus:border-primary-500 focus:ring focus:ring-primary-500/20 rounded-lg shadow-sm bg-surface-50/50 text-surface-800 transition-all outline-none']) !!}>
