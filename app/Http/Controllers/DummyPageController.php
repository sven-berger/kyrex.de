<?php

namespace App\Http\Controllers;

use App\Models\DummyPageEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DummyPageController extends Controller
{
    public function index(Request $request): View
    {

        $entries = DummyPageEntry::query()
            ->latest()
            ->get();

        $editEntry = null;
        $editId = (int) $request->query('edit', 0);
        if ($editId > 0) {
            $editEntry = DummyPageEntry::query()->find($editId);
        }

        return view('dummy-page', [
            'entries' => $entries,
            'editEntry' => $editEntry,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string'],
            'content' => ['required', 'string'],
        ]);

        DummyPageEntry::create($validated);
        return redirect()
            ->route('dummy-page.index')
            ->with('status', 'Eintrag gespeichert.');
    }

    public function update(Request $request, DummyPageEntry $entry): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string'],
            'content' => ['required', 'string'],

        ]);

        $entry->update($validated);
        return redirect()
            ->route('dummy-page.index')
            ->with('status', 'Eintrag aktualisiert.');
    }

    public function destroy(DummyPageEntry $entry): RedirectResponse
    {
        $entry->delete();
        return redirect()
            ->route('dummy-page.index')
            ->with('status', 'Eintrag geloescht.');
    }
}
