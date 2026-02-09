<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registerForm">
        @csrf

        <div id="step-email">
            <h2 class="text-xl font-bold mb-4">Langkah 1: Masukkan Email</h2>
            
            <div class="mt-4">
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between mt-6">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    Sudah punya akun? Login
                </a>

                <div class="flex gap-2">
                    <button type="button" onclick="window.location='{{ route('login') }}'" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md">
                        Batalkan
                    </button>
                    <button type="button" id="btnToOtp" class="px-4 py-2 bg-indigo-600 text-white rounded-md">
                        Selanjutnya
                    </button>
                </div>
            </div>
        </div>

        <div id="step-otp" class="hidden">
            <h2 class="text-xl font-bold mb-4">Langkah 1: Verifikasi OTP</h2>
            <p class="text-sm text-gray-600 mb-4">Kode OTP telah dikirim ke email Anda.</p>

            <div class="mt-4">
                <x-input-label for="otp" value="Kode OTP" />
                <x-text-input id="otp" name="otp" type="text" maxlength="4" inputmode="numeric" placeholder="1234" class="block mt-1 w-full" />
                <x-input-error :messages="$errors->get('otp')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between mt-6">
                <button type="button" id="resendOtpBtn" class="text-sm text-indigo-600 hover:underline">
                    Kirim Ulang Kode
                </button>

                <div class="flex gap-2">
                    <button type="button" onclick="switchStep('step-otp', 'step-email')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md">
                        Kembali
                    </button>
                    <button type="button" onclick="switchStep('step-otp', 'step-password')" class="px-4 py-2 bg-indigo-600 text-white rounded-md">
                        Lanjutkan
                    </button>
                </div>
            </div>
        </div>

        <div id="step-password" class="hidden">
            <h2 class="text-xl font-bold mb-4">Langkah 2: Buat Password</h2>

            <div class="mt-4">
                <x-input-label for="password" value="Password" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex justify-end mt-6 gap-2">
                <button type="button" onclick="switchStep('step-password', 'step-otp')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md">
                    Kembali
                </button>
                <button type="button" onclick="switchStep('step-password', 'step-profile')" class="px-4 py-2 bg-indigo-600 text-white rounded-md">
                    Lanjutkan
                </button>
            </div>
        </div>

        <div id="step-profile" class="hidden">
            <h2 class="text-xl font-bold mb-4">Langkah 3: Data Pribadi</h2>

            <div>
                <x-input-label for="name" value="Nama Lengkap" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="phone" value="No Telepon" />
                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <div class="flex justify-end mt-6 gap-2">
                <button type="button" onclick="switchStep('step-profile', 'step-password')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md">
                    Kembali
                </button>
                <button type="button" onclick="switchStep('step-profile', 'step-school')" class="px-4 py-2 bg-indigo-600 text-white rounded-md">
                    Lanjutkan
                </button>
            </div>
        </div>

        <div id="step-school" class="hidden">
            <h2 class="text-xl font-bold mb-4">Langkah 4: Data Sekolah</h2>

            <div class="mt-4">
                <x-input-label for="school_logo" value="Logo Sekolah" />
                <input id="school_logo" type="file" name="school_logo" class="block mt-1 w-full text-sm text-gray-700 border border-gray-300 rounded-md p-2" />
                <x-input-error :messages="$errors->get('school_logo')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="school_name" value="Nama Sekolah" />
                <x-text-input id="school_name" class="block mt-1 w-full" type="text" name="school_name" :value="old('school_name')" required />
                <x-input-error :messages="$errors->get('school_name')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="school_address" value="Alamat Sekolah" />
                <textarea id="school_address" name="school_address" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="3">{{ old('school_address') }}</textarea>
                <x-input-error :messages="$errors->get('school_address')" class="mt-2" />
            </div>

            <div class="flex justify-end mt-6 gap-2">
                <button type="button" onclick="switchStep('step-school', 'step-profile')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md">
                    Kembali
                </button>
                
                <x-primary-button>
                    Daftar Sekarang
                </x-primary-button>
            </div>
        </div>

    </form>

    <script>
        // Fungsi umum untuk pindah step
        function switchStep(currentId, nextId) {
            // Validasi sederhana (opsional, biar tidak kosong saat next)
            const currentDiv = document.getElementById(currentId);
            const inputs = currentDiv.querySelectorAll('input[required], textarea[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                if (!input.value) {
                    isValid = false;
                    input.classList.add('border-red-500'); // Highlight error
                } else {
                    input.classList.remove('border-red-500');
                }
            });

            // Jika tombol Back ditekan (nextId urutannya mundur), skip validasi
            // Tapi logika sederhana di sini kita cek arah perpindahan via ID div manual
            // Untuk simplifikasi: Kita izinkan pindah jika valid atau jika itu tombol 'Kembali'
            
            // Cek apakah tombol yang diklik adalah tombol kembali (logic manual)
            const isBack = (currentId === 'step-otp' && nextId === 'step-email') ||
                           (currentId === 'step-password' && nextId === 'step-otp') ||
                           (currentId === 'step-profile' && nextId === 'step-password') ||
                           (currentId === 'step-school' && nextId === 'step-profile');

            if (!isValid && !isBack) {
                alert('Harap isi semua field wajib sebelum melanjutkan.');
                return;
            }

            document.getElementById(currentId).classList.add('hidden');
            document.getElementById(nextId).classList.remove('hidden');
        }

        // Logic kirim OTP (Tombol Selanjutnya di Step 1)
        document.getElementById('btnToOtp').addEventListener('click', function () {
            const emailInput = document.getElementById('email');
            const email = emailInput.value;

            if (!email) {
                alert('Isi email terlebih dahulu');
                emailInput.focus();
                return;
            }

            // Tampilkan loading state jika perlu (opsional)
            const btn = this;
            const originalText = btn.innerText;
            btn.innerText = 'Mengirim...';
            btn.disabled = true;

            fetch("{{ route('otp.send') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ email })
            })
            .then(res => res.json())
            .then(res => {
                btn.innerText = originalText;
                btn.disabled = false;
                
                // Asumsi response sukses
                // alert(res.message); // Boleh di uncomment kalau mau ada alert
                
                // Pindah ke step OTP
                switchStep('step-email', 'step-otp');
            })
            .catch(err => {
                btn.innerText = originalText;
                btn.disabled = false;
                alert('Gagal mengirim OTP. Silakan coba lagi.');
                console.error(err);
            });
        });

        // Logic Kirim Ulang OTP (di dalam Step OTP)
        document.getElementById('resendOtpBtn').addEventListener('click', function () {
            const email = document.getElementById('email').value;
            fetch("{{ route('otp.send') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ email })
            })
            .then(res => res.json())
            .then(res => alert('OTP dikirim ulang!'));
        });
    </script>
</x-guest-layout>