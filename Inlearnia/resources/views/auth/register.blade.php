<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Register - Inlearnia</title>

    @vite('resources/css/app.css')

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
    [x-cloak] { display: none !important; }
</style>

</head>

<body class="min-h-screen font-[Inter] text-white no-drag">

    {{-- Logika PHP: Menentukan Step awal berdasarkan Error Server-side (Laravel) --}}
    @php
        $initialStep = 1;
        if ($errors->has('email')) {
            $initialStep = 1;
        } elseif ($errors->has('password') || $errors->has('password_confirmation')) {
            $initialStep = 2;
        } elseif ($errors->has('name') || $errors->has('phone')) {
            $initialStep = 3;
        } elseif ($errors->has('school_name') || $errors->has('school_address') || $errors->has('school_logo')) {
            $initialStep = 4;
        }
    @endphp

    <div class="relative min-h-screen bg-gradient-to-br from-[#031826] via-[#06253A] to-[#020B13] overflow-hidden" 
         x-data="{ 
    step: {{ $initialStep }},

    validateNext() {
        let currentStepDiv = document.getElementById('step-group-' + this.step);
        let inputs = currentStepDiv.querySelectorAll('input, select, textarea');

        for (const input of inputs) {
            if (!input.checkValidity()) {
                input.reportValidity();
                return;
            }
        }

        this.step++;
    },

    goBack() {
        this.step--;
    }
}"

        >

        <div class="absolute inset-0 bg-[linear-gradient(90deg,#000000_0%,#000000_5%,#092C4C_45%,#000000_75%,#000000_100%)]">
        </div>

        <x-logres.logo />

        <div class="relative z-10 flex min-h-[calc(100vh-96px)] px-10">
            

            <div class="w-1/3 flex justify-center pt-40">

    <ol class="relative flex flex-col gap-10">

        {{-- STEP 1 --}}
        <li class="relative pl-10"
            :class="{ 'opacity-40': step < 1 }">

            <span
                class="absolute left-0 top-1 flex h-6 w-6 items-center justify-center rounded-full text-xs font-semibold z-10 transition"
                :class="step >= 1 
                    ? 'bg-teal-500 text-white' 
                    : 'bg-[#092C4C] border border-white/20 text-white/60'">
                1
            </span>

            <h3 class="font-medium text-white">Masukkan Email</h3>
            <p class="text-sm text-white/60">Konfirmasi bahwa email itu milik Anda</p>

            <div class="absolute left-3 top-10 h-9 w-px bg-white/20"></div>
        </li>


        {{-- STEP 2 --}}
        <li class="relative pl-10"
            :class="{ 'opacity-40': step < 2 }">

            <span
                class="absolute left-0 top-1 flex h-6 w-6 items-center justify-center rounded-full text-xs z-10 transition"
                :class="step >= 2 
                    ? 'bg-teal-500 text-white' 
                    : 'bg-[#092C4C] border border-white/20 text-white/60'">
                2
            </span>

            <h3 class="font-medium text-white">Buat Password</h3>
            <p class="text-sm text-white/60">Buat password untuk akun Anda</p>

            <div class="absolute left-3 top-10 h-9 w-px bg-white/20"></div>
        </li>


        {{-- STEP 3 --}}
        <li class="relative pl-10"
            :class="{ 'opacity-40': step < 3 }">

            <span
                class="absolute left-0 top-1 flex h-6 w-6 items-center justify-center rounded-full text-xs z-10 transition"
                :class="step >= 3 
                    ? 'bg-teal-500 text-white' 
                    : 'bg-[#092C4C] border border-white/20 text-white/60'">
                3
            </span>

            <h3 class="font-medium text-white">Masukkan Nama</h3>
            <p class="text-sm text-white/60">Masukkan nama Anda sebagai Administrator</p>

            <div class="absolute left-3 top-10 h-9 w-px bg-white/20"></div>
        </li>


        {{-- STEP 4 --}}
        <li class="relative pl-10"
            :class="{ 'opacity-40': step < 4 }">

            <span
                class="absolute left-0 top-1 flex h-6 w-6 items-center justify-center rounded-full text-xs z-10 transition"
                :class="step >= 4 
                    ? 'bg-teal-500 text-white' 
                    : 'bg-[#092C4C] border border-white/20 text-white/60'">
                4
            </span>

            <h3 class="font-medium text-white">Masukkan Data Sekolah</h3>
            <p class="text-sm text-white/60">Masukkan data sekolah Anda</p>
        </li>

    </ol>
