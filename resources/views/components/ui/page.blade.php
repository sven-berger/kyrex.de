@props(['content' => null])

<section
    {{ $attributes->merge(['class' => 'min-h-0 min-w-0 flex-1 rounded-2xl p-4 border border-slate-300 bg-white shadow-sm']) }}>
    @if(!empty($content))
    {{ $content }}
    @else
    {{ $slot }}
    @endif
</section>