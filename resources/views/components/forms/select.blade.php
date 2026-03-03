@props([
'name',
'label' => null,
'options' => [],
'value' => null,
])

@if ($label)
<label for="{{ $name }}" class="block text-sm font-medium text-slate-700 mb-2">
    {{ $label }}
</label>
@endif

<select name="{{ $name }}" id="{{ $name }}"
    {{ $attributes->merge(['class' => 'w-full rounded border border-slate-300 bg-slate-100 px-2 py-1 mb-4']) }}>
    @foreach ($options as $optionValue => $optionLabel)
    <option value="{{ $optionValue }}" {{ $optionValue == ($value ?? old($name)) ? 'selected' : '' }}>
        {{ $optionLabel }}
    </option>
    @endforeach
</select>