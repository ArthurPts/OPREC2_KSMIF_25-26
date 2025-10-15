@props(['variant' => 'default'])

@php
    $classes = match($variant) {
        'success' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
        'warning' => 'bg-amber-100 text-amber-700 border-amber-200',
        'danger' => 'bg-red-100 text-red-700 border-red-200',
        'info' => 'bg-blue-100 text-blue-700 border-blue-200',
        default => 'bg-gray-100 text-gray-700 border-gray-200',
    };
@endphp

<span {{ $attributes->merge(['class' => "text-xs px-2 py-1 rounded-full border {$classes}"]) }}>
    {{ $slot }}
</span>

