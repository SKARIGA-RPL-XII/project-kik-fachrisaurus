@extends('layouts.app')

@section('content')
    <x-sidebar />

    <div class="flex justify-between items-center mb-10">
        <div class="flex-1">
            <x-breadcrumb :items="[
                ['label' => 'List Pengguna', 'url' => route('admin.users.index')],
                ['label' => 'Detail Pengguna', 'url' => null]
            ]" />
        </div>
        <x-calendar />
    </div>

    {{-- MAIN CONTENT --}}
    <div class="flex justify-between items-center mt-[40px] mb-[25px]">
        <x-ui.title text="Detail Pengguna" />
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        
        {{-- KOLOM KIRI: KARTU PROFIL --}}
        <div class="w-full lg:w-1/3">
            <div class="bg-white rounded-[20px] shadow-sm border border-slate-200 p-8 flex flex-col items-center text-center h-fit">
                
                {{-- Avatar Wrapper --}}
                <div class="relative mb-6">
                    {{-- PERBAIKAN: Border diperkecil jadi border-4 (sebelumnya 6px) --}}
                    <div class="w-48 h-48 rounded-full border-4 border-[#0F4C5C] overflow-hidden p-1">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                class="w-full h-full rounded-full object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=044153&color=ffffff&size=256" 
                                class="w-full h-full rounded-full object-cover">
                        @endif
                    </div>
                    
                    {{-- Edit Icon Floating --}}
                    <button class="absolute bottom-2 right-2 bg-[#00A99D] hover:bg-[#008f84] text-white w-10 h-10 rounded-full flex items-center justify-center shadow-lg transition border-4 border-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </button>
                </div>

                {{-- Name & Role --}}
                <h2 class="text-[#092C4C] text-[20px] font-bold uppercase tracking-wide mb-2">
                    {{ $user->name }}
                </h2>
                
                @php
                    // PERBAIKAN: Warna Role disesuaikan dengan List Pengguna
                    $roleConfig = match($user->role) {
                        'admin'   => ['label' => 'Admin',    'bg' => 'bg-red-100',    'text' => 'text-red-600'],
                        'teacher' => ['label' => 'Pengajar', 'bg' => 'bg-indigo-100', 'text' => 'text-indigo-600'], 
                        'student' => ['label' => 'Siswa',    'bg' => 'bg-green-100',  'text' => 'text-green-600'],
                        default   => ['label' => ucfirst($user->role), 'bg' => 'bg-gray-100', 'text' => 'text-gray-600']
                    };
                @endphp
                
                <span class="{{ $roleConfig['bg'] }} {{ $roleConfig['text'] }} px-6 py-1.5 rounded-full text-sm font-bold">
                    {{ $roleConfig['label'] }}
                </span>
            </div>
        </div>

        {{-- KOLOM KANAN: DATA DIRI, PASSWORD, KELAS --}}
        <div class="w-full lg:w-2/3 flex flex-col gap-6">

            {{-- 1. Kartu Data Diri --}}
            <div class="bg-white rounded-[20px] shadow-sm border border-slate-200 p-8">
                <div class="flex items-center gap-2 mb-6 border-b border-slate-100 pb-4">
                    <svg class="w-6 h-6 text-[#00A99D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="text-[#092C4C] text-[18px] font-semibold">Data Diri Pengguna</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-4 mb-8">
                    {{-- Nama --}}
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Nama Lengkap</label>
                        <div class="text-[#092C4C] font-medium text-[16px]">{{ $user->name }}</div>
                    </div>

                    {{-- Tergabung Dalam --}}
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Tergabung Dalam</label>
                        <div class="text-[#092C4C] font-medium text-[16px]">
                            {{ $enrolledClassCount ?? 0 }} Kelas
                        </div>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Email</label>
                        <div class="text-[#092C4C] font-medium text-[16px]">{{ $user->email }}</div>
                    </div>

                    {{-- Tanggal Bergabung --}}
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Tanggal User Bergabung</label>
                        <div class="text-[#092C4C] font-medium text-[16px]">
                            {{ $user->created_at->translatedFormat('d F Y') }}
                        </div>
                    </div>

                    {{-- No Telp --}}
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">No. Telp</label>
                        <div class="text-[#092C4C] font-medium text-[16px]">{{ $user->phone ?? '-' }}</div>
                    </div>
                    
                    {{-- Role (Text Version) --}}
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Role</label>
                        <div class="text-[#092C4C] font-medium text-[16px]">{{ $roleConfig['label'] }}</div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-[#00A99D] hover:bg-[#008f84] text-white px-6 py-2.5 rounded-[10px] text-sm font-medium transition shadow-md shadow-teal-500/20">
                        Ubah Data Diri
                    </a>
                </div>
            </div>

            {{-- 2. Kartu Kata Sandi --}}
            <div class="bg-white rounded-[20px] shadow-sm border border-slate-200 p-8 flex justify-between items-center">
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Kata Sandi</label>
                    <div class="text-[#092C4C] font-bold text-[24px] tracking-widest mt-1">**********</div>
                </div>
                <div>
                    <button onclick="openPasswordModal()" class="bg-[#00A99D] hover:bg-[#008f84] text-white px-6 py-2.5 rounded-[10px] text-sm font-medium transition shadow-md shadow-teal-500/20">
                        Ubah Kata Sandi
                    </button>
                </div>
            </div>

            {{-- 3. Kartu Tergabung Dalam Kelas (Sudah Terintegrasi Data) --}}
            <div class="bg-white rounded-[20px] shadow-sm border border-slate-200 p-8">
                <div class="flex items-center gap-2 mb-6 border-b border-slate-100 pb-4">
                    <svg class="w-6 h-6 text-[#00A99D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <h3 class="text-[#092C4C] text-[18px] font-semibold">Tergabung Dalam Kelas</h3>
                </div>

                <div class="flex flex-wrap gap-8">
                    @forelse($classes as $class)
                        <div class="flex flex-col items-center text-center w-[120px]">
                            <div class="w-16 h-16 rounded-full overflow-hidden mb-3 border border-slate-100 shadow-sm">
                                @if(!empty($class->image_url))
                                    <img src="{{ $class->image_url }}" alt="{{ $class->name }}" class="w-full h-full object-cover">
                                @else
                                    {{-- Placeholder warna dinamis berdasarkan ID agar tidak bosan --}}
                                    @php $colors = ['bg-orange-100', 'bg-blue-100', 'bg-purple-100', 'bg-green-100']; @endphp
                                    <div class="w-full h-full {{ $colors[$class->id % 4] }} flex items-center justify-center text-slate-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <h4 class="text-[#00A99D] font-bold text-xs leading-tight mb-1 line-clamp-2 h-[30px] flex items-center justify-center">
                                {{ $class->name }}
                            </h4>
                        </div>
                    @empty
                        <div class="w-full flex flex-col items-center justify-center py-6 text-slate-400 gap-2 border-2 border-dashed border-slate-100 rounded-xl">
                            <svg class="w-8 h-8 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            <span class="text-sm">Belum tergabung di kelas manapun</span>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
@endsection