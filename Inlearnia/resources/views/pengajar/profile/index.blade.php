@extends('layouts.app')

@section('content')
    <x-sidebar />

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-10">
        <div class="flex-1">
            <x-breadcrumb :items="[['label' => 'Profil', 'url' => null]]" />
        </div>
        <x-calendar />
    </div>

    {{-- =============================== MAIN GRID =============================== --}}
    <div class="flex flex-col lg:flex-row gap-6">

        {{-- ===================== KOLOM KIRI ===================== --}}
        <div class="w-full lg:w-[300px] flex-shrink-0 flex flex-col gap-5">

            {{-- Kartu Sekolah --}}
            <div class="bg-white rounded-[20px] shadow-sm border border-slate-200 overflow-hidden">
                {{-- Banner --}}
                <div class="h-[70px] bg-gradient-to-br from-[#0F4C5C] to-[#00A99D]"></div>

                <div class="px-6 pb-6 -mt-10 flex flex-col items-center text-center">
                    {{-- Logo Sekolah --}}
                    <div class="w-20 h-20 rounded-full border-4 border-white overflow-hidden shadow-md bg-white mb-3">
                        @if ($school?->logo)
                            <img src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <div
                                class="w-full h-full bg-[#0F4C5C] flex items-center justify-center text-white text-2xl font-bold">
                                {{ strtoupper(substr($school?->name ?? 'S', 0, 2)) }}
                            </div>
                        @endif
                    </div>

                    <h2 class="text-[#092C4C] font-bold text-[15px] leading-tight mb-1">
                        {{ $school?->name ?? 'Nama Sekolah Belum Diisi' }}
                    </h2>

                    @if ($school?->npsn)
                        <p class="text-slate-400 text-[11px] tracking-wide mb-2">NPSN: {{ $school->npsn }}</p>
                    @endif

                    @if ($school?->accreditation)
                        <span
                            class="bg-[#0F4C5C] text-white text-[10px] font-semibold px-4 py-1 rounded-full tracking-wider">
                            Akreditasi {{ $school->accreditation }}
                        </span>
                    @endif
                </div>

                {{-- Info singkat sekolah --}}
                <div class="border-t border-slate-100 px-6 py-4 flex flex-col gap-3">
                    @if ($school?->phone)
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-slate-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <span class="text-[12px] text-slate-600">{{ $school->phone }}</span>
                        </div>
                    @endif
                    @if ($school?->email)
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-slate-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-[12px] text-slate-600 truncate">{{ $school->email }}</span>
                        </div>
                    @endif
                    @if ($school?->address)
                        <div class="flex items-start gap-3">
                            <div
                                class="w-7 h-7 rounded-lg bg-slate-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <span class="text-[12px] text-slate-600 leading-relaxed">{{ $school->address }}</span>
                        </div>
                    @endif
                </div>
                {{-- Tombol Edit Data Sekolah Dihapus untuk Role Pengajar --}}
            </div>
        </div>

        {{-- ===================== KOLOM KANAN ===================== --}}
        <div class="flex-1 flex flex-col gap-5">

            {{-- Kartu Profil Pengajar (Sekarang di atas) --}}
            <div class="bg-white rounded-[20px] shadow-sm border border-slate-200 p-7">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-slate-100">
                    <div class="w-2 h-2 rounded-full bg-[#FFAE00]"></div>
                    <h3 class="text-[#092C4C] text-[16px] font-semibold">Informasi Pengajar</h3>
                </div>

                <div class="flex items-center gap-5 mb-6">
                    <div class="w-14 h-14 rounded-full overflow-hidden border-2 border-[#0F4C5C] flex-shrink-0">
                        @if ($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0F4C5C&color=ffffff&size=128"
                                class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div>
                        <div class="text-[#092C4C] font-bold text-[15px]">{{ $user->name }}</div>
                        <span
                            class="bg-blue-100 text-blue-600 text-[11px] font-semibold px-3 py-0.5 rounded-full">Pengajar</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-5 gap-x-8">
                    <div>
                        <label class="block text-[10px] text-slate-400 uppercase tracking-wider mb-1">Nama Lengkap</label>
                        <div class="text-[#092C4C] font-medium text-[14px]">{{ $user->name }}</div>
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-400 uppercase tracking-wider mb-1">Email</label>
                        <div class="text-[#092C4C] font-medium text-[14px]">{{ $user->email }}</div>
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-400 uppercase tracking-wider mb-1">No. Telp</label>
                        <div class="text-[#092C4C] font-medium text-[14px]">{{ $user->phone ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-400 uppercase tracking-wider mb-1">Bergabung Sejak</label>
                        <div class="text-[#092C4C] font-medium text-[14px]">
                            {{ $user->created_at->translatedFormat('d F Y') }}
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="button"
                        onclick="openEditModal(
                            '{{ addslashes($user->name) }}',
                            '{{ addslashes($user->email) }}',
                            '{{ $user->phone ?? '' }}',
                            '{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : '' }}'
                        )"
                        class="bg-[#00A99D] hover:bg-[#008f84] text-white px-6 py-2.5 rounded-[10px] text-[13px] font-medium transition shadow-md shadow-teal-500/20 flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                        Ubah Profil
                    </button>
                </div>
            </div>

            {{-- Kartu Data Sekolah Lengkap (Sekarang di bawah) --}}
            <div class="bg-white rounded-[20px] shadow-sm border border-slate-200 p-7">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-slate-100">
                    <div class="w-2 h-2 rounded-full bg-[#00A99D]"></div>
                    <h3 class="text-[#092C4C] text-[16px] font-semibold">Data Sekolah</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                    <div>
                        <label class="block text-[10px] text-slate-400 uppercase tracking-wider mb-1">Nama Sekolah</label>
                        <div class="text-[#092C4C] font-medium text-[14px]">{{ $school?->name ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-400 uppercase tracking-wider mb-1">NPSN</label>
                        <div class="text-[#092C4C] font-medium text-[14px]">{{ $school?->npsn ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-400 uppercase tracking-wider mb-1">Jenjang</label>
                        <div class="text-[#092C4C] font-medium text-[14px]">{{ $school?->level ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-400 uppercase tracking-wider mb-1">Status</label>
                        <div class="text-[#092C4C] font-medium text-[14px]">{{ $school?->status ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-400 uppercase tracking-wider mb-1">Akreditasi</label>
                        <div class="text-[#092C4C] font-medium text-[14px]">
                            @if ($school?->accreditation)
                                <span class="bg-[#0F4C5C] text-white text-[11px] font-bold px-3 py-0.5 rounded-full">
                                    {{ $school->accreditation }}
                                </span>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-400 uppercase tracking-wider mb-1">Kepala Sekolah</label>
                        <div class="text-[#092C4C] font-medium text-[14px]">{{ $school?->principal_name ?? '-' }}</div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] text-slate-400 uppercase tracking-wider mb-1">Alamat</label>
                        <div class="text-[#092C4C] font-medium text-[14px]">{{ $school?->address ?? '-' }}</div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    {{-- ===================== MODAL: Edit Profil Pengajar ===================== --}}
    <x-ui.modal-form id="modalEditProfile" title="Edit Profil Pengajar" description="Perbarui data akun Anda"
        iconBg="#FFAE00" buttonColor="bg-teal-500 hover:bg-teal-600">

        <x-slot name="icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-3.33 0-10 1.67-10 5v3h20v-3c0-3.33-6.67-5-10-5z" />
            </svg>
        </x-slot>

        {{-- Pastikan route ini sesuai dengan route update profile untuk pengajar di web.php Anda --}}
        <form id="editProfileForm" action="{{ route('teacher.profile.update') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            {{-- Foto --}}
            <div class="mb-5">
                <label class="text-[13px] font-medium text-slate-600 block mb-2">Foto Profil</label>
                <div id="editPhotoPreviewWrapper" class="mb-3 hidden">
                    <p class="text-[12px] text-slate-400 mb-1">Foto saat ini:</p>
                    <img id="editPhotoPreview" src="" alt="Foto profil"
                        class="w-14 h-14 rounded-full object-cover border border-slate-200">
                </div>
                <div class="flex items-center border border-slate-300 rounded-[10px] overflow-hidden h-[40px]">
                    <label for="editProfilePhoto"
                        class="px-4 h-full flex items-center bg-slate-50 text-[13px] text-slate-600 font-medium border-r border-slate-300 cursor-pointer hover:bg-slate-100 transition whitespace-nowrap">
                        Pilih File
                    </label>
                    <span id="fileLabelEditProfile" class="px-4 text-[13px] text-slate-400 truncate">Tidak ada file
                        dipilih</span>
                    <input type="file" name="profile_photo" id="editProfilePhoto" accept="image/*" class="hidden"
                        onchange="document.getElementById('fileLabelEditProfile').textContent = this.files[0]?.name || 'Tidak ada file dipilih'">
                </div>
            </div>

            {{-- Nama & Email --}}
            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="text-[13px] font-medium text-slate-600 block mb-2">Nama Lengkap <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="name" id="editName"
                        class="w-full border border-slate-300 rounded-[10px] px-4 py-2 text-[13px]"
                        placeholder="Nama lengkap" required>
                </div>
                <div>
                    <label class="text-[13px] font-medium text-slate-600 block mb-2">Email <span
                            class="text-red-500">*</span></label>
                    <input type="email" name="email" id="editEmail"
                        class="w-full border border-slate-300 rounded-[10px] px-4 py-2 text-[13px]" placeholder="Email"
                        required>
                </div>
            </div>

            {{-- Password --}}
            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="text-[13px] font-medium text-slate-600 block mb-2">
                        Password Baru <span class="text-slate-400 font-normal">(opsional)</span>
                    </label>
                    <input type="password" name="password"
                        class="w-full border border-slate-300 rounded-[10px] px-4 py-2 text-[13px]"
                        placeholder="Kosongkan jika tidak diubah">
                </div>
                <div>
                    <label class="text-[13px] font-medium text-slate-600 block mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full border border-slate-300 rounded-[10px] px-4 py-2 text-[13px]"
                        placeholder="Kosongkan jika tidak diubah">
                </div>
            </div>

            {{-- No Telp --}}
            <div class="mb-7">
                <label class="text-[13px] font-medium text-slate-600 block mb-2">No. Telp</label>
                <input type="text" name="phone" id="editPhone"
                    class="w-full border border-slate-300 rounded-[10px] px-4 py-2 text-[13px]" placeholder="No. telepon">
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="toggleModal('modalEditProfile')"
                    class="px-5 py-2 rounded-[10px] border border-slate-300 text-[13px] text-slate-500 hover:bg-slate-50 transition">
                    Batalkan
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-[#FFAE00] hover:bg-[#e69c00] text-white text-[13px] rounded-[10px] transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </x-ui.modal-form>

    {{-- ===================== SweetAlert & Scripts ===================== --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (t) => {
                    t.addEventListener('mouseenter', Swal.stopTimer);
                    t.addEventListener('mouseleave', Swal.resumeTimer);
                }
            }).fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                confirmButtonColor: '#0F4C5C'
            });
        @endif

        function toggleModal(id) {
            document.getElementById(id).classList.toggle('hidden');
        }

        // Buka modal Edit Profil Pengajar
        function openEditModal(name, email, phone, photoUrl) {
            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
            document.getElementById('editPhone').value = phone;
            document.getElementById('fileLabelEditProfile').textContent = 'Tidak ada file dipilih';
            document.getElementById('editProfilePhoto').value = '';

            const wrapper = document.getElementById('editPhotoPreviewWrapper');
            const img = document.getElementById('editPhotoPreview');
            if (photoUrl) {
                img.src = photoUrl;
                wrapper.classList.remove('hidden');
            } else {
                wrapper.classList.add('hidden');
            }

            toggleModal('modalEditProfile');
        }
    </script>
@endsection