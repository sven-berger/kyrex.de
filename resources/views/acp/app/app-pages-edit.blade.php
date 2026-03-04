<x-layouts.admin :title="$page->name">
    <x-ui.page>
        <form method="POST" action="{{ route('acp.app.pages.update', $page) }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <x-forms.input label="Name der Seite" name="name" :value="old('name', $page->name)" required />
            <x-forms.input label="URL der Seite" name="url" :value="old('url', $page->url)" required />
            <x-forms.select label="Kategorie" name="category_id" :options="$categories"
                :value="old('category_id', $page->category_id)" required />

            <div class="flex mt-auto justify-end gap-2">
                <x-ui.button type="submit" variant="primary">Seite speichern</x-ui.button>
                <x-ui.button url="{{ route('acp.app.pages') }}" variant="warning">Zurück</x-ui.button>
            </div>
        </form>
    </x-ui.page>
</x-layouts.admin>