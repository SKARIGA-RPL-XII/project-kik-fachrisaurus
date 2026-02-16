@extends('layouts.app')

@section('content')
    <x-sidebar />

    {{-- Breadcrumb & Header Simpel --}}
    <div class="flex justify-between items-center mb-8">
        <div class="flex-1">
            <x-breadcrumb :items="[
                ['label' => 'Kelas Saya', 'url' => route('teacher.kelas.index')],
                ['label' => $kelas->name, 'url' => null]
            ]" />
        </div>
        <x-calendar />
    </div>

    {{-- ========================================= --}}
    {{-- BAGIAN ATAS (HEADER KELAS - FIXED OVERLAY & COLORFUL STATS) --}}
    {{-- ========================================= --}}
    
    <div class="w-full bg-white rounded-[20px] shadow-lg shadow-slate-200/50 border border-slate-200 overflow-hidden mb-8 group relative z-0">
        
        {{-- A. Bagian Banner --}}
        <div class="relative w-full h-[220px] md:h-[260px] overflow-hidden">
            
            {{-- 1. Background Image --}}
            <div class="absolute inset-0 bg-slate-800 z-0">
                @if($kelas->logo)
                    <img src="{{ asset('storage/' . $kelas->logo) }}" 
                         class="w-full h-full object-cover transition-transform duration-700 ease-out 
                                group-hover:scale-110 group-hover:brightness-110">
                @else
                    <img src="https://img.freepik.com/free-vector/gradient-abstract-wireframe-background_23-2149009903.jpg" 
                         class="w-full h-full object-cover transition-transform duration-700 ease-out 
                                group-hover:scale-110 group-hover:brightness-110">
                @endif
            </div>

            {{-- 2. Gradient Overlay (TETAP ADA) --}}
            <div class="absolute inset-0 bg-gradient-to-t from-[#092C4C] via-[#092C4C]/60 to-transparent z-10 transition-opacity duration-500 ease-in-out"></div>

            {{-- 3. Text Content --}}
            <div class="absolute bottom-0 left-0 w-full p-6 md:p-8 z-20 pointer-events-none flex flex-col justify-end h-full">
                
                <div class="flex flex-col gap-1 transition-transform duration-500 group-hover:-translate-y-2">
                    
                    {{-- Badge Kurikulum --}}
                    <div class="mb-2">
                        <span class="bg-[#00A79D] text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-md">
                            {{ $kelas->subject->curriculum ?? 'Kurikulum Merdeka' }}
                        </span>
                    </div>

                    {{-- Judul Kelas --}}
                    <h1 class="text-3xl md:text-4xl font-bold text-white tracking-tight leading-none drop-shadow-md transition-all">
                        {{ $kelas->name }}
                    </h1>

                    {{-- Nama Mapel --}}
                    <p class="text-slate-100 text-sm md:text-base font-medium mt-1 flex items-center gap-2 drop-shadow-sm group-hover:text-white">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                        {{ $kelas->subject->name ?? 'Mata Pelajaran Umum' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- B. Bagian Info Bar (Putih - Footer) --}}
        <div class="relative z-30 bg-white px-12  py-5 flex flex-col md:flex-row justify-between items-center gap-6 md:gap-0 border-t border-slate-100">
            
            {{-- Kiri: Info Pengajar --}}
            <div class="flex items-center gap-4 w-full md:w-auto border-b md:border-b-0 border-slate-100 pb-4 md:pb-0">
                <div class="w-12 h-12 rounded-full bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0">
                    @if(isset($kelas->teacher->avatar)) 
                         <img src="{{ $kelas->teacher->avatar }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-[#092C4C] text-white font-bold text-lg">
                            {{ substr($kelas->teacher->name ?? auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Pengajar Kelas</p>
                    <p class="text-[#092C4C] font-bold text-base leading-none">
                        {{ $kelas->teacher->name ?? auth()->user()->name }}
                    </p>
                </div>
            </div>

            {{-- Kanan: Statistik (WARNA DIKEMBALIKAN) --}}
            <div class="flex items-center gap-8 md:gap-12 w-full md:w-auto justify-around md:justify-end">
                
                {{-- Siswa (Orange) --}}
                <div class="text-center group/stat cursor-default">
                    <span class="block text-xl font-bold text-orange-500 transition-colors">
                        {{ $kelas->students_count }}
                    </span>
                    <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wide">Siswa</span>
                </div>
                
                <div class="w-px h-8 bg-slate-200 hidden md:block"></div>

                {{-- Pertemuan (Teal) --}}
                <div class="text-center group/stat cursor-default">
                    <span class="block text-xl font-bold text-[#00A79D] transition-colors">
                        {{ $kelas->meetings_count }}
                    </span>
                    <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wide">Pertemuan</span>
                </div>

                <div class="w-px h-8 bg-slate-200 hidden md:block"></div>

                {{-- Info (Purple) --}}
                <div class="text-center group/stat cursor-default">
                    <span class="block text-xl font-bold text-purple-600 transition-colors">
                        {{ $kelas->announcements_count }}
                    </span>
                    <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wide">Info</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================= --}}
    {{-- SWITCH TAB NAVIGATION --}}
    {{-- ========================================= --}}
    <div class="mb-6 border-b border-gray-200">
        <nav class="flex space-x-8" aria-label="Tabs">
            <button onclick="switchTab('beranda')" id="tab-btn-beranda" 
                class="tab-btn active-tab py-4 px-1 border-b-2 font-medium text-sm">
                Beranda
            </button>
            <button onclick="switchTab('pertemuan')" id="tab-btn-pertemuan" 
                class="tab-btn py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Daftar Pertemuan
            </button>
            <button onclick="switchTab('anggota')" id="tab-btn-anggota" 
                class="tab-btn py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Anggota Kelas
            </button>
        </nav>
    </div>

    {{-- ========================================= --}}
    {{-- KONTEN TAB 1: BERANDA (Feed) --}}
    {{-- ========================================= --}}
    <div id="tab-content-beranda" class="tab-content block animate-fade-in">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Kolom Kiri: Feed Utama --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Card Buat Pengumuman (Trigger Modal) --}}
                <div class="bg-white rounded-[15px] p-4 shadow-sm border border-slate-100 flex gap-4 items-center cursor-pointer hover:shadow-md transition-all"
                     onclick="toggleModal('modalPengumuman')">
                    <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden flex-shrink-0">
                        <img src="{{ auth()->user()->avatar ?? asset('assets/img/default-avatar.png') }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 bg-gray-50 rounded-full px-4 py-3 text-gray-500 text-sm">
                        Umumkan sesuatu ke kelas Anda...
                    </div>
                </div>

                {{-- Loop Feeds (Gabungan Pengumuman & Pertemuan) --}}
                @forelse($feeds as $item)
                    @if($item->type === 'announcement')
                        {{-- CARD PENGUMUMAN --}}
                        <div class="bg-white rounded-[15px] p-6 shadow-sm border border-slate-100">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex gap-3 items-center">
                                    <div class="w-10 h-10 rounded-full bg-[#E6F6F5] flex items-center justify-center text-[#00A79D]">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-[#092C4C] text-sm md:text-base">Pengumuman</h3>
                                        <span class="text-xs text-slate-400">{{ $item->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                {{-- Dropdown Edit/Delete Opsional disini --}}
                            </div>
                            <div class="text-slate-600 text-sm leading-relaxed mb-4">
                                {!! nl2br(e($item->content)) !!}
                            </div>
                            @if($item->link)
                                <a href="{{ $item->link }}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 text-sm hover:underline bg-blue-50 px-3 py-2 rounded-lg">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"></path></svg>
                                    Lampiran Tautan
                                </a>
                            @endif
                        </div>
                    @else
                        {{-- CARD PERTEMUAN (Preview di Beranda) --}}
                        <div class="bg-white rounded-[15px] p-6 shadow-sm border border-l-4 border-slate-100 border-l-[#00A79D]">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="text-xs font-bold text-[#00A79D] uppercase tracking-wider mb-1 block">
                                        {{ $item->type_meeting == 'tugas' ? 'Tugas Baru' : 'Materi Baru' }}
                                    </span>
                                    <a href="#" class="font-bold text-[#092C4C] text-lg hover:text-[#00A79D] transition-colors">
                                        {{ $item->title }}
                                    </a>
                                    <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $item->description }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs text-slate-400 block">{{ $item->created_at->format('d M') }}</span>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t border-slate-100 flex justify-between items-center">
                                @if($item->deadline)
                                    <span class="text-xs text-red-500 font-medium flex items-center gap-1">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                        Tenggat: {{ \Carbon\Carbon::parse($item->deadline)->format('d M H:i') }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400">Tidak ada tenggat waktu</span>
                                @endif
                                <a href="#" class="text-sm font-medium text-[#00A79D] hover:underline">Lihat Detail &rarr;</a>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="text-center py-10">
                        <img src="{{ asset('assets/img/empty-state.svg') }}" class="w-32 mx-auto mb-4 opacity-50">
                        <p class="text-slate-400">Belum ada aktivitas di kelas ini.</p>
                    </div>
                @endforelse
            </div>

            {{-- Kolom Kanan: Widget (Opsional) --}}
            <div class="hidden lg:block space-y-6">
                <div class="bg-white rounded-[15px] p-5 shadow-sm border border-slate-100">
                    <h3 class="font-bold text-[#092C4C] mb-4">Mendatang</h3>
                    <p class="text-sm text-slate-400">Tidak ada tugas yang perlu segera dikumpulkan.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================= --}}
    {{-- KONTEN TAB 2: DAFTAR PERTEMUAN --}}
    {{-- ========================================= --}}
    <div id="tab-content-pertemuan" class="tab-content hidden animate-fade-in">
        
        {{-- Header Tab Pertemuan --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold text-[#092C4C]">Daftar Materi & Tugas</h2>
            
            {{-- Dropdown Buat Pertemuan --}}
            <div class="relative">
                <button onclick="toggleDropdown('dropdownBuatPertemuan')" 
                    class="bg-[#00A79D] hover:bg-[#008f87] text-white px-5 py-2.5 rounded-[10px] text-sm font-medium flex items-center gap-2 transition-all shadow-md shadow-teal-100">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Buat Pertemuan
                </button>
                
                {{-- Isi Dropdown --}}
                <div id="dropdownBuatPertemuan" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-slate-100 z-50 overflow-hidden">
                    <a href="{{ route('teacher.meetings.create', ['kelas_id' => $kelas->id, 'type' => 'tugas']) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#00A79D] flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Buat Tugas
                    </a>
                    <a href="{{ route('teacher.meetings.create', ['kelas_id' => $kelas->id, 'type' => 'materi']) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#00A79D] flex items-center gap-2 border-t border-slate-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        Buat Materi
                    </a>
                </div>
            </div>
        </div>

        {{-- List Pertemuan --}}
        <div class="space-y-4">
            @foreach($kelas->meetings as $meeting)
                <div class="bg-white rounded-[12px] p-5 shadow-sm border border-slate-100 hover:border-[#00A79D]/30 transition-colors group">
                    <div class="flex justify-between items-start">
                        <div class="flex gap-4">
                            {{-- Icon Type --}}
                            <div class="w-12 h-12 rounded-[10px] flex items-center justify-center flex-shrink-0 {{ $meeting->type == 'tugas' ? 'bg-orange-50 text-orange-500' : 'bg-blue-50 text-blue-500' }}">
                                @if($meeting->type == 'tugas')
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                                @else
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                @endif
                            </div>

                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded border uppercase tracking-wider
                                        {{ $meeting->type == 'tugas' ? 'bg-orange-50 text-orange-600 border-orange-100' : 'bg-blue-50 text-blue-600 border-blue-100' }}">
                                        {{ ucfirst($meeting->type) }}
                                    </span>
                                    <span class="text-xs text-slate-400">{{ $meeting->created_at->format('d M Y') }}</span>
                                </div>
                                <h3 class="font-bold text-[#092C4C] text-lg">{{ $meeting->title }}</h3>
                                
                                @if($meeting->deadline)
                                    <p class="text-sm text-red-500 mt-1 font-medium">Deadline: {{ \Carbon\Carbon::parse($meeting->deadline)->format('d F Y, H:i') }}</p>
                                @else
                                    <p class="text-sm text-slate-400 mt-1">Tidak ada batasan waktu</p>
                                @endif
                            </div>
                        </div>

                        {{-- Action Menu --}}
                        <div class="relative">
                            <button onclick="toggleDropdown('menu-{{ $meeting->id }}')" class="p-2 text-slate-400 hover:text-[#092C4C] hover:bg-slate-50 rounded-full transition-colors">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                            </button>
                            {{-- Dropdown Isi --}}
                            <div id="menu-{{ $meeting->id }}" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg border border-slate-100 z-10 overflow-hidden">
                                <a href="#" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-[#00A79D]">Edit</a>
                                <form action="#" method="POST" onsubmit="return confirm('Hapus pertemuan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ========================================= --}}
    {{-- KONTEN TAB 3: ANGGOTA KELAS --}}
    {{-- ========================================= --}}
    <div id="tab-content-anggota" class="tab-content hidden animate-fade-in">
        <div class="bg-white rounded-[15px] shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-[#092C4C]">Daftar Siswa</h3>
                <span class="text-sm text-slate-500">{{ $kelas->students_count }} Siswa</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 text-slate-500 font-medium">
                        <tr>
                            <th class="px-6 py-3">No</th>
                            <th class="px-6 py-3">Nama Siswa</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($kelas->students as $index => $student)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-slate-400">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-500">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                    <span class="font-medium text-[#092C4C]">{{ $student->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ $student->email }}</td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-red-500 hover:text-red-700 font-medium text-xs bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded transition-colors">
                                    Keluarkan
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ========================================= --}}
    {{-- MODAL BUAT PENGUMUMAN --}}
    {{-- ========================================= --}}
    <div id="modalPengumuman" class="hidden fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" onclick="toggleModal('modalPengumuman')"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-[15px] bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                
                {{-- Modal Header --}}
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-slate-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-bold text-[#092C4C]" id="modal-title">Buat Pengumuman</h3>
                        <button type="button" onclick="toggleModal('modalPengumuman')" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>

                {{-- Modal Body (Form) --}}
                <form action="{{ route('teacher.announcements.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="class_id" value="{{ $kelas->id }}">
                    
                    <div class="px-4 py-5 sm:p-6 space-y-4">
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Isi Pengumuman</label>
                            <textarea name="content" id="content" rows="4" class="w-full rounded-[8px] border-slate-300 focus:border-[#00A79D] focus:ring focus:ring-[#00A79D]/20 text-sm" placeholder="Apa yang ingin Anda sampaikan ke siswa?" required></textarea>
                        </div>
                        <div>
                            <label for="link" class="block text-sm font-medium text-gray-700 mb-1">Tautan (Opsional)</label>
                            <div class="flex rounded-md shadow-sm">
                                <span class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-gray-500 sm:text-sm">https://</span>
                                <input type="text" name="link" id="link" class="block w-full min-w-0 flex-1 rounded-none rounded-r-md border-gray-300 focus:border-[#00A79D] focus:ring-[#00A79D]/20 sm:text-sm" placeholder="google.com">
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="submit" class="inline-flex w-full justify-center rounded-[8px] bg-[#00A79D] px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#008f87] sm:ml-3 sm:w-auto transition-colors">
                            Posting
                        </button>
                        <button type="button" onclick="toggleModal('modalPengumuman')" class="mt-3 inline-flex w-full justify-center rounded-[8px] bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ========================================= --}}
    {{-- JAVASCRIPT LOGIC --}}
    {{-- ========================================= --}}
    <script>
        // 1. Logic Switch Tab
        function switchTab(tabName) {
            // Sembunyikan semua konten tab
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
                el.classList.remove('block');
            });
            
            // Hapus style active dari semua tombol
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('text-[#00A79D]', 'border-[#00A79D]', 'active-tab');
                btn.classList.add('text-gray-500', 'border-transparent');
            });

            // Tampilkan tab yang dipilih
            const selectedContent = document.getElementById('tab-content-' + tabName);
            if (selectedContent) {
                selectedContent.classList.remove('hidden');
                selectedContent.classList.add('block');
            }

            // Aktifkan style tombol yang dipilih
            const selectedBtn = document.getElementById('tab-btn-' + tabName);
            if (selectedBtn) {
                selectedBtn.classList.remove('text-gray-500', 'border-transparent');
                selectedBtn.classList.add('text-[#00A79D]', 'border-[#00A79D]', 'active-tab');
            }
        }

        // 2. Logic Modal
        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            if (modal) {
                modal.classList.toggle('hidden');
            }
        }

        // 3. Logic Dropdown (Buat Pertemuan & Menu Titik 3)
        function toggleDropdown(dropdownID) {
            const dropdown = document.getElementById(dropdownID);
            
            // Tutup dropdown lain yang terbuka (opsional, biar rapi)
            document.querySelectorAll('[id^="dropdown"], [id^="menu-"]').forEach(el => {
                if(el.id !== dropdownID) el.classList.add('hidden');
            });

            if (dropdown) {
                dropdown.classList.toggle('hidden');
            }
        }

        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            if (!e.target.matches('button') && !e.target.closest('button')) {
                document.querySelectorAll('[id^="dropdown"], [id^="menu-"]').forEach(el => {
                    el.classList.add('hidden');
                });
            }
        });

        // Set default tab on load
        document.addEventListener('DOMContentLoaded', () => {
            switchTab('beranda');
        });
    </script>

    {{-- Custom Style untuk Animasi Halus --}}
    <style>
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .active-tab {
            color: #00A79D !important;
            border-bottom-color: #00A79D !important;
        }
    </style>
@endsection