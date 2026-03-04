<x-layouts.admin>
    <x-ui.page>
        @if (session('status'))
        <p class="mb-4 rounded bg-green-100 p-3 text-sm text-green-700">{{ session('status') }}</p>
        @endif
        @if (session('error'))
        <p class="mb-4 rounded bg-red-100 p-3 text-sm text-red-700">{{ session('error') }}</p>
        @endif

        <form method="POST" action="{{ route('acp.app.categories.store') }}" class="mb-6 space-y-3 rounded">
            @csrf

            <div>
                <label for="name" class="mb-1 block text-xs text-slate-700">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required
                    class="w-full rounded border border-slate-300 bg-white px-2 py-1 text-sm">
                @error('name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="value" class="mb-1 block text-xs text-slate-700">Value</label>
                <input id="value" name="value" type="text" value="{{ old('value') }}" required
                    class="w-full rounded border border-slate-300 bg-white px-2 py-1 text-sm">
                @error('value')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="area" class="mb-1 block text-xs text-slate-700">Bereich</label>
                <select id="area" name="area" required class="w-full rounded border border-slate-300 bg-white px-2 py-1 text-sm">
                    <option value="index" @selected(old('area', 'index' )==='index' )>Index</option>
                    <option value="acp" @selected(old('area')==='acp' )>ACP</option>
                </select>
                @error('area')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="sort_order" class="mb-1 block text-xs text-slate-700">Reihenfolge</label>
                <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', 0) }}"
                    class="w-full rounded border border-slate-300 bg-white px-2 py-1 text-sm">
                @error('sort_order')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <x-ui.button type="submit" variant="primary">Kategorie speichern</x-ui.button>
        </form>

        <ul class="space-y-2">
            @php
            $areaOrderedIds = $entries
            ->groupBy(fn($item) => (string) ($item->area ?? 'index'))
            ->map(fn($items) => $items->pluck('id')->values());
            @endphp
            @forelse ($entries as $entry)
            @php
            $isProtectedCategory = in_array(strtolower(trim((string) $entry->value)), ['index', 'acp'], true);
            $entryArea = (string) ($entry->area ?? 'index');
            $orderedIdsForArea = $areaOrderedIds->get($entryArea, collect());
            $positionInArea = $orderedIdsForArea->search($entry->id);
            $canMoveUp = $positionInArea !== false && $positionInArea > 0;
            $canMoveDown = $positionInArea !== false && $positionInArea < ($orderedIdsForArea->count() - 1);
                @endphp
                <x-ui.box title="{{ $entry->name }}" class="mb-4 last:mb-0">
                    <li class="flex items-center justify-between p-4">
                        <div>
                            <p class="text-sm text-gray-600">{{ $entry->value }}</p>
                            <p class="text-xs text-gray-500">Bereich: {{ strtoupper($entry->area ?? 'index') }} · Reihenfolge: {{ $entry->sort_order ?? 0 }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('acp.app.categories.move-up', $entry) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" @disabled(!$canMoveUp)
                                    class="rounded-md border px-2 py-1 text-sm {{ $canMoveUp ? 'border-slate-300 text-slate-700 hover:bg-slate-100' : 'cursor-not-allowed border-slate-200 text-slate-400' }}"
                                    title="{{ $canMoveUp ? 'Nach oben' : 'Bereits oberste Kategorie im Bereich' }}">↑</button>
                            </form>
                            <form method="POST" action="{{ route('acp.app.categories.move-down', $entry) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" @disabled(!$canMoveDown)
                                    class="rounded-md border px-2 py-1 text-sm {{ $canMoveDown ? 'border-slate-300 text-slate-700 hover:bg-slate-100' : 'cursor-not-allowed border-slate-200 text-slate-400' }}"
                                    title="{{ $canMoveDown ? 'Nach unten' : 'Bereits unterste Kategorie im Bereich' }}">↓</button>
                            </form>
                            <x-ui.button url="{{ route('acp.app.categories.edit', $entry) }}" variant="secondary">Bearbeiten
                            </x-ui.button>
                            @if ($isProtectedCategory)
                            <span class="inline-flex cursor-not-allowed rounded-md border border-slate-300 px-3 py-1.5 text-sm text-slate-400"
                                title="Geschützte Kategorie">
                                Löschen gesperrt
                            </span>
                            @else
                            <form method="POST" action="{{ route('acp.app.categories.destroy', $entry) }}"
                                onsubmit="return confirm('Kategorie wirklich löschen?');">
                                @csrf
                                @method('DELETE')
                                <x-ui.button type="submit" variant="danger">Löschen</x-ui.button>
                            </form>
                            @endif
                        </div>
                    </li>
                </x-ui.box>
                @empty
                <li class="rounded bg-gray-100 p-3 text-sm text-gray-600">Keine Kategorien vorhanden.</li>
                @endforelse
        </ul>
    </x-ui.page>
</x-layouts.admin>