<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Inlearnia</title>

    @vite('resources/css/app.css')
    {{-- Prioritas 1: Light Mode (Warna) --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon-light.png') }}?v={{ time() }}"
        media="(prefers-color-scheme: light)">

    {{-- Prioritas 2: Dark Mode (Putih) --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon-dark.png') }}?v={{ time() }}"
        media="(prefers-color-scheme: dark)">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
</head>

<body class="scrollbar-hide overflow-auto min-h-screen font-sans text-white no-copy no-drag
             bg-gradient-to-br from-black via-[#041B2D] to-[#092C4C]">

    <!-- Logo -->
    <x-logres.logo />

    <div class="grid grid-cols-1 lg:grid-cols-2 h-full min-h-screen">

        <!-- LEFT : LOGIN -->
        <div class="relative flex flex-col justify-center px-8 sm:px-16 lg:px-24">

            <h1 class="text-3xl font-semibold mb-2 fade-in-up" style="animation-delay: 0.1s">
                Selamat Datang
            </h1>
            <p class="text-white/60 mb-10 fade-in-up" style="animation-delay: 0.2s">
                Selamat datang kembali, silahkan melakukan login.
            </p>


            @if ($errors->any())
                <div class="mb-6 rounded-xl bg-red-500/10 border border-red-500/30 p-4 text-sm">
                    <ul class="space-y-1 text-red-400">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form method="POST" action="{{ route('login') }}" class="space-y-6 fade-in-up"
                style="animation-delay: 0.3s">
                @csrf


                <!-- Email -->
                <div class="fade-in-up" style="animation-delay: 0.4s">
                    <label class="block mb-2 text-sm text-white/70">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="Masukan email" class="w-full rounded-xl bg-transparent border border-white/30
                            px-5 py-3 focus:outline-none focus:border-[#00A6FF]">
                </div>

                <!-- Password -->
                <div class="fade-in-up" style="animation-delay: 0.5s">
                    <label class="block mb-2 text-sm text-white/70">Password</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required placeholder="Masukan Password"
                            class="w-full rounded-xl bg-transparent border border-white/30
                                px-5 py-3 pr-14 focus:outline-none focus:border-[#00A6FF]">

                        <!-- Eye Toggle -->
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2
                                   h-11 w-11 rounded-full
                                   flex items-center justify-center
                                   text-white/60 hover:text-white">
                            <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" class="block h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5
                                     c4.478 0 8.268 2.943 9.542 7
                                     -1.274 4.057-5.064 7-9.542 7
                                     -4.477 0-8.268-2.943-9.542-7z" />
                            </svg>

                            <svg id="eye-off" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
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
                </div>

                <!-- Remember -->
                <div class="flex items-center justify-between text-sm fade-in-up" style="animation-delay: 0.6s">
                    <label class="flex items-center gap-2 text-white/70">
                        <input type="checkbox" name="remember" class="rounded border-white/30 bg-transparent">
                        Ingat saya
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="font-semibold text-[#00A6FF] hover:underline">
                            Lupa Password?
                        </a>
                    @endif

                </div>

                <button type="submit"
                    class="w-full rounded-xl bg-[#0BA69A] py-3 font-semibold hover:bg-[#08958F] transition-colors duration-300">
                    Masuk
                </button>


                <p class="text-center text-sm text-white/60 fade-in-up" style="animation-delay: 0.8s">
                    Apakah anda ingin daftar sebagai Sekolah?
                    <a href="{{ url('/register') }}" class="font-semibold text-[#00A6FF] hover:underline">
                        Register
                    </a>
                </p>
            </form>
        </div>

        <!-- RIGHT : SLIDER STABLE -->
        <div class="relative flex justify-end w-full min-h-screen overflow-hidden">

            <div id="slider-right" class="relative h-full w-full max-w-[720px] rounded-l-[1.8rem] shadow-[-20px_0_60px_rgba(0,0,0,0.35)]
                overflow-hidden">

                <!-- Image default supaya container tidak collapse -->
                <img id="quote-image" class="absolute inset-0 h-full w-full object-cover object-[center_30%]"
                    src="assets/img/teacher.png" alt="Slide Image">

                <!-- Quote Card -->
                <div id="quote-card"
                    class="absolute bottom-10 left-10 right-10 z-10 rounded-3xl backdrop-blur-xl p-8 opacity-0 transition-opacity duration-200">

                    <p id="quote-text" class="text-xl font-medium leading-relaxed mb-6"></p>

                    <div class="flex items-end justify-between">
                        <div>
                            <p id="quote-name" class="font-semibold"></p>
                            <p id="quote-role" class="text-sm text-white/70"></p>
                        </div>

                        <div class="flex flex-col items-end gap-4">
                            <div class="flex gap-1 text-white">★ ★ ★ ★ ★</div>
                            <div class="flex gap-3">
                                <button onclick="prevQuote()"
                                    class="h-9 w-9 rounded-full border border-white/40 flex items-center justify-center hover:bg-white/10">
                                    ←
                                </button>
                                <button onclick="nextQuote()"
                                    class="h-9 w-9 rounded-full border border-white/40 flex items-center justify-center hover:bg-white/10">
                                    →
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
            <!-- footer tanpa animasi -->
            <x-logres.footer />
        <script>
            // Fungsi untuk toggle password (ditambahkan di sini)
            function togglePassword() {
                const input = document.getElementById('password');
                const eyeOpen = document.getElementById('eye-open');
                const eyeOff = document.getElementById('eye-off');

                if (input.type === 'password') {
                    input.type = 'text';
                    eyeOpen.classList.add('hidden');
                    eyeOff.classList.remove('hidden');
                } else {
                    input.type = 'password';
                    eyeOpen.classList.remove('hidden');
                    eyeOff.classList.add('hidden');
                }
            }

            const slides = [
                {
                    text: '“Menghubungkan teknologi dengan pendidikan untuk membuka peluang belajar tanpa batas, di mana saja dan kapan saja”',
                    name: 'Siti Zubaidah S.Pd',
                    role: 'Guru Matematika, Pengajar<br>SMK Nowhere 404 Not Found',
                    image: 'assets/img/teacher.png'
                },
                {
                    text: '“Belajar bukan tentang tempat, tapi tentang kemauan dan konsistensi.”',
                    name: 'Siti Karina M.Pd',
                    role: 'Kepala Sekolah<br>SMA Negeri 404 Not Found',
                    image: 'assets/img/teacher2.png'
                }
            ];

            let index = 0;
            const img = document.getElementById('quote-image');
            const text = document.getElementById('quote-text');
            const name = document.getElementById('quote-name');
            const role = document.getElementById('quote-role');
            const card = document.getElementById('quote-card');

            function showSlide(idx) {
                card.style.opacity = 0;   // sembunyikan card dulu

                // update gambar
                img.src = slides[idx].image;

                // setelah gambar load, tampilkan card
                img.onload = () => {
                    text.innerHTML = slides[idx].text;
                    name.innerText = slides[idx].name;
                    role.innerHTML = slides[idx].role;
                    card.style.opacity = 1;
                };
            }

            function nextQuote() {
                index = (index + 1) % slides.length;
                showSlide(index);
            }

            function prevQuote() {
                index = (index - 1 + slides.length) % slides.length;
                showSlide(index);
            }

            // render pertama
            showSlide(index);
        </script>

</body>

</html>