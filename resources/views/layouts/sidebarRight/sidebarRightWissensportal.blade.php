@php
$rightCategoryKeys = ['vorlagen', 'ubungen', 'templates', 'exercises'];
$rightGroups = collect($wissensportalRightGroups ?? [])->filter(function ($group) use ($rightCategoryKeys) {
$nameKey = $group['name_key'] ?? \Illuminate\Support\Str::of((string) ($group['title'] ??
''))->lower()->ascii()->replaceMatches('/[^a-z0-9]/', '')->value();
$valueKey = $group['value_key'] ?? '';

return in_array($nameKey, $rightCategoryKeys, true) || in_array($valueKey, $rightCategoryKeys, true);
});
@endphp

@forelse ($rightGroups as $group)
<x-ui.box title="{{ $group['title'] }}">
    <ul class="space-y-2 p-3 text-xs text-slate-700">
        @forelse ($group['pages'] as $page)
        <li>
            <a href="{{ $page->url === 'index' ? route('wissensportal') : route('wissensportal.page', $page->url) }}"
                class="hover:text-slate-900">
                {{ $page->name }}
            </a>
        </li>
        @empty
        <li class="text-slate-500">Noch keine Seiten vorhanden.</li>
        @endforelse
    </ul>
</x-ui.box>
@empty
<x-ui.box title="Vorlagen">
    <p class="p-3 text-xs text-slate-700">Noch keine Seiten vorhanden.</p>
</x-ui.box>

<x-ui.box title="Übungen">
    <p class="p-3 text-xs text-slate-700">Noch keine Seiten vorhanden.</p>
</x-ui.box>
@endforelse