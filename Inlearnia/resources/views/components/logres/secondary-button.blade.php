@props([
    'onclick' => null,
])

<button
    type="button"
    onclick="{{ $onclick }}"
    {{ $attributes->merge([
        'class' => 'w-full border border-white/30 rounded-xl py-3
                    text-white/80 hover:bg-white/5 transition
                    flex items-center justify-center gap-2'
    ]) }}
>
    <svg width="17" height="15" viewBox="0 0 17 15" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <path
            d="M16.4141 7.45706H1.41406M1.41406 7.45706L8.16405 0.707031M1.41406 7.45706L8.16405 14.207"
            stroke="white" stroke-width="2" />
    </svg>

    {{ $slot }}
</button>
