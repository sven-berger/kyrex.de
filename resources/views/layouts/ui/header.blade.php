@php
$navLinks = [
['label' => 'Startseite', 'url' => route('home')],
['label' => 'Wissensportal', 'url' => route('wissensportal')],
['label' => 'Test #1', 'url' => '/test1'],
['label' => 'Test #2', 'url' => '/test2'],
];

$mobileVisibleCount = 1;
$visibleMobileLinks = array_slice($navLinks, 0, $mobileVisibleCount);
$moreMobileLinks = array_slice($navLinks, $mobileVisibleCount);
@endphp

<header class="rounded-lg border border-slate-300 bg-white px-3 py-2 shadow-sm" x-data="{ mobileNavOpen: false }"
    @click.outside="mobileNavOpen = false">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-sm font-bold text-slate-900">
            <a href="{{ route('home') }}">CodeVoyage</a>
        </h1>

        <nav class="text-xs text-slate-600 sm:min-w-0">
            <ul class="hidden items-center gap-4 lg:flex">
                @foreach ($navLinks as $link)
                <li>
                    <a href="{{ $link['url'] }}" class="hover:text-slate-900">{{ $link['label'] }}</a>
                </li>
                @endforeach
            </ul>

            <div class="relative flex items-center justify-end gap-3 lg:hidden">
                @foreach ($visibleMobileLinks as $link)
                <a href="{{ $link['url'] }}" class="hover:text-slate-900">{{ $link['label'] }}</a>
                @endforeach

                @if (!empty($moreMobileLinks))
                <button type="button"
                    class="rounded-md border border-slate-300 px-2 py-1 text-slate-700 hover:bg-slate-100"
                    @click="mobileNavOpen = !mobileNavOpen" :aria-expanded="mobileNavOpen.toString()">
                    Mehr
                </button>

                <div class="absolute right-0 top-8 z-30 min-w-40 rounded-lg border border-slate-300 bg-white p-1 shadow-sm"
                    x-show="mobileNavOpen" x-transition.opacity x-cloak>
                    @foreach ($moreMobileLinks as $link)
                    <a href="{{ $link['url'] }}" class="block rounded-md px-3 py-2 text-slate-700 hover:bg-slate-100"
                        @click="mobileNavOpen = false">{{ $link['label'] }}</a>
                    @endforeach
                </div>
                @endif
            </div>
        </nav>
    </div>
</header>