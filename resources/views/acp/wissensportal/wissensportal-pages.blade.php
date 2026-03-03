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

                        <a href="{{ route('wissensportal.page', $entry->url) }}" target="_blank"
                            class="font-semibold text-slate-800 hover:underline">
                            {{ $entry->name }}
                            <p class="text-sm text-slate-500">URL: <span
                                    class="font-medium text-slate-700">{{ $entry->url }}</span></p>
                        </a>


                        <div class="flex items-center gap-2">
                            <a href="{{ route('acp.wissensportal.pages.edit', $entry) }}"
                                class="rounded-md border border-blue-200 px-2 py-1 text-sm text-blue-600 hover:bg-blue-50">Bearbeiten</a>

                            <form method="POST" action="{{ route('acp.wissensportal.pages.destroy', $entry) }}"
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

        <form method="POST" action="{{ route('acp.wissensportal.pages.store') }}">
            @csrf
            <div class="mb-5">
                <x-forms.input label="Name der Seite" name="name" required />
                <x-forms.input label="URL der Seite" name="url" required />
                <x-forms.textarea label="Inhalt (falls kein Snippet)" name="content" class="js-rich-editor" required>
                </x-forms.textarea>
            </div>

            <div class="mb-5">
                <x-forms.input label="Name des Snippets #1" name="snippet_1_title" />
                <x-forms.textarea label="Snippet #1" name="snippet_1" class="js-rich-editor" required>
                </x-forms.textarea>
            </div>

            <div class="mb-5">
                <x-forms.input label="Name des Snippets #2" name="snippet_2_title" />
                <x-forms.textarea label="Snippet #2" name="snippet_2" class="js-rich-editor"></x-forms.textarea>
            </div>
            <div class="mb-5">

                <x-forms.input label="Name des Snippets #3" name="snippet_3_title" />
                <x-forms.textarea label="Snippet #3" name="snippet_3" class="js-rich-editor"></x-forms.textarea>
            </div>
            <div class="mb-5">
                <x-forms.input label="Name des Snippets #4" name="snippet_4_title" />
                <x-forms.textarea label="Snippet #4" name="snippet_4" class="js-rich-editor"></x-forms.textarea>
            </div>
            <div class="mb-5">
                <x-forms.input label="Name des Snippets #5" name="snippet_5_title" />
                <x-forms.textarea label="Snippet #5" name="snippet_5" class="js-rich-editor"></x-forms.textarea>
            </div>
            <div class="mb-5">
                <x-forms.select label="Kategorie" name="category_id" :options="$categories" required />
            </div>
            <x-ui.button type="submit" variant="primary">Seite speichern</x-ui.button>
        </form>
    </x-ui.page>
</x-layouts.admin>