<x-layouts.admin :title="$category->name">
    <x-ui.page>
        @php
        $isProtectedCategory = in_array(strtolower(trim((string) $category->value)), ['index', 'acp'], true);
        @endphp

        @if (session('error'))
        <p class="mb-4 rounded bg-red-100 p-3 text-sm text-red-700">{{ session('error') }}</p>
        @endif

        <form method="POST" action="{{ route('acp.app.categories.update', $category) }}" class="space-y-4">
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
                <input id="value" name="value" type="text" value="{{ old('value', $category->value) }}" required @readonly($isProtectedCategory)
                    class="w-full rounded border border-slate-300 bg-slate-100 px-2 py-1 text-sm">
                @if ($isProtectedCategory)
                <p class="mt-1 text-xs text-slate-500">Geschützte Kategorie: Der Value kann nicht geändert werden.</p>
                @endif
                @error('value')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="area" class="mb-1 block text-sm font-medium text-slate-700">Bereich</label>
                <select id="area" name="area" required @disabled($isProtectedCategory)
                    class="w-full rounded border border-slate-300 bg-slate-100 px-2 py-1 text-sm">
                    <option value="index" @selected(old('area', $category->area ?? 'index') === 'index')>Index</option>
                    <option value="acp" @selected(old('area', $category->area) === 'acp')>ACP</option>
                </select>
                @if ($isProtectedCategory)
                <input type="hidden" name="area" value="{{ old('area', $category->area ?? 'index') }}">
                <p class="mt-1 text-xs text-slate-500">Geschützte Kategorie: Der Bereich kann nicht geändert werden.</p>
                @endif
                @error('area')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="sort_order" class="mb-1 block text-sm font-medium text-slate-700">Reihenfolge</label>
                <input id="sort_order" name="sort_order" type="number" min="0"
                    value="{{ old('sort_order', $category->sort_order ?? 0) }}"
                    class="w-full rounded border border-slate-300 bg-slate-100 px-2 py-1 text-sm">
                @error('sort_order')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2">
                <x-ui.button type="submit" variant="primary">Speichern</x-ui.button>
                <x-ui.button url="{{ route('acp.app.categories') }}" variant="warning">Zurück</x-ui.button>
            </div>
        </form>
    </x-ui.page>
</x-layouts.admin>