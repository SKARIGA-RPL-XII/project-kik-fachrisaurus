@props(['id', 'placeholder'])

<div class="relative">
    <input
        id="{{ $id }}"
        type="password"
        placeholder="{{ $placeholder }}"
        class="w-full rounded-xl bg-transparent border border-white/30
               px-5 py-3 pr-14 focus:outline-none focus:border-[#00A6FF]"
    >

    <button
        type="button"
        onclick="togglePasswordField('{{ $id }}','eye-open-{{ $id }}','eye-off-{{ $id }}')"
        class="absolute right-3 top-1/2 -translate-y-1/2
               h-11 w-11 rounded-full flex items-center justify-center
               text-white/60 hover:text-white">

        {{-- EYE OPEN --}}
        <svg id="eye-open-{{ $id }}" xmlns="http://www.w3.org/2000/svg"
            class="block h-5 w-5" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5
                                     c4.478 0 8.268 2.943 9.542 7
                                     -1.274 4.057-5.064 7-9.542 7
                                     -4.477 0-8.268-2.943-9.542-7z" />
        </svg>

        {{-- EYE OFF --}}
        <svg id="eye-off-{{ $id }}" xmlns="http://www.w3.org/2000/svg"
            class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18M10.584 10.586a2 2 0 002.828 2.828
                                M9.878 9.88A3 3 0 0112 9c1.657 0 3 1.343 3 3
                                0 .528-.136 1.024-.374 1.456
                                M6.228 6.228C4.599 7.481 3.355 9.365 2.458 12
                                c1.274 4.057 5.064 7 9.542 7
                                1.14 0 2.234-.191 3.247-.538
                                M17.772 17.772C19.401 16.519 20.645 14.635 21.542 12
                                c-1.274-4.057-5.064-7-9.542-7
                                -1.14 0-2.234.191-3.247.538" />
        </svg>

    </button>
</div>

<script>
            function togglePasswordField(inputId, eyeOpenId, eyeOffId) {
            const input = document.getElementById(inputId);
            const eyeOpen = document.getElementById(eyeOpenId);
            const eyeOff = document.getElementById(eyeOffId);

            if (!input) return;

            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';

            eyeOpen.classList.toggle('hidden', isPassword);
            eyeOff.classList.toggle('hidden', !isPassword);
        }
</script>