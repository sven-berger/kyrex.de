<x-layouts.admin title="ACP">
    <x-ui.page>
        <p class="text-sm text-slate-700">
            Willkommen im Adminbereich. Hier kannst du später Admin-Funktionen ergänzen.
        </p>

        <div class="mt-4 flex gap-2">
            <x-ui.button url="{{ route('home') }}" variant="warning">Zur Startseite</x-ui.button>
            <x-ui.button url="{{ route('dummy-page.index') }}" variant="primary">Zur Dummy-Seite</x-ui.button>
        </div>
    </x-ui.page>
</x-layouts.admin>