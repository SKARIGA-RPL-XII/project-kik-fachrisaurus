
<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <!-- ================= DATA ADMIN ================= -->

        <!-- Nama Lengkap -->
        <div>
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input id="name" class="block mt-1 w-full"
                type="text"
                name="name"
                :value="old('name')"
                required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- No Telp -->
        <div class="mt-4">
            <x-input-label for="phone" value="No Telepon" />
            <x-text-input id="phone" class="block mt-1 w-full"
                type="text"
                name="phone"
                :value="old('phone')" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" class="block mt-1 w-full"
                type="password"
                name="password"
                required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Konfirmasi Password" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                type="password"
                name="password_confirmation"
                required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- ================= DATA SEKOLAH ================= -->

        <hr class="my-6">

        <h3 class="text-lg font-semibold mb-2">Data Sekolah</h3>

        <!-- Nama Sekolah -->
        <div>
            <x-input-label for="school_name" value="Nama Sekolah" />
            <x-text-input id="school_name" class="block mt-1 w-full"
                type="text"
                name="school_name"
                :value="old('school_name')"
                required />
            <x-input-error :messages="$errors->get('school_name')" class="mt-2" />
        </div>

        <!-- Alamat Sekolah -->
        <div class="mt-4">
            <x-input-label for="school_address" value="Alamat Sekolah" />
            <textarea id="school_address"
                name="school_address"
                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                rows="3">{{ old('school_address') }}</textarea>
            <x-input-error :messages="$errors->get('school_address')" class="mt-2" />
        </div>

        <!-- Logo Sekolah -->
        <div class="mt-4">
            <x-input-label for="school_logo" value="Logo Sekolah" />
            <input id="school_logo"
                type="file"
                name="school_logo"
                class="block mt-1 w-full text-sm text-gray-700" />
            <x-input-error :messages="$errors->get('school_logo')" class="mt-2" />
        </div>

        <!-- ================= ACTION ================= -->

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900"
               href="{{ route('login') }}">
                Sudah punya akun?
            </a>

            <x-primary-button class="ms-4">
                Daftar
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
