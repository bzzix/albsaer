@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-sm text-surface-700']) }}>
    {{ $value ?? $slot }}
</label>
