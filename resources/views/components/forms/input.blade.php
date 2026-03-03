@props([
'name',
'type' => 'text',
'label' => null,
'title' => null,
'value' => null,
])

@php
$fieldLabel = $label ?? $title;
@endphp

@if ($fieldLabel)
<label for="{{ $name }}" class="block text-sm font-medium text-slate-700 mb-3">
    {{ $fieldLabel }}
</label>
@endif

<input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" value="{{ $value ?? old($name) }}"
    {{ $attributes->merge(['class' => 'w-full rounded border border-slate-300 bg-slate-100 px-2 py-1 mb-4']) }}>