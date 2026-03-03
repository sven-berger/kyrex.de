<x-layouts.admin>
    <x-ui.page>
        @if (session('status'))
        <p class="mb-4 rounded bg-green-100 p-3 text-sm text-green-700">{{ session('status') }}</p>
        @endif

        <form method="POST" action="{{ route('acp.wissensportal.categories.store') }}" class="mb-6 space-y-3 rounded">
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

            <x-ui.button type="submit" variant="primary">Kategorie speichern</x-ui.button>
        </form>

        <ul class="space-y-2">
            @forelse ($entries as $entry)
            <x-ui.box title="{{ $entry->name }}" class="mb-4 last:mb-0">
                <li class="flex items-center justify-between p-4">
                    <div>
                        <p class="text-sm text-gray-600">{{ $entry->value }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-ui.button url="{{ route('acp.wissensportal.categories.edit', $entry) }}" variant="secondary">
                            Bearbeiten</x-ui.button>
                        <form method="POST" action="{{ route('acp.wissensportal.categories.destroy', $entry) }}"
                            onsubmit="return confirm('Kategorie wirklich löschen?');">
                            @csrf
                            @method('DELETE')
                            <x-ui.button type="submit" variant="danger">Löschen</x-ui.button>
                        </form>
                    </div>
                </li>

            </x-ui.box>
            @empty
            <li class="rounded bg-gray-100 p-3 text-sm text-gray-600">Keine Kategorien vorhanden.</li>
            @endforelse

        </ul>
    </x-ui.page>
</x-layouts.admin>