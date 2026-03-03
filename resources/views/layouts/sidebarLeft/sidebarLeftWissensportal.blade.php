@forelse ($wissensportalLeftGroups as $group)
<x-ui.box title="{{ $group['title'] }}">
    <ul class="space-y-2 p-3 text-xs text-slate-700">
        @foreach ($group['pages'] as $page)
        <li>
            <a href="{{ $page->url === 'index' ? route('wissensportal') : route('wissensportal.page', $page->url) }}"
                class="hover:text-slate-900">
                {{ $page->name }}
            </a>
        </li>
        @endforeach
    </ul>
</x-ui.box>
@empty
<x-ui.box title="Wissensportal">
    <p class="p-3 text-xs text-slate-700">Noch keine Seiten vorhanden.</p>
</x-ui.box>
@endforelse