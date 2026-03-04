@forelse ($appIndexGroups as $group)
<x-ui.box title="{{ $group['title'] }}">
    <ul class="space-y-2 p-3 text-xs text-slate-700 rounded-b-2xl">
        @foreach ($group['pages'] as $page)
        <li>
            <a href="{{ $page->url }}" class="hover:text-slate-900">{{ $page->name }}</a>
        </li>
        @endforeach
    </ul>
</x-ui.box>
@empty
<x-ui.box title="Index">
    <p class="p-3 text-xs text-slate-700">Noch keine Seiten vorhanden.</p>
</x-ui.box>
@endforelse