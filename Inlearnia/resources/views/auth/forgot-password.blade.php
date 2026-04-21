<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Lupa Password - Inlearnia</title>

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

    <x-logres.logo />

    <div class="grid grid-cols-1 lg:grid-cols-2 h-full min-h-screen">

        <div class="relative flex flex-col justify-center px-8 sm:px-16 lg:px-24">

            <h1 class="text-3xl font-semibold mb-2 fade-in-up" style="animation-delay: 0.1s">
                Lupa Password?
            </h1>
            <p class="text-white/60 mb-8 fade-in-up leading-relaxed" style="animation-delay: 0.2s">
                Tidak masalah. Beritahu kami alamat email Anda dan kami akan mengirimkan tautan untuk mereset password agar Anda dapat memilih yang baru.
            </p>

            @if (session('status'))
                <div class="mb-6 rounded-xl bg-green-500/10 border border-green-500/30 p-4 text-sm fade-in-up" style="animation-delay: 0.3s">
                    <p class="text-green-400">{{ session('status') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-xl bg-red-500/10 border border-red-500/30 p-4 text-sm fade-in-up" style="animation-delay: 0.3s">
                    <ul class="space-y-1 text-red-400">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6 fade-in-up"
                style="animation-delay: 0.4s">
                @csrf

                <div class="fade-in-up" style="animation-delay: 0.5s">
                    <label class="block mb-2 text-sm text-white/70">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="Masukan email Anda" class="w-full rounded-xl bg-transparent border border-white/30
                            px-5 py-3 focus:outline-none focus:border-[#00A6FF]">
                </div>

                <div class="pt-2 fade-in-up" style="animation-delay: 0.6s">
                    <button type="submit"
                        class="w-full rounded-xl bg-[#0BA69A] py-3 font-semibold hover:bg-[#08958F] transition-colors duration-300">
                        Kirim Tautan Reset Password
                    </button>
                </div>

                <p class="text-center text-sm text-white/60 fade-in-up" style="animation-delay: 0.7s">
                    Ingat password Anda?
                    <a href="{{ route('login') }}" class="font-semibold text-[#00A6FF] hover:underline">
                        Kembali ke Login
                    </a>
                </p>
            </form>
        </div>

        <div class="relative flex justify-end w-full min-h-screen overflow-hidden">

            <div id="slider-right" class="relative h-full w-full max-w-[720px] rounded-l-[1.8rem] shadow-[-20px_0_60px_rgba(0,0,0,0.35)]
                overflow-hidden">

                <img id="quote-image" class="absolute inset-0 h-full w-full object-cover object-[center_30%]"
                    src="{{ asset('assets/img/teacher.png') }}" alt="Slide Image">

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
        
        <x-logres.footer />
        
        <script>
            const slides = [
                {
                    text: '“Menghubungkan teknologi dengan pendidikan untuk membuka peluang belajar tanpa batas, di mana saja dan kapan saja”',
                    name: 'Siti Zubaidah S.Pd',
                    role: 'Guru Matematika, Pengajar<br>SMK Nowhere 404 Not Found',
                    image: '{{ asset('assets/img/teacher.png') }}'
                },
                {
                    text: '“Belajar bukan tentang tempat, tapi tentang kemauan dan konsistensi.”',
                    name: 'Siti Karina M.Pd',
                    role: 'Kepala Sekolah<br>SMA Negeri 404 Not Found',
                    image: '{{ asset('assets/img/teacher2.png') }}'
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