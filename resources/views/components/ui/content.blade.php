@props(['content' => null])

<section {{ $attributes->merge(['class' => 'min-w-0 rounded-2xl p-4 border border-slate-300 bg-white']) }}>
    @if(!empty($content))
    {{ $content }}
    @else
    {{ $slot }}
    @endif
</section>