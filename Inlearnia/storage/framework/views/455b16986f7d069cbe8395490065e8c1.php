<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Inlearnia</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    
    <link rel="icon" type="image/png" href="<?php echo e(asset('assets/img/favicon-light.png')); ?>?v=<?php echo e(time()); ?>"
        media="(prefers-color-scheme: light)">

    
    <link rel="icon" type="image/png" href="<?php echo e(asset('assets/img/favicon-dark.png')); ?>?v=<?php echo e(time()); ?>"
        media="(prefers-color-scheme: dark)">
        
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

</head>

<body class="min-h-screen text-white font-sans no-drag no-copy">

    <div class="relative min-h-screen flex items-center justify-center bg-cover bg-center bg-animate"
     style="background-image: url('assets/img/splash-bg.png');">


        <!-- Content -->
        <div class="relative z-10 w-full max-w-4xl px-6 text-center -translate-y-6">

            <!-- Logo -->
            <div class="flex justify-center mb-12 opacity-90 drop-shadow-[0_0_12px_rgba(255,255,255,0.15)]">
                <img src="assets/img/logo-white.png" alt="Inlearnia" class="h-8 md:h-11 fade-in"
                    style="animation-delay:0s;">
            </div>

            <!-- Title -->
            <h1 class="font-semibold tracking-tight text-center leading-snug mb-8 fade-in"
                style="font-size: clamp(1.5rem, 5vw, 3rem); white-space: nowrap; overflow:hidden; text-overflow: ellipsis; animation-delay:0s;">
                Ruang Belajar Digital
            </h1>

            <!-- Subtitle lurus, center -->
            <p id="splash-text"
                class="text-xl md:text-2xl leading-snug tracking-wide text-white/70 max-w-full mb-16 text-center typing">
            </p>

            <script>
                const text = "Sistem terpusat untuk mengelola kelas, tugas, dan proses belajar-mengajar";
                const el = document.getElementById('splash-text');

                const cursor = document.createElement('span');
                cursor.className = 'cursor';
                cursor.textContent = '|';
                el.appendChild(cursor);

                let i = 0;

                function type() {
                    if (i < text.length) {
                        const span = document.createElement('span');
                        span.textContent = text[i];
                        el.insertBefore(span, cursor); // tambahkan sebelum cursor
                        i++;
                        setTimeout(type, 50);
                    } else {
                        cursor.remove(); // cursor hilang setelah selesai
                    }
                }

                type();
            </script>


            <div class="flex flex-col md:flex-row gap-7 justify-center">

                <!-- Button Left -->
                <a href="<?php echo e(route('register')); ?>" class="group flex items-center justify-center gap-5 px-12 py-6 rounded-full
        bg-white/5 backdrop-blur-xl border border-white/15 shadow-lg shadow-black/30
        transition-all duration-300 hover:bg-[#0BA69A]/90 hover:border-[#0BA69A]/80 hover:shadow-[#0BA69A]/30 hover:-translate-y-1
        relative overflow-hidden w-full md:w-auto text-center fade-in-btn">

                    <img src="assets/img/register.svg" alt="Inlearnia"
                        class="h-8 w-8 opacity-90 group-hover:opacity-100">

                    <span class="text-xl font-medium tracking-wide">
                        Daftarkan Sekolah
                    </span>
                </a>

                <!-- Button Right -->
                <a href="<?php echo e(route('login')); ?>" class="group flex items-center justify-center gap-5 px-12 py-6 rounded-full
        bg-white/5 backdrop-blur-xl border border-white/15 shadow-lg shadow-black/30
        transition-all duration-300 hover:bg-[#0BA69A]/90 hover:border-[#0BA69A]/80 hover:shadow-[#0BA69A]/30 hover:-translate-y-1
        w-full md:w-auto text-center fade-in-btn">

                    <img src="assets/img/login.svg" alt="Inlearnia"
                        class="h-8 w-8 opacity-90 group-hover:opacity-100">

                    <span class="text-xl font-medium tracking-wide">
                        Masuk ke Aplikasi
                    </span>
                </a>
            </div>

        </div>
</body>

</html><?php /**PATH C:\laragon\www\project-kik-fachrisaurus\Inlearnia\resources\views/welcome.blade.php ENDPATH**/ ?>