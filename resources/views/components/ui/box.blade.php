@props(['title' => null])

<section {{ $attributes->class(['border border-slate-300 rounded-2xl bg-white shadow-sm']) }}>
    @if ($title)
    <h3 class="border-b border-slate-300 bg-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 rounded-t-2xl">
        {{ $title }}
    </h3>
    @endif

    {{ $slot }}
</section>