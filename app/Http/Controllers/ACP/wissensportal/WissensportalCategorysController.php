<?php

namespace App\Http\Controllers\ACP\wissensportal;

use App\Models\WissensPortalCategories;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WissensportalCategorysController extends Controller
{
    public function wissensportalCategoriesAction(): View
    {
        $wpCategoriesEntries = WissensPortalCategories::query()
            ->latest()
            ->get();

        return view('acp.wissensportal.wissensportal-categories', [
            'entries' => $wpCategoriesEntries,
        ]);
    }

    public function wpEditAction(WissensPortalCategories $category): View
    {
        return view('acp.wissensportal.wp-categories-edit', [
            'category' => $category,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
        ]);

        WissensPortalCategories::create($validated);

        return redirect()
            ->route('acp.wissensportal.categories')
            ->with('status', 'Eintrag gespeichert.');
    }

    public function update(Request $request, WissensPortalCategories $entry): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
        ]);

        $entry->update($validated);

        return redirect()
            ->route('acp.wissensportal.categories')
            ->with('status', 'Eintrag aktualisiert.');
    }

    public function destroy(WissensPortalCategories $entry): RedirectResponse
    {
        $entry->delete();

        return redirect()
            ->route('acp.wissensportal.categories')
            ->with('status', 'Eintrag geloescht.');
    }
}
