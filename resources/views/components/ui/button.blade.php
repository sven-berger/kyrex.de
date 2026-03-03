@props([
'name' => null,
'type' => 'submit',
'url' => null,
'link' => null,
'variant' => 'primary',
])

@php
$variantClasses = [
'primary' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
'success' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500',
'warning' => 'bg-amber-500 hover:bg-amber-600 focus:ring-amber-500',
'danger' => 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
];

$colorClasses = $variantClasses[$variant] ?? $variantClasses['primary'];
$baseClasses = "inline-flex items-center rounded-xl px-4 py-2 text-sm font-medium text-white focus:outline-none
focus:ring-2 focus:ring-offset-2 {$colorClasses}";
@endphp

@if ($url)
<a href="{{ $url }}" {{ $attributes->merge(['class' => $baseClasses]) }}>
    {{ $link ?? $slot }}
</a>
@else
<button type="{{ $type }}" @if ($name) name="{{ $name }}" @endif {{ $attributes->merge(['class' => $baseClasses]) }}>
    {{ $slot }}
</button>
@endif