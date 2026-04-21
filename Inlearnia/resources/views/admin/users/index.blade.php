@extends('layouts.app')

@section('content')
    <x-sidebar />

    <div class="flex justify-between items-center mb-10">
        <div class="flex-1">
            <x-breadcrumb :items="[['label' => 'List Pengguna', 'url' => null]]" />
        </div>
        <x-calendar />
    </div>

    <div class="flex justify-between items-center mt-[40px] mb-[25px]">
        <x-ui.title text="List Pengguna">
            <span class="text-[20px] font-normal text-[#092C4C] opacity-50">
                {{ $users->total() }} Data
            </span>
        </x-ui.title>

        <x-ui.button-create href="#" onclick="event.preventDefault(); toggleModal('modalCreateUser')"
            label="Tambah Pengguna" />
    </div>

    <div class="bg-white rounded-[20px] shadow-sm border border-slate-200 p-6">

        <form id="filterForm" method="GET" class="mb-6">
            <label class="text-[14px] font-medium text-[#092C4C] block mb-2">Cari & Filter</label>

            <div class="flex flex-wrap items-center gap-4">
                <div>
                    <x-ui.search-bar target="userResultContainer" formId="filterForm" />
                </div>

                <div class="relative">
                    <select name="role"
                        class="appearance-none bg-white pl-9 pr-8 py-2 rounded-[10px] border border-slate-300 focus:outline-none focus:border-[#0F4C5C] text-[14px] transition h-[40px] cursor-pointer">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>Pengajar</option>
                        <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Siswa</option>
                    </select>

                    <svg class="w-4 h-4 absolute left-3 top-3 text-gray-400 pointer-events-none" fill="none"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 4h18M6 10h12M10 16h4" />
                    </svg>
                </div>
            </div>
        </form>

        {{-- Tabel dalam container AJAX --}}
        <div id="userResultContainer">
            <div class="overflow-hidden rounded-[15px] border border-slate-200">
                <table class="w-full text-left">
                    <thead class="bg-[#0F4C5C] text-white text-[14px]">
                        <tr>
                            <x-ui.table.th-sort label="Nama Pengguna" field="name" class="pl-14" />
                            <x-ui.table.th-sort label="Email" field="email" />
                            <x-ui.table.th-sort label="Role" field="role" />
                            <th class="px-6 py-4">No. Telp</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="text-[14px] text-[#092C4C]">
                        @forelse($users as $user)
                            <tr class="border-t border-slate-200 hover:bg-slate-50 transition">

                                {{-- FOTO & NAMA --}}
                                <td class="pl-14 pr-6 py-4">
                                    <div class="flex items-center gap-4">
                                        @if ($user->profile_photo)
                                            <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                                class="w-10 h-10 rounded-full object-cover border border-slate-200">
                                        @else
                                            <div
                                                class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=044153&color=ffffff"
                                                    class="w-full h-full rounded-full object-cover">
                                            </div>
                                        @endif

                                        <div>
                                            <div class="font-medium text-[#092C4C]">{{ $user->name }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- EMAIL --}}
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $user->email }}
                                </td>

                                {{-- ROLE --}}
                                <td class="px-6 py-4">
                                    @php
                                        $roleColor = match ($user->role) {
                                            'admin' => 'danger',
                                            'teacher' => 'primary',
                                            'student' => 'success',
                                            default => 'gray',
                                        };
                                        $roleLabel = match ($user->role) {
                                            'admin' => 'Admin',
                                            'teacher' => 'Pengajar',
                                            'student' => 'Siswa',
                                            default => ucfirst($user->role),
                                        };
                                    @endphp
                                    <x-ui.badge :label="$roleLabel" :color="$roleColor" />
                                </td>

                                {{-- NO TELP --}}
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $user->phone ?? '-' }}
                                </td>

                                {{-- AKSI --}}
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">

                                        @php
                                            $currentUserId = auth()->id();
                                            $isMeOwner = $currentUserId === $schoolOwnerId;
                                            $isTargetAdmin = $user->role === 'admin';
                                            $canManage = !$isTargetAdmin || ($isTargetAdmin && $isMeOwner);
                                        @endphp

                                        <x-ui.action.detail href="{{ route('admin.users.show', $user->id) }}" />

                                        @if ($canManage)
                                            <x-ui.action.edit href="#"
                                                onclick="event.preventDefault(); openEditModal(
                                                    {{ $user->id }},
                                                    '{{ addslashes($user->name) }}',
                                                    '{{ addslashes($user->email) }}',
                                                    '{{ $user->phone ?? '' }}',
                                                    '{{ $user->role }}',
                                                    '{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : '' }}'
                                                )" />
                                            <x-ui.action.delete action="{{ route('admin.users.destroy', $user->id) }}" />
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-slate-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <span>Data pengguna tidak ditemukan</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center mt-6 text-[13px] text-slate-500">
                <div>
                    Menampilkan {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} dari
                    {{ $users->total() }} data
                </div>
                <div>
                    {{ $users->onEachSide(1)->links() }}
                </div>
            </div>
        </div>

        {{-- ===================== Modal Create User ===================== --}}
        <x-ui.modal-form id="modalCreateUser" title="Tambah Pengguna"
            description="Menambahkan pengguna dengan mengisi form di bawah ini" iconBg="#0F9E8A"
            buttonColor="bg-teal-500 hover:bg-teal-600">

            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-3.33 0-10 1.67-10 5v3h20v-3c0-3.33-6.67-5-10-5z" />
                </svg>
            </x-slot>

            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- FOTO --}}
                <div class="mb-5">
                    <label class="text-[13px] font-medium text-slate-600 block mb-2">Gambar Profile</label>
                    <div class="flex items-center border border-slate-300 rounded-[10px] overflow-hidden h-[40px]">
                        <label for="profilePhoto"
                            class="px-4 h-full flex items-center bg-slate-50 text-[13px] text-slate-600 font-medium border-r border-slate-300 cursor-pointer hover:bg-slate-100 transition whitespace-nowrap">
                            Choose File
                        </label>
                        <span id="fileLabelProfile" class="px-4 text-[13px] text-slate-400 truncate">No file
                            chosen</span>
                        <input type="file" name="profile_photo" id="profilePhoto" accept="image/*" class="hidden"
                            onchange="document.getElementById('fileLabelProfile').textContent = this.files[0]?.name || 'No file chosen'">
                    </div>
                </div>

                {{-- NAMA & EMAIL --}}
                <div class="grid grid-cols-2 gap-6 mb-5">
                    <div>
                        <label class="text-[13px] font-medium text-slate-600 block mb-2">Nama Lengkap<span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full border rounded-[10px] px-4 py-2 text-[13px] @error('name') border-red-500 @else border-slate-300 @enderror"
                            placeholder="Masukkan nama lengkap">
                        @error('name')
                            <p class="text-red-500 text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="text-[13px] font-medium text-slate-600 block mb-2">Email<span
                                class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full border rounded-[10px] px-4 py-2 text-[13px] @error('email') border-red-500 @else border-slate-300 @enderror"
                            placeholder="Masukkan email">
                        @error('email')
                            <p class="text-red-500 text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- PASSWORD --}}
                <div class="grid grid-cols-2 gap-6 mb-5">
                    <div>
                        <label class="text-[13px] font-medium text-slate-600 block mb-2">Kata Sandi<span
                                class="text-red-500">*</span></label>
                        <input type="password" name="password"
                            class="w-full border rounded-[10px] px-4 py-2 text-[13px] @error('password') border-red-500 @else border-slate-300 @enderror"
                            placeholder="Masukkan kata sandi">
                        @error('password')
                            <p class="text-red-500 text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="text-[13px] font-medium text-slate-600 block mb-2">Konfirmasi Kata Sandi<span
                                class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation"
                            class="w-full border rounded-[10px] px-4 py-2 text-[13px] @error('password') border-red-500 @else border-slate-300 @enderror"
                            placeholder="Konfirmasi kata sandi">
                    </div>
                </div>

                {{-- TELP & ROLE --}}
                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="text-[13px] font-medium text-slate-600 block mb-2">No Telp<span
                                class="text-red-500">*</span></label>
                        <input type="text" required name="phone" value="{{ old('phone') }}"
                            oninput="this.value = this.value.replace(/[^0-9()\-\s]/g, '')" pattern="[0-9()\-\s]+"
                            class="w-full border rounded-[10px] px-4 py-2 text-[13px] @error('phone') border-red-500 @else border-slate-300 @enderror"
                            placeholder="Masukkan no telp">
                        @error('phone')
                            <p class="text-red-500 text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="text-[13px] font-medium text-slate-600 block mb-2">Role<span
                                class="text-red-500">*</span></label>
                        <select name="role" required
                            class="w-full border rounded-[10px] px-4 py-2 text-[13px] bg-white @error('role') border-red-500 @else border-slate-300 @enderror">
                            <option value="">Pilih role</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Pengajar</option>
                            <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Siswa</option>
                        </select>
                        @error('role')
                            <p class="text-red-500 text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="toggleModal('modalCreateUser')"
                        class="px-5 py-2 rounded-[10px] border border-slate-300 text-[13px] text-slate-500 hover:bg-slate-50 transition">
                        Batalkan
                    </button>
                    <button type="submit"
                        class="px-5 py-2 bg-teal-500 hover:bg-teal-600 text-white text-[13px] rounded-[10px]">
                        Selesai
                    </button>
                </div>
            </form>
        </x-ui.modal-form>

        {{-- ===================== Modal Edit User ===================== --}}
        <x-ui.modal-form id="modalEditUser" title="Edit Pengguna"
            description="Perbarui data pengguna dengan mengisi form di bawah ini" iconBg="#FFAE00"
            buttonColor="bg-teal-500 hover:bg-teal-600">

            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-3.33 0-10 1.67-10 5v3h20v-3c0-3.33-6.67-5-10-5z" />
                </svg>
            </x-slot>

            <form id="editUserForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- PREVIEW FOTO & UPLOAD --}}
                <div class="mb-5">
                    <label class="text-[13px] font-medium text-slate-600 block mb-2">Gambar Profile</label>

                    <div id="editPhotoPreviewWrapper" class="mb-3 hidden">
                        <p class="text-[12px] text-slate-400 mb-1">Foto saat ini:</p>
                        <img id="editPhotoPreview" src="" alt="Foto profil"
                            class="w-14 h-14 rounded-full object-cover border border-slate-200">
                    </div>

                    <div class="flex items-center border border-slate-300 rounded-[10px] overflow-hidden h-[40px]">
                        <label for="editProfilePhoto"
                            class="px-4 h-full flex items-center bg-slate-50 text-[13px] text-slate-600 font-medium border-r border-slate-300 cursor-pointer hover:bg-slate-100 transition whitespace-nowrap">
                            Choose File
                        </label>
                        <span id="fileLabelEditProfile" class="px-4 text-[13px] text-slate-400 truncate">No file
                            chosen</span>
                        <input type="file" name="profile_photo" id="editProfilePhoto" accept="image/*"
                            class="hidden"
                            onchange="document.getElementById('fileLabelEditProfile').textContent = this.files[0]?.name || 'No file chosen'">
                    </div>
                </div>

                {{-- NAMA & EMAIL --}}
                <div class="grid grid-cols-2 gap-6 mb-5">
                    <div>
                        <label class="text-[13px] font-medium text-slate-600 block mb-2">Nama Lengkap<span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" id="editName"
                            class="w-full border border-slate-300 rounded-[10px] px-4 py-2 text-[13px]"
                            placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div>
                        <label class="text-[13px] font-medium text-slate-600 block mb-2">Email<span
                                class="text-red-500">*</span></label>
                        <input type="email" name="email" id="editEmail"
                            class="w-full border border-slate-300 rounded-[10px] px-4 py-2 text-[13px]"
                            placeholder="Masukkan email" required>
                    </div>
                </div>

                {{-- PASSWORD (opsional) --}}
                <div class="grid grid-cols-2 gap-6 mb-5">
                    <div>
                        <label class="text-[13px] font-medium text-slate-600 block mb-2">
                            Password Baru
                            <span class="text-slate-400 font-normal">(opsional)</span>
                        </label>
                        <input type="password" name="password"
                            class="w-full border border-slate-300 rounded-[10px] px-4 py-2 text-[13px]"
                            placeholder="Kosongkan jika tidak diubah">
                    </div>
                    <div>
                        <label class="text-[13px] font-medium text-slate-600 block mb-2">Konfirmasi Password
                            Baru</label>
                        <input type="password" name="password_confirmation"
                            class="w-full border border-slate-300 rounded-[10px] px-4 py-2 text-[13px]"
                            placeholder="Kosongkan jika tidak diubah">
                    </div>
                </div>

                {{-- TELP & ROLE --}}
                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="text-[13px] font-medium text-slate-600 block mb-2">No Telp</label>
                        <input type="text" name="phone" id="editPhone"
                            oninput="this.value = this.value.replace(/[^0-9()\-\s]/g, '')" pattern="[0-9()\-\s]+"
                            class="w-full border border-slate-300 rounded-[10px] px-4 py-2 text-[13px]"
                            placeholder="Masukkan no telp">
                    </div>
                    <div>
                        <label class="text-[13px] font-medium text-slate-600 block mb-2">Role</label>
                        <select name="role" id="editRole"
                            class="w-full border border-slate-300 rounded-[10px] px-4 py-2 text-[13px] bg-white">
                            <option value="admin">Admin</option>
                            <option value="teacher">Pengajar</option>
                            <option value="student">Siswa</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="toggleModal('modalEditUser')"
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

        @if ($errors->any())
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    toggleModal('modalCreateUser');
                });
            </script>
        @endif

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Akses Ditolak',
                text: '{{ session('error') }}',
                confirmButtonColor: '#0F4C5C'
            });
        @endif

        function toggleModal(id) {
            const el = document.getElementById(id);
            el.classList.toggle('hidden');
        }

        function openEditModal(id, name, email, phone, role, photoUrl) {
            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
            document.getElementById('editPhone').value = phone;
            document.getElementById('editRole').value = role;

            document.getElementById('editUserForm').action = `/admin/users/${id}`;

            document.getElementById('fileLabelEditProfile').textContent = 'No file chosen';
            document.getElementById('editProfilePhoto').value = '';

            const previewWrapper = document.getElementById('editPhotoPreviewWrapper');
            const previewImg = document.getElementById('editPhotoPreview');
            if (photoUrl) {
                previewImg.src = photoUrl;
                previewWrapper.classList.remove('hidden');
            } else {
                previewWrapper.classList.add('hidden');
            }

            toggleModal('modalEditUser');
        }
    </script>
@endsection
