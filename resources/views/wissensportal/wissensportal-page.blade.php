<x-layouts.app :title="$page->name">
    <x-ui.page class="flex flex-col">
        @if (!empty($page->content))
        <div class="wp-rich-content mt-4 text-slate-800">{!! $page->content !!}</div>
        @endif

        @foreach ([1, 2, 3, 4, 5] as $snippetNumber)
        @php
        $titleField = 'snippet_' . $snippetNumber . '_title';
        $contentField = 'snippet_' . $snippetNumber;
        $snippetTitle = $page->{$titleField};
        $snippetContent = $page->{$contentField};
        @endphp

        @if (!empty($snippetTitle) && !empty($snippetContent))
        <div class="mt-8">
            <h3 class="snippet-title">{{ $snippetTitle }}</h3>
            <div class="wp-rich-content text-slate-800">{!! $snippetContent !!}</div>
        </div>
        @endif
        @endforeach

        <!-- Seite bearbeiten Link nur anzeigen, wenn der Admin angemeldet ist -->
        @auth
        @if (auth()->user()->hasRole('admin'))
        <div class="mt-auto flex justify-end">
            <x-ui.button url="{{ route('acp.wissensportal.pages.edit', $page) }}" variant="primary">
                Seite bearbeiten
            </x-ui.button>
        </div>
        @endif
        @endauth
    </x-ui.page>
</x-layouts.app>