</div>


            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="w-2/3">
                @csrf

                <div id="step-group-1"
     x-show="step === 1"
     x-cloak
     class="flex justify-center pt-24 min-h-full">

                    <x-step.layout title="Masukkan Email Anda" subtitle="Masukkan alamat email yang valid untuk menerima kode">
                        <x-slot:icon>
                            <x-step-icon>
                                <svg class="z-10" width="43" height="43" viewBox="0 0 43 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M33.6442 7.0835H8.85254C7.44358 7.0835 6.09232 7.6432 5.09603 8.63949C4.09975 9.63578 3.54004 10.987 3.54004 12.396V30.1043C3.54004 31.5133 4.09975 32.8645 5.09603 33.8608C6.09232 34.8571 7.44358 35.4168 8.85254 35.4168H33.6442C35.0532 35.4168 36.4044 34.8571 37.4007 33.8608C38.397 32.8645 38.9567 31.5133 38.9567 30.1043V12.396C38.9567 10.987 38.397 9.63578 37.4007 8.63949C36.4044 7.6432 35.0532 7.0835 33.6442 7.0835ZM33.6442 10.6252L22.1338 18.5408C21.8646 18.6962 21.5592 18.778 21.2484 18.778C20.9375 18.778 20.6322 18.6962 20.363 18.5408L8.85254 10.6252H33.6442Z" fill="white" />
                                </svg>
                            </x-step-icon>
                        </x-slot:icon>

                        <div class="space-y-4">
                            <div class="fade-in-up" style="animation-delay:0.4s">
                                <x-form.input name="email" type="email" placeholder="Masukkan email" :value="old('email')" required autofocus />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                        </div>

                        <p class="text-sm text-left text-white/60 mt-6">
                            Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-[#00A6FF] hover:underline">Login</a>
                        </p>

                        <div class="mt-8 space-y-4">
                            <x-logres.primary-button type="button" @click="validateNext()">
                                Lanjutkan
                            </x-logres.primary-button>

                            <x-logres.link-button href="{{ url('/') }}">
                                <svg width="23" height="23" viewBox="0 0 25 25" fill="none" class="mr-2" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.022 3.125C12.7565 3.12529 12.5011 3.22696 12.3081 3.40922C12.115 3.59148 11.9988 3.84058 11.9833 4.10562C11.9677 4.37066 12.0539 4.63164 12.2243 4.83524C12.3948 5.03884 12.6365 5.16968 12.9001 5.20104L13.022 5.20833H18.7512V19.7917H8.33449V13.0208C8.33419 12.7553 8.23253 12.5 8.05027 12.3069C7.86801 12.1138 7.61891 11.9977 7.35387 11.9821C7.08883 11.9666 6.82784 12.0528 6.62425 12.2232C6.42065 12.3936 6.2898 12.6353 6.25845 12.899L6.25115 13.0208V19.7917H4.16782C3.90232 19.792 3.64696 19.8936 3.4539 20.0759C3.26084 20.2581 3.14466 20.5072 3.1291 20.7723C3.11354 21.0373 3.19977 21.2983 3.37018 21.5019C3.54059 21.7055 3.7823 21.8363 4.04595 21.8677L4.16782 21.875H20.8345C21.1 21.8747 21.3554 21.773 21.5484 21.5908C21.7415 21.4085 21.8576 21.1594 21.8732 20.8944C21.8888 20.6293 21.8025 20.3684 21.6321 20.1648C21.4617 19.9612 21.22 19.8303 20.9564 19.799L20.8345 19.7917V5.20833C20.8347 4.68273 20.6361 4.17649 20.2788 3.7911C19.9214 3.4057 19.4315 3.16963 18.9074 3.13021L18.7512 3.125H13.022ZM10.9387 11.4583C10.7335 11.4583 10.5303 11.4987 10.3407 11.5773C10.1511 11.6558 9.97889 11.7709 9.8338 11.916C9.68871 12.0611 9.57362 12.2333 9.49509 12.4229C9.41657 12.6125 9.37615 12.8156 9.37615 13.0208C9.37615 13.226 9.41657 13.4292 9.49509 13.6188C9.57362 13.8083 9.68871 13.9806 9.8338 14.1257C9.97889 14.2708 10.1511 14.3859 10.3407 14.4644C10.5303 14.5429 10.7335 14.5833 10.9387 14.5833C11.3531 14.5833 11.7505 14.4187 12.0435 14.1257C12.3365 13.8327 12.5012 13.4352 12.5012 13.0208C12.5012 12.6064 12.3365 12.209 12.0435 11.916C11.7505 11.623 11.3531 11.4583 10.9387 11.4583ZM5.6397 4.34583L3.43136 6.55417C3.2364 6.74947 3.1269 7.01415 3.1269 7.2901C3.1269 7.56606 3.2364 7.83074 3.43136 8.02604L5.6397 10.2375C5.83602 10.4274 6.09908 10.5326 6.3722 10.5304C6.64533 10.5282 6.90667 10.4189 7.09994 10.2259C7.29321 10.0329 7.40295 9.77167 7.40552 9.49854C7.40808 9.22542 7.30327 8.96222 7.11365 8.76562L6.68032 8.33125H10.022C10.2983 8.33125 10.5632 8.2215 10.7586 8.02615C10.9539 7.8308 11.0637 7.56585 11.0637 7.28958C11.0637 7.01332 10.9539 6.74836 10.7586 6.55301C10.5632 6.35766 10.2983 6.24792 10.022 6.24792H6.68345L7.11261 5.81875C7.30236 5.62229 7.40736 5.35916 7.40498 5.08604C7.40261 4.81292 7.29306 4.55166 7.09993 4.35852C6.90679 4.16539 6.64553 4.05584 6.37241 4.05346C6.09929 4.05109 5.83616 4.15609 5.6397 4.34583Z" fill="white" />
                                </svg>
                                Batalkan
                            </x-logres.link-button>
                        </div>
                        <x-step.indicator :active="1" />
                    </x-step.layout>
                </div>

                <div id="step-group-2"
     x-show="step === 2"
     x-cloak
     class="flex justify-center pt-24 min-h-full">

                    <x-step.layout title="Buat Password Anda" subtitle="Buat password untuk akun Anda">
                        <x-slot:icon>
                            <x-step-icon>
                                <svg class="z-10" width="43" height="43" viewBox="0 0 43 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M33.6442 7.0835H8.85254C7.44358 7.0835 6.09232 7.6432 5.09603 8.63949C4.09975 9.63578 3.54004 10.987 3.54004 12.396V30.1043C3.54004 31.5133 4.09975 32.8645 5.09603 33.8608C6.09232 34.8571 7.44358 35.4168 8.85254 35.4168H33.6442C35.0532 35.4168 36.4044 34.8571 37.4007 33.8608C38.397 32.8645 38.9567 31.5133 38.9567 30.1043V12.396C38.9567 10.987 38.397 9.63578 37.4007 8.63949C36.4044 7.6432 35.0532 7.0835 33.6442 7.0835ZM33.6442 10.6252L22.1338 18.5408C21.8646 18.6962 21.5592 18.778 21.2484 18.778C20.9375 18.778 20.6322 18.6962 20.363 18.5408L8.85254 10.6252H33.6442Z" fill="white" />
                                </svg>
                            </x-step-icon>
                        </x-slot:icon>
                        <div class="space-y-4">
                            <div class="fade-in-up" style="animation-delay:0.5s">
                                <x-form.password name="password" id="password" placeholder="Masukkan password" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                            <div class="fade-in-up" style="animation-delay:0.6s">
                                <x-form.password name="password_confirmation" id="confirm-password" placeholder="Konfirmasi password" required />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>
                        <div class="mt-8 space-y-4">
                            <x-logres.primary-button type="button" @click="validateNext()">Lanjutkan</x-logres.primary-button>
                            <x-logres.secondary-button type="button" @click="goBack()">Kembali</x-logres.secondary-button>
                        </div>
                        <x-step.indicator :active="2" />
                    </x-step.layout>
                </div>

                <div id="step-group-3"
     x-show="step === 3"
     x-cloak
     class="flex justify-center pt-24 min-h-full">

                    <x-step.layout title="Masukkan Nama Anda" subtitle="Masukkan nama Anda sebagai administrator sekolah">
                        <x-slot:icon>
                            <x-step-icon>
                                <svg class="z-10" width="43" height="43" viewBox="0 0 43 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M33.6442 7.0835H8.85254C7.44358 7.0835 6.09232 7.6432 5.09603 8.63949C4.09975 9.63578 3.54004 10.987 3.54004 12.396V30.1043C3.54004 31.5133 4.09975 32.8645 5.09603 33.8608C6.09232 34.8571 7.44358 35.4168 8.85254 35.4168H33.6442C35.0532 35.4168 36.4044 34.8571 37.4007 33.8608C38.397 32.8645 38.9567 31.5133 38.9567 30.1043V12.396C38.9567 10.987 38.397 9.63578 37.4007 8.63949C36.4044 7.6432 35.0532 7.0835 33.6442 7.0835ZM33.6442 10.6252L22.1338 18.5408C21.8646 18.6962 21.5592 18.778 21.2484 18.778C20.9375 18.778 20.6322 18.6962 20.363 18.5408L8.85254 10.6252H33.6442Z" fill="white" />
                                </svg>
                            </x-step-icon>
                        </x-slot:icon>
                        <div class="space-y-4">
                            <div class="fade-in-up" style="animation-delay:0.4s">
                                <x-form.input name="name" type="text" placeholder="Masukkan nama lengkap" :value="old('name')" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div class="fade-in-up" style="animation-delay:0.5s">
                                <x-form.input name="phone" type="text" placeholder="Masukkan nomor telepon" :value="old('phone')" required />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>
                        </div>
                        <div class="mt-8 space-y-4">
                            <x-logres.primary-button type="button" @click="validateNext()">Lanjutkan</x-logres.primary-button>
                            <x-logres.secondary-button type="button" @click="goBack()">Kembali</x-logres.secondary-button>
                        </div>
                        <x-step.indicator :active="3" />
                    </x-step.layout>
                </div>

                <div id="step-group-4"
     x-show="step === 4"
     x-cloak
     class="flex justify-center pt-24 min-h-full">

                    <x-step.layout title="Data Sekolah" subtitle="Masukkan data sekolah yang ingin Anda daftarkan">
                        <x-slot:icon>
                            <x-step-icon>
                                <svg class="z-10" width="43" height="43" viewBox="0 0 43 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M33.6442 7.0835H8.85254C7.44358 7.0835 6.09232 7.6432 5.09603 8.63949C4.09975 9.63578 3.54004 10.987 3.54004 12.396V30.1043C3.54004 31.5133 4.09975 32.8645 5.09603 33.8608C6.09232 34.8571 7.44358 35.4168 8.85254 35.4168H33.6442C35.0532 35.4168 36.4044 34.8571 37.4007 33.8608C38.397 32.8645 38.9567 31.5133 38.9567 30.1043V12.396C38.9567 10.987 38.397 9.63578 37.4007 8.63949C36.4044 7.6432 35.0532 7.0835 33.6442 7.0835ZM33.6442 10.6252L22.1338 18.5408C21.8646 18.6962 21.5592 18.778 21.2484 18.778C20.9375 18.778 20.6322 18.6962 20.363 18.5408L8.85254 10.6252H33.6442Z" fill="white" />
                                </svg>
                            </x-step-icon>
                        </x-slot:icon>
                        <div class="space-y-4">
                            <label class="mb-6 border-2 border-dashed border-[#00A6FF] rounded-xl p-6 h-[180px] flex items-center justify-center text-center cursor-pointer hover:border-teal-400 transition relative overflow-hidden">
                                <input type="file" name="school_logo" id="schoolLogoInput" accept="image/*" class="hidden" onchange="previewSchoolLogo(event)">
                                <button type="button" onclick="removeSchoolLogo(event)" id="removeLogoBtn" class="absolute top-3 right-3 hidden bg-red-500 hover:bg-red-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs z-10">✕</button>
                                <div id="logoPreviewWrapper" class="hidden w-full h-full flex items-center justify-center">
                                    <img id="logoPreview" class="max-h-full max-w-full object-contain rounded-md" />
                                </div>
                                <div id="uploadPlaceholder" class="flex flex-col items-center justify-center">
                                    <svg class="mx-auto mb-3" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="white">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16V12M12 16V8M17 16V12M3 20h18" />
                                    </svg>
                                    <p class="text-sm text-white/70">Drag & drop logo sekolah di sini<br><span class="text-white/40">atau klik untuk upload</span></p>
                                </div>
                            </label>
                            <x-input-error :messages="$errors->get('school_logo')" class="mt-2" />
                            
                            <div class="fade-in-up" style="animation-delay:0.4s">
                                <x-form.input name="school_name" type="text" placeholder="Masukkan nama sekolah" :value="old('school_name')" required />
                                <x-input-error :messages="$errors->get('school_name')" class="mt-2" />
                            </div>
                            <div class="fade-in-up" style="animation-delay:0.4s">
                                <textarea name="school_address" rows="3" class="w-full rounded-xl bg-transparent border border-white/30 px-5 py-3 focus:outline-none focus:border-[#00A6FF]" placeholder="Masukkan alamat sekolah" required>{{ old('school_address') }}</textarea>
                                <x-input-error :messages="$errors->get('school_address')" class="mt-2" />
                            </div>
                        </div>
                        <div class="mt-8 space-y-4">
                            <x-logres.primary-button type="submit">Selesai</x-logres.primary-button>
                            <x-logres.secondary-button type="button" @click="goBack()">Kembali</x-logres.secondary-button>
                        </div>
                        <x-step.indicator :active="4" />
                    </x-step.layout>
                </div>
            </form>
        </div>
        <x-logres.footer />
    </div>

    <script>
        function previewSchoolLogo(event) {
            const input = event.target;
            const file = input.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logoPreview').src = e.target.result;

                document.getElementById('logoPreviewWrapper').classList.remove('hidden');
                document.getElementById('uploadPlaceholder').classList.add('hidden');
                document.getElementById('removeLogoBtn').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }

        function removeSchoolLogo(event) {
            event.preventDefault();
            event.stopPropagation();

            const input = document.getElementById('schoolLogoInput');
            input.value = '';

            document.getElementById('logoPreviewWrapper').classList.add('hidden');
            document.getElementById('uploadPlaceholder').classList.remove('hidden');
            document.getElementById('removeLogoBtn').classList.add('hidden');
        }
    </script>

</body>

</html>