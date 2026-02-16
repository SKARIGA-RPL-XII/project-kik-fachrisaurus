@extends('layouts.app')

@section('content')
    <x-sidebar />

    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <div class="flex-1">
            <x-breadcrumb :items="[['label' => 'Kelas Saya', 'url' => null]]" />
        </div>
        <x-calendar />
    </div>

    {{-- Judul (Tanpa Tombol Buat Kelas) --}}
    <div class="flex justify-between items-center mt-[40px] mb-[25px]">
        <x-ui.title text="Kelas Saya">
            <span class="text-[20px] font-normal text-[#092C4C] opacity-50">
                {{ $classes->count() }} Data
            </span>
        </x-ui.title>
        {{-- Tombol Create Dihapus --}}
    </div>

    {{-- GARIS PEMBATAS --}}
    <hr class="border-t border-[#D9D9D9] border-[1px] opacity-70 mb-8">

    {{-- Search & Sort Wrapper --}}
    <form action="{{ route('teacher.kelas.index') }}" method="GET" class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
        <x-ui.search-bar placeholder="Cari kelas..." />
        <x-ui.sort-group />
    </form>

    {{-- Grid Kelas --}}
    <div id="kelasResultContainer" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-[25px] mt-[35px] pb-10">
        @foreach($classes as $class)
            {{-- KARTU KELAS VERSI PENGAJAR (Tanpa Tombol Aksi Hapus/Edit) --}}
            <div class="bg-white rounded-[15px] shadow-sm border border-slate-100 hover:shadow-md transition-shadow duration-300 relative flex flex-col overflow-hidden">
                
                {{-- GAMBAR --}}
                <div class="w-full aspect-video border-b border-[#D9D9D9]/70 relative group overflow-hidden mb-[20px]">
                    @if($class->logo)
                        <img src="{{ asset('storage/' . $class->logo) }}" alt="Logo" 
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    @else
                        <div class="w-full h-full bg-[#F8F9FA] flex items-center justify-center relative group">
                             <img src="https://img.freepik.com/free-vector/gradient-abstract-wireframe-background_23-2149009903.jpg" 
                                  class="w-full h-full object-cover opacity-80 transition-transform duration-500 group-hover:scale-110">
                        </div>
                    @endif
                </div>

                {{-- KONTEN --}}
                <div class="px-[15px] pb-[15px]">
                    
                    {{-- Judul & Badge --}}
                    <div class="flex justify-between items-start mb-1">
                        <h3 class="text-[18px] font-bold text-[#2d3748] leading-tight line-clamp-1 w-[70%]">{{ $class->name }}</h3>
                        <span class="text-[12px] font-medium text-gray-400 bg-gray-50 px-2 py-1 rounded border border-gray-100">
                            @php
                                $kurikulum = is_numeric($class->subject->curriculum ?? '2013') ? 'K-' . ($class->subject->curriculum ?? '2013') : ($class->subject->curriculum ?? '2013');
                            @endphp
                            {{ $kurikulum }}
                        </span>
                    </div>

                    {{-- Nama Pengajar tidak perlu ditampilkan karena ini dashboard pengajar itu sendiri, 
                         tapi jika mau tetap ada, bisa diganti "Saya" atau nama mapel --}}
                    <p class="text-[12px] text-gray-400 mb-0">
                        Mata Pelajaran: <span class="text-gray-600 font-medium">{{ $class->subject->name ?? '-' }}</span>
                    </p>

                    {{-- STATS --}}
                    <div class="flex justify-between mt-[20px] mb-[30px]">
                        <x-ui.stat-badge label="Siswa" :value="$class->students_count ?? 0" color="orange" />
                        <x-ui.stat-badge label="Pertemuan" :value="$class->meetings_count ?? 0" color="teal" />
                        <x-ui.stat-badge label="Info" :value="$class->announcements_count ?? 0" color="purple" />
                    </div>

                    {{-- TOMBOL DETAIL (Full Width karena tidak ada tombol aksi) --}}
                    <div class="flex items-center relative z-10">
                        <a href="{{ route('teacher.kelas.show', $class->id) }}" class="w-full h-[40px] flex items-center justify-center rounded-[8px] bg-[#00A79D] text-white text-sm font-medium hover:bg-[#008f87] transition-all shadow-sm shadow-teal-100">
                            Masuk Kelas
                        </a>
                    </div>

                </div>
            </div>
        @endforeach

        @if($classes->isEmpty())
            <div class="col-span-full flex flex-col items-center justify-center py-20">
                <p class="text-gray-400 font-medium">Anda belum memiliki kelas.</p>
            </div>
        @endif
    </div>

    {{-- Script JS (Hanya Search) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const clearSearchBtn = document.getElementById('clearSearchBtn');
            const resultContainer = document.getElementById('kelasResultContainer');
            let debounceTimer;

            if(searchInput && clearSearchBtn) {
                function toggleClearBtn() {
                    clearSearchBtn.classList.toggle('hidden', searchInput.value.length === 0);
                }
                toggleClearBtn();
                searchInput.addEventListener('input', () => {
                    toggleClearBtn();
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        const url = new URL(window.location.href);
                        url.searchParams.set('search', searchInput.value);
                        resultContainer.style.opacity = '0.5';
                        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(res => res.text())
                        .then(html => {
                            const doc = new DOMParser().parseFromString(html, 'text/html');
                            const newContent = doc.getElementById('kelasResultContainer');
                            if(newContent) resultContainer.innerHTML = newContent.innerHTML;
                            resultContainer.style.opacity = '1';
                            window.history.pushState({}, '', url);
                        });
                    }, 300);
                });
                clearSearchBtn.addEventListener('click', () => {
                    searchInput.value = '';
                    toggleClearBtn();
                    searchInput.dispatchEvent(new Event('input'));
                });
            }
        });
    </script>
@endsection