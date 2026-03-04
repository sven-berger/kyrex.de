<?php

namespace App\Http\Controllers\ACP\app;

use App\Http\Controllers\Controller;
use App\Models\AppCategories;
use App\Models\AppPages;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppPagesController extends Controller
{
    public function index(): View
    {
        $entries = AppPages::query()
            ->with('category')
            ->latest()
            ->get();

        $groupedEntries = $entries->groupBy(function (AppPages $page) {
            return $page->category?->id ?? 0;
        });

        $categories = AppCategories::query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();

        $categories = ['' => 'Bitte Kategorie wählen'] + $categories;

        return view('acp.app.app-pages', [
            'entries' => $entries,
            'groupedEntries' => $groupedEntries,
            'categories' => $categories,
        ]);
    }

    public function edit(AppPages $page): View
    {
        $categories = AppCategories::query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();

        $categories = ['' => 'Bitte Kategorie wählen'] + $categories;

        return view('acp.app.app-pages-edit', [
            'page' => $page,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:app_categories,id'],
        ]);

        AppPages::create($validated);

        return redirect()
            ->route('acp.app.pages')
            ->with('status', 'Seite gespeichert.');
    }

    public function update(Request $request, AppPages $entry): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:app_categories,id'],
        ]);

        $entry->update($validated);

        return redirect()
            ->route('acp.app.pages')
            ->with('status', 'Seite aktualisiert.');
    }

    public function destroy(AppPages $entry): RedirectResponse
    {
        $entry->delete();

        return redirect()
            ->route('acp.app.pages')
            ->with('status', 'Seite gelöscht.');
    }
}
