@props([
    'href'
])

<a href="{{ $href }}"
   {{ $attributes->merge([
       'class' => 'w-full border border-white/30 rounded-xl py-3
                   text-white/80 hover:bg-white/5 transition
                   flex items-center justify-center gap-2'
   ]) }}
>
    {{ $slot }}
</a>
