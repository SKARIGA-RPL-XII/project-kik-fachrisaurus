@props(['type' => 'text', 'placeholder' => ''])

<input
    type="{{ $type }}"
    placeholder="{{ $placeholder }}"
    {{ $attributes->merge([
        'class' => 'w-full rounded-xl bg-transparent border border-white/30
                    px-5 py-3 focus:outline-none focus:border-[#00A6FF]'
    ]) }}
>
