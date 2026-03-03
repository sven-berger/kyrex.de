<header class="rounded-lg border border-slate-300 bg-white px-3 py-2 shadow-sm">
    <div class="flex items-center gap-5 text-xs">
        <h1 class="text-sm font-bold text-slate-900">
            <a href="{{ route('home') }}">CodeVoyage</a>
        </h1>

        <nav class="text-slate-600">
            <ul class="flex items-center gap-4">
                <li><a href="{{ route('home') }}" class="hover:text-slate-900">Startseite</a></li>
                <li><a href="{{ route('wissensportal') }}" class="hover:text-slate-900">Wissensportal</a></li>
            </ul>
        </nav>
    </div>
</header>