<?php

namespace App\Http\Controllers\ACP\app;

use App\Http\Controllers\Controller;
use App\Models\AppCategories;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AppCategoriesController extends Controller
{
    public function index(): View
    {
        $entries = AppCategories::query()
            ->orderBy('area')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('acp.app.app-categories', [
            'entries' => $entries,
        ]);
    }

    public function edit(AppCategories $category): View
    {
        return view('acp.app.app-categories-edit', [
            'category' => $category,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
            'area' => ['required', 'in:index,acp'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        AppCategories::create($validated);

        return redirect()
            ->route('acp.app.categories')
            ->with('status', 'Kategorie gespeichert.');
    }

    public function update(Request $request, AppCategories $entry): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
            'area' => ['required', 'in:index,acp'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        if ($this->isProtectedCategory($entry)) {
            $currentValue = strtolower(trim((string) $entry->value));
            $newValue = strtolower(trim((string) ($validated['value'] ?? '')));
            $newArea = strtolower(trim((string) ($validated['area'] ?? '')));

            if ($currentValue !== $newValue) {
                return redirect()
                    ->route('acp.app.categories.edit', $entry)
                    ->withInput()
                    ->with('error', 'Der Value der geschützten Kategorien "index" und "acp" darf nicht geändert werden.');
            }

            if ($currentValue !== $newArea) {
                return redirect()
                    ->route('acp.app.categories.edit', $entry)
                    ->withInput()
                    ->with('error', 'Der Bereich der geschützten Kategorien "index" und "acp" darf nicht geändert werden.');
            }
        }

        $entry->update($validated);

        return redirect()
            ->route('acp.app.categories')
            ->with('status', 'Kategorie aktualisiert.');
    }

    public function destroy(AppCategories $entry): RedirectResponse
    {
        if ($this->isProtectedCategory($entry)) {
            return redirect()
                ->route('acp.app.categories')
                ->with('error', 'Die Kategorien "index" und "acp" sind geschützt und können nicht gelöscht werden.');
        }

        $entry->delete();

        return redirect()
            ->route('acp.app.categories')
            ->with('status', 'Kategorie gelöscht.');
    }

    public function moveUp(AppCategories $entry): RedirectResponse
    {
        return $this->moveInArea($entry, -1);
    }

    public function moveDown(AppCategories $entry): RedirectResponse
    {
        return $this->moveInArea($entry, 1);
    }

    private function moveInArea(AppCategories $entry, int $direction): RedirectResponse
    {
        $area = (string) $entry->area;

        $orderedIds = AppCategories::query()
            ->where('area', $area)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->pluck('id')
            ->values();

        $currentIndex = $orderedIds->search($entry->id);

        if ($currentIndex === false) {
            return redirect()->route('acp.app.categories');
        }

        $targetIndex = $currentIndex + $direction;

        if ($targetIndex < 0 || $targetIndex >= $orderedIds->count()) {
            return redirect()->route('acp.app.categories');
        }

        $ids = $orderedIds->all();
        $currentId = $ids[$currentIndex];
        $ids[$currentIndex] = $ids[$targetIndex];
        $ids[$targetIndex] = $currentId;

        DB::transaction(function () use ($ids): void {
            foreach ($ids as $position => $categoryId) {
                AppCategories::query()
                    ->where('id', $categoryId)
                    ->update(['sort_order' => $position * 10]);
            }
        });

        return redirect()
            ->route('acp.app.categories')
            ->with('status', 'Reihenfolge aktualisiert.');
    }

    private function isProtectedCategory(AppCategories $category): bool
    {
        $value = strtolower(trim((string) $category->value));

        return in_array($value, ['index', 'acp'], true);
    }
}
