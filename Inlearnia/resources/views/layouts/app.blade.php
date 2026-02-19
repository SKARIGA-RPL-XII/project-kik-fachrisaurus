<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inlearnia</title>
    {{-- resources/views/layouts/admin.blade.php --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- 1. Hapus link icon lama jika ada, ganti dengan ini --}}

    {{-- Prioritas 1: Light Mode (Warna) --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon-light.png') }}?v={{ time() }}"
        media="(prefers-color-scheme: light)">

    {{-- Prioritas 2: Dark Mode (Putih) --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon-dark.png') }}?v={{ time() }}"
        media="(prefers-color-scheme: dark)">

    {{-- Fallback: Penting ditaruh paling bawah --}}
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon-light.png') }}">
    {{-- 1. Load Font Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- 2. Aturan Font Global --}}
    <style>
        * {
            font-family: 'Inter', sans-serif !important;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-[#f8fafc] antialiased">

    {{-- 3. Memanggil Sidebar (Lebar 300px + Jarak Kiri 20px) --}}
    <x-sidebar />

    {{--
    4. MAIN CONTENT WRAPPER
    Aturan Jarak:
    - ml-[370px] : Jarak Kiri ke Sidebar 50px (20px layar + 300px sidebar + 50px gap)
    - pt-[40px] : Jarak Atas 40px
    - pr-[40px] : Jarak Kanan 40px
    - pb-[50px] : Jarak Bawah 50px
    --}}
    <main class="ml-[370px] pt-[40px] pr-[40px] pb-[50px] min-h-screen">
        @yield('content')
    </main>
@stack('scripts')
</body>

</html>