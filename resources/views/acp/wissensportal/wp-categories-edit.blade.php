<x-layouts.admin :title="$category->name">
    <x-ui.page>

        <form method="POST" action="{{ route('acp.wissensportal.categories.update', $category) }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $category->name) }}" required
                    class="w-full rounded border border-slate-300 bg-slate-100 px-2 py-1 text-sm">
                @error('name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="value" class="mb-1 block text-sm font-medium text-slate-700">Value</label>
                <input id="value" name="value" type="text" value="{{ old('value', $category->value) }}" required
                    class="w-full rounded border border-slate-300 bg-slate-100 px-2 py-1 text-sm">
                @error('value')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2">
                <x-ui.button type="submit" variant="primary">Speichern</x-ui.button>
                <x-ui.button url="{{ route('acp.wissensportal.categories') }}" variant="warning">Zurück</x-ui.button>
            </div>
        </form>
    </x-ui.page>
</x-layouts.admin>