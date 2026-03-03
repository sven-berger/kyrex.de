<x-layouts.admin :title="$page->name">
    <x-ui.page>
        <form method="POST" action="{{ route('acp.wissensportal.pages.update', $page) }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <x-forms.input label="Name der Seite" name="name" :value="old('name', $page->name)" required />
            <x-forms.input label="URL der Seite" name="url" :value="old('url', $page->url)" required />
            <x-forms.textarea label="Inhalt (falls keine Snippets)" name="content"
                :value="old('content', $page->content)" class="js-rich-editor"></x-forms.textarea>
            <x-forms.input label="Name des Snippets #1" name="snippet_1_title"
                :value="old('snippet_1_title', $page->snippet_1_title)" required />
            <x-forms.textarea label="Snippet #1" name="snippet_1" :value="old('snippet_1', $page->snippet_1)"
                class="js-rich-editor" required>
            </x-forms.textarea>
            <x-forms.input label="Name des Snippets #2" name="snippet_2_title"
                :value="old('snippet_2_title', $page->snippet_2_title)" />
            <x-forms.textarea label="Snippet #2" name="snippet_2" :value="old('snippet_2', $page->snippet_2)"
                class="js-rich-editor">
            </x-forms.textarea>
            <x-forms.input label="Name des Snippets #3" name="snippet_3_title"
                :value="old('snippet_3_title', $page->snippet_3_title)" />
            <x-forms.textarea label="Snippet #3" name="snippet_3" :value="old('snippet_3', $page->snippet_3)"
                class="js-rich-editor">
            </x-forms.textarea>
            <x-forms.input label="Name des Snippets #4" name="snippet_4_title"
                :value="old('snippet_4_title', $page->snippet_4_title)" />
            <x-forms.textarea label="Snippet #4" name="snippet_4" :value="old('snippet_4', $page->snippet_4)"
                class="js-rich-editor">
            </x-forms.textarea>
            <x-forms.input label="Name des Snippets #5" name="snippet_5_title"
                :value="old('snippet_5_title', $page->snippet_5_title)" />
            <x-forms.textarea label="Snippet #5" name="snippet_5" :value="old('snippet_5', $page->snippet_5)"
                class="js-rich-editor">
            </x-forms.textarea>
            <x-forms.select label="Kategorie" name="category_id" :options="$categories"
                :value="old('category_id', $page->category_id)" required />
            <div class="flex mt-auto justify-end gap-2">
                <x-ui.button type="submit" variant="primary">Seite speichern</x-ui.button>
                <x-ui.button url="{{ route('acp.wissensportal.pages') }}" variant="warning">Zurück</x-ui.button>
            </div>
        </form>

    </x-ui.page>
</x-layouts.admin>