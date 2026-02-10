@props([
    'type' => 'button',
    'onclick' => null,
])

<button
    type="{{ $type }}"
    @if($onclick) onclick="{{ $onclick }}" @endif
    {{ $attributes->merge([
        'class' => 'w-full bg-teal-500 hover:bg-teal-600 transition
                    rounded-xl py-3 font-semibold text-white'
    ]) }}
>
    {{ $slot }}
</button>
