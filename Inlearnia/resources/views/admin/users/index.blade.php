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

        <x-ui.button-create href="{{ route('admin.users.create') }}" label="Tambah User" />
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

                    <svg class="w-4 h-4 absolute left-3 top-3 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 4h18M6 10h12M10 16h4"/>
                    </svg>
                </div>
            </div>
        </form>

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
                                        @if($user->profile_photo)
                                            <img src="{{ asset('storage/' . $user->profile_photo) }}" class="w-10 h-10 rounded-full object-cover border border-slate-200">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=044153&color=ffffff" 
                                class="w-full h-full rounded-full object-cover">
                                            </div>
                                        @endif
                                        
                                        <div>
                                            <div class="font-medium text-[#092C4C]">{{ $user->name }}</div>
                                            <div class="text-xs text-slate-400">ID: {{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- EMAIL --}}
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $user->email }}
                                </td>

                                {{-- ROLE (Dengan Translasi & Warna) --}}
                                <td class="px-6 py-4">
                                    @php
                                        $roleColor = match($user->role) {
                                            'admin'   => 'danger',
                                            'teacher' => 'primary',
                                            'student' => 'success',
                                            default   => 'gray'
                                        };

                                        // Translasi Bahasa Indonesia
                                        $roleLabel = match($user->role) {
                                            'admin'   => 'Admin',
                                            'teacher' => 'Pengajar',
                                            'student' => 'Siswa',
                                            default   => ucfirst($user->role)
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
                                            $isMeOwner     = $currentUserId === $schoolOwnerId;
                                            $isTargetAdmin = $user->role === 'admin';
                                            
                                            // Logic Boleh Manage (Edit/Delete)
                                            // 1. Target BUKAN Admin (Guru/Siswa) -> Boleh
                                            // 2. Target Admin TAPI saya Owner -> Boleh
                                            $canManage = !$isTargetAdmin || $isTargetAdmin && $isMeOwner;
                                        @endphp

                                        {{-- 1. Tombol Detail (Semua Bisa Lihat) --}}
                                        <x-ui.action.detail href="{{ route('admin.users.show', $user->id) }}" />

                                        {{-- 2. Tombol Edit & Delete (Sesuai Logic) --}}
                                        @if($canManage)
                                            <x-ui.action.edit href="{{ route('admin.users.edit', $user->id) }}" />
                                            <x-ui.action.delete action="{{ route('admin.users.destroy', $user->id) }}" />
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-slate-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-8 h-8 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
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
                    Menampilkan {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data
                </div>
                <div>
                    {{ $users->onEachSide(1)->links() }}
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
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
            Toast.fire({ icon: 'success', title: '{{ session('success') }}' });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Akses Ditolak',
                text: '{{ session('error') }}',
                confirmButtonColor: '#0F4C5C'
            });
        @endif
    </script>
@endsection