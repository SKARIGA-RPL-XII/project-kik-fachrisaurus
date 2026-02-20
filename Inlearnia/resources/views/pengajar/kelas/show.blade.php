@extends('layouts.app')

@section('content')
    <x-sidebar />

    <div class="flex justify-between items-center mb-8">
        <div class="flex-1">
            <x-breadcrumb :items="[
                ['label' => 'Kelas Saya', 'url' => route('teacher.kelas.index')],
                ['label' => $kelas->name, 'url' => null]
            ]" />
        </div>
        <x-calendar />
    </div>

    {{-- Header Kelas (Diberi ID agar bisa di-hide via JS) --}}
    <div id="kelas-header" class="w-full bg-white rounded-[20px] shadow-lg shadow-slate-200/50 border border-slate-200 overflow-hidden mb-8 group relative z-0 transition-all duration-300">
        
        <div class="relative w-full h-[220px] md:h-[260px] overflow-hidden">
            <div class="absolute inset-0 bg-slate-800 z-0">
                @if($kelas->logo)
                    <img src="{{ asset('storage/' . $kelas->logo) }}" 
                         class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110 group-hover:brightness-110">
                @else
                    <img src="https://img.freepik.com/free-vector/gradient-abstract-wireframe-background_23-2149009903.jpg" 
                         class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110 group-hover:brightness-110">
                @endif
            </div>

            <div class="absolute inset-0 bg-gradient-to-t from-[#092C4C] via-[#092C4C]/60 to-transparent z-10 transition-opacity duration-500 ease-in-out"></div>

            <div class="absolute bottom-0 left-0 w-full p-6 md:p-8 z-20 pointer-events-none flex flex-col justify-end h-full">
                <div class="flex flex-col gap-1 transition-transform duration-500 group-hover:-translate-y-2">
                    <div class="mb-2">
                        <span class="bg-[#00A79D] text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-md">
                            {{ $kelas->subject->curriculum ?? 'Kurikulum Merdeka' }}
                        </span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold text-white tracking-tight leading-none drop-shadow-md transition-all">
                        {{ $kelas->name }}
                    </h1>
                    <p class="text-slate-100 text-sm md:text-base font-medium mt-1 flex items-center gap-2 drop-shadow-sm group-hover:text-white">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                        {{ $kelas->subject->name ?? 'Mata Pelajaran Umum' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="relative z-30 bg-white px-12 py-5 flex flex-col md:flex-row justify-between items-center gap-6 md:gap-0 border-t border-slate-100">
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

            <div class="flex items-center gap-8 md:gap-12 w-full md:w-auto justify-around md:justify-end">
                <div class="text-center group/stat cursor-default">
                    <span class="block text-xl font-bold text-orange-500 transition-colors">{{ $kelas->students_count }}</span>
                    <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wide">Siswa</span>
                </div>
                <div class="w-px h-8 bg-slate-200 hidden md:block"></div>
                <div class="text-center group/stat cursor-default">
                    <span class="block text-xl font-bold text-[#00A79D] transition-colors">{{ $kelas->meetings_count }}</span>
                    <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wide">Pertemuan</span>
                </div>
                <div class="w-px h-8 bg-slate-200 hidden md:block"></div>
                <div class="text-center group/stat cursor-default">
                    <span class="block text-xl font-bold text-purple-600 transition-colors">{{ $kelas->announcements_count }}</span>
                    <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wide">Info</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Switch Tab Navigasi --}}
    <div class="mb-6 border-b border-gray-200">
        <nav class="flex space-x-8" aria-label="Tabs">
            <button onclick="switchTab('beranda')" id="tab-btn-beranda" class="tab-btn active-tab py-4 px-1 border-b-2 font-medium text-sm">
                Beranda
            </button>
            <button onclick="switchTab('pertemuan')" id="tab-btn-pertemuan" class="tab-btn py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Daftar Pertemuan
            </button>
            <button onclick="switchTab('anggota')" id="tab-btn-anggota" class="tab-btn py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Anggota Kelas
            </button>
        </nav>
    </div>

    {{-- Tab 1: Beranda --}}
    <div id="tab-content-beranda" class="tab-content block animate-fade-in">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-[15px] p-4 shadow-sm border border-slate-100 flex gap-4 items-center cursor-pointer hover:shadow-md transition-all" onclick="toggleModal('modalPengumuman')">
                    <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden flex-shrink-0">
                        <img src="{{ auth()->user()->avatar ?? asset('assets/img/default-avatar.png') }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 bg-gray-50 rounded-full px-4 py-3 text-gray-500 text-sm">
                        Umumkan sesuatu ke kelas Anda...
                    </div>
                </div>

                @forelse($feeds as $item)
                    @if($item->type === 'announcement')
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

            <div class="hidden lg:block space-y-6">
                <div class="bg-white rounded-[15px] p-5 shadow-sm border border-slate-100">
                    <h3 class="font-bold text-[#092C4C] mb-4">Mendatang</h3>
                    <p class="text-sm text-slate-400">Tidak ada tugas yang perlu segera dikumpulkan.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tab 2: Pertemuan --}}
    <div id="tab-content-pertemuan" class="tab-content hidden animate-fade-in">
        
        {{-- HEADER: Search, Filter, & Tombol Buat --}}
        <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center mb-8 gap-4">
            
            {{-- Komponen Search & Filter --}}
            <div class="flex-1 w-full max-w-3xl">
                 <form id="searchMeetingForm" class="flex flex-col sm:flex-row gap-3 items-center">
                     
                     {{-- 1. Search Bar --}}
                     <div class="w-full sm:w-auto">
                        <x-ui.search-bar 
                            placeholder="Cari materi atau tugas..." 
                            target="pertemuanResultContainer" 
                            formId="searchMeetingForm" 
                        />
                     </div>
                     
                     {{-- 2. Filter Jenis --}}
                     <div class="w-full sm:w-40 flex-shrink-0">
                        <select name="type_filter" class="w-full h-[40px] border border-gray-200 rounded-[10px] text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#00A79D] focus:border-[#00A79D] text-gray-600 bg-white cursor-pointer transition-all">
                            <option value="">Semua Jenis</option>
                            <option value="materi" {{ request('type_filter') == 'materi' ? 'selected' : '' }}>Materi</option>
                            <option value="tugas" {{ request('type_filter') == 'tugas' ? 'selected' : '' }}>Tugas</option>
                        </select>
                     </div>

                     {{-- 3. Filter Topik (BARU) --}}
                     <div class="w-full sm:w-40 flex-shrink-0">
                        @php
                            // Mengambil semua topik unik dari data pertemuan untuk mengisi opsi dropdown
                            $availableTopics = $tabMeetings->pluck('topic')->filter()->unique();
                        @endphp
                        <select name="topic_filter" class="w-full h-[40px] border border-gray-200 rounded-[10px] text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#00A79D] focus:border-[#00A79D] text-gray-600 bg-white cursor-pointer transition-all">
                            <option value="">Semua Topik</option>
                            @foreach($availableTopics as $topic)
                                <option value="{{ $topic }}" {{ request('topic_filter') == $topic ? 'selected' : '' }}>{{ $topic }}</option>
                            @endforeach
                        </select>
                     </div>

                     <input type="hidden" name="tab" value="pertemuan">
                 </form>
            </div>
            
            {{-- Tombol Buat Baru --}}
            <div class="relative w-full xl:w-auto flex-shrink-0">
                <button onclick="toggleDropdown('dropdownBuatPertemuan')" class="w-full xl:w-auto bg-[#00A79D] hover:bg-[#008f87] text-white px-5 py-[9px] rounded-[10px] text-sm font-medium flex items-center justify-center gap-2 transition-all shadow-sm shadow-teal-100">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Buat Baru
                </button>
                
                <div id="dropdownBuatPertemuan" class="hidden absolute right-0 mt-2 w-full xl:w-48 bg-white rounded-lg shadow-xl border border-slate-100 z-50 overflow-hidden">
                    <a href="{{ route('teacher.meetings.create', ['kelas_id' => $kelas->id, 'type' => 'tugas']) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#00A79D] flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Buat Tugas
                    </a>
                    <a href="{{ route('teacher.meetings.create', ['kelas_id' => $kelas->id, 'type' => 'materi']) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#00A79D] flex items-center gap-2 border-t border-slate-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477-4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        Buat Materi
                    </a>
                </div>
            </div>
        </div>

        {{-- LIST PERTEMUAN (Dikelompokkan per Topik) --}}
        <div id="pertemuanResultContainer" class="space-y-8"> {{-- space-y diperbesar antar topik --}}
            @php
                // Logika Grouping: Kelompokkan berdasarkan topik, jika kosong masuk ke 'Tanpa Topik'
                $groupedMeetings = $tabMeetings->groupBy(function($item) {
                    return empty($item->topic) ? 'Tanpa Topik' : $item->topic;
                });
            @endphp

            @forelse($groupedMeetings as $topic => $meetings)
                <div>
                    {{-- Judul Topik --}}
                    <h2 class="text-sm font-bold text-[#00A79D] uppercase tracking-wider mb-4 pl-2 border-b-2 border-teal-100 pb-2 flex items-center gap-2">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                        {{ $topic }}
                    </h2>

                    {{-- Card Pertemuan dalam Topik tersebut --}}
                    <div class="space-y-3">
                        @foreach($meetings as $meeting)
                            <div class="bg-white rounded-[10px] shadow-sm border border-slate-100 hover:border-[#00A79D]/30 transition-all duration-300 group">
                                
                                {{-- HEADER BISA DIKLIK --}}
                                <div class="p-4 flex justify-between items-start cursor-pointer rounded-[10px]" onclick="toggleAccordion('accordion-{{ $meeting->id }}')">
                                    <div class="flex gap-4 w-full">
                                        <div class="w-10 h-10 rounded-[8px] flex items-center justify-center flex-shrink-0 {{ $meeting->type == 'tugas' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600' }}">
                                            @if($meeting->type == 'tugas')
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect><path d="M9 14l2 2 4-4"></path></svg>
                                            @else
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                                            @endif
                                        </div>

                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded border uppercase tracking-wider {{ $meeting->type == 'tugas' ? 'bg-orange-50 text-orange-600 border-orange-100' : 'bg-blue-50 text-blue-600 border-blue-100' }}">
                                                    {{ ucfirst($meeting->type) }}
                                                </span>
                                                <span class="text-[11px] font-medium text-slate-400">{{ $meeting->created_at->format('d M Y') }}</span>
                                            </div>
                                            <h3 class="font-bold text-[#092C4C] text-base leading-tight">{{ $meeting->title }}</h3>
                                            
                                            @if($meeting->type == 'tugas')
                                                @if($meeting->deadline)
                                                    <p class="text-xs text-red-500 mt-1.5 font-medium flex items-center gap-1">
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                                        Deadline: {{ \Carbon\Carbon::parse($meeting->deadline)->format('d M Y, H:i') }}
                                                    </p>
                                                @else
                                                    <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                                        Tidak ada batasan waktu
                                                    </p>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 pl-2">
                                        {{-- Dropdown Aksi --}}
                                        <div class="relative z-10" onclick="event.stopPropagation()">
                                            <button onclick="toggleDropdown('menu-{{ $meeting->id }}')" class="p-1.5 text-slate-400 hover:text-[#092C4C] hover:bg-slate-50 rounded-md transition-colors">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                                            </button>
                                            <div id="menu-{{ $meeting->id }}" class="hidden absolute right-0 mt-1 w-36 bg-white rounded-lg shadow-lg border border-slate-100 z-50 overflow-hidden">
                                                <a href="{{ route('teacher.meetings.edit', $meeting->id) }}" class="block px-4 py-2.5 text-xs font-medium text-slate-600 hover:bg-slate-50 hover:text-[#00A79D]">
                                                    Edit
                                                </a>
                                                <form action="{{ route('teacher.meetings.destroy', $meeting->id) }}" method="POST" class="delete-form">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="button" class="btn-delete block w-full text-left px-4 py-2.5 text-xs font-medium text-red-500 hover:bg-red-50 transition-colors border-t border-slate-50">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- KONTEN EXPAND (ACCORDION) --}}
                                {{-- Tambahkan class 'accordion-content' agar mudah di-target oleh JS --}}
                                <div id="accordion-{{ $meeting->id }}" class="accordion-content max-h-0 opacity-0 overflow-hidden transition-all duration-300 ease-in-out bg-slate-50/50 border-t border-transparent rounded-b-[10px]">
                                    <div class="p-5 md:p-6 text-sm text-slate-600 border-t border-slate-100">
                                        
                                        @if($meeting->type == 'tugas')
                                            <div class="flex items-center gap-4 mb-4 text-xs font-medium">
                                                <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded">
                                                    Max Nilai: {{ $meeting->max_score ?? 'Tidak dinilai' }}
                                                </span>
                                                @if($meeting->disable_late_submission)
                                                    <span class="bg-red-50 text-red-600 px-3 py-1 rounded flex items-center gap-1">
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"></path><path d="M21 3v5h-5"></path></svg>
                                                        Kunci Otomatis
                                                    </span>
                                                @endif
                                            </div>
                                        @endif

                                        @if($meeting->description)
                                            <div class="prose prose-sm max-w-none mb-4 line-clamp-3">
                                                {!! $meeting->description !!}
                                            </div>
                                        @else
                                            <p class="mb-4 italic text-slate-400">Tidak ada instruksi tambahan.</p>
                                        @endif

                                        {{-- Lampiran File --}}
                                        @php
                                            $rawFiles = $meeting->files;
                                            if (is_string($rawFiles)) {
                                                $rawFiles = json_decode($rawFiles, true);
                                                if (is_string($rawFiles)) $rawFiles = json_decode($rawFiles, true); 
                                            }
                                            $files = is_array($rawFiles) ? $rawFiles : [];
                                        @endphp
                                        
                                        @if(count($files) > 0)
                                            <div class="mb-5">
                                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">File Terlampir</p>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                                    @foreach($files as $file)
                                                        @if(is_array($file) && isset($file['path']))
                                                            <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" class="flex items-center gap-3 bg-white p-3 rounded-lg border border-slate-200 hover:border-[#00A79D] hover:shadow-sm transition-all group/file">
                                                                
                                                                <div class="w-10 h-10 rounded bg-red-50 text-red-500 flex items-center justify-center flex-shrink-0">
                                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                                        <polyline points="14 2 14 8 20 8"></polyline>
                                                                    </svg>
                                                                </div>

                                                                <div class="flex-1 min-w-0">
                                                                    <p class="text-xs font-semibold text-slate-700 truncate group-hover/file:text-[#00A79D]" title="{{ $file['original_name'] ?? 'File Lampiran' }}">{{ $file['original_name'] ?? 'File Lampiran' }}</p>
                                                                    <p class="text-[10px] text-slate-400 mt-0.5">
                                                                        {{ isset($file['size']) ? number_format($file['size'] / 1024, 1) . ' KB' : 'Unknown Size' }}
                                                                    </p>
                                                                </div>
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Tautan Lihat Selengkapnya --}}
                                        <div class="mt-4 pt-4 border-t border-slate-200">
                                            <a href="{{ route('teacher.meetings.show', $meeting->id) }}" class="inline-flex items-center text-sm font-semibold text-[#00A79D] hover:text-[#008f87] hover:underline transition-colors">
                                                Lihat Selengkapnya
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                            </a>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-10 bg-slate-50 rounded-[10px] border border-dashed border-slate-200">
                    <p class="text-slate-400 text-sm font-medium">Tidak ada data ditemukan.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Tab 3: Anggota Kelas --}}
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

    {{-- Modal Buat Pengumuman --}}
    <div id="modalPengumuman" class="hidden fixed inset-0 z-[60] overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" onclick="toggleModal('modalPengumuman')"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-[15px] bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-slate-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-bold text-[#092C4C]">Buat Pengumuman</h3>
                        <button type="button" onclick="toggleModal('modalPengumuman')" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>

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
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="submit" class="inline-flex w-full justify-center rounded-[8px] bg-[#00A79D] px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#008f87] sm:ml-3 sm:w-auto transition-colors">Posting</button>
                        <button type="button" onclick="toggleModal('modalPengumuman')" class="mt-3 inline-flex w-full justify-center rounded-[8px] bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- TAMBAHKAN CDN SWEETALERT2 JIKA BELUM ADA --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Konfigurasi State Tab Berdasarkan ID Kelas
        const tabStorageKey = 'activeTab_class_{{ $kelas->id }}';

        // --- Core Functions ---
        function switchTab(tabName) {
            // Sembunyikan semua konten & reset tombol
            document.querySelectorAll('.tab-content').forEach(el => el.classList.replace('block', 'hidden'));
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('text-[#00A79D]', 'border-[#00A79D]', 'active-tab');
                btn.classList.add('text-gray-500', 'border-transparent');
            });

            // Aktifkan konten & tombol yang dipilih
            const selectedContent = document.getElementById(`tab-content-${tabName}`);
            const selectedBtn = document.getElementById(`tab-btn-${tabName}`);
            
            if (selectedContent) selectedContent.classList.replace('hidden', 'block');
            if (selectedBtn) {
                selectedBtn.classList.remove('text-gray-500', 'border-transparent');
                selectedBtn.classList.add('text-[#00A79D]', 'border-[#00A79D]', 'active-tab');
            }

            // Tampilkan/Sembunyikan Header Banner
            const header = document.getElementById('kelas-header');
            if (header) header.style.display = (tabName === 'beranda') ? 'block' : 'none';

            // Simpan State ke LocalStorage
            localStorage.setItem(tabStorageKey, tabName);
        }

        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            if (modal) modal.classList.toggle('hidden');
        }

        function toggleDropdown(dropdownID) {
            // Tutup dropdown lain yang sedang terbuka
            document.querySelectorAll('[id^="dropdown"], [id^="menu-"]').forEach(el => {
                if(el.id !== dropdownID) el.classList.add('hidden');
            });
            const dropdown = document.getElementById(dropdownID);
            if (dropdown) dropdown.classList.toggle('hidden');
        }

        function toggleAccordion(contentId) {
            // 1. Ambil elemen yang baru saja diklik
            const targetContent = document.getElementById(contentId);
            if (!targetContent) return;

            // 2. Cari semua elemen accordion di halaman ini
            const allAccordions = document.querySelectorAll('.accordion-content');

            // 3. Loop: Tutup semua accordion KECUALI yang baru saja diklik
            allAccordions.forEach(content => {
                if (content.id !== contentId) {
                    content.classList.add('max-h-0', 'opacity-0');
                    content.classList.remove('max-h-[3000px]', 'opacity-100');
                }
            });

            // 4. Buka / Tutup accordion yang diklik
            const isCollapsed = targetContent.classList.contains('max-h-0');
            
            if (isCollapsed) {
                // Buka
                targetContent.classList.remove('max-h-0', 'opacity-0');
                targetContent.classList.add('max-h-[3000px]', 'opacity-100');
            } else {
                // Tutup
                targetContent.classList.add('max-h-0', 'opacity-0');
                targetContent.classList.remove('max-h-[3000px]', 'opacity-100');
            }
        }

        // --- Event Listeners Initialization ---
        document.addEventListener('DOMContentLoaded', () => {
            
            // 1. Inisialisasi Tab Aktif (Prioritas: URL Param > LocalStorage > 'beranda')
            const urlParams = new URLSearchParams(window.location.search);
            const tabFromUrl = urlParams.get('tab');
            const activeTab = tabFromUrl || localStorage.getItem(tabStorageKey) || 'beranda';
            
            switchTab(activeTab);

            // Bersihkan param 'tab' dari URL bar agar rapi (opsional)
            if (tabFromUrl) {
                const newUrl = window.location.href.split('?')[0];
                window.history.replaceState({}, document.title, newUrl);
            }

            // 2. Tutup Dropdown saat klik di luar elemen
            window.addEventListener('click', (e) => {
                // Tambahkan pengecekan .relative agar dropdown tidak tertutup saat icon titik tiga di-klik
                if (!e.target.closest('button') && !e.target.closest('.relative')) {
                    document.querySelectorAll('[id^="dropdown"], [id^="menu-"]').forEach(el => el.classList.add('hidden'));
                }
            });

            // 3. Konfigurasi Notifikasi (SweetAlert2 Toast)
            @if(session('success'))
                Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                }).fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif

            // 4. Konfirmasi Hapus Data (SweetAlert2 Modal)
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Hapus Data?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) this.closest('form').submit();
                    });
                });
            });

        });
    </script>

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