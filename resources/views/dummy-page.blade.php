<x-layouts.app>
    <x-ui.page>
        @if (session('status'))
        <div class="bg-green-600 border-green-400 rounded-2xl text-white p-4 my-4 flex justify-center">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('dummy-page.store') }}">
            @csrf
            <x-forms.input name="title" label="Titel" required></x-forms.input>
            <x-forms.textarea name="content" label="Neuen Eintrag erstellen" required></x-forms.textarea>
            <x-ui.button type="submit" variant="primary">Absenden</x-ui.button>
            <x-ui.button type="reset" variant="warning">Zurücksetzen</x-ui.button>
            <x-ui.button url="{{ route('home') }}" variant="danger">Abbrechen</x-ui.button>
        </form>

        <x-ui.h2>Gespeicherte Einträge</x-ui.h2>
        @if ($entries->isEmpty())
        <p>Noch keine Einträge vorhanden.</p>
        @else
        <table class="w-full text-left">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titel</th>
                    <th>Inhalt</th>
                    <th>Aktionen</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($entries as $entry)
                <tr>
                    <td>{{ $entry->id }}</td>
                    <td>{{ $entry->title }}</td>
                    <td>{{ $entry->content }}</td>
                    <td class="py-2">
                        <div class="flex gap-2">
                            <x-ui.button variant="warning" url="{{ route('dummy-page.index', ['edit' => $entry->id]) }}"
                                class="m-0 p-0">
                                Bearbeiten</x-ui.button>
                            <form method="POST" action="{{ route('dummy-page.destroy', $entry) }}">
                                @csrf
                                @method('DELETE')
                                <x-ui.button type="submit" variant="danger">Löschen</x-ui.button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        @if ($editEntry)
        <x-ui.h2>Eintrag bearbeiten</x-ui.h2>
        <form method="POST" action="{{ route('dummy-page.update', $editEntry) }}">
            @csrf
            @method('PATCH')

            <x-forms.input name="title" label="Titel" :value="old('title', $editEntry->title)" required />
            <x-forms.textarea name="content" label="Neuen Eintrag erstellen"
                :value="old('content', $editEntry->content)" required />
            <x-ui.button type="submit" variant="primary">Änderungen speichern</x-ui.button>
            <x-ui.button url="{{ route('dummy-page.index') }}" variant="danger">Abbrechen</x-ui.button>
        </form>
        @endif
    </x-ui.page>

</x-layouts.app>