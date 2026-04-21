<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Inlearnia' }}</title>

    @vite('resources/css/app.css')

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon-light.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon-dark.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
</head>

<body class="flex flex-col min-h-screen font-sans text-white no-copy no-drag
             bg-gradient-to-br from-black via-[#041B2D] to-[#092C4C]">

    <!-- Logo -->
    <x-logres.logo />

    <!-- CONTENT -->
    <div class="grid grid-cols-1 lg:grid-cols-2 flex-1">

        <!-- LEFT -->
        <div class="flex flex-col justify-center px-8 sm:px-16 lg:px-24">

            {{ $slot }}

        </div>

        <!-- RIGHT -->
        <div class="relative hidden lg:flex justify-end w-full h-full overflow-hidden">
            <div class="relative h-full w-full max-w-[720px]
                rounded-l-[1.8rem] shadow-[-20px_0_60px_rgba(0,0,0,0.35)]
                overflow-hidden">

                <img class="absolute inset-0 h-full w-full object-cover object-[center_30%]"
                    src="{{ asset('assets/img/teacher.png') }}" alt="Image">

            </div>
        </div>

    </div>

    <!-- Footer -->
    <x-logres.footer />

</body>
</html>