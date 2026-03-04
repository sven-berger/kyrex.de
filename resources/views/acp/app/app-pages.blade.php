<x-layouts.admin>
    <x-ui.page>
        @if (session('status'))
        <p class="mb-4 rounded bg-green-100 p-3 text-sm text-green-700">{{ session('status') }}</p>
        @endif

        @if ($errors->any())
        <div class="mb-4 rounded bg-red-100 p-3 text-sm text-red-700">
            <p class="mb-1 font-semibold">Speichern fehlgeschlagen:</p>
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="space-y-4">
            @forelse ($groupedEntries as $categoryId => $pages)
            <x-ui.box class="mb-4 last:mb-0" title="{{ $pages->first()->category?->name ?? 'Ohne Kategorie' }}">
                <ul class="space-y-2 p-3">
                    @foreach ($pages as $entry)
                    <li class="flex items-start justify-between gap-3 rounded">
                        <a href="{{ $entry->url }}" target="_blank" class="font-semibold text-slate-800 hover:underline">
                            {{ $entry->name }}
                            <p class="text-sm text-slate-500">URL: <span
                                    class="font-medium text-slate-700">{{ $entry->url }}</span></p>
                        </a>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('acp.app.pages.edit', $entry) }}"
                                class="rounded-md border border-blue-200 px-2 py-1 text-sm text-blue-600 hover:bg-blue-50">Bearbeiten</a>

                            <form method="POST" action="{{ route('acp.app.pages.destroy', $entry) }}"
                                onsubmit="return confirm('Seite wirklich löschen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="rounded-md border border-red-200 px-2 py-1 text-sm text-red-600 hover:bg-red-50">Löschen</button>
                            </form>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </x-ui.box>
            @empty
            <p class="rounded bg-gray-100 p-3 text-sm text-gray-600">Keine Seiten vorhanden.</p>
            @endforelse
        </div>

        <x-ui.h2 class="mt-6">Neue Seite anlegen</x-ui.h2>

        <form method="POST" action="{{ route('acp.app.pages.store') }}">
            @csrf
            <div class="mb-5">
                <x-forms.input label="Name der Seite" name="name" required />
                <x-forms.input label="URL der Seite" name="url" required />
                <x-forms.select label="Kategorie" name="category_id" :options="$categories" required />
            </div>
            <x-ui.button type="submit" variant="primary">Seite speichern</x-ui.button>
        </form>
    </x-ui.page>
</x-layouts.admin>