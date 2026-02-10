@props(['title', 'subtitle'])

<div class="w-full max-w-md">


    {{-- ICON --}}
    @isset($icon)
        <div class="mb-8">
            {{ $icon }}
        </div>
    @endisset

    {{-- TITLE --}}
    <h1 class="text-3xl font-semibold text-center mb-2">
        {{ $title }}
    </h1>

    {{-- SUBTITLE --}}
    <p class="text-center text-white/60 mb-10">
        {{ $subtitle }}
    </p>

    {{-- CONTENT --}}
    {{ $slot }}

</div>
